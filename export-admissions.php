<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
include("include/classes/session.php");
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

ob_end_clean(); // Clear any output before sending CSV headers

// Set headers to force download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=admissions_export_' . date('Y-m-d') . '.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, array(
    'ID', 'Name', 'Gender', 'Mobile No', 'Email', 'Current Grade', 'Institute',
    'Field Education', 'Field Interest', 'Education Status', 'Grade', 'Current Semester',
    'Other Degree', 'Graduation Year', 'Address', 'Additional Info',
    'Visit Date', 'Visit Time', 'Submitted At', 'Reference'
));

// Fetch data
$query = "SELECT `id`, `name`, `gender`, `mobile_no`, `email`, `current_grade`, `institute`,
          `field_education`, `field_interest`, `education_status`, `grade`, `current_semester`,
          `other_degree`, `graduation_year`, `address`, `additional_info`,
          `visit_date`, `visit_time`, `timestamp`, `reference`
          FROM `admissions`
          ORDER BY `timestamp` DESC";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $row['mobile_no'] = "'" . $row['mobile_no'];
    $row['visit_time'] = !empty($row['visit_time']) ? date("h:i A", strtotime($row['visit_time'])) : '';
    $row['timestamp'] = !empty($row['timestamp']) ? date("d M Y, h:i A", strtotime($row['timestamp'])) : '';

    fputcsv($output, $row);
}

fclose($output);
exit;
