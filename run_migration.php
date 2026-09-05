<?php
include 'include/classes/constants.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sqlFile = 'include/sqls/dynamic_timetable_migration.sql';
if (!file_exists($sqlFile)) {
    die("Migration file not found: $sqlFile");
}

$sql = file_get_contents($sqlFile);
// Remove comments
$sql = preg_replace('/--.*$/m', '', $sql);
$queries = array_filter(array_map('trim', explode(';', $sql)));

foreach ($queries as $q) {
    if (!empty($q)) {
        echo "Executing: " . substr($q, 0, 50) . "...\n";
        if (mysqli_query($conn, $q)) {
            echo "SUCCESS\n";
        } else {
            echo "ERROR: " . mysqli_error($conn) . "\n";
        }
        echo "---------------------------------\n";
    }
}

mysqli_close($conn);
?>
