<?php
// Verification script for Grading System
// connect to db
$connection = mysqli_connect("localhost", "root", "", "academy");
if (!$connection) { die("Connection failed: " . mysqli_connect_error()); }

echo "<h2>Grading System Verification</h2>";

// 1. Create Test Course (Check learning_hours)
$course_title = "TEST_COURSE_" . time();
$learning_hours = 120;
$q1 = "INSERT INTO courses (category_id, course_title, duration, learning_hours, created_at, timestamp, image) 
       VALUES (1, '$course_title', '3', '$learning_hours', NOW(), '" . time() . "', 'test.jpg')";
if (mysqli_query($connection, $q1)) {
    $course_id = mysqli_insert_id($connection);
    echo "[PASS] Created Course (ID: $course_id) with learning_hours.<br>";
} else {
    echo "[FAIL] Create Course: " . mysqli_error($connection) . "<br>";
}

// Verify Course
$res = mysqli_query($connection, "SELECT learning_hours FROM courses WHERE id = $course_id");
$row = mysqli_fetch_assoc($res);
if ($row && $row['learning_hours'] == $learning_hours) {
    echo "[PASS] Verified learning_hours = $learning_hours<br>";
} else {
    echo "[FAIL] Verified learning_hours mismatch (Expected: $learning_hours, Got: " . ($row['learning_hours'] ?? 'null') . ")<br>";
}

// 2. Create Test Task (Check total_marks)
$task_title = "TEST_TASK_" . time();
$total_marks = 50;
$q2 = "INSERT INTO tasks (title, course_id, category_id, total_marks, created_at, reference) 
       VALUES ('$task_title', '$course_id', 1, '$total_marks', NOW(), 'admin')";
if (mysqli_query($connection, $q2)) {
    $task_id = mysqli_insert_id($connection);
    echo "[PASS] Created Task (ID: $task_id) with total_marks.<br>";
} else {
    echo "[FAIL] Create Task: " . mysqli_error($connection) . "<br>";
}

// Verify Task
$res = mysqli_query($connection, "SELECT total_marks FROM tasks WHERE id = $task_id");
$row = mysqli_fetch_assoc($res);
if ($row && $row['total_marks'] == $total_marks) {
    echo "[PASS] Verified task total_marks = $total_marks<br>";
} else {
    echo "[FAIL] Verified task total_marks mismatch (Expected: $total_marks, Got: " . ($row['total_marks'] ?? 'null') . ")<br>";
}

// 3. Create Test Exercise (Check max_marks)
$ex_title = "TEST_EXERCISE_" . time();
$max_marks = 25;
$q3 = "INSERT INTO excercises (excercise_title, max_marks, created_at) 
       VALUES ('$ex_title', '$max_marks', NOW())";
if (mysqli_query($connection, $q3)) {
    $ex_id = mysqli_insert_id($connection);
    echo "[PASS] Created Exercise (ID: $ex_id) with max_marks.<br>";
} else {
    echo "[FAIL] Create Exercise: " . mysqli_error($connection) . "<br>";
}

// Verify Exercise
$res = mysqli_query($connection, "SELECT max_marks FROM excercises WHERE id = $ex_id");
$row = mysqli_fetch_assoc($res);
if ($row && $row['max_marks'] == $max_marks) {
    echo "[PASS] Verified exercise max_marks = $max_marks<br>";
} else {
    echo "[FAIL] Verified exercise max_marks mismatch (Expected: $max_marks, Got: " . ($row['max_marks'] ?? 'null') . ")<br>";
}

// 4. Simulate Grading (Check teacher_grades with exercise_id)
$student_id = 999; // Dummy student
$teacher_id = 888; // Dummy teacher
$marks = 20;

$q4 = "INSERT INTO teacher_grades (task_id, student_id, excercise_id, graded_by, marks, feedback, created_at) 
       VALUES ('$task_id', '$student_id', '$ex_id', '$teacher_id', '$marks', 'Good job', NOW())";

if (mysqli_query($connection, $q4)) {
    $grade_id = mysqli_insert_id($connection);
    echo "[PASS] Inserted Grade (ID: $grade_id) for Exercise $ex_id.<br>";
} else {
    echo "[FAIL] Insert Grade: " . mysqli_error($connection) . "<br>";
}

// Verify Grade
$res = mysqli_query($connection, "SELECT marks, excercise_id FROM teacher_grades WHERE id = $grade_id");
$row = mysqli_fetch_assoc($res);
if ($row && $row['marks'] == $marks && $row['excercise_id'] == $ex_id) {
    echo "[PASS] Verified grade marks = $marks for exercise_id = $ex_id<br>";
} else {
    echo "[FAIL] Verified grade mismatch.<br>";
}

// Cleanup
mysqli_query($connection, "DELETE FROM courses WHERE id = $course_id");
mysqli_query($connection, "DELETE FROM tasks WHERE id = $task_id");
mysqli_query($connection, "DELETE FROM excercises WHERE id = $ex_id");
mysqli_query($connection, "DELETE FROM teacher_grades WHERE id = $grade_id");
echo "[INFO] Cleaned up test data.<br>";

?>
