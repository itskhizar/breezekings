<?php
include("include/classes/session.php");

// Check if user is logged in and is a teacher or admin
if (!$session->logged_in || ($session->userlevel != 2 && $session->userlevel != 1 && $session->userlevel != 4)) {
    $_SESSION['error_message'] = 'Unauthorized access';
    header('location: index.php');
    exit();
}

// Get teacher ID (falling back to user id for admins)
$username = $session->username;
$user_info = $database->getUserInfo($username);
$registration_no = $user_info['registration_no'];
$teacher_data = $database->getteacherbyreg($registration_no);
$teacher_id = $teacher_data ? $teacher_data['id'] : $user_info['userid'];

// Validate POST data
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = 'Invalid request method';
    header('location: numberofstudents-teacher.php');
    exit();
}

// Get and sanitize inputs
$student_id = isset($_POST['student_id']) ? mysqli_real_escape_string($database->connection, $_POST['student_id']) : '';
$task_id = isset($_POST['task_id']) ? mysqli_real_escape_string($database->connection, $_POST['task_id']) : '';
$excercise_id = isset($_POST['excercise_id']) ? mysqli_real_escape_string($database->connection, $_POST['excercise_id']) : '';
$marks = isset($_POST['marks']) ? (int)$_POST['marks'] : null;
$feedback = isset($_POST['feedback']) ? mysqli_real_escape_string($database->connection, trim($_POST['feedback'])) : '';

// Validate inputs
if (empty($student_id) || empty($task_id) || empty($excercise_id) || $marks === null) {
    $_SESSION['error_message'] = 'Missing required fields (Student, Task, Exercise, Marks)';
    header("location: student-detail-teacher.php?id=$student_id");
    exit();
}

// Fetch EXERCISE max marks for validation
$ex_query = "SELECT max_marks FROM excercises WHERE id = '$excercise_id' LIMIT 1";
$ex_result = mysqli_query($database->connection, $ex_query);
$ex_data = mysqli_fetch_assoc($ex_result);
$max_marks = $ex_data ? (int)$ex_data['max_marks'] : 10; // Fallback to 10 if not found

// Validate marks range
if ($marks < 0 || $marks > $max_marks) {
    $_SESSION['error_message'] = "Marks must be between 0 and $max_marks";
    
    if ($session->userlevel == 2) {
        header("location: student-detail-teacher.php?id=$student_id");
    } else {
        header("location: student-detail-admin.php?student_id=$student_id&task_id=$task_id");
    }
    exit();
}


// Check if grade already exists for this EXERCISE
$check_query = "
    SELECT id 
    FROM teacher_grades 
    WHERE task_id = '$task_id' 
    AND student_id = '$student_id'
    AND excercise_id = '$excercise_id'
    LIMIT 1
";

$check_result = mysqli_query($database->connection, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    // UPDATE existing grade
    $grade_row = mysqli_fetch_assoc($check_result);
    $grade_id = $grade_row['id'];
    
    $update_query = "
        UPDATE teacher_grades 
        SET marks = '$marks',
            feedback = '$feedback',
            updated_at = NOW()
        WHERE id = '$grade_id'
    ";
    
    if (mysqli_query($database->connection, $update_query)) {
        $_SESSION['success_message'] = 'Exercise grade updated successfully!';
    } else {
        $_SESSION['error_message'] = 'Failed to update grade: ' . mysqli_error($database->connection);
    }
    
} else {
    // Fetch task_assign_id for this submission
    $ta_query = "SELECT id FROM task_assign 
                 WHERE task_id = '$task_id' 
                 AND student_id = '$student_id' 
                 AND excercise_id = '$excercise_id' 
                 LIMIT 1";
    $ta_result = mysqli_query($database->connection, $ta_query);
    $ta_row = mysqli_fetch_assoc($ta_result);
    $task_assign_id = $ta_row ? $ta_row['id'] : 0;

    if ($task_assign_id == 0) {
        // If not submitted yet, we might need to create a placeholder or just use 0 if allowed
        // But for consistency let's try to ensure a task_assign entry exists?
        // Actually, let's just use 0 for now as it matches the INT type.
    }

    // INSERT new grade
    $insert_query = "
    INSERT INTO teacher_grades (
        task_assign_id,
        task_id, 
        student_id, 
        excercise_id,
        graded_by, 
        marks, 
        feedback,
        status,
        created_at
    ) VALUES (
        '$task_assign_id',
        '$task_id',
        '$student_id',
        '$excercise_id',
        '$teacher_id',
        '$marks',
        '$feedback',
        'graded',
        NOW()
    )
";

if (mysqli_query($database->connection, $insert_query)) {
    // Update task_assign status to 'Graded' for this student & task & exercise
    $updateStatusQuery = "
        UPDATE task_assign
        SET status = 'Graded'
        WHERE task_id = '$task_id'
          AND student_id = '$student_id'
          AND excercise_id = '$excercise_id'
    ";
    mysqli_query($database->connection, $updateStatusQuery);

    $_SESSION['success_message'] = 'Exercise graded successfully!';
} else {
    $_SESSION['error_message'] = 'Failed to save grade: ' . mysqli_error($database->connection);
    file_put_contents('grading_error_log.txt', date('Y-m-d H:i:s') . " - INSERT Error: " . mysqli_error($database->connection) . " - Query: " . $insert_query . "\n", FILE_APPEND);
}
}

// Redirect back to appropriate page based on user level
if ($session->userlevel == 2) { // Teacher
    header("location: student-detail-teacher.php?id=$student_id");
} else { // Admin
    header("location: student-detail-admin.php?student_id=$student_id&task_id=$task_id");
}
exit();
?>