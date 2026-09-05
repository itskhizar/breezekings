<?php
include("include/classes/session.php");
header('Content-Type: application/json');

// Check if logged in
if (!$session->logged_in) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get POST data
$session_id = isset($_POST['session_id']) ? mysqli_real_escape_string($database->connection, $_POST['session_id']) : '';
$title = isset($_POST['title']) ? mysqli_real_escape_string($database->connection, $_POST['title']) : '';
$slots = isset($_POST['slots']) ? mysqli_real_escape_string($database->connection, $_POST['slots']) : '';
$start_time = isset($_POST['start_time']) ? mysqli_real_escape_string($database->connection, $_POST['start_time']) : '';
$end_time = isset($_POST['end_time']) ? mysqli_real_escape_string($database->connection, $_POST['end_time']) : '';

// Validation
if (empty($session_id) || empty($title) || empty($slots) || empty($start_time) || empty($end_time)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit();
}

// Update Query
// Note: We are only updating the fields exposed in the modal for now to prevent accidental relationship breaking.
// class_id, category_id, course_id, teacher_id are preserved.
$query = "UPDATE sessions SET 
            title = '$title', 
            slots = '$slots', 
            start_time = '$start_time', 
            end_time = '$end_time' 
          WHERE id = '$session_id'";

if (mysqli_query($database->connection, $query)) {
    echo json_encode(['success' => true, 'message' => 'Session updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($database->connection)]);
}
?>
