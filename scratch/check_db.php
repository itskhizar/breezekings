<?php
include("include/classes/database.php");
$res = $database->query("DESCRIBE users");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}
?>
