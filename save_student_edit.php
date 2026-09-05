<?php
include("include/classes/session.php");

if (!$session->logged_in || ($session->userlevel != 1 && $session->userlevel != 4)) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid student ID']);
        exit();
    }

    // Escape and Sanitize Inputs
    $name = mysqli_real_escape_string($database->connection, $_POST['name']);
    $gender = mysqli_real_escape_string($database->connection, $_POST['gender']);
    $dob = mysqli_real_escape_string($database->connection, $_POST['dob']);
    $cnic = mysqli_real_escape_string($database->connection, $_POST['cnic']);
    $mobile_no = mysqli_real_escape_string($database->connection, $_POST['mobile_no']);
    $email = mysqli_real_escape_string($database->connection, $_POST['email']);
    $address = mysqli_real_escape_string($database->connection, $_POST['address']);
    
    $institute = mysqli_real_escape_string($database->connection, $_POST['institute']);
    $field_education = mysqli_real_escape_string($database->connection, $_POST['field_education']);
    $education_status = mysqli_real_escape_string($database->connection, $_POST['education_status']);
    
    $current_grade = mysqli_real_escape_string($database->connection, $_POST['current_grade']);
    $current_semester = mysqli_real_escape_string($database->connection, $_POST['current_semester']);
    $graduation_year = mysqli_real_escape_string($database->connection, $_POST['graduation_year']);
    $other_degree = mysqli_real_escape_string($database->connection, $_POST['other_degree']);
    
    $guardian_name = mysqli_real_escape_string($database->connection, $_POST['guardian_name']);
    $guardian_relation = mysqli_real_escape_string($database->connection, $_POST['guardian_relation']);
    $guardian_phone = mysqli_real_escape_string($database->connection, $_POST['guardian_phone']);
    $slot = mysqli_real_escape_string($database->connection, $_POST['slot']);

    // Construct Update Query
    $q = "UPDATE students SET 
            name = '$name',
            gender = '$gender',
            dob = '$dob',
            cnic = '$cnic',
            mobile_no = '$mobile_no',
            email = '$email',
            address = '$address',
            institute = '$institute',
            field_education = '$field_education',
            education_status = '$education_status',
            current_grade = '$current_grade',
            current_semester = '$current_semester',
            graduation_year = '$graduation_year',
            other_degree = '$other_degree',
            guardian_name = '$guardian_name',
            guardian_relation = '$guardian_relation',
            guardian_phone = '$guardian_phone',
            slot = '$slot'
          WHERE id = '$id'";

    if (mysqli_query($database->connection, $q)) {
        // Also update the users table if registration_no matches (optional but recommended for consistency)
        // First get registration_no
        $res = mysqli_query($database->connection, "SELECT registration_no FROM students WHERE id = '$id'");
        if ($row = mysqli_fetch_assoc($res)) {
            $reg_no = $row['registration_no'];
            $q_user = "UPDATE users SET 
                        display_name = '$name',
                        email = '$email',
                        phone = '$mobile_no'
                       WHERE registration_no = '$reg_no'";
            mysqli_query($database->connection, $q_user);
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($database->connection)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
