<?php
include("include/classes/session.php");

header('Content-Type: application/json');

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enquiry_id']) && isset($_POST['status'])) {
    
    $enquiry_id = intval($_POST['enquiry_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_query = "UPDATE admissions SET 
        enquiry_status = '$new_status',
        last_contact_date = NOW()
    WHERE id = $enquiry_id";
    
    if (mysqli_query($conn, $update_query)) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

mysqli_close($conn);
?>