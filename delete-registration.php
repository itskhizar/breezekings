<?php
include("include/classes/constants.php");

// Database connection
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {

    $registration_no = mysqli_real_escape_string($conn, $_GET['id']);

    // Start Transaction
    mysqli_begin_transaction($conn);

    try {

        // 1. Get student ID using registration_no
        $studentQuery = "SELECT id FROM students WHERE registration_no = '$registration_no' LIMIT 1";
        $studentRes = mysqli_query($conn, $studentQuery);

        if (!$studentRes || mysqli_num_rows($studentRes) == 0) {
            header("Location: numberofstudents.php?msg=notfound");
            exit;
        }

        $studentRow = mysqli_fetch_assoc($studentRes);
        $student_id = $studentRow['id'];

        // 2. Delete student fees
        $deleteFees = "DELETE FROM student_fees WHERE student_id = '$student_id'";
        mysqli_query($conn, $deleteFees);

        // 3. Delete student record
        $deleteStudent = "DELETE FROM students WHERE registration_no = '$registration_no' LIMIT 1";
        mysqli_query($conn, $deleteStudent);

        // 4. Delete user record
        $deleteUser = "DELETE FROM users WHERE registration_no = '$registration_no' LIMIT 1";
        mysqli_query($conn, $deleteUser);

        // OPTIONAL: delete other related data (uncomment if needed)
        // mysqli_query($conn, "DELETE FROM bookings WHERE student_id = '$student_id'");
        // mysqli_query($conn, "DELETE FROM attendance WHERE student_id = '$student_id'");

        // Commit Transaction
        mysqli_commit($conn);

        header("Location: numberofstudents.php?msg=deleted");
        exit;

    } catch (Exception $e) {

        // If anything fails, rollback
        mysqli_rollback($conn);

        header("Location: numberofstudents.php?msg=error");
        exit;
    }

} else {
    header("Location: numberofstudents.php?msg=invalid");
    exit;
}
?>
