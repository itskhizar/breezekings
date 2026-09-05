<?php
include("include/classes/session.php");

function test_endpoint($url, $data) {
    echo "Testing $url...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost/Academy/" . $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Pass session cookie if needed, but for local testing without session, 
    // we might need to mock session in the endpoint or just see the error.
    // Let's assume the session works for now or we see the "Unauthorized" JSON.
    
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    echo "HTTP Status: " . $info['http_code'] . "\n";
    echo "Raw Response:\n";
    echo "----------------------------------------\n";
    echo $response . "\n";
    echo "----------------------------------------\n";
    
    $json = json_decode($response);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "JSON Parse: SUCCESS\n";
    } else {
        echo "JSON Parse: FAILED (" . json_last_error_msg() . ")\n";
    }
    echo "\n\n";
}

// Test data
$student_id = 35; // Example student
$course_id = 1;   // Example course
$task_id = 41;    // Example task

test_endpoint('ajax-load-teacher-student-tasks.php', ['student_id' => $student_id, 'course_id' => $course_id]);
test_endpoint('ajax-get-teacher-task-details.php', ['task_id' => $task_id, 'student_id' => $student_id]);
test_endpoint('ajax-load-admin-student-tasks.php', ['student_id' => $student_id, 'course_id' => $course_id]);
test_endpoint('ajax-get-admin-task-details.php', ['task_id' => $task_id, 'student_id' => $student_id]);
