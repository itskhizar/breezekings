<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed");
}

echo "Scanning for incorrect end times...\n";
$res = mysqli_query($conn, "SELECT * FROM session_classes WHERE end_time = '01:00:00' OR end_time = '12:00 AM' OR end_time = '01:00 AM'");
$count = 0;

while ($row = mysqli_fetch_assoc($res)) {
    $id = $row['id'];
    $start_time = $row['start_time'];
    $duration = $row['duration_hours'];
    
    $start_ts = strtotime($start_time);
    if ($start_ts) {
        $end_ts = $start_ts + ($duration * 3600);
        $new_end_time = date('h:i A', $end_ts);
        
        // Also format start_time to h:i A if it's in 24h format
        $new_start_time = date('h:i A', $start_ts);
        
        $update_q = "UPDATE session_classes SET end_time = '$new_end_time', start_time = '$new_start_time' WHERE id = '$id'";
        if (mysqli_query($conn, $update_q)) {
            $count++;
            echo "Fixed ID $id: $new_start_time - $new_end_time\n";
        }
    }
}

echo "\nTotal records fixed: $count\n";
mysqli_close($conn);
?>
