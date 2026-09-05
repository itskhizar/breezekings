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
     <link href="nod.png" rel="icon">
    <title>Create Session</title>
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
            <h3 class="section-title text-center">Add Session</h3>
        </div>

        <div class="card">
            <h5 class="card-header">Add New Session</h5>

            <div class="card-body">

                <form action="process.php" method="POST" enctype="multipart/form-data">

                        <div class="row g-4">

                        	<div class="col-md-6 mb-3">
	                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
	                            <input type="text" name="title" class="form-control" value='<?php echo $form->value("title"); ?>' required placeholder="title">
	                            <p><?php echo $form->error("title"); ?></p>
                        	</div>

                        	 <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Select Class <span class="text-danger">*</span></label>
                                <select name="class_id"  class="form-control" required>
                                    <option selected disabled>Select Class</option>
                                    <?php echo $database->groupdata("classes_dropdown",""); ?>
                                </select>
                                <p><?php echo $form->error("class_id"); ?></p>
                            </div>

                            <!-- Select Category -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Select Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" value='<?php echo $form->value("category_id"); ?>' class="form-control" required>
                                    <option selected disabled>Select Category</option>
                                    <?php echo $database->groupdata("categories_dropdown",""); ?>
                                </select>
                                <p><?php echo $form->error("category_id"); ?></p>
                            </div>

                            <!-- Select Course -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Select Courses <span class="text-danger">*</span></label>

                                <div id="course_id" class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                    <span class="text-muted">Please select a category first.</span>
                                </div>

                                <p><?php echo $form->error("course_ids"); ?></p>
                            </div>


                             
                           

                            <!-- Session Start Date -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Session Start Date (First Monday) <span class="text-danger">*</span></label>
                                <input type="date" name="session_start_date" value='<?php echo $form->value("session_start_date"); ?>' class="form-control" required>
                                <p><?php echo $form->error("session_start_date"); ?></p>
                                <small class="text-muted">The date of the first Monday of the 8-week course.</small>
                            </div>

                            <!-- Start Time -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Default Daily Start Time <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" value='<?php echo $form->value("start_time"); ?>' class="form-control" required>
                                <p><?php echo $form->error("start_time"); ?></p>
                                <small class="text-muted">Classes will start at this time every day (Mon-Fri).</small>
                            </div>

                            <!-- Duration Weeks -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Course Duration (Weeks) <span class="text-danger">*</span></label>
                                <input type="number" name="course_duration_weeks" value='8' class="form-control" required min="1" max="52">
                                <p><?php echo $form->error("course_duration_weeks"); ?></p>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Select Teacher <span class="text-danger">*</span></label>
                                <select name="teacher_id"  class="form-control" required>
                                    <option selected disabled>Select Teacher</option>
                                    <?php echo $database->groupdata("teachers_dropdown",""); ?>
                                </select>
                                <p><?php echo $form->error("teacher_id"); ?></p>
                            </div>
                            <!-- Select Slots -->
                            <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Select Slots <span class="text-danger">*</span></label>
                            <input type="text" name="slots" class="form-control" value='<?php echo $form->value("slots"); ?>' required placeholder="slots">
                            <p><?php echo $form->error("slots"); ?></p>
                        </div>

                        </div>

                        <div class="mt-4 text-center">
                        <button type="submit" name="add_session" class="btn btn-primary px-4"
                            style="background:#173663; border:none; border-radius:8px; width:30%;">
                            Create Session
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
            url: 'fetch_courses_forsession.php',
            type: 'POST',
            data: { category_id: category_id },
            success: function (data) {
                $('#course_id').html(data);
            }
        });

    });
});
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