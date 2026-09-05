<?php
include 'include/classes/constants.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

$tables = mysqli_query($conn, "SHOW TABLES LIKE 'session_classes'");
echo "session_classes: " . (mysqli_num_rows($tables) > 0 ? "EXISTS" : "NOT FOUND") . "\n";

$cols = mysqli_query($conn, "SHOW COLUMNS FROM sessions LIKE 'course_duration_weeks'");
echo "sessions.course_duration_weeks: " . (mysqli_num_rows($cols) > 0 ? "EXISTS" : "NOT FOUND") . "\n";

$cols2 = mysqli_query($conn, "SHOW COLUMNS FROM student_attendance LIKE 'class_id'");
echo "student_attendance.class_id: " . (mysqli_num_rows($cols2) > 0 ? "EXISTS" : "NOT FOUND") . "\n";

mysqli_close($conn);
?>
