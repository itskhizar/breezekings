<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = intval($_POST['class_id']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $duration = floatval($_POST['duration']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $updated_by = $session->username;

    // Check if override already exists
    $check_q = "SELECT id FROM timetable_overrides WHERE class_id = '$class_id'";
    $check_res = mysqli_query($conn, $check_q);

    if (mysqli_num_rows($check_res) > 0) {
        $update_q = "UPDATE timetable_overrides 
                     SET new_start_time = '$start_time', 
                         new_end_time = '$end_time', 
                         new_duration = '$duration', 
                         status = '$status', 
                         reason = '$reason', 
                         updated_by = '$updated_by' 
                     WHERE class_id = '$class_id'";
        $success = mysqli_query($conn, $update_q);
    } else {
        $insert_q = "INSERT INTO timetable_overrides (class_id, new_start_time, new_end_time, new_duration, status, reason, updated_by) 
                     VALUES ('$class_id', '$start_time', '$end_time', '$duration', '$status', '$reason', '$updated_by')";
        $success = mysqli_query($conn, $insert_q);
    }

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Class override saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving override: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
