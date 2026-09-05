<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = intval($_POST['class_id']);
    
    $delete_q = "DELETE FROM timetable_overrides WHERE class_id = '$class_id'";
    
    if (mysqli_query($conn, $delete_q)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove override']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
