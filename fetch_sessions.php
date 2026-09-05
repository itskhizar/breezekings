<?php
include 'include/classes/session.php';

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("DB Connection Error");
}

if (isset($_POST['course_id'])) {

    $course_id = intval($_POST['course_id']); // sanitize

    // FIND_IN_SET searches inside comma-separated values
    $query = "SELECT * FROM sessions 
              WHERE FIND_IN_SET('$course_id', course_id) 
              ORDER BY title ASC";

    $result = mysqli_query($conn, $query);

    echo "<option selected disabled>Select Timing</option>";

    while ($row = mysqli_fetch_assoc($result)) {

        $start = date("h:i A", strtotime($row['start_time']));
        $end   = date("h:i A", strtotime($row['end_time']));

        $total = (int)$row['slots'];
        $booked = (int)$row['booked_slots'];
        $available = $total - $booked;

        echo "<option value='".$row['id']."' data-available='".$available."'>
                ".$row['title']." (".$start." - ".$end.")
              </option>";
    }
}
?>
