<?php
include("include/classes/constants.php");

function urlEncoder($value) {
    return urlencode(base64_encode($value));
}

// Database connection
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
} else {
    echo "Database connected successfully.";
}

$sql = "SELECT * FROM visitors";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // Debug SQL errors
}

$output = "";


if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        /* ---------------- CATEGORY ---------------- */
        if (!empty($row['category_id'])) {
            $q2 = "SELECT category FROM categories WHERE id = '{$row['category_id']}' LIMIT 1";
            $result2 = mysqli_query($conn, $q2);
            $catarray = mysqli_fetch_assoc($result2);

            $category = $catarray ? $catarray['category'] : "N/A";
        } else {
            $category = "N/A";
        }

        /* ---------------- COURSE ---------------- */
        if (!empty($row['course_id'])) {
            $q3 = "SELECT course_title FROM courses WHERE id = '{$row['course_id']}' LIMIT 1";
            $result3 = mysqli_query($conn, $q3);
            $crsarray = mysqli_fetch_assoc($result3);

            $course = $crsarray ? $crsarray['course_title'] : "N/A";
        } else {
            $course = "N/A";
        }

        /* ---------------- OUTPUT ROW ---------------- */
        $output .= "<tr style='white-space: nowrap;'>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['gender']) . "</td>
            <td>" . htmlspecialchars($row['mobile_no']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            

            <td>
            <a href='visitor-details.php?id=" . $row['id'] . "' title='View Details'>
                    <i class='fas fa-eye text-info'></i>
                </a>&nbsp;&nbsp;

                <a href='delete-visitor.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this Visitor?');\">
                    <i class='fas fa-trash-alt text-danger'></i> 
                </a>
            </td>
        </tr>";
    }

} else {
    $output = "<tr><td colspan='6' class='text-center'>No Record Found.</td></tr>";
}



echo $output;

?>
