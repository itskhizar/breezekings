<?php
include("include/classes/constants.php");

function urlEncoder($value) {
    return urlencode(base64_encode($value));
}

// Database connection
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM sessions";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$output = "";

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        // ---------------------------------------
        //   GET MULTIPLE COURSES (course_ids)
        // ---------------------------------------
        $courses_list = "N/A";

        if (!empty($row['course_id'])) {

            $ids = explode(",", $row['course_id']);  // Array like [3,7,12]

            // Convert to integers for safe IN ()
            $ids_clean = array_map('intval', $ids);
            $ids_string = implode(",", $ids_clean);

            // Fetch all course names in one query
            $q = "SELECT course_title FROM courses WHERE id IN ($ids_string)";
            $result2 = mysqli_query($conn, $q);

            $course_names = [];
            while ($cr = mysqli_fetch_assoc($result2)) {
                $course_names[] = $cr['course_title'];
            }

            // Convert course names to comma-separated list
            $courses_list = implode(", ", $course_names);
        }

        // Calculate available slots
        $total_slots = $row['slots'];
        $booked_slots = $row['booked_slots'];
        $available_slots = (int)$total_slots - (int)$booked_slots;

        // ---------------------------------------
        //   OUTPUT ROW
        // ---------------------------------------
        $output .= "<tr>
            <td>" . htmlspecialchars($row['title']) . "</td>
            <td>" . htmlspecialchars($courses_list) . "</td>
            <td>" . htmlspecialchars($row['slots']) . "</td>
            <td>" . htmlspecialchars($row['booked_slots']) . "</td>
            <td>" . htmlspecialchars($available_slots) . "</td>
            <td>
            <a href='session-details.php?id=" . $row['id'] . "' class='btn-action btn-view'  title='View Details'>
                <i class='fas fa-eye'></i></a>
                <a href='javascript:void(0);' 
                   class='btn-action btn-edit me-2' 
                   style='background: linear-gradient(135deg, #0dcaf0, #0aa2c0); color: white;'
                   title='Edit Session'
                   data-id='" . $row['id'] . "'
                   data-title='" . htmlspecialchars($row['title'], ENT_QUOTES) . "'
                   data-class-id='" . $row['class_id'] . "'
                   data-category-id='" . $row['category_id'] . "'
                   data-course-id='" . $row['course_id'] . "'
                   data-teacher-id='" . $row['teacher_id'] . "'
                   data-start-time='" . $row['start_time'] . "'
                   data-end-time='" . $row['end_time'] . "'
                   data-slots='" . $row['slots'] . "'
                   onclick='openEditModal(this)'>
                    <i class='fas fa-edit'></i>
                </a>
                
                <a href='delete-session.php?id=" . $row['id'] . "' 
                class='btn btn-sm btn-outline-danger rounded'
                title='Delete'
                onclick=\"return confirm('Are you sure you want to delete this session?');\">
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
