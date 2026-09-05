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

    echo "<div class='course-checkbox-group'>";

    // Check if no rows found
    if (mysqli_num_rows($result) == 0) {
        echo "<p style='color:#b00; font-weight:bold; padding:5px;'>No course found in this category.</p>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "
                <label style='display:block; margin:3px 0;'>
                    <input type='checkbox' name='course_ids[]' value='".$row['id']."'>
                    ".$row['course_title']."
                </label>
            ";
        }
    }

    echo "</div>";
}
?>
