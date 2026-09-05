<?php
include 'include/classes/session.php';

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("DB Connection Error");
}

if (isset($_POST['class_id'])) {

    $class_id = $_POST['class_id'];

    $query = "SELECT * FROM sessions WHERE class_id = '$class_id' ORDER BY title ASC";
    $result = mysqli_query($conn, $query);

    echo "<option selected disabled>Select Session</option>";

    while ($row = mysqli_fetch_assoc($result)) {

        // Get course ID from this session
        $course_id = $row['course_id'];

        // Fetch course title
        $query2 = "SELECT course_title FROM courses WHERE id = '$course_id' LIMIT 1";
        $result2 = mysqli_query($conn, $query2);
        $courseRow = mysqli_fetch_assoc($result2);

        $course_title = $courseRow['course_title'] ?? "N/A";

        // Format time
        $start = date("h:i A", strtotime($row['start_time']));
        $end   = date("h:i A", strtotime($row['end_time']));

        echo "<option value='".$row['id']."'>
                ".$row['title']." - ".$course_title." (".$start." - ".$end.")
              </option>";
    }
}
?>
