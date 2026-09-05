<?php
include 'include/classes/session.php';

header('Content-Type: application/json');

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Check if required parameters are present
if (!isset($_POST['student_id']) || !isset($_POST['session_id'])) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

$student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
$session_id = mysqli_real_escape_string($conn, $_POST['session_id']);

// Optional date range parameters
$start = isset($_POST['start']) ? mysqli_real_escape_string($conn, $_POST['start']) : null;
$end = isset($_POST['end']) ? mysqli_real_escape_string($conn, $_POST['end']) : null;

// Build query
if ($start && $end) {
    $query = "SELECT 
        DATE(created_at) as attendance_date,
        attendance,
        entry_time,
        exit_time,
        note
    FROM student_attendance 
    WHERE student_id = '$student_id' 
    AND session_id = '$session_id'
    AND DATE(created_at) BETWEEN '$start' AND '$end'
    ORDER BY created_at ASC";
} else {
    $query = "SELECT 
        DATE(created_at) as attendance_date,
        attendance,
        entry_time,
        exit_time,
        note
    FROM student_attendance 
    WHERE student_id = '$student_id' 
    AND session_id = '$session_id'
    ORDER BY created_at ASC";
}

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]);
    exit;
}

$events = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $className = '';
        
        // Set CSS class based on attendance status
        switch ($row['attendance']) {
            case 'Present':
                $className = 'present';
                break;
            case 'Leave':
                $className = 'late';
                break;
            case 'Absent':
                $className = 'absent';
                break;
            case 'Holiday':
                $className = 'holiday';
                break;
            case 'Half Day':
                $className = 'halfday';
                break;
            default:
                $className = 'present';
        }
        
        $events[] = [
            'title' => $row['attendance'],
            'start' => $row['attendance_date'],
            'allDay' => true,
            'className' => $className,
            'extendedProps' => [
                'entry_time' => $row['entry_time'] ?? '',
                'exit_time' => $row['exit_time'] ?? '',
                'note' => $row['note'] ?? ''
            ]
        ];
    }
}

mysqli_close($conn);

echo json_encode($events);
?>