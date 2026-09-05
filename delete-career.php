<?php
include("include/classes/session.php");
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed");
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Delete query
    $query = "DELETE FROM careers WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}

mysqli_close($conn);
?>