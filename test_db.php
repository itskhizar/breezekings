<?php
include("include/classes/database.php");
$database = new MySQLDB();
$res = mysqli_query($database->connection, "SELECT id FROM users ORDER BY id DESC LIMIT 1");
if ($res) {
    echo "Query successful! ID found.\n";
    $row = mysqli_fetch_row($res);
    echo "Highest ID: " . $row[0] . "\n";
} else {
    echo "Query failed: " . mysqli_error($database->connection) . "\n";
}

$res = mysqli_query($database->connection, "SELECT DATABASE()");
$db = mysqli_fetch_row($res)[0];
echo "Connected to database: " . $db . "\n";

$res = mysqli_query($database->connection, "DESC users");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
