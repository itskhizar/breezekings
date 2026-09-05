<?php
include("include/classes/constants.php");

// Database connection
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize id

    $query = "DELETE FROM `sessions` WHERE `id` = '$id' LIMIT 1";
    if (mysqli_query($conn, $query)) {
        // Redirect to numberofadmissions.php with success message
        header("Location: numberofsessions.php?msg=success");
        exit;
    } else {
        header("Location: numberofsessions.php?msg=error");
        exit;
    }
} else {
    header("Location: numberofsessions.php?msg=error");
    exit;
}
?>
