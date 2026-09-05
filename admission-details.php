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
    $data = $database->getAdmissionDetails($id); // CHANGE THIS NAME IF NEEDED
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
    <title>Admission Details</title>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #ffffff;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --text-primary: #2c3e50;
            --text-secondary: #6c757d;
            --border-color: #e8ecef;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 2px 8px rgba(0,0,0,0.08);
        }

        body {
            background: linear-gradient(135deg, #f8f9fd 0%, #eef1f9 100%);
            font-family: 'Circular Std', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .page-header {
            background: #ffffff;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            border-bottom: 3px solid var(--primary-color);
        }

        .page-header h2 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .page-header .breadcrumb {
            background: transparent;
            margin: 0.5rem 0 0 0;
            padding: 0;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            box-shadow: var(--shadow-sm);
            font-size: 0.9rem;
        }

        .btn-edit {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-edit:hover {
            background-color: #1a252f;
            box-shadow: var(--shadow-md);
            color: white;
        }

        .btn-delete {
            background-color: white;
            color: var(--danger-color);
            border: 2px solid var(--danger-color);
        }

        .btn-delete:hover {
            background-color: var(--danger-color);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .details-card {
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-section {
            padding: 2.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .card-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary-color);
            font-size: 1.3rem;
        }

        .info-group {
            margin-bottom: 1.75rem;
        }

        .info-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.6rem;
            display: block;
        }
/*.info-value {
            font-size: 1rem;
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            background-color: var(--secondary-color);
            border-radius: 8px;
            border-left: 3px solid var(--primary-color);
            min-height: 45px;
            display: flex;
            align-items: center;
        }*/

        .info-value.large {
            min-height: auto;
            padding: 1rem;
        }
        .info-value {
            font-size: 1rem;
            color: var(--text-primary);
            padding: 0.9rem 0;
            /*background-color: transparent;*/
            background-color: var(--secondary-color);
            border-bottom: 2px solid var(--border-color);
            min-height: auto;
            display: block;
        }

        .info-value.large {
            min-height: auto;
            padding: 0.9rem 0;
            line-height: 1.6;
        }

        .status-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
            background-color: #f8f9fa;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .timestamp-info {
            background: #f8f9fa;
            padding: 0.9rem 0;
            border-bottom: 2px solid var(--border-color);
        }

        .modal-content {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .modal-header {
            background: var(--primary-color);
            color: white;
            border-radius: 8px 8px 0 0;
            padding: 1.5rem;
            border-bottom: none;
        }

        .modal-header .close {
            color: white;
            opacity: 0.8;
            text-shadow: none;
        }

        .modal-header .close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0.75rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.15rem rgba(44, 62, 80, 0.1);
            outline: none;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .page-header {
                text-align: center;
            }
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
            font-style: italic;
        }


    </style>
</head>

<body>

<?php if ($session->logged_in == true) { ?>

<div class="dashboard-main-wrapper">

    <?php include('navbar.php'); ?>
    <?php include('leftbar.php'); ?>

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">

            <!-- Page Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2><i class="fas fa-user-graduate mr-2"></i>Admission Details</h2>
                        <div class="breadcrumb">
                            <span>Dashboard</span> / <span>Admissions</span> / <span>Details</span>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i> Edit Details
                        </a>
                        <a href="delete-admission.php?id=<?php echo $data['id']; ?>"
                           onclick="return confirm('Are you sure you want to delete this admission?');"
                           class="btn-action btn-delete">
                            <i class="fas fa-trash-alt"></i> Delete
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="details-card">
                
                <!-- Personal Information Section -->
                <div class="card-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h3>
                    
                    <div class="grid-3">
                        <div class="info-group">
                            <div class="info-label">
                                Full Name
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($data['name']); ?>
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">
                                Gender
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($data['gender']); ?>
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">
                                Mobile Number
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($data['mobile_no']); ?>
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">
                                Email Address
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($data['email']); ?>
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">
                                Reference
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($data['reference']); ?>
                            </div>
                        </div>
                    </div>

                    <div class="info-group mt-4">
                        <div class="info-label">
                            Address
                        </div>
                        <div class="info-value large">
                            <?php echo htmlspecialchars($data['address']); ?>
                        </div>
                    </div>
                </div>

                <!-- Academic Information Section -->
                <div class="card-section">
                    <h3 class="section-title">
                        <i class="fas fa-graduation-cap"></i>
                        Academic Information
                    </h3>
                    
                    <div class="grid-3">
                        <div class="info-group">
                            <div class="info-label">
                                Institute
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($data['institute']); ?>
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">
                                Education Status
                            </div>
                            <div class="info-value">
                                <span class="status-badge"><?php echo htmlspecialchars($data['education_status']); ?></span>
                            </div>
                        </div>

                        <?php if (!empty($data['grade'])) { ?>
                        <div class="info-group">
                            <div class="info-label">
                                Grade
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($data['grade']); ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (!empty($data['current_semester'])) { ?>
                        <div class="info-group">
                            <div class="info-label">
                                Current Semester
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($data['current_semester']); ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (!empty($data['graduation_year'])) { ?>
                        <div class="info-group">
                            <div class="info-label">
                                Graduation Year
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($data['graduation_year']); ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="info-group mt-4">
                        <div class="info-label">
                            Field of Interest
                        </div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($data['field_interest']); ?>
                        </div>
                    </div>

                    <?php if (!empty($data['additional_info'])) { ?>
                    <div class="info-group mt-4">
                        <div class="info-label">
                            Additional Information
                        </div>
                        <div class="info-value large">
                            <?php echo htmlspecialchars($data['additional_info']); ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Visit & Submission Information Section -->
                <div class="card-section">
                    <h3 class="section-title">
                        <i class="fas fa-clock"></i>
                        Visit & Submission Details
                    </h3>
                    
                    <div class="grid-3">
                        <?php if (!empty($data['visit_date']) && !empty($data['visit_time'])) { ?>
                        <div class="info-group">
                            <div class="info-label">
                                Visit Date
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($data['visit_date']); ?>
                            </div>
                        </div>

                        <div class="info-group">
                            <div class="info-label">
                                Visit Time
                            </div>
                            <div class="info-value">
                                <?php echo date("h:i A", strtotime($data['visit_time'])); ?>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="info-group">
                            <div class="info-label">
                                Submitted At
                            </div>
                            <div class="info-value timestamp-info">
                                <strong><?php echo date("d M Y, h:i A", strtotime($data['timestamp'])); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- EDIT ADMISSION MODAL -->
            <div class="modal fade" id="editAdmissionModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit Admission Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form action="process-admission.php" method="POST">
                            <div class="modal-body">

                                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                                <div class="form-group">
                                    <label><i class="fas fa-user mr-2"></i>Name</label>
                                    <input type="text" name="name" class="form-control"
                                           value="<?php echo htmlspecialchars($data['name']); ?>">
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-phone mr-2"></i>Mobile No</label>
                                    <input type="text" name="mobile_no" class="form-control"
                                           value="<?php echo htmlspecialchars($data['mobile_no']); ?>">
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-university mr-2"></i>Institute</label>
                                    <input type="text" name="institute" class="form-control"
                                           value="<?php echo htmlspecialchars($data['institute']); ?>">
                                </div>

                                <div class="form-group">
                                    <label><i class="fas fa-info-circle mr-2"></i>Additional Info</label>
                                    <textarea name="additional_info" class="form-control" rows="4"><?php echo htmlspecialchars($data['additional_info']); ?></textarea>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times mr-2"></i>Close
                                </button>
                                <button type="submit" name="update_admission" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<?php } else { header("Location: index.php?not_logged_in"); } ?>

<!-- jQuery (latest, for plugins that still require it) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- Bootstrap 5 JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="assetss/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="assetss/libs/js/main-js.js"></script>
<script src="assetss/vendor/charts/chartist-bundle/chartist.min.js"></script>
<script src="assetss/vendor/charts/sparkline/jquery.sparkline.js"></script>
<script src="assetss/vendor/charts/morris-bundle/raphael.min.js"></script>
<script src="assetss/vendor/charts/morris-bundle/morris.js"></script>
<script src="assetss/vendor/charts/c3charts/c3.min.js"></script>
<script src="assetss/vendor/charts/c3charts/d3-5.4.0.min.js"></script>
<script src="assetss/vendor/charts/c3charts/C3chartjs.js"></script>
<script src="assetss/libs/js/dashboard-ecommerce.js"></script>
</body>
</html>