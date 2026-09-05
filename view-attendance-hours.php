<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database Error");
}

$username = $session->username;
$user_info = $database->getUserInfo($username);
$user_level = $user_info['userlevel'];

// Get target student ID
$student_id = 0;
if ($user_level == 0) { // Student
    $student_q = "SELECT id FROM students WHERE registration_no = '$username' LIMIT 1";
    $student_res = mysqli_query($conn, $student_q);
    if ($student_row = mysqli_fetch_assoc($student_res)) {
        $student_id = $student_row['id'];
    }
} else if (isset($_GET['student_id'])) { // Admin/Teacher
    $student_id = intval($_GET['student_id']);
}

if ($student_id <= 0) {
    die("Invalid Student ID");
}

// Fetch Student Info and Session
$student_q = "SELECT s.*, sess.title as session_title, sess.id as session_id 
              FROM students s 
              LEFT JOIN sessions sess ON s.session_id = sess.id 
              WHERE s.id = '$student_id' LIMIT 1";
$student_res = mysqli_query($conn, $student_q);
$student_data = mysqli_fetch_assoc($student_res);
$session_id = $student_data['session_id'];

// Calculate Attendance Hours
// Rule: Present = 1.0, Half Day = 0.5, others = 0
$attendance_q = "SELECT 
                    SUM(CASE 
                        WHEN attendance = 'Present' THEN duration_hours 
                        WHEN attendance = 'Half Day' THEN duration_hours * 0.5 
                        ELSE 0 
                    END) as total_attended_hours,
                    SUM(duration_hours) as total_conducted_hours
                 FROM student_attendance 
                 WHERE student_id = '$student_id' AND session_id = '$session_id'";
$attendance_res = mysqli_query($conn, $attendance_q);
$attendance_stats = mysqli_fetch_assoc($attendance_res);

$attended_hours = $attendance_stats['total_attended_hours'] ?: 0;
$conducted_hours = $attendance_stats['total_conducted_hours'] ?: 0;
$target_hours = 60.5;
$percentage = ($target_hours > 0) ? min(100, round(($attended_hours / $target_hours) * 100, 1)) : 0;
$is_eligible = ($attended_hours >= $target_hours);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assetss/libs/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Attendance Progress - Filenod Academy</title>
    <style>
        .progress-card {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
        }
        .progress-header {
            background: linear-gradient(135deg, #0e0c28 0%, #2d2463 100%);
            color: white;
            padding: 30px;
        }
        .hours-stat {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1;
        }
        .goal-label {
            opacity: 0.8;
            font-size: 0.9rem;
        }
        .status-badge {
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="dashboard-main-wrapper">
        <?php include('navbar.php'); ?>
        <?php include('leftbar.php'); ?>
        
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-10 col-md-12">
                        
                        <div class="card progress-card mb-4">
                            <div class="progress-header text-center">
                                <h2 class="text-white mb-4">Attendance Progress</h2>
                                <div class="row align-items-center">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="hours-stat"><?php echo $attended_hours; ?></div>
                                        <div class="goal-label">Hours Attended</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="hours-stat"><?php echo $target_hours; ?></div>
                                        <div class="goal-label">Certification Goal</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body p-4 p-md-5">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="mb-0">Progress toward Certification</h5>
                                        <span class="fw-bold text-primary"><?php echo $percentage; ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 15px; border-radius: 10px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated <?php echo $is_eligible ? 'bg-success' : 'bg-primary'; ?>" 
                                             role="progressbar" 
                                             style="width: <?php echo $percentage; ?>%" 
                                             aria-valuenow="<?php echo $percentage; ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 bg-white h-100">
                                            <h6 class="text-muted text-uppercase small mb-2">Student Information</h6>
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fas fa-user me-2 text-primary"></i>
                                                <strong><?php echo htmlspecialchars($student_data['name']); ?></strong>
                                            </div>
                                            <div class="small text-muted mb-1"><?php echo htmlspecialchars($student_data['registration_no']); ?></div>
                                            <div class="small text-muted">Session: <?php echo htmlspecialchars($student_data['session_title']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 bg-white h-100 text-center d-flex flex-column justify-content-center align-items-center">
                                            <h6 class="text-muted text-uppercase small mb-2">Certification Eligibility</h6>
                                            <?php if ($is_eligible): ?>
                                                <div class="status-badge bg-success-light text-success mb-2">
                                                    <i class="fas fa-check-circle me-1"></i> ELIGIBLE
                                                </div>
                                                <p class="small text-muted mb-0">Grade requirements must also be met.</p>
                                            <?php else: ?>
                                                <div class="status-badge bg-warning-light text-warning mb-2">
                                                    <i class="fas fa-clock me-1"></i> IN PROGRESS
                                                </div>
                                                <p class="small text-muted mb-0">Need <?php echo max(0, $target_hours - $attended_hours); ?> more hours.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-5 text-center">
                                    <a href="student-attendance.php" class="btn btn-outline-primary btn-sm me-2">
                                        <i class="fas fa-list me-1"></i> View Detailed Attendance
                                    </a>
                                    <a href="student-tasks.php" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-tasks me-1"></i> View My Grades
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Info Alert -->
                        <div class="alert alert-info border-0 shadow-sm" style="border-radius: 15px;">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading">About Certification Requirements</h6>
                                    <p class="small mb-0">To qualify for certification, students must complete at least <strong>60.5 hours</strong> of attendance and maintain an average grade of <strong>B (24+ marks)</strong> across all assignments. Progress is updated daily.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assetss/libs/js/main-js.js"></script>
    
    <style>
        .bg-success-light { background-color: rgba(40, 167, 69, 0.1); }
        .bg-warning-light { background-color: rgba(255, 193, 7, 0.1); }
    </style>
</body>
</html>
