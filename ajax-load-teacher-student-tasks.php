<?php
include("include/classes/session.php");
header('Content-Type: application/json');
// echo json_encode(['post_received' => $_POST]);


// Check if user is logged in and is a teacher
if (!$session->logged_in || $session->userlevel != 2) {
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

// Fetch all tasks for this student's course
$query = "
    SELECT 
        t.id,
        t.title,
        t.description,
        t.max_time,
        t.total_marks,
        t.created_at,
        t.exercise_ids,
        c.course_title,
        cat.category as category_name,
        (SELECT COUNT(*) 
         FROM task_assign 
         WHERE task_id = t.id 
         AND student_id = '$student_id' 
         AND excercise_id IS NOT NULL 
         AND status IN ('Turned In', 'Returned', 'Graded')) as submitted_exercises,
        (SELECT COUNT(*) 
         FROM task_assign 
         WHERE task_id = t.id 
         AND student_id = '$student_id' 
         AND excercise_id IS NOT NULL 
         AND status = 'Graded') as graded_exercises,
        (SELECT SUM(marks) FROM teacher_grades WHERE task_id = t.id AND student_id = '$student_id') as total_obtained_marks,
        COALESCE(
            (SELECT status 
             FROM task_assign 
             WHERE task_id = t.id 
             AND student_id = '$student_id' 
             LIMIT 1), 
            'Pending'
        ) as status
    FROM tasks t
    INNER JOIN courses c ON t.course_id = c.id
    LEFT JOIN categories cat ON t.category_id = cat.id
    WHERE t.course_id = '$course_id'
    ORDER BY t.created_at ASC
";

$result = mysqli_query($database->connection, $query);

if (!$result) {
    file_put_contents('debug_ajax_teacher.txt', date('Y-m-d H:i:s') . " - SQL Error: " . mysqli_error($database->connection) . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Database query failed: ' . mysqli_error($database->connection)]);
    exit();
}
file_put_contents('debug_ajax_teacher.txt', date('Y-m-d H:i:s') . " - SQL Success. Rows: " . mysqli_num_rows($result) . "\n", FILE_APPEND);


$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Calculate total exercises from comma-separated ID list
    $exercise_ids = trim($row['exercise_ids']);
    $total_exercises = !empty($exercise_ids) ? count(explode(',', $exercise_ids)) : 0;

    // Calculate status logic
    $status = 'Assigned';
    if ($total_exercises > 0) {
        if ($row['graded_exercises'] == $total_exercises) {
            $status = 'Graded';
        } elseif ($row['submitted_exercises'] == $total_exercises) {
            $status = 'Grading in Progress';
        } elseif ($row['submitted_exercises'] > 0) {
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
        'total_exercises' => $total_exercises,
        'submitted_exercises' => (int) $row['submitted_exercises'],
        'graded_exercises' => (int) $row['graded_exercises'],
        'total_obtained_marks' => $row['total_obtained_marks']
    ];
}

echo json_encode([
    'success' => true,
    'tasks' => $tasks,
    'student_id' => $student_id
]);
?>