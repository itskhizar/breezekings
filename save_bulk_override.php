<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session_id = intval($_POST['session_id']);
    $weeks = isset($_POST['weeks']) ? $_POST['weeks'] : []; // Array of week numbers
    $days = isset($_POST['days']) ? $_POST['days'] : []; // Array of day names (e.g., 'Monday')
    
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time = mysqli_real_escape_string($conn, $_POST['end_time']);
    $duration = floatval($_POST['duration']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $updated_by = $session->username;

    if (empty($weeks) || empty($days)) {
        echo json_encode(['success' => false, 'message' => 'Please select at least one week and one day.']);
        exit;
    }

    $success_count = 0;
    $error_count = 0;

    foreach ($weeks as $week) {
        foreach ($days as $day) {
            $week = intval($week);
            $day = mysqli_real_escape_string($conn, $day);

            // Find the class_id for this session, week, and day
            $find_q = "SELECT id FROM session_classes WHERE session_id = '$session_id' AND week_number = '$week' AND day_of_week = '$day' LIMIT 1";
            $find_res = mysqli_query($conn, $find_q);

            if ($find_res && mysqli_num_rows($find_res) > 0) {
                $row = mysqli_fetch_assoc($find_res);
                $class_id = $row['id'];

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
                    if (mysqli_query($conn, $update_q)) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                } else {
                    $insert_q = "INSERT INTO timetable_overrides (class_id, new_start_time, new_end_time, new_duration, status, reason, updated_by) 
                                 VALUES ('$class_id', '$start_time', '$end_time', '$duration', '$status', '$reason', '$updated_by')";
                    if (mysqli_query($conn, $insert_q)) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                }
            } else {
                // Class slot not found in session_classes for this week/day
                $error_count++;
            }
        }
    }

    if ($success_count > 0) {
        echo json_encode(['success' => true, 'message' => "Successfully updated $success_count class slots." . ($error_count > 0 ? " Failed to update $error_count slots." : "")]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update any class slots. Ensure the classes are scheduled for the selected weeks and days.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
