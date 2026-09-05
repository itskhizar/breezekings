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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="assets/img/favicon-filenod.png" rel="icon">
    <title>Timetable - Filenod Academy</title>
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .attendance-options {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .attendance-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .custom-control {
            margin: 0;
        }
        .custom-control-label {
            font-size: 0.875rem;
        }
        table th {
            background-color: #f8f9fa;
            font-weight: 600;
            white-space: nowrap;
        }
        table td {
            vertical-align: middle;
        }
        .time-input {
            width: 120px;
        }
        .note-input {
            min-width: 150px;
        }
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

    <div class="dashboard-main-wrapper">
        <?php include('navbar.php'); ?>
        <?php include('leftbar.php'); ?>
       
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">Timetable</h2>
                           
                        </div>
                    </div>
                </div>

                <!-- Select Criteria Section -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Select Criteria</h5>
                            </div>
                            <div class="card-body">
                                
                                    <div class="form-row row">
                                        <div class="form-group col-md-4 mb-3">
				                            <label class="form-label fw-semibold">Select Class <span class="text-danger">*</span></label>
				                            <select name="class_id" id="class_id" class="form-control" value='<?php echo $form->value("class_id"); ?>' >
				                                <option selected disabled>Select Class</option>
				                                <?php echo $database->groupdata("classes_dropdown",""); ?>
				                            </select>
				                            <p><?php echo $form->error("class_id"); ?></p>
				                        </div>
                                        <div class="col-md-4 mb-3">
				                            <label class="form-label fw-semibold">Session <span class="text-danger">*</span></label>
				                            <select name="session_id" id="session_id" class="form-control" value='<?php echo $form->value("course_id"); ?>' >
				                                <option selected disabled>Select Class first</option>
				                            </select>
				                            <p><?php echo $form->error("session_id"); ?></p>
				                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label fw-semibold">Week <span class="text-danger">*</span></label>
                                            <select name="week_number" id="week_number" class="form-control">
                                                <?php for($i=1; $i<=8; $i++) { echo "<option value='$i'>Week $i</option>"; } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-1 mb-3 d-flex align-items-end">
                                            <button type="button" class="btn btn-dark w-100" id="bulkUpdateBtn" title="Bulk Update Timetable">
                                                <i class="fas fa-layer-group"></i>
                                            </button>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timetable Result -->
                <div id="timetable_result"></div>

                <!-- Edit Class Modal -->
                <div class="modal fade" id="editClassModal" tabindex="-1" aria-labelledby="editClassModalLabel" aria-hidden="true">
                    <!-- ... existing modal content ... -->
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editClassModalLabel">Override Class Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="overrideForm">
                                    <input type="hidden" name="class_id" id="modal_class_id">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Day/Date</label>
                                        <input type="text" class="form-control" id="modal_display_date" readonly disabled>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Start Time</label>
                                            <input type="text" name="start_time" id="modal_start_time" class="form-control" placeholder="10:00 AM">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">End Time</label>
                                            <input type="text" name="end_time" id="modal_end_time" class="form-control" placeholder="12:00 PM">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Duration (Hours)</label>
                                        <input type="number" step="0.5" name="duration" id="modal_duration" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" id="modal_status" class="form-control">
                                            <option value="Active">Active</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Reason for Change</label>
                                        <textarea name="reason" id="modal_reason" class="form-control" rows="2" placeholder="e.g. Public Holiday, Teacher Unavailable"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveOverrideBtn">Save Override</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bulk Update Modal -->
                <div class="modal fade" id="bulkUpdateModal" tabindex="-1" aria-labelledby="bulkUpdateModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-dark text-white">
                                <h5 class="modal-title" id="bulkUpdateModalLabel"><i class="fas fa-layer-group me-2"></i>Bulk Update Timetable</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="bulkUpdateForm">
                                    <input type="hidden" name="session_id" id="bulk_session_id">
                                    
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Select Weeks</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php for($i=1; $i<=8; $i++): ?>
                                                    <div class="form-check form-check-inline m-0">
                                                        <input class="form-check-input" type="checkbox" name="weeks[]" value="<?php echo $i; ?>" id="week_<?php echo $i; ?>">
                                                        <label class="form-check-label" for="week_<?php echo $i; ?>">W<?php echo $i; ?></label>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Select Days</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php 
                                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                                foreach($days as $day): ?>
                                                    <div class="form-check form-check-inline m-0">
                                                        <input class="form-check-input" type="checkbox" name="days[]" value="<?php echo $day; ?>" id="day_<?php echo $day; ?>">
                                                        <label class="form-check-label" for="day_<?php echo $day; ?>"><?php echo substr($day, 0, 3); ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row mt-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Start Time</label>
                                            <input type="text" name="start_time" id="bulk_start_time" class="form-control" placeholder="10:00 AM">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">End Time</label>
                                            <input type="text" name="end_time" id="bulk_end_time" class="form-control" placeholder="12:00 PM">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Duration (Hours)</label>
                                            <input type="number" step="0.5" name="duration" id="bulk_duration" class="form-control" placeholder="2.0">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Status</label>
                                            <select name="status" id="bulk_status" class="form-select">
                                                <option value="Active">Active</option>
                                                <option value="Cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-semibold">Reason for Bulk Change</label>
                                        <textarea name="reason" id="bulk_reason" class="form-control" rows="2" placeholder="e.g. Ramzan Timing Schedule"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-dark px-4" id="saveBulkBtn">Apply Bulk Changes</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- jQuery (latest, for plugins that still require it) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="assetss/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="assetss/libs/js/main-js.js"></script>

    <script>
$(document).ready(function () {
    $('#class_id').change(function () {
        var class_id = $(this).val();
        $.ajax({
            url: 'fetch_sessionsforclass.php',
            type: 'POST',
            data: { class_id: class_id },
            success: function (data) {
                $('#session_id').html(data);
                $('#timetable_result').html(''); // Clear previous results
            }
        });
    });

    function loadTimetable() {
        var session_id = $('#session_id').val();
        var week_number = $('#week_number').val();

        if (session_id && week_number) {
            $.ajax({
                url: 'fetch_timetable.php',
                type: 'POST',
                data: { 
                    session_id: session_id,
                    week_number: week_number
                },
                success: function (data) {
                    $("#timetable_result").html(data);
                }
            });
        }
    }

    $('#session_id, #week_number').change(function () {
        loadTimetable();
    });

    // Global function to open the edit modal
    window.editClass = function(classData) {
        $('#modal_class_id').val(classData.id);
        $('#modal_display_date').val(classData.day_of_week + ' (' + classData.class_date + ')');
        $('#modal_start_time').val(classData.start_time);
        $('#modal_end_time').val(classData.end_time);
        $('#modal_duration').val(classData.duration_hours);
        $('#modal_status').val(classData.class_status);
        $('#modal_reason').val(classData.override_reason || '');
        
        var myModal = new bootstrap.Modal(document.getElementById('editClassModal'));
        myModal.show();
    };

    $('#saveOverrideBtn').click(function() {
        var formData = $('#overrideForm').serialize();
        $.ajax({
            url: 'save_override.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    bootstrap.Modal.getInstance(document.getElementById('editClassModal')).hide();
                    loadTimetable(); // Refresh table
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred while saving the override.');
            }
        });
    });

    // Bulk Update Logic
    $('#bulkUpdateBtn').click(function() {
        var session_id = $('#session_id').val();
        if(!session_id) {
            alert('Please select a session first.');
            return;
        }
        $('#bulk_session_id').val(session_id);
        var bulkModal = new bootstrap.Modal(document.getElementById('bulkUpdateModal'));
        bulkModal.show();
    });

    $('#saveBulkBtn').click(function() {
        var formData = $('#bulkUpdateForm').serialize();
        
        // Basic validation
        if ($('input[name="weeks[]"]:checked').length === 0) {
            alert('Please select at least one week.');
            return;
        }
        if ($('input[name="days[]"]:checked').length === 0) {
            alert('Please select at least one day.');
            return;
        }

        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Applying...');

        $.ajax({
            url: 'save_bulk_override.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#saveBulkBtn').prop('disabled', false).html('Apply Bulk Changes');
                if (response.success) {
                    bootstrap.Modal.getInstance(document.getElementById('bulkUpdateModal')).hide();
                    alert(response.message);
                    loadTimetable(); // Refresh table
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                $('#saveBulkBtn').prop('disabled', false).html('Apply Bulk Changes');
                alert('An error occurred while applying bulk changes.');
            }
        });
    });

    // Global function to remove override
    window.removeOverride = function(classId) {
        if (confirm('Are you sure you want to remove this override and revert to the original schedule?')) {
            $.ajax({
                url: 'remove_override.php',
                type: 'POST',
                data: { class_id: classId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        loadTimetable(); // Refresh table
                    } else {
                        alert(response.message || 'Failed to remove override.');
                    }
                },
                error: function() {
                    alert('An error occurred while removing the override.');
                }
            });
        }
    };
});
</script>

<?php } else { 
    header("Location: login.php");
    exit();
} ?>

</body>
</html>