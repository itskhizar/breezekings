<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed");
}

$session_id = isset($_GET['session_id']) ? intval($_GET['session_id']) : 0;
if ($session_id == 0) {
    $res = mysqli_query($conn, "SELECT session_id FROM session_classes ORDER BY id DESC LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    $session_id = $row['session_id'] ?? 0;
}

echo "Session ID: $session_id\n";
$res = mysqli_query($conn, "SELECT * FROM session_classes WHERE session_id = '$session_id' LIMIT 10");
while($row = mysqli_fetch_assoc($res)) {
    echo "ID: " . $row['id'] . " | Day: " . $row['day_of_week'] . " | Start: [" . $row['start_time'] . "] | End: [" . $row['end_time'] . "] | Dur: " . $row['duration_hours'] . "\n";
}

mysqli_close($conn);
?>
