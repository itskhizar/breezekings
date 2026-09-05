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
    <title>Add-purchase</title>
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
                alertDiv.textContent = 'New Purchase inserted successfully.';
            <?php elseif ($_GET['msg'] == 'error'): ?>
                alertDiv.classList.add('error-alert');
                alertDiv.textContent = 'There was an error while inserting the purchase. Please try again.';
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
                                    <h3 class="section-title text-center">Add Purchase</h3>
                                  
                                </div>
                                <div class="card">
                                    <h5 class="card-header">Create New Purchase</h5>
                                    <div class="card-body">
                                    <form action="process.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="purchase_date" class="col-form-label">Purchase Date</label>
        <input type="date" name="purchase_date" class="form-control">
        <p><?php echo $form->error("purchase_date"); ?></p>
    </div>

    <div class="form-group">
        <label for="supplier_name" class="col-form-label">Supplier Name</label>
        <input type="text" name="supplier_name" class="form-control" placeholder="Enter supplier name">
        <p><?php echo $form->error("supplier_name"); ?></p>
    </div>

    <div class="form-group">
        <label for="itemdescription" class="col-form-label">Item Description</label>
        <textarea name="itemdescription" class="form-control" placeholder="Enter item description"></textarea>
        <p><?php echo $form->error("itemdescription"); ?></p>
    </div>

    <div class="form-group">
        <label for="unitprice" class="col-form-label">Unit Price</label>
        <input type="number" name="unitprice" class="form-control" placeholder="Enter unit price">
        <p><?php echo $form->error("unitprice"); ?></p>
    </div>

    <div class="form-group">
        <label for="totalamount" class="col-form-label">Total Amount</label>
        <input type="number" name="totalamount" class="form-control" placeholder="Enter total amount">
        <p><?php echo $form->error("totalamount"); ?></p>
    </div>

    <div class="form-group">
        <label for="payment_duedate" class="col-form-label">Payment Due Date</label>
        <input type="date" name="payment_duedate" class="form-control">
        <p><?php echo $form->error("payment_duedate"); ?></p>
    </div>

    <div class="form-group">
        <label for="paidamount" class="col-form-label">Paid Amount</label>
        <input type="number" name="paidamount" class="form-control" placeholder="Enter paid amount">
        <p><?php echo $form->error("paidamount"); ?></p>
    </div>

    <div class="form-group">
        <label for="balanceamount" class="col-form-label">Balance Amount</label>
        <input type="number" name="balanceamount" class="form-control" placeholder="Enter balance amount">
        <p><?php echo $form->error("balanceamount"); ?></p>
    </div>

    

    <input type="hidden" name="add_purchase" value="1">
    <div class="text-center">
                                    <input type="submit" class="btn btn-danger " value="Add Purchase" style="width: 30%; ">
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