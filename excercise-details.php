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
    $data = $database->getexcercisedetails($id);
    $course_id = $data['course_id'];
    $task_id = $data['task_id'];
    $taskdata = $database->gettaskdetails($task_id);
    $coursedata = $database->getcoursebyid($course_id);
    // $category_id = $coursedata['category_id'];
    // $categorydata = $database->getcategorybyid($category_id);
    // $tasks = $database->gettasksassignedbyid($id);
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="assetss/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assetss/libs/css/style.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="assetss/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="assetss/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assetss/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <title>exercise-Details</title>
    <style>
        .dashboard-wrapper {
            margin-left: 0px;
        }

        .dashboard-main-wrapper {
            padding-top: 0px !important;
        }
    </style>
</head>

<body>

    <?php if ($session->logged_in == true) { ?>
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
                                z-index: 1050;
                                /* Ensures it appears on top */
                                width: auto;
                                color: white;
                                padding: 15px 20px;
                                border-radius: 4px;
                                font-family: Arial, sans-serif;
                                font-size: 16px;
                                text-align: center;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                /* Optional shadow for style */
                                animation: fadeInOut 7s ease-in-out;
                                /* Fade in and out animation */
                            }

                            /* Green alert for success */
                            .success-alert {
                                background-color: #28a745;
                                /* Green */
                            }

                            /* Red alert for error */
                            .error-alert {
                                background-color: #dc3545;
                                /* Red */
                            }

                            /* Animation for fading in and out */
                            @keyframes fadeInOut {
                                0% {
                                    opacity: 0;
                                    transform: translateX(-50%) translateY(-10px);
                                }

                                10% {
                                    opacity: 1;
                                    transform: translateX(-50%) translateY(0);
                                }

                                90% {
                                    opacity: 1;
                                }

                                100% {
                                    opacity: 0;
                                    transform: translateX(-50%) translateY(-10px);
                                }
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
                                <h3 class="section-title text-center">Excercise Details</h3>
                            </div>

                            <div class="col-md-12 mb-4">
                                <div class="card position-relative">
                                    <?php if ($session->userlevel == 1) { ?>
                                        <!-- Edit + Delete Icons -->
                                        <div style="position:absolute; top:10px; right:15px;">
                                            <!-- Edit Icon -->
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#editSessionModal" class="me-2">
                                                <i class="fas fa-edit text-primary" style="font-size:20px;"></i>
                                            </a>

                                            <!-- Delete Icon -->
                                            <a href="delete-class.php?id=<?php echo $data['id']; ?>"
                                                onclick="return confirm('Are you sure you want to delete this class?');">
                                                <i class="fas fa-trash-alt text-danger" style="font-size:20px;"></i>
                                            </a>
                                        </div>
                                    <?php } ?>


                                    <h5 class="card-header">Excercise Information</h5>

                                    <div class="card-body">
                                        <div class="row">

                                            <!-- Session Title -->
                                            <div class="col-md-6 mb-3">
                                                <label class="font-weight-bold">Excercise Title:</label>
                                                <p class="form-control">
                                                    <?php echo htmlspecialchars($data['excercise_title']); ?></p>
                                            </div>


                                            <!-- Category -->
                                            <!-- <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Category:</label>
                            <p class="form-control"><?php echo htmlspecialchars($categorydata['category']); ?></p>
                        </div> -->

                                            <!-- Course -->
                                            <div class="col-md-6 mb-3">
                                                <label class="font-weight-bold">Course:</label>
                                                <p class="form-control"><?php echo htmlspecialchars($coursedata['course_title'] ?? 'N/A'); ?></p>
                                            </div>


                                            <div class="col-md-6 mb-3">
                                                <label class="font-weight-bold">Task Title:</label>
                                                <p class="form-control">
                                                    <?php echo !empty($taskdata['title']) ? htmlspecialchars($taskdata['title']) : "Not Set"; ?>
                                                </p>
                                            </div>
                                            <!-- Credit Hours -->
                                            <!-- <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Task Credit Hours:</label>
                            <p class="form-control">
                                <?php echo !empty($taskdata['credit_hours']) ? $taskdata['credit_hours'] : "Not Set"; ?>
                            </p>
                        </div> -->

                                            <div class="col-md-6 mb-3">
                                                <label class="font-weight-bold">Preview Image:</label>
                                                <?php if (!empty($data['excercise_preview_image'])): ?>
                                                    <div class="mt-2">
                                                        <img src="excercises/<?php echo $data['excercise_preview_image']; ?>"
                                                            alt="Preview" class="img-fluid rounded shadow-sm"
                                                            style="max-height: 200px;">
                                                    </div>
                                                <?php else: ?>
                                                    <p class="form-control">No preview image uploaded</p>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="font-weight-bold">Exercise Assets (Sheets):</label>
                                                <?php
                                                $filePath = 'excercises/' . $data['excercise_assets'];
                                                if (!empty($data['excercise_assets']) && file_exists($filePath)): ?>
                                                    <p class="form-control">
                                                        <a href="<?php echo 'excercises/' . urlencode($data['excercise_assets']); ?>"
                                                            download style="text-decoration: none; color: #173663;">
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                    </p>
                                                <?php else: ?>
                                                    <p class="form-control">No assets uploaded</p>
                                                <?php endif; ?>
                                            </div>


                                            <!-- Description -->
                                            <div class="col-md-12 mb-3">
                                                <label class="font-weight-bold">Guidelines:</label>
                                                <div class="card bg-light border-0 shadow-none">
                                                    <div class="card-body p-3"
                                                        style="min-height:70px; background: #fff; border: 1px solid #ced4da; border-radius: .25rem;">
                                                        <?php echo !empty($data['excercise_description']) ? html_entity_decode($data['excercise_description']) : "No Description Provided"; ?>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-md-6 mb-3">
                                                <label class="font-weight-bold">Created at:</label>
                                                <p class="form-control">
                                                    <?php echo htmlspecialchars(date("d M Y", strtotime($data['created_at']))); ?>
                                                </p>
                                            </div>

                                            <!-- <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Student Assigned:</label>
                            <p class="form-control"><?php echo $database->numberofstudentsintasks($id); ?></p>
                        </div> -->
                                            <!-- <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Upload Time:</label>
                            <p class="form-control"><?php echo htmlspecialchars($data['created_at']); ?></p>
                        </div> -->
                                            <!-- <div class="col-md-6 mb-3">
                            <label class="font-weight-bold">Deadline:</label>
                            <p class="form-control"><?php echo htmlspecialchars($data['deadline']); ?></p>
                        </div> -->




                                            <!-- Created At -->


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- ===========================================================================================
    EDIT CLASS MODAL
=========================================================================================== -->
                    <div class="modal fade" id="editSessionModal" tabindex="-1" role="dialog"
                        aria-labelledby="editSessionModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSessionModalLabel">Edit Session Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
                                                <?php echo $database->groupdata("classes_dropdown", ""); ?>
                                            </select>
                                        </div>

                                        <!-- Category -->
                                        <div class="form-group">
                                            <label>Select Category</label>
                                            <select name="category_id" class="form-control" required>
                                                <option value="<?php echo $data['category_id']; ?>" selected>
                                                    <?php echo htmlspecialchars($categorydata['category']); ?>
                                                </option>
                                                <?php echo $database->groupdata("categories_dropdown", ""); ?>
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
                                                echo $database->groupdata("courses_dropdown", $data['category_id']);
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
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="update_session" class="btn btn-primary">Save
                                            Changes</button>
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
    <?php } else {
        header("Location: index.php?not_logged_in");
    } ?>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->


    <!-- jquery 3.3.1 -->
    <!-- jQuery (latest, for plugins that still require it) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- bootstap bundle js -->
    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
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