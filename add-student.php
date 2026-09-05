<?php
ob_start();
include("include/classes/session.php");
if ($session->logged_in) {
$username = $session->username;
$result1 = $database->getUserInfo($username);
$ulevel = ($result1['userlevel']);
$display_name = $result1['display_name'];
$email = $result1['email'];
$phone = $result1['phone'];
$image = $result1['parent_directory'];
$password = $result1['password'];

    if (isset($_GET['id'])) {
        $admission_id = $_GET['id'];

        $result = $database->getadmissions($admission_id);
        $name = $result['name'];
        $gender = $result['gender'];
        $mobile_no = $result['mobile_no'];
        $email = $result['email'];
        $address = $result['address'];
        $current_grade = $result['current_grade'];
        $institute = $result['institute'];
        $field_education = $result['field_education'];
        $field_interest = $result['field_interest'];
        $education_status = $result['education_status'];
        $grade = $result['grade'];
        $current_semester = $result['current_semester'];
        $other_degree = $result['other_degree'];
        $graduation_year = $result['graduation_year'];
        $additional_info = $result['additional_info'];
    }elseif (isset($_GET['visitor_id'])) {
        $visitor_id = $_GET['visitor_id'];
        $result = $database->getVisitorsDetails($visitor_id);
        $name = $result['name'];
        $gender = $result['gender'];
        $mobile_no = $result['mobile_no'];
        $email = $result['email'];
        $address = $result['address'];
        $current_grade = $result['current_grade'];
        $institute = $result['institute'];
    }
}else{
    header('location: index.php');
}

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="assetss/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assetss/libs/css/style.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="assetss/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="assetss/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assetss/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/flag-icon-css/flag-icon.min.css">
     <link href="assets/img/favicon-filenod.png" rel="icon">
    <title>Registration</title>
    <style>
         .dashboard-wrapper{
            margin-left: 0px;
        }
        .dashboard-main-wrapper{
            padding-top: 0px !important;
        }
    </style>
</head>

<body>
    
<?php if($session->logged_in == true){ ?>
<!-- /**
 * User has already logged in, so display relevant links, including
 * a link to the admin center if the user is an administrator.
 */ -->


    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
       <!-- navbar -->
        <?php include('navbar.php'); ?>
        <!-- left sidebar -->
        <!-- ============================================================== -->
         <?php include('leftbar.php'); ?>
        <!-- end left sidebar --> 
       <!-- ============================================================== -->
        <!-- wrapper  -->

        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
             <?php if (isset($_GET['msg'])): ?>
    <style>
        /* CSS for the alert */
        .top-center-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1050; /* Ensures it appears on top */
            width: auto;
            color: white;
            padding: 15px 20px;
            border-radius: 4px;
            font-family: Arial, sans-serif;
            font-size: 16px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional shadow for style */
            animation: fadeInOut 7s ease-in-out; /* Fade in and out animation */
        }

        /* Green alert for success */
        .success-alert {
            background-color: #28a745; /* Green */
        }

        /* Red alert for error */
        .error-alert {
            background-color: #dc3545; /* Red */
        }

        /* Animation for fading in and out */
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateX(-50%) translateY(-10px); }
            10% { opacity: 1; transform: translateX(-50%) translateY(0); }
            90% { opacity: 1; }
            100% { opacity: 0; transform: translateX(-50%) translateY(-10px); }
        }

    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Create the alert div
            let alertDiv = document.createElement('div');
            alertDiv.className = 'top-center-alert';

            <?php if ($_GET['msg'] == 'success'): ?>
                alertDiv.classList.add('success-alert');
                alertDiv.textContent = 'Student registered successfully.';
            <?php elseif ($_GET['msg'] == 'error'): ?>
                alertDiv.classList.add('error-alert');
                alertDiv.textContent = 'There was an error while added the Student. Please try again.';
            <?php endif; ?>

            // Append the alert to the body
            document.body.appendChild(alertDiv);

            // Remove the alert after 7 seconds
            setTimeout(() => alertDiv.remove(), 7000);
        });
    </script>
