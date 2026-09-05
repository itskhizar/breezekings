<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed");
}

echo "--- SESSION CLASSES CHECK ---\n";
$res = mysqli_query($conn, "SELECT COUNT(*) as count FROM session_classes");
$row = mysqli_fetch_assoc($res);
echo "Total Class Slots in DB: " . $row['count'] . "\n";

$res = mysqli_query($conn, "SELECT title, course_duration_weeks FROM sessions ORDER BY id DESC LIMIT 1");
$row = mysqli_fetch_assoc($res);
echo "Latest Session: " . $row['title'] . " (Weeks: " . $row['course_duration_weeks'] . ")\n";

echo "\n--- STUDENT ATTENDANCE COLUMNS CHECK ---\n";
$res = mysqli_query($conn, "SHOW COLUMNS FROM student_attendance LIKE 'class_id'");
if (mysqli_num_rows($res) > 0) echo "class_id column exists.\n"; else echo "class_id column MISSING.\n";

$res = mysqli_query($conn, "SHOW COLUMNS FROM student_attendance LIKE 'duration_hours'");
if (mysqli_num_rows($res) > 0) echo "duration_hours column exists.\n"; else echo "duration_hours column MISSING.\n";

echo "\n--- TEACHER ATTENDANCE COLUMNS CHECK ---\n";
$res = mysqli_query($conn, "SHOW COLUMNS FROM teacher_attendance LIKE 'class_id'");
if (mysqli_num_rows($res) > 0) echo "class_id column exists.\n"; else echo "class_id column MISSING.\n";

echo "\n--- SAMPLE SESSION CLASSES (First 5) ---\n";
$res = mysqli_query($conn, "SELECT day_of_week, class_date, start_time, duration_hours FROM session_classes LIMIT 5");
while($row = mysqli_fetch_assoc($res)) {
    echo $row['day_of_week'] . " | " . $row['class_date'] . " | " . $row['start_time'] . " | " . $row['duration_hours'] . " hrs\n";
}

mysqli_close($conn);
?>
