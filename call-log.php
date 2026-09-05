<?php
include("include/classes/session.php");
header('Content-Type: application/json');

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validate required fields
    if (empty($_POST['enquiry_id']) || empty($_POST['communication_type'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    $communication_type = mysqli_real_escape_string($conn, $_POST['communication_type']);
    
    // Additional validation based on communication type
    if ($communication_type === 'call' && empty($_POST['call_outcome'])) {
        echo json_encode(['success' => false, 'message' => 'Call outcome is required for calls']);
        exit;
    }
    
    // Sanitize inputs
    $enquiry_id = intval($_POST['enquiry_id']);
    $call_outcome = mysqli_real_escape_string($conn, $_POST['call_outcome'] ?? '');
    $call_duration = mysqli_real_escape_string($conn, $_POST['call_duration'] ?? '0:00');
    $duration_seconds = intval($_POST['duration_seconds'] ?? 0);
    $note = mysqli_real_escape_string($conn, $_POST['note'] ?? '');
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status'] ?? '');
    $next_follow_up = mysqli_real_escape_string($conn, $_POST['next_follow_up'] ?? '');
    $next_follow_up_time = mysqli_real_escape_string($conn, $_POST['next_follow_up_time'] ?? '');
    $call_type = mysqli_real_escape_string($conn, $_POST['call_type'] ?? 'Outgoing');
    $counsellor = mysqli_real_escape_string($conn, $_POST['counsellor']);
    
    // Get enquiry details
    $enquiry_query = "SELECT name, mobile_no, enquiry_status FROM admissions WHERE id = $enquiry_id";
    $enquiry_result = mysqli_query($conn, $enquiry_query);
    
    if (!$enquiry_result || mysqli_num_rows($enquiry_result) == 0) {
        echo json_encode(['success' => false, 'message' => 'Enquiry not found']);
        exit;
    }
    
    $enquiry_data = mysqli_fetch_assoc($enquiry_result);
    $previous_status = $enquiry_data['enquiry_status'];
    
    // Set call_type based on communication type
    if ($communication_type === 'whatsapp') {
        $call_type = 'WhatsApp';
        $call_outcome = $call_outcome ?: 'WhatsApp Message';
    }
    
    // Insert call log
    $insert_query = "INSERT INTO call_logs 
        (enquiry_id, name, phone, call_type, call_outcome, call_duration, 
         duration_seconds, date, note, previous_status, new_status, 
         next_follow_up, next_follow_up_time, counsellor, created_at, communication_type)
    VALUES 
        ($enquiry_id, 
         '" . mysqli_real_escape_string($conn, $enquiry_data['name']) . "',
         '" . mysqli_real_escape_string($conn, $enquiry_data['mobile_no']) . "',
         '$call_type', 
         '$call_outcome', 
         '$call_duration', 
         $duration_seconds,
         NOW(), 
         '$note', 
         '$previous_status', 
         " . ($new_status ? "'$new_status'" : "NULL") . ",
         " . ($next_follow_up ? "'$next_follow_up'" : "NULL") . ",
         " . ($next_follow_up_time ? "'$next_follow_up_time'" : "NULL") . ",
         '$counsellor',
         NOW(),
         '$communication_type')";
    
    if (mysqli_query($conn, $insert_query)) {
        
        // Update admission record
        $update_parts = [];
        $update_parts[] = "last_contact_date = NOW()";
        
        if ($communication_type === 'call') {
            $update_parts[] = "total_calls = total_calls + 1";
        }
        
        $update_parts[] = "assigned_counsellor = '$counsellor'";
        
        if (!empty($new_status)) {
            $update_parts[] = "enquiry_status = '$new_status'";
        }
        
        if (!empty($next_follow_up)) {
            $update_parts[] = "next_follow_up = '$next_follow_up'";
        }
        
        if (!empty($next_follow_up_time)) {
            $update_parts[] = "next_follow_up_time = '$next_follow_up_time'";
        }
        
        if (!empty($note)) {
            $comm_label = $communication_type === 'whatsapp' ? 'WhatsApp' : 'Call';
            $update_parts[] = "remarks = CONCAT(COALESCE(remarks, ''), '\n\n[" . date('d M Y H:i') . " - $counsellor - $comm_label]\n$note')";
        }
        
        $update_query = "UPDATE admissions SET " . implode(", ", $update_parts) . " WHERE id = $enquiry_id";
        mysqli_query($conn, $update_query);
        
        echo json_encode([
            'success' => true, 
            'message' => ucfirst($communication_type) . ' log saved successfully',
            'call_id' => mysqli_insert_id($conn)
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to save log: ' . mysqli_error($conn)
        ]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

mysqli_close($conn);
?>