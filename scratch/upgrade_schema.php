<?php
require_once(__DIR__ . '/../include/classes/database.php');

$db = new MySQLDB();
$connection = $db->connection;

echo "Starting Database Upgrade...\n";

// Helper function to add column if not exists
function addColumn($conn, $table, $column, $definition) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if (mysqli_num_rows($check) == 0) {
        $q = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
        if (mysqli_query($conn, $q)) {
            echo "Added column: $column to $table\n";
        } else {
            echo "Error adding column $column: " . mysqli_error($conn) . "\n";
        }
    } else {
        echo "Column $column already exists in $table\n";
    }
}

// Upgrade Posts table
addColumn($connection, 'posts', 'slug', "VARCHAR(255) AFTER title");
addColumn($connection, 'posts', 'excerpt', "TEXT AFTER content");
addColumn($connection, 'posts', 'author_id', "INT(11) AFTER category_id");
addColumn($connection, 'posts', 'meta_title', "VARCHAR(255) AFTER author_id");
addColumn($connection, 'posts', 'meta_description', "TEXT AFTER meta_title");
addColumn($connection, 'posts', 'tags', "VARCHAR(255) AFTER meta_description");
addColumn($connection, 'posts', 'is_featured', "TINYINT(1) DEFAULT 0 AFTER views");
addColumn($connection, 'posts', 'published_at', "TIMESTAMP NULL AFTER is_featured");
addColumn($connection, 'posts', 'updated_at', "TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER published_at");
addColumn($connection, 'posts', 'is_deleted', "TINYINT(1) DEFAULT 0 AFTER updated_at");

// Rename thumbnail to featured_image if it exists
$check_thumb = mysqli_query($connection, "SHOW COLUMNS FROM `posts` LIKE 'thumbnail'");
$check_feat = mysqli_query($connection, "SHOW COLUMNS FROM `posts` LIKE 'featured_image'");
if (mysqli_num_rows($check_thumb) > 0 && mysqli_num_rows($check_feat) == 0) {
    if (mysqli_query($connection, "ALTER TABLE `posts` CHANGE COLUMN `thumbnail` `featured_image` VARCHAR(255)")) {
        echo "Renamed thumbnail to featured_image\n";
    } else {
        echo "Error renaming thumbnail: " . mysqli_error($connection) . "\n";
    }
}

echo "Database Upgrade Completed.\n";
?>
