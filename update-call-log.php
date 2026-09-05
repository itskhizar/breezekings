<?php
include("include/classes/session.php");
header('Content-Type: application/json');

// Check if user is logged in
if (!$session->logged_in) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validate required fields
    if (empty($_POST['call_log_id']) || empty($_POST['enquiry_id'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    // Sanitize inputs
    $call_log_id = intval($_POST['call_log_id']);
    $enquiry_id = intval($_POST['enquiry_id']);
    $communication_type = mysqli_real_escape_string($conn, $_POST['communication_type'] ?? 'call');
    $call_outcome = mysqli_real_escape_string($conn, $_POST['call_outcome'] ?? '');
    $call_duration = mysqli_real_escape_string($conn, $_POST['call_duration'] ?? '0:00');
    $duration_seconds = intval($_POST['duration_seconds'] ?? 0);
    $note = mysqli_real_escape_string($conn, $_POST['note'] ?? '');
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status'] ?? '');
    $next_follow_up = mysqli_real_escape_string($conn, $_POST['next_follow_up'] ?? '');
    $next_follow_up_time = mysqli_real_escape_string($conn, $_POST['next_follow_up_time'] ?? '');
    $call_type = mysqli_real_escape_string($conn, $_POST['call_type'] ?? 'Outgoing');
    
    // Get current call log data
    $current_query = "SELECT * FROM call_logs WHERE id = $call_log_id AND enquiry_id = $enquiry_id";
    $current_result = mysqli_query($conn, $current_query);
    
    if (!$current_result || mysqli_num_rows($current_result) == 0) {
        echo json_encode(['success' => false, 'message' => 'Call log not found']);
        exit;
    }
    
    $current_data = mysqli_fetch_assoc($current_result);
    
    // Update call log
    $update_query = "UPDATE call_logs SET 
        call_type = '$call_type',
        call_outcome = '$call_outcome',
        call_duration = '$call_duration',
        duration_seconds = $duration_seconds,
        note = '$note',
        new_status = " . ($new_status ? "'$new_status'" : "NULL") . ",
        next_follow_up = " . ($next_follow_up ? "'$next_follow_up'" : "NULL") . ",
        next_follow_up_time = " . ($next_follow_up_time ? "'$next_follow_up_time'" : "NULL") . ",
        communication_type = '$communication_type'
    WHERE id = $call_log_id AND enquiry_id = $enquiry_id";
    
    if (mysqli_query($conn, $update_query)) {
        
        // Update admission record if status or follow-up changed
        $update_parts = [];
        
        if (!empty($new_status) && $new_status != $current_data['new_status']) {
            $update_parts[] = "enquiry_status = '$new_status'";
        }
        
        if (!empty($next_follow_up)) {
            $update_parts[] = "next_follow_up = '$next_follow_up'";
        }
        
        if (!empty($next_follow_up_time)) {
            $update_parts[] = "next_follow_up_time = '$next_follow_up_time'";
        }
        
        if (!empty($update_parts)) {
            $admission_update = "UPDATE admissions SET " . implode(", ", $update_parts) . " WHERE id = $enquiry_id";
            mysqli_query($conn, $admission_update);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => 'Call log updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to update log: ' . mysqli_error($conn)
        ]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>
