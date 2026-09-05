<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database Error");
}

if (isset($_POST['update_status'])) {

    $id             = $_POST['id'];
    $registration_no  = $_POST['registration_no'];
    // $fee_status     = $_POST['fee_status'];
    $profile_status = $_POST['profile_status'];
    $reference      = $_POST['reference'];

    // Fetch student email + name + password
    $getStudent = mysqli_query($conn, "SELECT display_name, email, password FROM users WHERE registration_no='$registration_no'");

if (!$getStudent || mysqli_num_rows($getStudent) == 0) {
    die("Student record not found or database error: " . mysqli_error($conn));
}

$student = mysqli_fetch_assoc($getStudent);


    $student_name  = $student['display_name'];
    $student_email = $student['email'];
    $student_pass  = "filenod12345"; // assuming stored in database

    $query = "UPDATE students 
              SET status='$profile_status', reference='$reference' 
              WHERE id='$id'";

    if (mysqli_query($conn, $query)) {

        /* ------------------ SEND EMAIL ------------------ */

        $subject = "Filenod Academy — Your Profile Has Been Updated";

            if ($profile_status == 'Active') {

                $removeInactive = "DELETE FROM inactive_users WHERE username='$registration_no'";
                mysqli_query($conn, $removeInactive);

                $message = "
                <html>
                <body style='font-family: Arial; background-color:#f7f7f7; padding:20px;'>
                <div style='max-width:600px; margin:auto; background:white; padding:20px; border-radius:8px; border:1px solid #ddd;'>
                    <div style='text-align:center; margin-bottom:20px;'>
                        <img src='https://academy.filenod.com/images/logo.png' alt='Filenod Academy' style='max-width:150px; margin-bottom:10px;'>
                        <h2 style='color:#173663; margin:0;'>Filenod Academy</h2>
                    </div>

                    <p>Dear <strong>$student_name</strong>,</p>

                    <p>Your student profile has been successfully <strong>activated</strong>.</p>

                    <h3 style='color:#173663;'>Your Login Details</h3>
                    <table cellpadding='8' style='width:100%; border-collapse: collapse;'>
                        <tr>
                            <td style='border-bottom:1px solid #ddd;'>Username:</td>
                            <td style='border-bottom:1px solid #ddd;'>$registration_no</td>
                        </tr>
                        <tr>
                            <td style='border-bottom:1px solid #ddd;'>Password:</td>
                            <td style='border-bottom:1px solid #ddd;'>$student_pass</td>
                        </tr>
                    </table>

                    <p><a href='https://academy.filenod.com/login.php' style='color:#173663;'>Student Login Portal</a></p>
                    <p>Regards,<br><strong>Filenod Academy</strong></p>
                </div>
                </body>
                </html>";
            }
            else {

                // 🔥 INSERT INTO INACTIVE USERS TABLE
                $insertInactive = "INSERT INTO inactive_users (username, timestamp)
                   SELECT '$registration_no', NOW()
                   FROM DUAL
                   WHERE NOT EXISTS (
                       SELECT 1 FROM inactive_users WHERE username='$registration_no'
                   )";

if (!mysqli_query($conn, $insertInactive)) {
    die("Inactive insert failed: " . mysqli_error($conn));
}


                $message = "
                <html>
                <body style='font-family: Arial; background-color:#f7f7f7; padding:20px;'>
                <div style='max-width:600px; margin:auto; background:white; padding:20px; border-radius:8px; border:1px solid #ddd;'>
                    <div style='text-align:center; margin-bottom:20px;'>
                        <img src='https://academy.filenod.com/images/logo.png' alt='Filenod Academy' style='max-width:150px; margin-bottom:10px;'>
                        <h2 style='color:#173663; margin:0;'>Filenod Academy</h2>
                    </div>
                    
                    <p>Your account is currently marked as <strong style='color:red;'>INACTIVE</strong>.</p>

                    <p>Please contact the administration to activate your account.</p>

                    <p>Regards,<br><strong>Filenod Academy</strong></p>
                </div>
                </body>
                </html>";
            }


        // Email headers
        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Filenod Academy <admin@filenod.com>" . "\r\n";

        mail($student_email, $subject, $message, $headers);

        /* ------------------------------------------------ */

        header("Location: student-details.php?id=".$id."&msg=status_updated");
        exit();
    } 
    else {
        echo "Error updating status!";
    }
}
?>
