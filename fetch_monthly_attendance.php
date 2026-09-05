<?php
include 'include/classes/session.php';

header('Content-Type: application/json');

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo json_encode([]);
    exit;
}

$student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
$session_id = mysqli_real_escape_string($conn, $_POST['session_id']);
$month = mysqli_real_escape_string($conn, $_POST['month']);
$year = mysqli_real_escape_string($conn, $_POST['year']);

$query = "SELECT 
    DATE(sa.created_at) as attendance_date,
    sa.attendance,
    sa.entry_time,
    sa.exit_time,
    sa.note,
    s.start_time
FROM student_attendance sa
JOIN sessions s ON sa.session_id = s.id
WHERE sa.student_id = '$student_id' 
AND sa.session_id = '$session_id'
AND MONTH(sa.created_at) = '$month'
AND YEAR(sa.created_at) = '$year'
ORDER BY sa.created_at ASC";

$result = mysqli_query($conn, $query);
$attendance_data = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        
        $late_minutes = 0;
        if ($row['attendance'] == 'Present' && $row['entry_time'] && $row['start_time']) {
            $entry = strtotime($row['entry_time']);
            $start = strtotime($row['start_time']);
            if ($entry > $start) {
                $late_minutes = round(($entry - $start) / 60);
            }
        }

        $attendance_data[$row['attendance_date']] = [
            'status' => $row['attendance'],
            'entry_time' => $row['entry_time'],
            'exit_time' => $row['exit_time'],
            'note' => $row['note'],
            'late_minutes' => $late_minutes
        ];
    }
}

mysqli_close($conn);

echo json_encode($attendance_data);
?>