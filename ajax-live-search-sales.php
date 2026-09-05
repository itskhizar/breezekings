<?php
include("include/classes/constants.php");

function urlEncoder($value) {
    return urlencode(base64_encode($value));
}

$search_value = $_POST["search"] ?? ''; // Use null coalescing operator for safety

// Database connection
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to search based on first name, last name, and email
$sql = "SELECT * FROM sales 
        WHERE (customer_id LIKE '%{$search_value}%' 
            OR product_id LIKE '%{$search_value}%'
            OR product_name LIKE '%{$search_value}%'
            OR sale_person LIKE '%{$search_value}%') ";

$result = mysqli_query($conn, $sql) or die("SQL Query Failed.");

// Initialize output variable
$output = "";

// Check if records are found
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // $url = urlEncoder($row['reference_no']);
        // Use default fallback image if photo is not available
        // $photo = !empty($row['photo']) ? "requitment/" . htmlspecialchars($row['photo']) : "images/avatar.png";

        // Build the table row
         $output .= "<tr>
            <td>" . htmlspecialchars($row['customer_id']) . "</td>
            <td>" . htmlspecialchars($row['customer_name']) . "</td>
            <td>" . htmlspecialchars($row['product_id']) . "</td>
            <td>" . htmlspecialchars($row['product_name']) . "</td>
            <td>" . htmlspecialchars($row['sale_person']) . "</td>
            <td><a href='schooldetails.php'>View Details</a></td>
        </tr>";
    }
} else {
    // If no results, show a message
    $output = "<tr><td colspan='6' class='text-center'>No Record Found.</td></tr>";
}

// Close the database connection
mysqli_close($conn);

// Return the output
echo $output;
?>
