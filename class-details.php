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

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$data = $database->getclassdetails($id);
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
    <link rel="stylesheet" href="assetss/vendor/vector-map/jqvmap.css">
    <link rel="stylesheet" href="assetss/vendor/jvectormap/jquery-jvectormap-2.0.2.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <title>class-Details</title>
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
                alertDiv.textContent = 'Class added successfully.';
            <?php elseif ($_GET['msg'] == 'error'): ?>
                alertDiv.classList.add('error-alert');
                alertDiv.textContent = 'There was an error while added the Class. Please try again.';
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
                       <div class="row mt-4">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="section-block" id="basicform">
            <h3 class="section-title text-center">Class Details</h3>
        </div>

        <div class="card position-relative">

            <!-- Edit + Delete Icons -->
            <div style="position:absolute; top:10px; right:15px;">
                <!-- Edit Icon -->
                <a href="#" data-toggle="modal" data-target="#editClassModal" class="mr-2">
                    <i class="fas fa-edit text-primary" style="font-size:20px;"></i>
                </a>

                <!-- Delete Icon -->
                <!-- <a href="delete-class.php?id=<?php echo $data['id']; ?>" 
                   onclick="return confirm('Are you sure you want to delete this class?');">
                    <i class="fas fa-trash-alt text-danger" style="font-size:20px;"></i>
                </a> -->
            </div>

            <h5 class="card-header">Class Information</h5>

            <div class="card-body">
                <div class="row">

                    <!-- Class Name -->
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold">Class Name:</label>
                        <p class="form-control"><?php echo htmlspecialchars($data['name']); ?></p>
                    </div>

                    <!-- Description -->
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold">Description:</label>
                        <p class="form-control" style="height:auto;">
                            <?php echo htmlspecialchars($data['description']); ?>
                        </p>
                    </div>

                    <!-- Slots -->
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold">Slots:</label>
                        <p class="form-control"><?php echo htmlspecialchars($data['slots']); ?></p>
                    </div>

                    <!-- Created At -->
                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold">Created At:</label>
                        <p class="form-control"><?php echo htmlspecialchars($data['created_at']); ?></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- ===========================================================================================
    EDIT CLASS MODAL
=========================================================================================== -->
<div class="modal fade" id="editClassModal" tabindex="-1" role="dialog" aria-labelledby="editClassModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editClassModalLabel">Edit Class Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="process.php" method="POST">
        <div class="modal-body">

            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

            <!-- Class Name -->
            <div class="form-group">
                <label>Class Name</label>
                <input type="text" name="name" class="form-control" 
                       value="<?php echo htmlspecialchars($data['name']); ?>">
            </div>

            <!-- Description -->
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($data['description']); ?></textarea>
            </div>

            <!-- Slots -->
            <div class="form-group">
                <label>Slots</label>
                <input type="number" name="slots" class="form-control" 
                       value="<?php echo htmlspecialchars($data['slots']); ?>">
            </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="update_class" class="btn btn-primary">Save Changes</button>
        </div>

      </form>

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
    <!-- bootstrap bundle js-->
    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- slimscroll js-->
    <script src="assetss/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- chartjs js-->
    <script src="assetss/vendor/charts/charts-bundle/Chart.bundle.js"></script>
    <script src="assetss/vendor/charts/charts-bundle/chartjs.js"></script>
   
    <!-- main js-->
    <script src="assetss/libs/js/main-js.js"></script>
    <!-- jvactormap js-->
    <script src="assetss/vendor/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assetss/vendor/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- sparkline js-->
    <script src="assetss/vendor/charts/sparkline/jquery.sparkline.js"></script>
    <script src="assetss/vendor/charts/sparkline/spark-js.js"></script>
     <!-- dashboard sales js-->
    <script src="assetss/libs/js/dashboard-sales.js"></script>

     
</body>
</html>