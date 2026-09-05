<?php
include("include/classes/session.php");
header('Content-Type: application/json');

// Check if user is logged in and is admin
if (!$session->logged_in || ($session->userlevel != 1 && $session->userlevel != 4)) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Get and sanitize input
$student_id = isset($_POST['student_id']) ? mysqli_real_escape_string($database->connection, $_POST['student_id']) : '';
$course_id = isset($_POST['course_id']) ? mysqli_real_escape_string($database->connection, $_POST['course_id']) : '';

if (empty($student_id)) {
    echo json_encode(['success' => false, 'message' => 'Student ID is required']);
    exit();
}

// Build query to fetch all tasks for this student's course
$tasks_query = "
    SELECT 
        t.id,
        t.title,
        t.description,
        t.max_time,
        t.total_marks,
        t.created_at,
        c.course_title,
        cat.category as category_name,
        (SELECT COUNT(*) FROM excercises WHERE task_id = t.id) as total_exercises,
        (SELECT COUNT(*) 
         FROM task_assign 
         WHERE task_id = t.id 
         AND student_id = '$student_id' 
         AND excercise_id IS NOT NULL 
         AND status IN ('Turned In', 'Returned')) as submitted_exercises,
        (SELECT COUNT(*) 
         FROM task_assign 
         WHERE task_id = t.id 
         AND student_id = '$student_id' 
         AND excercise_id IS NOT NULL 
         AND status = 'Graded') as graded_exercises,
        (SELECT SUM(marks) FROM teacher_grades WHERE task_id = t.id AND student_id = '$student_id') as total_obtained_marks

    FROM tasks t
    INNER JOIN courses c ON t.course_id = c.id
    LEFT JOIN categories cat ON t.category_id = cat.id
    WHERE t.course_id = '$course_id'
    ORDER BY t.created_at DESC
";

$tasks_result = mysqli_query($database->connection, $tasks_query);

if (!$tasks_result) {
    echo json_encode(['success' => false, 'message' => 'Database query failed: ' . mysqli_error($database->connection)]);
    exit();
}

$tasks = [];
while ($row = mysqli_fetch_assoc($tasks_result)) {
    // Calculate status logic
    $status = 'Assigned';
    $total_ex = (int)$row['total_exercises'];
    $sub_ex = (int)$row['submitted_exercises'];
    $grad_ex = (int)$row['graded_exercises'];

    if ($total_ex > 0) {
        if ($grad_ex == $total_ex) {
            $status = 'Graded';
        } elseif ($sub_ex == $total_ex) {
            $status = 'Grading in Progress';
        } elseif ($sub_ex > 0) {
            $status = 'In Progress';
        }
    }

    $tasks[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'description' => $row['description'],
        'max_time' => $row['max_time'],
        'total_marks' => $row['total_marks'],
        'created_at' => date('M d, Y', strtotime($row['created_at'])),
        'course_title' => $row['course_title'],
        'category' => $row['category_name'],
        'status' => $status,
        'total_exercises' => $total_ex,
        'submitted_exercises' => $sub_ex,
        'graded_exercises' => $grad_ex,
        'total_obtained_marks' => $row['total_obtained_marks']
    ];
}

echo json_encode([
    'success' => true,
    'tasks' => $tasks,
    'total_count' => count($tasks)
]);
?>