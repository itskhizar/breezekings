<?php
include("include/classes/constants.php");
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$res = mysqli_query($conn, "SHOW TABLES");
while($row = mysqli_fetch_row($res)) {
    echo $row[0] . "\n";
}

mysqli_close($conn);
