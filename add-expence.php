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
    <title>Add-expence</title>
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
                alertDiv.textContent = 'Expence inserted successfully.';
            <?php elseif ($_GET['msg'] == 'error'): ?>
                alertDiv.classList.add('error-alert');
                alertDiv.textContent = 'There was an error while inserting the expence. Please try again.';
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
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="section-block" id="basicform">
                                    <h3 class="section-title text-center">Add Expence</h3>
                                  
                                </div>
                                <div class="card">
                                    <h5 class="card-header">Create New Expence</h5>
                                    <div class="card-body">
                                       <form action="process.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="date" class="col-form-label">Date <small><?php echo $form->error("date"); ?></small></label>
        <input type="date" name="date" class="form-control">
        
    </div>
    
    <div class="form-group">
        <label for="category" class="col-form-label">Category <small><?php echo $form->error("category"); ?></small></label>
        <select name="category" class="form-control">
            <option selected disabled>Select category</option>
            <?php echo $database->groupdata("categories_dropdown",""); ?>
        </select>

    </div>
     <div class="form-group">
        <label for="category" class="col-form-label">Client <small><?php echo $form->error("client"); ?></small></label>
        <select name="client" class="form-control">
            <option selected disabled>Select client</option>
            <?php echo $database->groupdata("client_dropdown",""); ?>
        </select>

    </div>


    <div class="form-group">
        <label for="payment_method" class="col-form-label">Payment Method</label>
        <input type="text" name="payment_method" class="form-control" placeholder="Enter payment method">
        <p><?php echo $form->error("payment_method"); ?></p>
    </div>

    <div class="form-group">
        <label for="description" class="col-form-label">Description</label>
        <textarea name="description" class="form-control" placeholder="Enter description"></textarea>
        <p><?php echo $form->error("description"); ?></p>
    </div>

    <div class="form-group">
        <label for="vendor" class="col-form-label">Vendor</label>
        <input type="text" name="vendor" class="form-control" placeholder="Enter vendor name">
        <p><?php echo $form->error("vendor"); ?></p>
    </div>

    <div class="form-group">
        <label for="recurring" class="col-form-label">Recurring</label>
        <select name="recurring" class="form-control">
            <option value="" disabled selected>Select Recurring</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        <p><?php echo $form->error("recurring"); ?></p>
    </div>

    <div class="form-group">
        <label for="currency" class="col-form-label">Currency</label>
        <input type="text" name="currency" class="form-control" placeholder="Enter currency type">
        <p><?php echo $form->error("currency"); ?></p>
    </div>

    <div class="form-group">
        <label for="attachment" class="col-form-label">Attachment</label>
        <input type="file" name="attachment" class="form-control" accept="image/*, application/pdf" >
        <p><?php echo $form->error("attachment"); ?></p>
    </div>

    <div class="form-group">
        <label for="project_account" class="col-form-label">Project Account</label>
        <input type="text" name="project_account" class="form-control" placeholder="Enter project account">
        <p><?php echo $form->error("project_account"); ?></p>
    </div>

   

    <input type="hidden" name="add_expence" value="1">
    <div class="text-center">
    <input type="submit" class="btn btn-danger " value="Add Expence" style="width: 30%; ">
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
    <!-- jQuery (latest, for plugins that still require it) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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