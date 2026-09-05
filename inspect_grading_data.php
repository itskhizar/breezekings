<?php
include("include/classes/constants.php");
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "--- Tasks with 0 total_marks ---\n";
$res = mysqli_query($conn, "SELECT id, title, course_id, exercise_ids FROM tasks WHERE total_marks = 0 OR total_marks IS NULL");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}

echo "\n--- Exercises Data (by ID) ---\n";
$res = mysqli_query($conn, "SELECT id, task_id, excercise_title, max_marks FROM excercises WHERE id IN (41,42,43)");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}

echo "\n--- task_assign for Student 35 --- \n";
$res = mysqli_query($conn, "SELECT id, task_id, excercise_id, status FROM task_assign WHERE student_id = '35'");
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
}

mysqli_close($conn);
