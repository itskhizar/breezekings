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

$sql = "SELECT * FROM admissions";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn)); // Debug SQL errors
}

$output = "";


if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        // Determine academic field based on education_status
        $status = $row['education_status'];
        if (in_array($status, ['Primary', 'Secondary', 'Intermediate'])) {
            $academic = $row['grade'];
        } elseif ($status == 'Undergraduate') {
            $academic = $row['current_semester'];
        } elseif ($status == 'Graduate') {
            $academic = $row['graduation_year'];
        } elseif ($status === 'Other') {
            $academic = $row['other_degree'];
        } else {
            $academic = ''; // fallback if empty or unknown status
        }
        $visit_display = "";

        if (!empty($row['visit_date']) && !empty($row['visit_time'])) {
            $visit_date = date("d M Y", strtotime($row['visit_date']));
            $visit_time = date("h:i A", strtotime($row['visit_time']));
            $visit_display = $visit_date . " - " . $visit_time;
        } else {
            $visit_display = "N/A";
        }

        // Output Row
        $output .= "<tr style='white-space: nowrap;'>
            <td>" . htmlspecialchars($row['id']) . "</td>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['gender']) . "</td>
            <td>" . htmlspecialchars($row['mobile_no']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            
            
            <td>" . htmlspecialchars($row['reference']) . "</td>
            <td>
                <!-- View Details -->
                <a href='admission-details.php?id=" . $row['id'] . "' title='View Details'>
                    <i class='fas fa-eye text-info'></i>
                </a>&nbsp;&nbsp;

               <a href='add-student.php?id=" . $row['id'] . "' title='Register Student'>
                    <i class=\"fas fa-user-plus text-primary\"></i>
                </a>&nbsp;&nbsp;
                <a href='delete-admission.php?id=" . $row['id'] . "' title='Delete Student' onclick=\"return confirm('Are you sure you want to delete this Admission?');\">
                    <i class='fas fa-trash-alt text-danger'></i> 
                </a>
            </td>

        </tr>";
    }
} else {
    $output = "<tr><td colspan='12' class='text-center'>No Record Found.</td></tr>";
}


echo $output;

?>
