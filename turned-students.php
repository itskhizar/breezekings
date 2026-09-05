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
	$exercise_id = $_GET['id'];
	$data = $database->getexcercisedetails($exercise_id);
	$course_id = $data['course_id'];
	$coursedata = $database->getcoursebyid($course_id);
	$category_id = $coursedata['category_id'];
	// $categorydata = $database->getcategorybyid($category_id);
    // $tasks = $database->gettasksassignedbyid($id);
    $excercise_data = $database->getexcercisedetailsbytaskid($exercise_id);
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
    <title>Session-Details</title>
    <style>
         .dashboard-wrapper{
            margin-left: 0px;

        }
        .dashboard-main-wrapper{
            padding-top: 0px !important;

        }
        .container{
             max-width: 100% !important;
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
        <!-- left sidebar -->

        <?php include('navbar.php'); ?>
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
                      
<!-- ===========================================================================================
    EDIT CLASS MODAL
=========================================================================================== -->
<div class="modal fade" id="editSessionModal" tabindex="-1" role="dialog" aria-labelledby="editSessionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="editSessionModalLabel">Edit Session Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="#" method="POST">
        <div class="modal-body">

            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

            <!-- Session Title -->
            <div class="form-group">
                <label>Session Title</label>
                <input type="text" name="title" class="form-control" 
                       value="<?php echo htmlspecialchars($data['title']); ?>" required>
            </div>

            <!-- Class -->
            <div class="form-group">
                <label>Select Class</label>
                <select name="class_id" class="form-control" required>
                    <option value="<?php echo $data['class_id']; ?>" selected>
                        <?php echo htmlspecialchars($classdata['name']); ?>
                    </option>
                    <?php echo $database->groupdata("classes_dropdown",""); ?>
                </select>
            </div>

            <!-- Category -->
            <div class="form-group">
                <label>Select Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="<?php echo $data['category_id']; ?>" selected>
                        <?php echo htmlspecialchars($categorydata['category']); ?>
                    </option>
                    <?php echo $database->groupdata("categories_dropdown",""); ?>
                </select>
            </div>

            <!-- Course -->
            <div class="form-group">
                <label>Select Course</label>
                <select name="course_id" class="form-control" required>
                    <option value="<?php echo $data['course_id']; ?>" selected>
                        <?php echo htmlspecialchars($coursedata['course_title']); ?>
                    </option>
                    <?php
                        // Optional: dynamically load courses for this category
                        echo $database->groupdata("courses_dropdown",$data['category_id']);
                    ?>
                </select>
            </div>
            
            <!-- Start Time -->
            <div class="form-group">
                <label>Start Time</label>
                <input type="time" name="start_time" class="form-control" 
                       value="<?php echo htmlspecialchars($data['start_time']); ?>" required>
            </div>

            <!-- End Time -->
            <div class="form-group">
                <label>End Time</label>
                <input type="time" name="end_time" class="form-control" 
                       value="<?php echo htmlspecialchars($data['end_time']); ?>" required>
            </div>

            <!-- Slots -->
            <div class="form-group">
                <label>Slots</label>
                <input type="number" name="slots" class="form-control" 
                       value="<?php echo htmlspecialchars($data['slots']); ?>" required>
            </div>

            <!-- Created At (readonly) -->
            <div class="form-group">
                <label>Created At</label>
                <input type="text" class="form-control" 
                       value="<?php echo htmlspecialchars($data['created_at']); ?>" readonly>
            </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="update_session" class="btn btn-primary">Save Changes</button>
        </div>

      </form>

    </div>
  </div>
</div>


<div class="container mt-4">

    <h3 class="mb-4">
        Turned In Students – <span class="text-primary"><?php echo $data['excercise_title']; ?></span>
    </h3>

    <div class="card">
        <div class="card-header bg-success text-white">
            Students Who Turned In This Exercise
        </div>
        <div class="card-body p-0">
           <table class="table table-bordered table-striped mb-0">
    <thead class="table-muted">
        <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Submitted File</th>
            <th>Submitted Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch turned-in students
        $turnedQuery = $database->query("
            SELECT t.*, s.name
            FROM task_assign t
            LEFT JOIN students s ON t.student_id = s.id
            WHERE t.excercise_id = '$exercise_id'
              AND t.status = 'Turned_in'
            ORDER BY t.submitted_at DESC
        ");
        $turnedQuery = $database->query("
    SELECT t.*, s.name
    FROM task_assign t
    LEFT JOIN students s ON t.student_id = s.id
    WHERE t.excercise_id = '$exercise_id'
      AND LOWER(REPLACE(t.status, ' ', '_')) = 'turned_in'
    ORDER BY t.submitted_at DESC
");


      if (mysqli_num_rows($turnedQuery) > 0) {
    $count = 1;
    while ($row = mysqli_fetch_assoc($turnedQuery)) {

        $file = $row['submitted_file']; // Full file name (example: assignment1.pdf)

        // Make student name safe for file download
        $studentName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $row['name']);

        // Extract file extension
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        // Final download file name
        $downloadName = $studentName . "_Submission." . $ext;

        echo "
        <tr>
            <td>{$count}</td>
            <td>" . htmlspecialchars($row['name']) . "</td>

            <td>
                <a href='submitted_excercises/{$file}' 
                   download='{$downloadName}' 
                   class='btn btn-sm btn-info'>
                    Download File
                </a>
            </td>

            <td>" . date('d M Y - h:i A', strtotime($row['submitted_at'])) . "</td>

            <td><span class='badge bg-primary text-white'>Turned In</span></td>

           <td>
			    <form method='post' action='update_returned_status.php' class='m-0 p-0 d-flex align-items-center' style='gap: 4px;'>
			        <!-- Hidden fields -->
			        <input type='hidden' name='submission_id' value='".$row['id']."'>
			         <input type='hidden' name='exercise_id' value='".$row['excercise_id']."'>
			        <input type='hidden' name='remarks' value='Well Done'> <!-- Optional dynamic remarks -->

			        <!-- Submit button -->
			        <button type='submit' class='btn btn-sm btn-success d-flex align-items-center' title='Mark Returned'>
            <i class='fas fa-check'></i>&nbsp;Returned
        </button>
			    </form>
			</td>

        </tr>
        ";

        $count++;
    }
}else {
    echo "
    <tr>
        <td colspan='6' class='text-center p-3 text-muted'>
            No students have turned in this exercise yet.
        </td>
    </tr>
    ";
}

        ?>
    </tbody>
</table>
        </div>
    </div>
</div>

<!-- Review & Grade Modal -->

<!-- <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Review & Grade</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

        <input type="hidden" id="submission_id">

        <div class="form-group">
            <label>Grade</label>
            <div class="input-group">
                <input type="number" class="form-control" id="grade_value" name="grades" placeholder="Enter grade" required>
                <div class="input-group-append">
                    <span class="input-group-text" id="grade_out_of">/10</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Remarks</label>
            <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" id="submitGrade" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div> -->


                        </div>
                        <!-- ============================================================== -->
                        
            </div>
           
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
    <script>
    	$(document).on("click", ".reviewBtn", function () {

    let id = $(this).data("id");  
    $("#submission_id").val(id);

    // Optional: Set dynamic grade out of value
    $("#grade_out_of").text("/10");  // change dynamically

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