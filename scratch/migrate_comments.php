<?php
include("include/classes/database.php");

$q = "UPDATE comments SET status = 'Approved' WHERE status = 'Published'";
$res = $database->query($q);

if ($res) {
    echo "Migration successful. Updated comments to 'Approved' status.";
} else {
    echo "Migration failed.";
}
?>
