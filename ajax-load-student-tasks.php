<?php
/**
 * AJAX Endpoint: Load Student Tasks (JSON Response)
 * 
 * This file fetches all tasks assigned to a student based on their course
 * and returns the data in JSON format for the frontend dashboard.
 */

header('Content-Type: application/json');

// Prevent any PHP errors from breaking JSON
error_reporting(0);
ini_set('display_errors', 0);

// Include session and database classes
include("include/classes/session.php");

// Check if user is logged in
if (!$session->logged_in) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in',
        'tasks' => []
    ]);
    exit();
}

// Get user info
$username = $session->username;
$result = $database->getUserInfo($username);
$reg_no = $result['registration_no'];

// Initialize variables
$student_id = 0;
$course_id = 0;

// Get student data
if ($session->userlevel == 0) {
    $result2 = $database->getstudentbyreg($reg_no);
    if ($result2) {
        $student_id = intval($result2['id']);
        $course_id = intval($result2['course_id']);
    }
}

// Override course_id if provided in POST
if (isset($_POST['course_id']) && !empty($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']);
}

// Also accept GET parameter for backward compatibility
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $course_id = intval($_GET['id']);
}

// Validate course_id
if (empty($course_id)) {
    echo json_encode([
        'success' => false,
        'message' => 'Course ID is required',
        'tasks' => []
    ]);
    exit();
}

try {
    // Database connection
    $conn = $database->connection;
    
    // Get course info first
    $course_query = mysqli_query($conn, "SELECT * FROM courses WHERE id = '$course_id' LIMIT 1");
    $course_data = mysqli_fetch_assoc($course_query);
    $course_title = $course_data['course_title'] ?? 'Unknown Course';
    
    // Fetch tasks for the course - using direct query to ensure it works
    $tasks_query = mysqli_query($conn, "
        SELECT t.*, c.course_title, cat.category 
        FROM tasks t
        LEFT JOIN courses c ON t.course_id = c.id
        LEFT JOIN categories cat ON t.category_id = cat.id
        WHERE t.course_id = '$course_id'
        ORDER BY t.created_at DESC
    ");
    
    $tasks = [];
    
    if ($tasks_query && mysqli_num_rows($tasks_query) > 0) {
        while ($task = mysqli_fetch_assoc($tasks_query)) {
            $task_id = intval($task['id']);
            
            // Get exercises count for this task
            $exercises_query = mysqli_query($conn, "
                SELECT id FROM excercises WHERE task_id = '$task_id'
            ");
            $total_exercises = mysqli_num_rows($exercises_query);
            
            // Count completed/submitted exercises for this student
            $completed = 0;
            $submitted = 0;
            $reviewed = 0;
            
            if ($total_exercises > 0) {
                // Get submission stats
                $stats_query = mysqli_query($conn, "
                    SELECT ta.status
                    FROM task_assign ta
                    INNER JOIN excercises e ON ta.excercise_id = e.id
                    WHERE e.task_id = '$task_id' AND ta.student_id = '$student_id'
                ");
                
                if ($stats_query) {
                    while ($stat = mysqli_fetch_assoc($stats_query)) {
                        $status = strtolower($stat['status'] ?? '');
                        if ($status === 'turned in' || $status === 'submitted') {
                            $submitted++;
                            $completed++;
                        } elseif ($status === 'returned' || $status === 'reviewed') {
                            $reviewed++;
                            $completed++;
                        }
                    }
                }
            }
            
            // Determine overall task status
            $task_status = 'Assigned';
            if ($total_exercises > 0) {
                if ($reviewed === $total_exercises) {
                    $task_status = 'Graded';
                } elseif ($completed === $total_exercises) {
                    $task_status = 'Grading in Progress';
                } elseif ($completed > 0) {
                    $task_status = 'In Progress';
                }
            }
            
            $tasks[] = [
                'id' => $task_id,
                'title' => $task['title'] ?? 'Untitled Task',
                'description' => $task['description'] ?? '',
                'course_id' => intval($task['course_id']),
                'course_title' => $task['course_title'] ?? $course_title,
                'category' => $task['category'] ?? 'N/A',
                'max_time' => $task['max_time'] ?? null,
                'total_marks' => $task['total_marks'] ?? null,
                'created_at' => isset($task['created_at']) ? date("d M Y", strtotime($task['created_at'])) : 'N/A',
                'status' => $task_status,
                'total_exercises' => $total_exercises,
                'completed_exercises' => $completed,
                'submitted_exercises' => $submitted,
                'reviewed_exercises' => $reviewed
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'tasks' => $tasks,
        'total' => count($tasks),
        'student_id' => $student_id,
        'course_id' => $course_id
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'tasks' => []
    ]);
}
?>