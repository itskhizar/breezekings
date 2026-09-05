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

$sql = "SELECT * FROM classes";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // Debug SQL errors
}

$output = "";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<tr>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['description']) . "</td>
            <td>" . htmlspecialchars($row['slots']) . "</td>
            <td><a href='class-details.php?id=".$row['id']."' class='btn-action btn-view'  title='View Details'>
                <i class='fas fa-eye'></i></a>
                <a href='delete-class.php?id=". $row['id'] ."' 
                   class='btn btn-sm btn-outline-danger rounded delete-btn'
                   title='Delete'
                   data-id='". $row['id']."'>
                   <i class='fas fa-trash-alt'></i>
                </a>

            </td>
        </tr>";
    }
} else {
    $output = "<tr><td colspan='6' class='text-center'>No Record Found.</td></tr>";
}

echo $output;

?>
