<?php
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$q = "UPDATE comments SET status = 'Approved' WHERE status = 'Published'";
$res = mysqli_query($conn, $q);
if ($res) {
    echo "Updated " . mysqli_affected_rows($conn) . " rows.";
} else {
    echo "Error: " . mysqli_error($conn);
}
mysqli_close($conn);
?>
