<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!empty($_POST['course_id'])) {

    $course_id = $_POST['course_id'];

    // Fetch students based on course
    $query = "SELECT * FROM students 
              WHERE course_id = '$course_id'
              ORDER BY name ASC";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        echo "<label class='fw-bold'>Select Students:</label>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "
                <div class='form-check mb-1'>
                    <input class='form-check-input' type='checkbox' name='students[]' value='".$row['id']."' id='s".$row['id']."'>
                    <label class='form-check-label' for='s".$row['id']."'>
                        ".$row['name']."
                    </label>
                </div>
            ";
        }

    } else {
        echo "<span class='text-muted'>No students found for this course.</span>";
    }
}
?>
