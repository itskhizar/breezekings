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

$sql = "SELECT * FROM users WHERE userlevel = 4";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // Debug SQL errors
}

$output = "";


if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

       

        // Output Row
        $output .= "<tr style='white-space: nowrap;'>
            <td>" . htmlspecialchars($row['registration_no']) . "</td>
            <td>" . htmlspecialchars($row['display_name']) . "</td>
            <td>" . htmlspecialchars($row['phone']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>" . date('d-M-Y',strtotime($row['created_at'])) . "</td>
            <td>
            	<a href='#" . $row['username'] . "' onclick=\"return confirm('Are you sure you want to delete this User?');\">
            <i class='fas fa-trash-alt text-danger'></i> 
        </a>&nbsp;
        <a href='#'>
    <i class='fas fa-edit text-warning'></i>
</a>

    		</td>
        </tr>";

    }
} else {
    $output = "<tr><td colspan='6' class='text-center'>No Record Found.</td></tr>";
}


echo $output;

?>
