<?php
include("include/classes/session.php");
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set headers to force download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=visitors_export_' . date('Y-m-d') . '.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, array('ID', 'Category', 'Course', 'Name', 'Gender', 'Mobile No', 'Email', 'Current Grade', 'Institute', 'Address', 'Created At'));

// Fetch visitors data
$query = "SELECT `id`, `category_id`, `course_id`, `name`, `gender`, `mobile_no`, `email`, `current_grade`, `institute`, `address`, `created_at` FROM `visitors` ORDER BY `created_at` DESC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {

    // Get category name
    if (!empty($row['category_id'])) {
        $q2 = "SELECT category FROM categories WHERE id = '{$row['category_id']}' LIMIT 1";
        $result2 = mysqli_query($conn, $q2);
        $catarray = mysqli_fetch_assoc($result2);
        $category = $catarray ? $catarray['category'] : "N/A";
    } else {
        $category = "N/A";
    }

    // Get course name
    if (!empty($row['course_id'])) {
        $q3 = "SELECT course_title FROM courses WHERE id = '{$row['course_id']}' LIMIT 1";
        $result3 = mysqli_query($conn, $q3);
        $crsarray = mysqli_fetch_assoc($result3);
        $course = $crsarray ? $crsarray['course_title'] : "N/A";
    } else {
        $course = "N/A";
    }

    // Format created_at nicely
    $row['created_at'] = date("d M Y, h:i A", strtotime($row['created_at']));

    // Prepare row for CSV
    $csv_row = array(
        $row['id'],
        $category,
        $course,
        $row['name'],
        $row['gender'],
        $row['mobile_no'],
        $row['email'],
        $row['current_grade'],
        $row['institute'],
        $row['address'],
        $row['created_at']
    );

    fputcsv($output, $csv_row);
}

fclose($output);
exit;
?>
