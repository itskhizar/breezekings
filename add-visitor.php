<?php
ob_start();
include("include/classes/session.php");
$username = $session->username;
$result1 = $database->getUserInfo($username);
$ulevel = ($result1['userlevel']);
$display_name = $result1['display_name'];
$email = $result1['email'];
$phone = $result1['phone'];
$image = $result1['parent_directory'];
$password = $result1['password'];


?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
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
    <title>Visitor</title>
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
            <h3 class="section-title text-center">Visitors</h3>
        </div>

        <div class="card">
            <h5 class="card-header">Add New Visitor</h5>

            <div class="card-body">

                <form id="studentRegistrationForm" action="process.php" method="post" enctype="multipart/form-data">

                    <div class="row g-4">
                        <!-- Select Category -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Select Category <sup class="text-primary">optional</sup></label>
                            <select name="category_id" id="category_id" class="form-control" value='<?php echo $form->value("category_id"); ?>' >
                                <option selected disabled>Select Category</option>
                                <?php echo $database->groupdata("categories_dropdown",""); ?>
                            </select>
                            <p><?php echo $form->error("category_id"); ?></p>
                        </div>

                        <!-- Select Course (Dynamic) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Course <sup class="text-primary">optional</sup></label>
                            <select name="course_id" id="course_id" class="form-control" value='<?php echo $form->value("course_id"); ?>' >
                                <option selected disabled>Select Category first</option>
                            </select>
                            <p><?php echo $form->error("course_id"); ?></p>
                        </div>
                        

                        <!-- Student Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Visitor Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value='<?php echo $form->value("name"); ?>'  placeholder="Enter full name">
                            <p><?php echo $form->error("name"); ?></p>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select form-control" value='<?php echo $form->value("gender"); ?>' >
                                <option value="" selected disabled>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            <p><?php echo $form->error("gender"); ?></p>
                        </div>


                        <!-- Mobile No -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_no" class="form-control" value='<?php echo $form->value("mobile_no"); ?>'  placeholder="03xxxxxxxxx">
                            <p><?php echo $form->error("mobile_no"); ?></p>
                        </div>


                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value='<?php echo $form->value("email"); ?>'  placeholder="Enter email address">
                            <p><?php echo $form->error("email"); ?></p>
                        </div>

                        <!-- Current Grade -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Current Grade </label>
                            <input type="text" name="current_grade" class="form-control" value='<?php echo $form->value("current_grade"); ?>' placeholder="Enter current grade">
                            <p><?php echo $form->error("current_grade"); ?></p>
                        </div>

                        <!-- Institute -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Institute </label>
                            <input type="text" name="institute" class="form-control" value='<?php echo $form->value("institute"); ?>' placeholder="Enter institute name">
                            <p><?php echo $form->error("institute"); ?></p>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Address </label>
                            <textarea name="address" class="form-control" rows="3" value='<?php echo $form->value("address"); ?>' placeholder="Enter full address"></textarea>
                            <p><?php echo $form->error("address"); ?></p>
                        </div>

                       

                        

                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4 text-center">
                        <button type="submit" name="add_visitor" class="btn btn-primary px-4"
                            style="background:#173663; border:none; border-radius:8px; width:30%;">
                            Add Visitor
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