<?php endif; ?>

            <!-- basic form  -->
                        <!-- ============================================================== -->
                       <style>
    /* Improve Select & Input look */
    .form-control, .form-select {
        height: 45px !important;
        border-radius: 6px;
    }

    textarea.form-control {
        height: auto !important;
    }

    /* Fix alignment on small screens */
    @media (max-width: 576px) {
        .form-control, .form-select {
            height: 42px !important;
        }
    }
</style>

<div class="row">
    <div class="col-12">

        <div class="section-block" id="basicform">
            <h3 class="section-title text-center">Student Registration</h3>
        </div>

        <div class="card">
            <h5 class="card-header">Register New Student</h5>

            <div class="card-body">

                <form id="studentRegistrationForm" action="process.php" method="post" enctype="multipart/form-data">
                    <?php 
                    $name = $name ?? $form->value("name");
                    $gender = $gender ?? $form->value("gender");
                    $mobile_no = $mobile_no ?? $form->value("mobile_no");
                    $email = $email ?? $form->value("email");
                    $address = $address ?? $form->value("address");
                    $current_grade = $current_grade ?? $form->value("grade");
                    $institute = $institute ?? $form->value("institute");
                    $field_education = $field_education ?? $form->value("field_education");
                    $education_status = $education_status ?? $form->value("education_status");
                    $grade = $grade ?? $form->value("grade");
                    $current_semester = $current_semester ?? $form->value("current_semester");
                    $other_degree = $other_degree ?? $form->value("other_degree");
                    $graduation_year = $graduation_year ?? $form->value("graduation_year");
                    
                    ?>
                    <div class="row g-4">
                        <!-- Select Category -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Select Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control" value='<?php echo $form->value("category_id"); ?>' >
                                <option selected disabled>Select Category</option>
                                <?php echo $database->groupdata("categories_dropdown",""); ?>
                            </select>
                            <p><?php echo $form->error("category_id"); ?></p>
                        </div>

                        <!-- Select Course (Dynamic) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Course <span class="text-danger">*</span></label>
                            <select name="course_id" id="course_id" class="form-control" value='<?php echo $form->value("course_id"); ?>' >
                                <option selected disabled>Select Category first</option>
                            </select>
                            <p><?php echo $form->error("course_id"); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold d-flex justify-content-between">
                                <span>Session <span class="text-danger">*</span></span>
                                <small id="available_slots" class="text-primary"></small> 
                            </label>

                            <select name="session_id" id="session_id" class="form-control" >
                                <option selected disabled>Select Course first</option>
                            </select>

                            <p><?php echo $form->error("session_id"); ?></p>
                        </div>

                        <!-- Student Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Student Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value='<?php echo $name; ?>'  placeholder="Enter full name">
                            <p><?php echo $form->error("name"); ?></p>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select form-control">
                                <option value="" disabled <?php echo empty($gender) ? 'selected' : ''; ?>>Select Gender</option>
                                <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo ($gender === 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                            <p><?php echo $form->error("gender"); ?></p>
                        </div>


                        <!-- DOB -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" value='<?php echo $form->value("dob"); ?>' >
                            <p><?php echo $form->error("dob"); ?></p>
                        </div>

                        <!-- Mobile No -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_no" class="form-control" value='<?php echo $mobile_no; ?>'  placeholder="03xxxxxxxxx">
                            <p><?php echo $form->error("mobile_no"); ?></p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">CNIC <span class="text-danger">*</span></label>
                            <input type="text" name="cnic" class="form-control" value='<?php echo $form->value("cnic"); ?>'   placeholder="12345-6789012-3" maxlength="15">
                            <p><?php echo $form->error("cnic"); ?></p>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value='<?php echo $email; ?>'  placeholder="Enter email address">
                            <p><?php echo $form->error("email"); ?></p>
                        </div>
                        <!-- Email -->
                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Enter full address"><?php echo htmlspecialchars($address); ?></textarea>
                            <p><?php echo $form->error("address"); ?></p>
                        </div>

                       <!-- Education Status -->
            <!-- Education Status -->
<div class="col-md-6 mb-3">
    <label class="form-label fw-semibold">Education Status <span class="text-danger">*</span></label>
    <select name="education_status" id="education_status" class="form-control" required onchange="toggleEducationFields()">
        <option value="" disabled <?php echo empty($education_status) ? 'selected' : ''; ?>>Select Status</option>
        <option value="Primary" <?php echo ($education_status === 'Primary') ? 'selected' : ''; ?>>Primary</option>
        <option value="Secondary" <?php echo ($education_status === 'Secondary') ? 'selected' : ''; ?>>Secondary</option>
        <option value="Intermediate" <?php echo ($education_status === 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
        <option value="Undergraduate" <?php echo ($education_status === 'Undergraduate') ? 'selected' : ''; ?>>Undergraduate</option>
        <option value="Graduate" <?php echo ($education_status === 'Graduate') ? 'selected' : ''; ?>>Graduate</option>
        <option value="Other" <?php echo ($education_status === 'Other') ? 'selected' : ''; ?>>Other / Diploma / Other Degree</option>
    </select>
</div>

<!-- Grade -->
<div class="col-md-6 mb-3" id="gradeField" style="display:none;">
    <label class="form-label fw-semibold">Grade <span class="text-danger">*</span></label>
    <input type="text" name="grade" class="form-control" value='<?php echo $grade; ?>' placeholder="Enter class">
</div>

<!-- Current Semester -->
<div class="col-md-6 mb-3" id="semesterField" style="display:none;">
    <label class="form-label fw-semibold">Current Semester <span class="text-danger">*</span></label>
    <input type="text" name="current_semester" class="form-control" value='<?php echo $current_semester; ?>' placeholder="e.g., 3rd, 5th">
</div>

<!-- Graduation Year -->
<div class="col-md-6 mb-3" id="graduationYear" style="display:none;">
    <label class="form-label fw-semibold">Graduation Year <span class="text-danger">*</span></label>
    <input type="text" name="graduation_year" class="form-control" value='<?php echo $graduation_year; ?>' placeholder="e.g., 2024, 2025">
</div>

<!-- Other Degree -->
<div class="col-md-6 mb-3" id="otherField" style="display:none;">
    <label class="form-label fw-semibold">Specify Degree / Diploma</label>
    <input type="text" name="other_degree" class="form-control" value='<?php echo $other_degree; ?>' placeholder="e.g., Diploma in IT">
</div>

<!-- Institute -->
<div class="col-md-6 mb-3">
    <label class="form-label fw-semibold">Institute <span class="text-danger">*</span></label>
    <input type="text" name="institute" class="form-control" value='<?php echo $institute; ?>' placeholder="University / College / School" required>
</div>

<!-- Field of Education -->
<div class="col-md-6 mb-3">
    <label class="form-label fw-semibold">Field of Education <span class="text-danger">*</span></label>
    <input type="text" name="field_education" class="form-control" value='<?php echo $field_education; ?>' placeholder="e.g., Computer Science, Medical" required>
</div>
<!-- JS TOGGLE LOGIC -->
<script>
function toggleEducationFields() {
    const status = document.getElementById("education_status").value;
    
    // Hide all by default
    document.getElementById("gradeField").style.display = "none";
    document.getElementById("semesterField").style.display = "none";
    document.getElementById("graduationYear").style.display = "none";
    document.getElementById("otherField").style.display = "none";

    if (status === "Primary" || status === "Secondary" || status === "Intermediate") {
        document.getElementById("gradeField").style.display = "block";
    } else if (status === "Undergraduate") {
        document.getElementById("semesterField").style.display = "block";
    } else if (status === "Graduate") {
        document.getElementById("graduationYear").style.display = "block";
    } else if (status === "Other") {
        document.getElementById("otherField").style.display = "block";
    }
}

// Run once on page load in case of pre-filled value
document.addEventListener("DOMContentLoaded", toggleEducationFields);
</script>
<style>
input[type="radio"] {
    opacity: 1 !important;
    position: relative !important;
    left: 0 !important;
    visibility: visible !important;
    display: inline-block !important;
}
</style>


                        

                        <!-- Guardian Details Subheading -->
                        <div class="col-12 mb-3">
                            <h5 class="fw-semibold" style="border-bottom: 2px solid #173663; padding-bottom: 5px;">Guardian Details</h5>
                        </div>

                        <!-- Guardian Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Guardian Name <span class="text-danger">*</span></label>
                            <input type="text" name="guardian_name" class="form-control" placeholder="Enter guardian's full name" value='<?php echo $form->value("guardian_name"); ?>' >
                            <p><?php echo $form->error("guardian_name"); ?></p>
                        </div>

                        <!-- Guardian Relation -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Guardian Relation <span class="text-danger">*</span></label>
                            <input type="text" name="guardian_relation" class="form-control" placeholder="e.g., Father, Mother, Uncle" value='<?php echo $form->value("guardian_relation"); ?>' >
                            <p><?php echo $form->error("guardian_relation"); ?></p>
                        </div>

                        <!-- Guardian Phone -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Guardian Phone <span class="text-danger">*</span></label>
                            <input type="text" name="guardian_phone" class="form-control" placeholder="03xxxxxxxxx" value='<?php echo $form->value("guardian_phone"); ?>' >
                            <p><?php echo $form->error("guardian_phone"); ?></p>
                        </div>


                        

                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4 text-center">
                        <button type="submit" name="add_student" class="btn btn-primary px-4"
                            style="background:#173663; border:none; border-radius:8px; width:30%;">
                            Register Student
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>


                        </div>
                        <!-- ============================================================== -->
                        
            </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
           
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <?php } else { header("Location: index.php?not_logged_in"); } ?>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->


    <!-- jquery 3.3.1 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $('#category_id').change(function () {

        var category_id = $(this).val(); // correct variable

        $.ajax({
            url: 'fetch_courses.php',
            type: 'POST',
            data: { category_id: category_id },
            success: function (data) {
                $('#course_id').html(data);
            }
        });

    });

    $('#course_id').change(function () {

        var course_id = $(this).val(); // correct variable

        $.ajax({
            url: 'fetch_sessions.php',
            type: 'POST',
            data: { course_id: course_id },
            success: function (data) {
                $('#session_id').html(data);
                $('#available_slots').text('');

            }
        });

    });

    $('#session_id').on('change', function() {
    var available = $(this).find(":selected").data("available");
    $('#available_slots').text("Available: " + available);
});

});
</script>



</script>
    <!-- bootstap bundle js -->
    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- slimscroll js -->
    <script src="assetss/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js -->
    <script src="assetss/libs/js/main-js.js"></script>
    <!-- chart chartist js -->
    <script src="assetss/vendor/charts/chartist-bundle/chartist.min.js"></script>
    <!-- sparkline js -->
    <script src="assetss/vendor/charts/sparkline/jquery.sparkline.js"></script>
    <!-- morris js -->
    <script src="assetss/vendor/charts/morris-bundle/raphael.min.js"></script>
    <script src="assetss/vendor/charts/morris-bundle/morris.js"></script>
    <!-- chart c3 js -->
    <script src="assetss/vendor/charts/c3charts/c3.min.js"></script>
    <script src="assetss/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
    <script src="assetss/vendor/charts/c3charts/C3chartjs.js"></script>
    <script src="assetss/libs/js/dashboard-ecommerce.js"></script>
</body>
</html>