<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database Error");
}
// Check if submission_id is provided
if (isset($_POST['submission_id'])) {
    $submission_id = intval($_POST['submission_id']); // sanitize input

    // Optionally, get the exercise_id to redirect back correctly
    $exercise_id = isset($_POST['exercise_id']) ? intval($_POST['exercise_id']) : 0;

    // Update the task_assign status to 'Returned'
    $query = "UPDATE task_assign 
              SET status = 'Returned' 
              WHERE id = '$submission_id'";

    $queryruned =mysqli_query($conn, $query);

    if ($queryruned) {
        // Redirect back to turned-students.php with success message
        header("Location: turned-students.php?id={$exercise_id}&msg=returned");
        exit();
    } else {
        // Redirect back with error message
        header("Location: turned-students.php?id={$exercise_id}&msg=error");
        exit();
    }

} else {
    // Invalid request
    header("Location: turned-students.php?msg=invalid");
    exit();
}
?>
