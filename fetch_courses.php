<?php
include 'include/classes/session.php';

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("DB Connection Error");
}

if (isset($_POST['category_id'])) {

    $category_id = $_POST['category_id'];

    $query = "SELECT * FROM courses WHERE category_id = '$category_id' ORDER BY course_title ASC";
    $result = mysqli_query($conn, $query);

    echo "<option selected disabled>Select Course</option>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='".$row['id']."'>".$row['course_title']."</option>";
    }
}
?>
