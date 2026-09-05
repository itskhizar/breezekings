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

$session_id = isset($_GET['id']) ? $_GET['id'] : '';
$sql = "SELECT * FROM students WHERE session_id = '$session_id'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // Debug SQL errors
}

$output = "";


if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        // Get Category
        $q = "SELECT * FROM categories WHERE id = '{$row['category_id']}'";
        $result2 = mysqli_query($conn, $q);
        $catarray = mysqli_fetch_assoc($result2);

        // Status Badge
        $status = strtolower($row['status']);

        if ($status == "pending") {
            $badge = "<span class='badge badge-warning'>Pending</span>";
        } 
        elseif ($status == "active") {
            $badge = "<span class='badge badge-success'>Active</span>";
        } 
        elseif ($status == "inactive") {
            $badge = "<span class='badge badge-danger'>Inactive</span>";
        } 
        else {
            $badge = "<span class='badge badge-secondary'>" . htmlspecialchars($row['status']) . "</span>";
        }

        // Output Row
        $output .= "<tr style='white-space: nowrap;'>
            <td>" . htmlspecialchars($row['admission_no']) . "</td>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['mobile_no']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>$badge</td>
            <td><a href='student-detail-teacher.php?id=" . $row['id'] . "' class='action-btn-modern'>
                    <i class='fas fa-eye'></i> View Details
                </a></td>
        </tr>";
    }
} else {
    $output = "<tr><td colspan='6' class='text-center'>No Record Found.</td></tr>";
}


echo $output;

?>
