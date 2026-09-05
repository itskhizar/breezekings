<?php
ob_start();
ini_set('html_errors', 1);
ini_set('docref_root', 0);
ini_set('docref_ext', 0);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
error_reporting(E_ALL);

include("include/classes/session.php");

if (!$session->logged_in) {
    header('location: index.php');
    exit;
}

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Get logged-in user info
$username = $session->username;
$result1 = $database->getUserInfo($username);
$ulevel = $result1['userlevel'];
$display_name = $result1['display_name'];
$email = $result1['email'];
$phone = $result1['phone'];
$image = $result1['parent_directory'];
$password = $result1['password'];

// Get student ID from URL parameter
$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($student_id == 0) {
    header('location: dashboard.php');
    exit;
}

$result2 = $database->getstudentdetails($student_id);
if (empty($result2)) {
    header('location: dashboard.php');
    exit;
}

// Extract student data
$student_id = $result2['id'];
$admission_no = $result2['admission_no'];
$registration_no = $result2['registration_no'];
$name = $result2['name'];
$gender = $result2['gender'];
$dob = $result2['dob'];
$mobile_no = $result2['mobile_no'];
$cnic = $result2['cnic'];
$student_email = $result2['email'];
$current_grade = $result2['current_grade'];
$current_semester = $result2['current_semester'];
$field_education = $result2['field_education'];
$institute = $result2['institute'];
$education_status = $result2['education_status'];
$other_degree = $result2['other_degree'];
$graduation_year = $result2['graduation_year'] ?? '';
$address = $result2['address'];
$category_id = $result2['category_id'];
$course_id = $result2['course_id'];
$session_id = $result2['session_id'];
$slot = $result2['slot'];
$guardian_name = $result2['guardian_name'];
$guardian_relation = $result2['guardian_relation'];
$guardian_phone = $result2['guardian_phone'];
$registration_date = $result2['registration_date'];
$months = $result2['months'];
$fee_status = $result2['fee_status'];
$student_status = $result2['status'];

// Education label/value based on status
$education_label = "Education Detail";
$education_value = "-";
switch ($education_status) {
    case "Undergraduate":
        $education_label = "Current Semester";
        $education_value = $current_semester;
        break;
    case "Graduate":
        $education_label = "Graduation Year";
        $education_value = $graduation_year;
        break;
    case "Primary":
    case "Secondary":
    case "Intermediate":
        $education_label = "Current Grade";
        $education_value = $current_grade;
        break;
    case "Other":
        $education_label = "Other Degree";
        $education_value = $other_degree;
        break;
}

// Get course info
$result4 = $database->getcoursebyid($course_id);
$course_title = $result4 ? ($result4['course_title'] ?? '') : '';
$duration = $result4 ? ($result4['duration'] ?? '') : '';

// Get category info
$result5 = $database->getcategorybyid($category_id);
$category = $result5 ? ($result5['category'] ?? '') : '';

// Get session info
$result6 = $database->getsessionbyid($session_id);
$session_name = $result6 ? ($result6['title'] ?? '') : '';
$start_time = $result6 ? ($result6['start_time'] ?? '') : '';
$end_time = $result6 ? ($result6['end_time'] ?? '') : '';

// Fetch attendance statistics
$attendance_stats_query = "SELECT 
    COUNT(CASE WHEN attendance = 'Present' THEN 1 END) as total_present,
    COUNT(CASE WHEN attendance = 'Leave' THEN 1 END) as total_leave,
    COUNT(CASE WHEN attendance = 'Absent' THEN 1 END) as total_absent,
    COUNT(CASE WHEN attendance = 'Half Day' THEN 1 END) as total_halfday,
    COUNT(CASE WHEN attendance = 'Holiday' THEN 1 END) as total_holiday,
    COUNT(*) as total_days
FROM student_attendance 
WHERE student_id = '$student_id' 
AND session_id = '$session_id'";

$stats_result = mysqli_query($conn, $attendance_stats_query);
$stats = mysqli_fetch_assoc($stats_result);

$total_present = $stats['total_present'] ?? 0;
$total_leave = $stats['total_leave'] ?? 0;
$total_absent = $stats['total_absent'] ?? 0;
$total_halfday = $stats['total_halfday'] ?? 0;
$total_holiday = $stats['total_holiday'] ?? 0;
$total_days = $stats['total_days'] ?? 0;

// Calculate attendance percentage (excluding Leave and Holiday from denominator)
$denominator = $total_days - $total_leave - $total_holiday;
$attendance_percentage = $denominator > 0 ? round(($total_present) / $denominator * 100, 1) : 0;

// Fetch fees data
$fees = $database->getfeesbycourseid($course_id);
$fee_records = [];
$total_amount = $total_balance = $total_paid = 0;

if (!empty($fees)) {
    foreach ($fees as $row) {
        $monthly = floatval($row['monthly']);
        $balance = $monthly;
        
        // Get student-specific fee status
        $student_fee_res = $database->query("
            SELECT status, paid_amount, reference, created_at 
            FROM student_fees 
            WHERE student_id = '$student_id' AND fee_id = '{$row['id']}'
        ");
        $student_fee_row = mysqli_fetch_assoc($student_fee_res);
        
        $fee_status_val = $student_fee_row['status'] ?? 'Unpaid';
        $paid_amount = $student_fee_row['paid_amount'] ?? 0;
        $reference = $student_fee_row['reference'] ?? '';
        $created_at = $student_fee_row['created_at'] ?? '';
        
        // Get reference user name
        $ref_name = '';
        if (!empty($reference)) {
            $fee_ref = $database->query("SELECT display_name FROM users WHERE username = '$reference'");
            $fee_ref_row = mysqli_fetch_assoc($fee_ref);
            $ref_name = $fee_ref_row['display_name'] ?? $reference;
        }
        
        if ($fee_status_val == 'Paid') {
            $balance = 0;
            $total_paid += $monthly;
        }
        
        $total_amount += $monthly;
        $total_balance += $balance;
        
        $fee_records[] = [
            'id' => $row['id'],
            'type' => $row['type'],
            'fee_month' => $row['fee_month'],
            'amount' => $monthly,
            'due_date' => $row['due_date'],
            'balance' => $balance,
            'status' => $fee_status_val,
            'paid_amount' => $paid_amount,
            'created_at' => $created_at,
            'reference' => $reference,
            'ref_name' => $ref_name
        ];
    }
}

// Attendance stats already fetched above

// Fetch monthly attendance data for calendar
$current_month = date('m');
$current_year = date('Y');

$monthly_attendance_query = "SELECT 
    DATE(sa.created_at) as attendance_date,
    sa.attendance,
    sa.entry_time,
    sa.exit_time,
    sa.note,
    s.start_time
FROM student_attendance sa
JOIN sessions s ON sa.session_id = s.id
WHERE sa.student_id = '$student_id' 
AND sa.session_id = '$session_id'
AND YEAR(sa.created_at) = '$current_year'
ORDER BY sa.created_at ASC";

$monthly_result = mysqli_query($conn, $monthly_attendance_query);
$attendance_data = [];

while ($row = mysqli_fetch_assoc($monthly_result)) {
    $late_minutes = 0;
    if ($row['attendance'] == 'Present' && $row['entry_time'] && $row['start_time']) {
        $entry = strtotime($row['entry_time']);
        $start = strtotime($row['start_time']);
        if ($entry > $start) {
            $late_minutes = round(($entry - $start) / 60);
        }
    }

    $attendance_data[$row['attendance_date']] = [
        'status' => $row['attendance'],
        'entry_time' => $row['entry_time'],
        'exit_time' => $row['exit_time'],
        'note' => $row['note'],
        'late_minutes' => $late_minutes
    ];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assetss/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assetss/libs/css/style.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link href="assets/img/favicon-filenod.png" rel="icon">

    <title>Student Profile - <?php echo htmlspecialchars($name); ?></title>
    
    <style>
        :root {
            --primary: #173663;
            --primary-light: #1f4c8f;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --gray-50: #f8f9fa;
            --gray-100: #f1f3f5;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-500: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
        }
        
        body { background: var(--gray-100); }
        
        .dashboard-main-wrapper { padding-top: 0; }
        
        /* Page Container */
        .page-container {
            padding: 20px;
            max-width: 100%;
            margin: 0 auto;
        }
        
        /* Page Header */
        .page-header {
            background: white;
            padding: 20px 24px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }
        
        .page-title i { color: var(--primary); margin-right: 10px; }
        
        .header-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        
        .btn-action {
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-primary-custom {
            background: var(--primary);
            color: white;
            border: none;
        }
        
        .btn-primary-custom:hover {
            background: var(--primary-light);
            color: white;
        }
        
        .btn-outline-custom {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        .btn-outline-custom:hover {
            background: var(--primary);
            color: white;
        }
        
        .btn-danger-custom {
            background: white;
            color: var(--danger);
            border: 1px solid var(--danger);
        }
        
        .btn-danger-custom:hover {
            background: var(--danger);
            color: white;
        }
        
        /* Card Styles */
        .card-section {
            background: white;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card-header-custom {
            background: var(--gray-50);
            padding: 14px 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .card-title i { color: var(--primary); font-size: 14px; }
        
        .card-body-custom { padding: 20px; }
        
        /* Profile Header */
        .profile-header {
            display: flex;
            gap: 24px;
            align-items: flex-start;
            flex-wrap: wrap;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
            border: 3px solid var(--gray-200);
            flex-shrink: 0;
        }
        
        .profile-info { flex: 1; min-width: 250px; }
        
        .profile-name {
            font-size: 22px;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0 0 4px 0;
        }
        
        .profile-id {
            font-size: 13px;
            color: var(--gray-500);
            margin-bottom: 12px;
        }
        
        .profile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .meta-item {
            font-size: 13px;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .meta-item i { color: var(--gray-500); font-size: 12px; }
        
        .profile-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 12px;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-active { background: #d4edda; color: #155724; }
        .badge-inactive { background: #f8d7da; color: #721c24; }
        .badge-paid { background: #d4edda; color: #155724; }
        .badge-unpaid { background: #f8d7da; color: #721c24; }
        
        /* Quick Stats */
        .quick-stats {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }
        
        .stat-box {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 16px 24px;
            text-align: center;
            min-width: 100px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }
        
        .stat-label {
            font-size: 11px;
            color: var(--gray-500);
            margin-top: 4px;
            text-transform: uppercase;
        }
        
        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 0;
        }
        
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid var(--gray-100);
        }
        
        .info-row:last-child { border-bottom: none; }
        
        .info-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-500);
            min-width: 140px;
        }
        
        .info-value {
            font-size: 13px;
            color: var(--gray-800);
        }
        
        /* Tab Navigation */
        .tab-nav {
            display: flex;
            gap: 4px;
            padding: 0 20px;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .tab-btn {
            padding: 12px 20px;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-500);
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .tab-btn:hover { color: var(--gray-700); }
        
        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        /* Fee Table */
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        .fee-table th {
            background: var(--gray-50);
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            border-bottom: 2px solid var(--gray-200);
            white-space: nowrap;
        }
        
        .fee-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }
        
        .fee-table tbody tr:hover { background: var(--gray-50); }
        
        .fee-table tfoot td {
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-800);
            border-top: 2px solid var(--gray-200);
        }
        
        .fee-status {
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }
        
        .fee-status.paid { background: #d4edda; color: #155724; }
        .fee-status.unpaid { background: #f8d7da; color: #721c24; }
        
        .fee-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        
        .btn-sm-action {
            padding: 4px 10px;
            font-size: 11px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }
        
        .btn-print { background: var(--gray-100); color: var(--gray-700); }
        .btn-print:hover { background: var(--gray-200); }
        
        .btn-mark-paid { background: var(--success); color: white; }
        .btn-mark-paid:hover { background: #218838; }
        
        .ref-text {
            font-size: 11px;
            color: var(--gray-500);
        }
        
        /* Attendance Stats */
        .attendance-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .att-stat {
            text-align: center;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
        }
        
        .att-stat.present { background: #d4edda; border-color: #c3e6cb; }
        .att-stat.leave { background: #fff3cd; border-color: #ffeeba; }
        .att-stat.absent { background: #f8d7da; border-color: #f5c6cb; }
        .att-stat.halfday { background: #d1ecf1; border-color: #bee5eb; }
        .att-stat.holiday { background: #e2e3e5; border-color: #d6d8db; }
        
        .att-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-800);
        }
        
        .att-label {
            font-size: 11px;
            color: var(--gray-600);
            margin-top: 4px;
        }
        
        /* Progress Bar */
        .progress-section {
            background: var(--gray-50);
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .progress-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 8px;
        }
        
        .progress-bar-container {
            height: 24px;
            background: var(--gray-200);
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--success);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 600;
            transition: width 0.3s;
        }
        
        /* Print Modal */
        .print-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            overflow-y: auto;
            padding: 20px;
        }
        
        .print-modal-content {
            background: white;
            max-width: 800px;
            margin: 20px auto;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .print-modal-header {
            background: var(--gray-50);
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .print-modal-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-800);
        }
        
        .print-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--gray-500);
            cursor: pointer;
        }
        
        .print-modal-body { padding: 20px; }
        
        .print-modal-footer {
            background: var(--gray-50);
            padding: 16px 20px;
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        
        /* Print Styles */
        /* Print Styles */
        @media print {
            @page {
                size: auto;
                margin: 5mm;
            }
            body * { visibility: hidden; }
            
            #printArea, #printArea * { visibility: visible; }
            
            /* Fix the Modal Container constraints */
            #printModal {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                display: block !important;
                overflow: visible !important;
                background: white !important;
                width: 100% !important;
                height: auto !important;
                z-index: 9999;
            }

            /* Reset inner modal content wrapper */
            .print-modal-content {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important; 
                max-width: none !important;
            }

            /* Print Area positioning */
            #printArea {
                position: static !important; /* Let it flow normally within the reset modal */
                width: 100%;
                padding: 15px; /* Adjust padding for print */
                margin: 0;
            }

            /* Hide modal UI elements */
            .print-modal-header, 
            .print-modal-footer, 
            .print-modal-close,
            .no-print { 
                display: none !important; 
            }
            
            /* Ensure tables print completely */
            .print-table {
                width: 100% !important;
                max-width: 100% !important;
                table-layout: auto !important;
                page-break-inside: auto !important;
                border-collapse: collapse !important;
            }
            
            .print-table th,
            .print-table td {
                border: 1px solid #ddd !important;
                padding: 4px 6px !important;
                font-size: 9px !important;
                word-wrap: break-word !important;
                overflow: visible !important;
            }
            
            .print-table tr {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
            }
            
            @page {
                size: A4;
                margin: 5mm;
            }
        }
        
        /* Print Document Styles */
        .print-doc {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        
        .print-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        
        .print-logo { width: 60px; height: auto; margin-bottom: 8px; }
        
        .print-title {
            font-size: 16px;
            font-weight: bold;
            margin: 4px 0;
        }
        
        .print-subtitle {
            font-size: 10px;
            color: #666;
        }
        
        .print-section {
            margin-bottom: 16px;
        }
        
        .print-section-title {
            font-size: 12px;
            font-weight: bold;
            background: #f5f5f5;
            padding: 6px 10px;
            margin-bottom: 8px;
            border-left: 3px solid #333;
        }
        
        .print-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            table-layout: auto;
        }
        
        .print-table th,
        .print-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            word-wrap: break-word;
        }
        
        .print-table th {
            background: #f5f5f5;
            font-weight: bold;
        }
        
        @media print {
            .print-table {
                width: 100% !important;
                max-width: 100% !important;
                font-size: 9px !important;
            }
            .print-table th,
            .print-table td {
                padding: 4px 6px !important;
                font-size: 9px !important;
            }
        }
        
        .print-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 16px;
        }
        
        .print-info-row {
            display: flex;
            padding: 4px 0;
            border-bottom: 1px solid #eee;
        }
        
        .print-info-label {
            font-weight: bold;
            min-width: 120px;
            color: #555;
        }
        
        .print-info-value { color: #333; }
        
        /* Ensure print tables don't overflow */
        .print-doc {
            max-width: 100%;
            overflow-x: visible;
        }
        
        .print-table {
            page-break-inside: auto;
        }
        
        .print-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        .print-footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .profile-header { flex-direction: column; align-items: center; text-align: center; }
            .profile-meta { justify-content: center; }
            .profile-badges { justify-content: center; }
            .quick-stats { flex-direction: column; width: 100%; }
            .stat-box { width: 100%; }
            .attendance-grid { grid-template-columns: repeat(2, 1fr); }
            .info-grid { grid-template-columns: 1fr; }
            .header-actions { width: 100%; justify-content: center; }
        }





        
    </style>
</head>

<body>
<?php if($session->logged_in): ?>
<div class="dashboard-main-wrapper">
    <?php include('navbar.php'); ?>
    <?php include('leftbar.php'); ?>
    
    <div class="dashboard-wrapper">
        <div class="page-container">
            
            <!-- Page Header -->
            <div class="page-header no-print">
                <h1 class="page-title">
                    <i class="fas fa-user-graduate"></i>Student Profile
                </h1>
                <div class="header-actions">
                    <button class="btn-action btn-primary-custom" onclick="printFullProfile()">
                        <i class="fas fa-print"></i> Print Profile
                    </button>
                    <?php if ($session->userlevel == 1 || $session->userlevel == 4): ?>
                    <button class="btn-action btn-outline-custom" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                    <button class="btn-action btn-outline-custom" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                        <i class="fas fa-user"></i> Update Status
                    </button>
                    <a href="delete-registration.php?id=<?php echo $registration_no; ?>" 
                       onclick="return confirm('Are you sure you want to delete this student?');"
                       class="btn-action btn-danger-custom">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Profile Overview Card -->
            <div class="card-section">
                <div class="card-body-custom">
                    <div class="profile-header">
                        <img src="<?php echo (!empty($image) && file_exists('images/' . $image)) ? 'images/' . $image : 'images/avatar.png'; ?>" 
                             alt="<?php echo htmlspecialchars($name); ?>" 
                             class="profile-avatar">
                        
                        <div class="profile-info">
                            <h2 class="profile-name"><?php echo htmlspecialchars($name); ?></h2>
                            <p class="profile-id">Admission No: <?php echo htmlspecialchars($admission_no); ?> | Reg No: <?php echo htmlspecialchars($registration_no); ?></p>
                            
                            <div class="profile-meta">
                                <span class="meta-item"><i class="fas fa-book"></i> <?php echo htmlspecialchars($course_title); ?></span>
                                <span class="meta-item"><i class="fas fa-layer-group"></i> <?php echo htmlspecialchars($category); ?></span>
                                <span class="meta-item"><i class="fas fa-clock"></i> <?php echo htmlspecialchars($session_name); ?> (<?php echo $start_time; ?> - <?php echo $end_time; ?>)</span>
                                <span class="meta-item"><i class="fas fa-calendar"></i> Joined: <?php echo date("d M Y", strtotime($registration_date)); ?></span>
                            </div>
                            
                            <div class="profile-badges">
                                <span class="status-badge <?php echo $student_status == 'Active' ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?php echo htmlspecialchars($student_status); ?>
                                </span>
                                <!-- <span class="status-badge <?php echo $fee_status == 'Paid' ? 'badge-paid' : 'badge-unpaid'; ?>">
                                    Fee: <?php echo htmlspecialchars($fee_status); ?>
                                </span> -->
                                <?php if ($slot): ?>
                                <span class="status-badge" style="background: var(--gray-100); color: var(--gray-700);">
                                    Slot: <?php echo htmlspecialchars($slot); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="quick-stats">
                            <div class="stat-box">
                                <div class="stat-value"><?php echo $attendance_percentage; ?>%</div>
                                <div class="stat-label">Attendance</div>
                            </div>
                            <div class="stat-box">
                                <div class="stat-value"><?php echo count($fee_records); ?></div>
                                <div class="stat-label">Fee Months</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabs Section -->
            <div class="card-section">
                <div class="tab-nav no-print">
                    <button class="tab-btn active" data-tab="profile"><i class="fas fa-user"></i> Profile</button>
                    <button class="tab-btn" data-tab="fees"><i class="fas fa-money-bill"></i> Fees</button>
                    <button class="tab-btn" data-tab="attendance"><i class="fas fa-calendar-check"></i> Attendance</button>
                    <button class="tab-btn" data-tab="timetable"><i class="fas fa-calendar-times"></i> Timetable</button>
                </div>
                
                <!-- Profile Tab -->
                <div class="tab-content active" id="tab-profile">
                    <div class="card-body-custom">
                        
                        <!-- Personal Information -->
                        <div class="card-header-custom" style="margin: -20px -20px 20px -20px;">
                            <h3 class="card-title"><i class="fas fa-user"></i> Personal Information</h3>
                        </div>
                        
                        <div class="info-grid">
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Full Name</span>
                                    <span class="info-value"><?php echo htmlspecialchars($name); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Gender</span>
                                    <span class="info-value"><?php echo htmlspecialchars($gender); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Date of Birth</span>
                                    <span class="info-value"><?php echo $dob ? date("d M Y", strtotime($dob)) : '-'; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">CNIC</span>
                                    <span class="info-value"><?php echo htmlspecialchars($cnic) ?: '-'; ?></span>
                                </div>
                            </div>
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Mobile No</span>
                                    <span class="info-value"><?php echo htmlspecialchars($mobile_no); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Email</span>
                                    <span class="info-value"><?php echo htmlspecialchars($student_email) ?: '-'; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Address</span>
                                    <span class="info-value"><?php echo htmlspecialchars($address) ?: '-'; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Education Information -->
                        <div class="card-header-custom" style="margin: 20px -20px 20px -20px;">
                            <h3 class="card-title"><i class="fas fa-graduation-cap"></i> Education Information</h3>
                        </div>
                        
                        <div class="info-grid">
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Institute</span>
                                    <span class="info-value"><?php echo htmlspecialchars($institute) ?: '-'; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Field of Education</span>
                                    <span class="info-value"><?php echo htmlspecialchars($field_education) ?: '-'; ?></span>
                                </div>
                            </div>
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Education Status</span>
                                    <span class="info-value"><?php echo htmlspecialchars($education_status) ?: '-'; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label"><?php echo htmlspecialchars($education_label); ?></span>
                                    <span class="info-value"><?php echo htmlspecialchars($education_value) ?: '-'; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Course Information -->
                        <div class="card-header-custom" style="margin: 20px -20px 20px -20px;">
                            <h3 class="card-title"><i class="fas fa-book"></i> Course Information</h3>
                        </div>
                        
                        <div class="info-grid">
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Course</span>
                                    <span class="info-value"><?php echo htmlspecialchars($course_title); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Category</span>
                                    <span class="info-value"><?php echo htmlspecialchars($category); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Duration</span>
                                    <span class="info-value"><?php echo htmlspecialchars($duration); ?> Months</span>
                                </div>
                            </div>
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Session</span>
                                    <span class="info-value"><?php echo htmlspecialchars($session_name); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Timing</span>
                                    <span class="info-value"><?php echo $start_time; ?> - <?php echo $end_time; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Slot</span>
                                    <span class="info-value"><?php echo htmlspecialchars($slot) ?: '-'; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Guardian Information -->
                        <div class="card-header-custom" style="margin: 20px -20px 20px -20px;">
                            <h3 class="card-title"><i class="fas fa-users"></i> Guardian Information</h3>
                        </div>
                        
                        <div class="info-grid">
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Guardian Name</span>
                                    <span class="info-value"><?php echo htmlspecialchars($guardian_name) ?: '-'; ?></span>
                                </div>
                            </div>
                            <div>
                                <div class="info-row">
                                    <span class="info-label">Relation</span>
                                    <span class="info-value"><?php echo htmlspecialchars($guardian_relation) ?: '-'; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Phone</span>
                                    <span class="info-value"><?php echo htmlspecialchars($guardian_phone) ?: '-'; ?></span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Fees Tab -->
                <div class="tab-content" id="tab-fees">
                    <div class="card-body-custom">
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                            <h3 class="card-title" style="margin: 0;"><i class="fas fa-money-bill"></i> Fee Records</h3>
                            <button class="btn-action btn-outline-custom" id="regFeeBtn" onclick="toggleRegistrationFee()">
                                <i class="far fa-square"></i> Registration Fee (+5000)
                            </button> <!-- Added Registration Fee Button -->
                            <button class="btn-action btn-outline-custom" onclick="printAllFees()">
                                <i class="fas fa-print"></i> Print All Fees
                            </button>
                        </div>
                        
                        <div style="overflow-x: auto;">
                            <table class="fee-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Fee Type</th>
                                        <th>Month</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Paid By</th>
                                        <th>Time</th>
                                        <th class="no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($fee_records)): ?>
                                        <?php foreach ($fee_records as $index => $fee): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($fee['type']); ?></td>
                                            <td><?php echo htmlspecialchars($fee['fee_month']); ?></td>
                                            <td>Rs. <?php echo number_format($fee['amount'], 2); ?></td>
                                            <td><?php echo $fee['due_date'] ? date('d M Y', strtotime($fee['due_date'])) : '-'; ?></td>
                                            <td>
                                                <span class="fee-status <?php echo $fee['status'] == 'Paid' ? 'paid' : 'unpaid'; ?>">
                                                    <?php echo $fee['status']; ?>
                                                </span>
                                            </td>
                                             <td>
                                                <?php if ($fee['status'] == 'Paid' && $fee['ref_name']): ?>
                                                    <span class="ref-text"><?php echo htmlspecialchars($fee['ref_name']); ?></span>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($fee['status'] == 'Paid' && $fee['ref_name']): ?>
                                                    <span class="ref-text">
                                                        <?php echo date('d M Y, h:i A', strtotime($fee['created_at'])); ?>
                                                    </span>

                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td class="no-print">
                                                <div class="fee-actions">
                                                    <button class="btn-sm-action btn-print" 
                                                            onclick="printIndividualFee(<?php echo htmlspecialchars(json_encode($fee), ENT_QUOTES); ?>)">
                                                        <i class="fas fa-print"></i> Print
                                                    </button>
                                                    <?php if ($fee['status'] != 'Paid'): ?>
                                                    <form method="post" action="update_fee_status.php" style="margin: 0;" id="markPaidForm">
                                                        <input type="hidden" name="fee_id" value="<?php echo $fee['id']; ?>">
                                                        <input type="hidden" name="status" value="Paid">
                                                        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                                                        <input type="hidden" name="paid_amount" value="<?php echo $fee['amount']; ?>">
                                                        <input type="hidden" name="fee_month" value="<?php echo $fee['fee_month']; ?>">
                                                        <input type="hidden" name="reference" value="<?php echo $session->username; ?>">

                                                        <button type="submit" class="btn-sm-action btn-mark-paid">
                                                            <i class="fas fa-check"></i> Mark Paid
                                                        </button>
                                                    </form>
                                                    <?php else: ?>
                                                    <form method="post" action="update_fee_status.php" style="margin: 0;" class="markUnpaidForm">
                                                        <input type="hidden" name="fee_id" value="<?php echo $fee['id']; ?>">
                                                        <input type="hidden" name="status" value="Unpaid">
                                                        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                                                        <input type="hidden" name="paid_amount" value="0">
                                                        <input type="hidden" name="fee_month" value="<?php echo $fee['fee_month']; ?>">
                                                        <input type="hidden" name="reference" value="<?php echo $session->username; ?>">

                                                        <button type="submit" class="btn-sm-action btn-danger-custom" style="padding: 4px 10px; font-size: 11px;">
                                                            <i class="fas fa-times"></i> Mark Unpaid
                                                        </button>
                                                    </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--gray-500);">
                                                No fee records found
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if (!empty($fee_records)): ?>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="text-align: right;">Total:</td>
                                        <td id="tableTotalAmount">Rs. <?php echo number_format($total_amount, 2); ?></td>
                                        <td></td>
                                        <td>Paid: Rs. <?php echo number_format($total_paid, 2); ?></td>
                                        <td>Balance: Rs. <?php echo number_format($total_balance, 2); ?></td>
                                        <td></td>
                                        <td class="no-print"></td>
                                    </tr>
                                </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Attendance Tab -->
                <div class="tab-content" id="tab-attendance">
                    <div class="card-body-custom">
                        
                        <h3 class="card-title" style="margin-bottom: 20px;"><i class="fas fa-chart-bar"></i> Attendance Overview</h3>
                        
                        <div class="attendance-grid">
                            <div class="att-stat present">
                                <div class="att-value"><?php echo $total_present; ?></div>
                                <div class="att-label">Present</div>
                            </div>
                            <div class="att-stat leave">
                                <div class="att-value"><?php echo $total_leave; ?></div>
                                <div class="att-label">Leave</div>
                            </div>
                            <div class="att-stat absent">
                                <div class="att-value"><?php echo $total_absent; ?></div>
                                <div class="att-label">Absent</div>
                            </div>
                            <div class="att-stat halfday">
                                <div class="att-value"><?php echo $total_halfday; ?></div>
                                <div class="att-label">Half Day</div>
                            </div>
                            <div class="att-stat holiday">
                                <div class="att-value"><?php echo $total_holiday; ?></div>
                                <div class="att-label">Holiday</div>
                            </div>
                        </div>
                        
                        <div class="progress-section">
                            <div class="progress-label">Overall Attendance: <?php echo $attendance_percentage; ?>%</div>
                            <div class="progress-bar-container">
                                <div class="progress-fill" style="width: <?php echo $attendance_percentage; ?>%;">
                                    <?php echo $attendance_percentage; ?>%
                                </div>
                            </div>
                        </div>
                        <!-- Monthly Calendar View -->
        <div class="calendar-section">
            <h5 class="section-header mt-4">
                <i class="fas fa-calendar-alt"></i> Monthly Attendance Calendar
            </h5>
            
            <div class="text-center mb-3">
                <button class="btn btn-sm btn-primary" onclick="changeMonth(-1)">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <span class="mx-3 font-weight-bold" id="current-month-year">
                    <?php echo date('F Y'); ?>
                </span>
                <button class="btn btn-sm btn-primary" onclick="changeMonth(1)">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div id="attendance-calendar"></div>
            
            <!-- <div class="text-center mt-3">
                <a href="view-attendance-student.php?id=<?php echo $student_id; ?>" class="btn btn-info">
                    <i class="fas fa-calendar-check"></i> View Full Calendar
                </a>
            </div> -->
        </div>
        <style>
        /* Additional CSS for calendar and legend */
        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-box {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .attendance-percentage {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        #attendance-calendar {
            background: white;
            border-radius: 8px;
            padding: 15px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-top: 15px;
        }

        .calendar-day-header {
            text-align: center;
            font-weight: 600;
            padding: 10px;
            background: #173663;
            color: white;
            border-radius: 4px;
            font-size: 12px;
        }

        .calendar-day {
            aspect-ratio: 1;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            text-align: center;
            position: relative;
            cursor: pointer;
            transition: all 0.3s;
        }

        .calendar-day:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .calendar-day.empty {
            background: #f8f9fa;
            cursor: default;
        }
        
        .calendar-day.present.late-present {
            background: linear-gradient(135deg, #d1fae5 60%, #fef3c7 60%);
        }
        
        .late-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            font-size: 8px;
            font-weight: bold;
            color: #d97706;
            background: rgba(255, 255, 255, 0.8);
            padding: 0 3px;
            border-radius: 3px;
            line-height: 1;
        }

        .calendar-day.empty:hover {
            box-shadow: none;
            transform: none;
        }

        .calendar-day-number {
            font-size: 12px;
            font-weight: 600;
            color: #333;
        }

        .calendar-day-status {
            font-size: 10px;
            font-weight: 700;
            margin-top: 5px;
            padding: 2px;
            border-radius: 3px;
            color: white;
        }

        .calendar-day.present {
            background: #d4edda;
        }

        .calendar-day.leave {
            background: #fff3cd;
        }

        .calendar-day.absent {
            background: #f8d7da;
        }

        .calendar-day.holiday {
            background: #e2e3e5;
        }

        .calendar-day.halfday {
            background: #ffe4b3;
        }
        </style>
                        <!-- Recent Attendance Table -->
                        <h4 style="font-size: 14px; font-weight: 600; color: var(--gray-700); margin: 24px 0 16px;">
                            Recent Attendance (Last 10 Days)
                        </h4>
                        
                        <div style="overflow-x: auto;">
                            <table class="fee-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Late Time</th>
                                        <th>Entry Time</th>
                                        <th>Exit Time</th>
                                        <th>Note</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $recent_query = "SELECT id, DATE(created_at) as attendance_date, attendance, entry_time, exit_time, note
                                        FROM student_attendance 
                                        WHERE student_id = '$student_id' AND session_id = '$session_id'
                                        ORDER BY created_at DESC LIMIT 10";
                                    $recent_result = mysqli_query($conn, $recent_query);
                                    
                                    if (mysqli_num_rows($recent_result) > 0):
                                        while ($att = mysqli_fetch_assoc($recent_result)):
                                            $status_class = '';
                                            $late_display = '-';
                                            // Calculate late minutes if Present and times exist
                                            if ($att['attendance'] == 'Present' && $att['entry_time']) {
                                                // Fetch session start time if not already in $att (it's not in the simple query)
                                                // We need to fetch it or use the global $start_time if it's the same session
                                                // Since we are in student details for a specific session, we can use the $start_time variable fetched earlier
                                                
                                                if ($start_time && $att['entry_time']) {
                                                    $entry = strtotime($att['entry_time']);
                                                    $start = strtotime($start_time); // $start_time is available globally in this file
                                                    if ($entry > $start) {
                                                        $diff = round(($entry - $start) / 60);
                                                        if ($diff > 0) {
                                                            $late_display = "<span class='text-danger font-weight-bold'>{$diff} mins</span>";
                                                        }
                                                    }
                                                }
                                            }

                                            switch ($att['attendance']) {
                                                case 'Present': $status_class = 'paid'; break;
                                                case 'Leave': $status_class = 'style="background: #fff3cd; color: #856404;"'; break;
                                                case 'Absent': $status_class = 'unpaid'; break;
                                                default: $status_class = '';
                                            }
                                    ?>
                                    <tr>
                                        <td><?php echo date('d M Y', strtotime($att['attendance_date'])); ?></td>
                                        <td>
                                            <span class="fee-status <?php echo $att['attendance'] == 'Present' ? 'paid' : ($att['attendance'] == 'Absent' ? 'unpaid' : ''); ?>" 
                                                  <?php echo ($att['attendance'] == 'Leave') ? 'style="background: #fff3cd; color: #856404;"' : ''; ?>>
                                                <?php echo $att['attendance']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $late_display; ?></td>
                                        <td><?php echo $att['entry_time'] ? date('h:i A', strtotime($att['entry_time'])) : '-'; ?></td>
                                        <td><?php echo $att['exit_time'] ? date('h:i A', strtotime($att['exit_time'])) : '-'; ?></td>
                                        <td><?php echo htmlspecialchars($att['note']) ?: '-'; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="openEditAttendanceModal(<?php echo $att['id']; ?>, '<?php echo $att['attendance']; ?>', '<?php echo htmlspecialchars($att['note'], ENT_QUOTES); ?>', '<?php echo $att['entry_time']; ?>', '<?php echo $att['exit_time']; ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--gray-500);">
                                            No attendance records found
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
                <!-- Timetable Tab -->
                <div class="tab-content" id="tab-timetable">
                    <div class="card-body-custom">
                        <h3 class="card-title" >
                            <i class="fas fa-calendar-times"></i> Timetable
                        </h3>
                        <div id="timetable_result">
                             <div class="text-center p-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</div>


<!-- Edit Attendance Modal -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAttendanceForm">
                <div class="modal-body">
                    <input type="hidden" name="attendance_id" id="edit_attendance_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="attendance_status" id="edit_attendance_status" required>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Leave">Leave</option>
                            <option value="Half Day">Half Day</option>
                            <option value="Holiday">Holiday</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea class="form-control" name="note" id="edit_attendance_note" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Entry Time</label>
                            <input type="time" class="form-control" name="entry_time" id="edit_entry_time">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Exit Time</label>
                            <input type="time" class="form-control" name="exit_time" id="edit_exit_time">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Student Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="student_status.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                    <input type="hidden" name="registration_no" value="<?php echo $registration_no; ?>">
                    <input type="hidden" name="reference" value="<?php echo $session->username; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Student Name:</strong></label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($name); ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Profile Status:</strong></label>
                        <select class="form-control" name="profile_status" required>
                            <option value="">Select Status</option>
                            <option value="Active" <?php echo $student_status == 'Active' ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo $student_status == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="update_status">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editProfileModalLabel"><i class="fas fa-user-edit me-2"></i>Edit Student Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProfileForm">
                <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                <div class="modal-body">
                    <!-- Personal Information -->
                    <div class="section-title mb-3 pb-2 border-bottom">
                        <h6 class="mb-0 text-primary fw-bold">Personal Information</h6>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="male" <?php echo strtolower($gender) == 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo strtolower($gender) == 'female' ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo strtolower($gender) == 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" value="<?php echo $dob; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">CNIC</label>
                            <input type="text" name="cnic" class="form-control" value="<?php echo htmlspecialchars($cnic); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Mobile No</label>
                            <input type="text" name="mobile_no" class="form-control" value="<?php echo htmlspecialchars($mobile_no); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student_email); ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($address); ?></textarea>
                        </div>
                    </div>

                    <!-- Education Information -->
                    <div class="section-title mb-3 pb-2 border-bottom">
                        <h6 class="mb-0 text-primary fw-bold">Education Information</h6>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Institute</label>
                            <input type="text" name="institute" class="form-control" value="<?php echo htmlspecialchars($institute); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Field of Education</label>
                            <input type="text" name="field_education" class="form-control" value="<?php echo htmlspecialchars($field_education); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Education Status</label>
                            <select name="education_status" id="edit_education_status" class="form-select" onchange="toggleEducationFields()">
                                <option value="Primary" <?php echo $education_status == 'Primary' ? 'selected' : ''; ?>>Primary</option>
                                <option value="Secondary" <?php echo $education_status == 'Secondary' ? 'selected' : ''; ?>>Secondary (Matriculation)</option>
                                <option value="Intermediate" <?php echo $education_status == 'Intermediate' ? 'selected' : ''; ?>>Intermediate (F.Sc/A-Level)</option>
                                <option value="Undergraduate" <?php echo $education_status == 'Undergraduate' ? 'selected' : ''; ?>>Undergraduate (Bachelor)</option>
                                <option value="Graduate" <?php echo $education_status == 'Graduate' ? 'selected' : ''; ?>>Graduate (Masters/PhD)</option>
                                <option value="Other" <?php echo $education_status == 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 edu-field" id="field_current_grade" style="display: none;">
                            <label class="form-label fw-semibold">Current Grade</label>
                            <input type="text" name="current_grade" class="form-control" value="<?php echo htmlspecialchars($current_grade); ?>">
                        </div>
                        <div class="col-md-6 mb-3 edu-field" id="field_current_semester" style="display: none;">
                            <label class="form-label fw-semibold">Current Semester</label>
                            <input type="text" name="current_semester" class="form-control" value="<?php echo htmlspecialchars($current_semester); ?>">
                        </div>
                        <div class="col-md-6 mb-3 edu-field" id="field_graduation_year" style="display: none;">
                            <label class="form-label fw-semibold">Graduation Year</label>
                            <input type="text" name="graduation_year" class="form-control" value="<?php echo htmlspecialchars($graduation_year); ?>">
                        </div>
                        <div class="col-md-6 mb-3 edu-field" id="field_other_degree" style="display: none;">
                            <label class="form-label fw-semibold">Degree Name</label>
                            <input type="text" name="other_degree" class="form-control" value="<?php echo htmlspecialchars($other_degree); ?>">
                        </div>
                    </div>

                    <!-- Guardian & Slot Information -->
                    <div class="section-title mb-3 pb-2 border-bottom">
                        <h6 class="mb-0 text-primary fw-bold">Guardian & Slot</h6>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Guardian Name</label>
                            <input type="text" name="guardian_name" class="form-control" value="<?php echo htmlspecialchars($guardian_name); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Relation</label>
                            <input type="text" name="guardian_relation" class="form-control" value="<?php echo htmlspecialchars($guardian_relation); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Guardian Phone</label>
                            <input type="text" name="guardian_phone" class="form-control" value="<?php echo htmlspecialchars($guardian_phone); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold" style="background: #fff9db; padding: 2px 5px; border-radius: 3px;">Current Slot</label>
                            <input type="text" name="slot" class="form-control border-warning" value="<?php echo htmlspecialchars($slot); ?>" placeholder="e.g. 1">
                            <small class="text-muted">Manually adjust class slot if needed.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold" id="saveProfileBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Print Modal -->
<div class="print-modal" id="printModal">
    <div class="print-modal-content">
        <div class="print-modal-header no-print">
            <h3 class="print-modal-title" id="printModalTitle">Print Preview</h3>
            <button class="print-modal-close" onclick="closePrintModal()">&times;</button>
        </div>
        <div class="print-modal-body" id="printArea">
            <!-- Print content will be inserted here -->
        </div>
        <div class="print-modal-footer no-print">
            <button class="btn-action btn-primary-custom" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
            <button class="btn-action btn-outline-custom" onclick="closePrintModal()">
                Close
            </button>
        </div>
    </div>
    </div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assetss/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="assetss/libs/js/main-js.js"></script>

<script>
function openEditAttendanceModal(id, status, note, entryTime, exitTime) {
    $('#edit_attendance_id').val(id);
    $('#edit_attendance_status').val(status);
    $('#edit_attendance_note').val(note);
    $('#edit_entry_time').val(entryTime);
    $('#edit_exit_time').val(exitTime);
    var modal = new bootstrap.Modal(document.getElementById('editAttendanceModal'));
    modal.show();
}

$('#editAttendanceForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: 'update_attendance_ajax.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Attendance updated successfully');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while updating attendance');
        }
    });
});
</script>
<!-- 
                <td style="border: 1px solid #ddd;">${fee.fee_month || '-'}</td> -->
<script>
// Global variables for AJAX and Print
const studentId = '<?php echo $student_id; ?>';
const sessionId = '<?php echo $session_id; ?>';

// Student data for print functions
let includeRegistrationFee = false; // State for registration fee

function toggleRegistrationFee() {
    includeRegistrationFee = !includeRegistrationFee;
    const btn = document.getElementById('regFeeBtn');
    if (includeRegistrationFee) {
        btn.classList.remove('btn-outline-custom', 'btn-action'); // Reset classes slightly to handle overrides if needed, but simple toggle is safer
        // Actually, let's just toggle specific classes as per CSS
        btn.className = 'btn-action btn-primary-custom'; 
        btn.innerHTML = '<i class="fas fa-check-square"></i> Registration Fee Added';
    } else {
        btn.className = 'btn-action btn-outline-custom';
        btn.innerHTML = '<i class="far fa-square"></i> Registration Fee (+5000)';
    }

    // Update HTML Table Total
    const displayTotal = totalAmount + (includeRegistrationFee ? 5000 : 0);
    const tableTotalEl = document.getElementById('tableTotalAmount');
    if(tableTotalEl) {
        // Simple formatting to match PHP number_format
        tableTotalEl.innerText = 'Rs. ' + displayTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
}

const studentData = {
    admission_no: '<?php echo htmlspecialchars($admission_no); ?>',
    registration_no: '<?php echo htmlspecialchars($registration_no); ?>',
    name: '<?php echo htmlspecialchars($name); ?>',
    gender: '<?php echo htmlspecialchars($gender); ?>',
    dob: '<?php echo $dob ? date("d M Y", strtotime($dob)) : "-"; ?>',
    mobile_no: '<?php echo htmlspecialchars($mobile_no); ?>',
    cnic: '<?php echo htmlspecialchars($cnic); ?>',
    email: '<?php echo htmlspecialchars($student_email); ?>',
    address: '<?php echo htmlspecialchars($address); ?>',
    institute: '<?php echo htmlspecialchars($institute); ?>',
    field_education: '<?php echo htmlspecialchars($field_education); ?>',
    education_status: '<?php echo htmlspecialchars($education_status); ?>',
    education_label: '<?php echo htmlspecialchars($education_label); ?>',
    education_value: '<?php echo htmlspecialchars($education_value); ?>',
    current_grade: '<?php echo htmlspecialchars($current_grade); ?>',
    course_title: '<?php echo htmlspecialchars($course_title); ?>',
    category: '<?php echo htmlspecialchars($category); ?>',
    duration: '<?php echo htmlspecialchars($duration); ?>',
    session_name: '<?php echo htmlspecialchars($session_name); ?>',
    start_time: '<?php echo $start_time; ?>',
    end_time: '<?php echo $end_time; ?>',
    slot: '<?php echo htmlspecialchars($slot); ?>',
    registration_date: '<?php echo date("d M Y", strtotime($registration_date)); ?>',
    student_status: '<?php echo htmlspecialchars($student_status); ?>',
    fee_status: '<?php echo htmlspecialchars($fee_status); ?>',
    guardian_name: '<?php echo htmlspecialchars($guardian_name); ?>',
    guardian_relation: '<?php echo htmlspecialchars($guardian_relation); ?>',
    guardian_phone: '<?php echo htmlspecialchars($guardian_phone); ?>',
    total_present: <?php echo $total_present; ?>,
    total_leave: <?php echo $total_leave; ?>,
    total_absent: <?php echo $total_absent; ?>,
    total_halfday: <?php echo $total_halfday; ?>,
    total_holiday: <?php echo $total_holiday; ?>,
    attendance_percentage: <?php echo $attendance_percentage; ?>,
    total_days: <?php echo $total_days; ?>
};

const feeRecords = <?php echo json_encode($fee_records); ?>;
const totalAmount = <?php echo $total_amount; ?>;
const totalPaid = <?php echo $total_paid; ?>;
const totalBalance = <?php echo $total_balance; ?>;

// Tab functionality
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
});

// Print modal functions
function openPrintModal(title, content) {
    document.getElementById('printModalTitle').textContent = title;
    document.getElementById('printArea').innerHTML = content;
    document.getElementById('printModal').style.display = 'block';
}

function closePrintModal() {
    document.getElementById('printModal').style.display = 'none';
}

// Print Individual Fee Receipt
function printIndividualFee(fee) {
    const currentDate = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    const dueDate = fee.due_date ? new Date(fee.due_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
    const paidDate = fee.created_at ? new Date(fee.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
    
    let finalsAmount = parseFloat(fee.amount);
    if (includeRegistrationFee) {
        finalsAmount += 5000;
    }
    
    const content = `
        <div class="print-doc">
            <div class="print-header">
                <img src="images/logo.png" alt="Logo" class="print-logo" onerror="this.style.display='none'">
                <div class="print-title">FILENOD ACADEMY</div>
                <div class="print-subtitle">Fee Receipt</div>
                <div class="print-subtitle">Opposite UBL Mandian, Abbottabad | info@filenod.com</div>
            </div>
            
            <div class="print-section">
                <div class="print-section-title">Student Information</div>
                <div class="print-info-grid">
                    <div class="print-info-row">
                        <span class="print-info-label">Student Name:</span>
                        <span class="print-info-value">${studentData.name}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Admission No:</span>
                        <span class="print-info-value">${studentData.admission_no}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Registration No:</span>
                        <span class="print-info-value">${studentData.registration_no}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">CNIC:</span>
                        <span class="print-info-value">${studentData.cnic || '-'}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Email:</span>
                        <span class="print-info-value">${studentData.email || '-'}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Course:</span>
                        <span class="print-info-value">${studentData.course_title}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Category:</span>
                        <span class="print-info-value">${studentData.category || '-'}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Current Grade:</span>
                        <span class="print-info-value">${studentData.education_value || '-'}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Session:</span>
                        <span class="print-info-value">${studentData.session_name}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Joining Date:</span>
                        <span class="print-info-value">${studentData.registration_date}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Guardian Name:</span>
                        <span class="print-info-value">${studentData.guardian_name || '-'}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Relation:</span>
                        <span class="print-info-value">${studentData.guardian_relation || '-'}</span>
                    </div>
                </div>
            </div>
            
            <div class="print-section">
                <div class="print-section-title">Fee Details</div>
                <table class="print-table">
                    <tr>
                        <th style="width: 30%;">Field</th>
                        <th>Details</th>
                    </tr>
                    <tr>
                        <td>Fee Type</td>
                        <td>${fee.type}</td>
                    </tr>
                    <tr>
                        <td>Fee Month</td>
                        <td>${fee.fee_month || '-'}</td>
                    </tr>
                    <tr>
                        <td>${includeRegistrationFee ? 'Course Fee' : 'Amount'}</td>
                        <td>Rs. ${parseFloat(fee.amount).toFixed(2)}</td>
                    </tr>
                    ${includeRegistrationFee ? `
                    <tr>
                        <td>Registration Fee</td>
                        <td>Rs. 5000.00</td>
                    </tr>
                    <tr>
                        <td><strong>Total Payable</strong></td>
                        <td><strong>Rs. ${finalsAmount.toFixed(2)}</strong></td>
                    </tr>
                    ` : ''}
                    <tr>
                        <td>Due Date</td>
                        <td>${dueDate}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><strong>${fee.status}</strong></td>
                    </tr>
                    ${fee.status === 'Paid' ? `
                    <tr>
                        <td>Paid Date</td>
                        <td>${paidDate}</td>
                    </tr>
                    <tr>
                        <td>Received By</td>
                        <td>${fee.ref_name || '-'}</td>
                    </tr>
                    ` : ''}
                </table>
            </div>
            
            <div class="print-footer">
                <p>Receipt generated on: ${currentDate}</p>
                <p>This is a computer-generated receipt. For queries, contact the administration.</p>
            </div>
        </div>
    `;
    
    openPrintModal('Fee Receipt', content);
}

// Print All Fees
function printAllFees() {
    const currentDate = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    
    let feeRows = '';
    
    if (includeRegistrationFee) {
        feeRows += `
            <tr>
                <td>-</td>
                <td>Registration Fee</td>
                <td>-</td>
                <td>Rs. 5000.00</td>
                <td>-</td>
                <td>Unpaid</td>
                <td>-</td>
            </tr>
        `;
    }

    feeRecords.forEach((fee, index) => {
        const dueDate = fee.due_date ? new Date(fee.due_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
        feeRows += `
            <tr>
                <td>${index + 1}</td>
                <td>${fee.type}</td>
                <td>${fee.fee_month || '-'}</td>
                <td>Rs. ${parseFloat(fee.amount).toFixed(2)}</td>
                <td>${dueDate}</td>
                <td>${fee.status}</td>
                <td>${fee.ref_name || '-'}</td>
            </tr>
        `;
    });
    
    const content = `
        <div class="print-doc">
            <div class="print-header">
                <img src="images/logo.png" alt="Logo" class="print-logo" onerror="this.style.display='none'">
                <div class="print-title">FILENOD ACADEMY</div>
                <div class="print-subtitle">Complete Fee Record</div>
                <div class="print-subtitle">Opposite UBL Mandian, Abbottabad | info@filenod.com</div>
            </div>
            
            <div class="print-section">
                <div class="print-section-title">Student Information</div>
                <div class="print-info-grid">
                    <div class="print-info-row">
                        <span class="print-info-label">Student Name:</span>
                        <span class="print-info-value">${studentData.name}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Admission No:</span>
                        <span class="print-info-value">${studentData.admission_no}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Course:</span>
                        <span class="print-info-value">${studentData.course_title}</span>
                    </div>
                    <div class="print-info-row">
                        <span class="print-info-label">Session:</span>
                        <span class="print-info-value">${studentData.session_name}</span>
                    </div>
                </div>
            </div>
            
            <div class="print-section">
                <div class="print-section-title">Fee Records</div>
                <table class="print-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Month</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Paid By</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${feeRows}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Totals:</strong></td>
                            <td><strong>Rs. ${(totalAmount + (includeRegistrationFee ? 5000 : 0)).toFixed(2)}</strong></td>

                            <td></td>
                            <td><strong>Paid: Rs. ${totalPaid.toFixed(2)}</strong></td>
                            <td><strong>Balance: Rs. ${totalBalance.toFixed(2)}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="print-footer">
                <p>Report generated on: ${currentDate}</p>
                <p>This is a computer-generated document. For queries, contact the administration.</p>
            </div>
        </div>
    `;
    
    openPrintModal('All Fee Records', content);
}

// Print Full Student Profile
function printFullProfile() {
    const currentDate = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
    
    let feeRows = '';
    
    if (includeRegistrationFee) {
        feeRows += `
            <tr>
                <td style="border: 1px solid #ddd;">-</td>
                <td style="border: 1px solid #ddd;">Registration Fee</td>
                <td style="border: 1px solid #ddd;">Rs. 5000.00</td>
                <td style="border: 1px solid #ddd;">Paid</td>
            </tr>
        `;
    }

    feeRecords.forEach((fee, index) => {
        feeRows += `
            <tr>
                <td style="border: 1px solid #ddd;">${index + 1}</td>
                <td style="border: 1px solid #ddd;">${fee.type}</td>
                <td style="border: 1px solid #ddd;">Rs. ${parseFloat(fee.amount).toFixed(2)}</td>
                <td style="border: 1px solid #ddd;">${fee.status}</td>
            </tr>
        `;
    });
    
    const content = `
        <div class="print-doc" style="font-size: 10px;">
            <div class="print-header" style="padding-bottom: 8px; margin-bottom: 12px;">
                <img src="images/logo.png" alt="Logo" class="print-logo" style="width: 50px;" onerror="this.style.display='none'">
                <div class="print-title" style="font-size: 14px;">FILENOD ACADEMY - STUDENT PROFILE</div>
                <div class="print-subtitle" style="font-size: 9px;">Opposite UBL Mandian, Abbottabad | info@filenod.com | Generated: ${currentDate}</div>
            </div>
            
            <table class="print-table" style="margin-bottom: 12px; width: 100%;">
                <tr>
                    <th colspan="4" style="background: #f8f9fa; color: #000; text-align: center; border: 1px solid #ddd; padding: 8px;"><strong>PERSONAL INFORMATION</strong></th>
                </tr>
                <tr>
                    <td style="width: 15%; border: 1px solid #ddd;"><strong>Name</strong></td>
                    <td style="width: 35%; border: 1px solid #ddd;">${studentData.name}</td>
                    <td style="width: 15%; border: 1px solid #ddd;"><strong>Admission No</strong></td>
                    <td style="width: 35%; border: 1px solid #ddd;">${studentData.admission_no}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><strong>Gender</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.gender || '-'}</td>
                    <td style="border: 1px solid #ddd;"><strong>Registration No</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.registration_no}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><strong>Date of Birth</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.dob}</td>
                    <td style="border: 1px solid #ddd;"><strong>CNIC</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.cnic || '-'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><strong>Mobile</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.mobile_no || '-'}</td>
                    <td style="border: 1px solid #ddd;"><strong>Email</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.email || '-'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><strong>Address</strong></td>
                    <td colspan="3" style="border: 1px solid #ddd;">${studentData.address || '-'}</td>
                </tr>
            </table>
            
            <table class="print-table" style="margin-bottom: 12px; width: 100%;">
                <tr>
                    <th colspan="4" style="background: #f8f9fa; color: #000; text-align: center; border: 1px solid #ddd; padding: 8px;"><strong>EDUCATION & COURSE INFORMATION</strong></th>
                </tr>
                <tr>
                    <td style="width: 15%; border: 1px solid #ddd;"><strong>Institute</strong></td>
                    <td style="width: 35%; border: 1px solid #ddd;">${studentData.institute || '-'}</td>
                    <td style="width: 15%; border: 1px solid #ddd;"><strong>Field</strong></td>
                    <td style="width: 35%; border: 1px solid #ddd;">${studentData.field_education || '-'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><strong>Education Status</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.education_status || '-'}</td>
                    <td style="border: 1px solid #ddd;"><strong>Current Grade</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.education_value || '-'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><strong>Course</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.course_title}</td>
                    <td style="border: 1px solid #ddd;"><strong>Category</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.category || '-'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><strong>Duration</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.duration} Months</td>
                    <td style="border: 1px solid #ddd;"><strong>Session</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.session_name} (${studentData.start_time} - ${studentData.end_time})</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><strong>Slot</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.slot || '-'}</td>
                    <td style="border: 1px solid #ddd;"><strong>Joining Date</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.registration_date}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><strong>Status</strong></td>
                    <td style="border: 1px solid #ddd;">${studentData.student_status}</td>
                </tr>
            </table>
            
            <table class="print-table" style="margin-bottom: 12px; width: 100%;">
                <tr>
                    <th colspan="4" style="background: #f8f9fa; color: #000; text-align: center; border: 1px solid #ddd; padding: 8px;"><strong>GUARDIAN INFORMATION</strong></th>
                </tr>
                <tr>
                    <td style="width: 15%; border: 1px solid #ddd;"><strong>Guardian Name</strong></td>
                    <td style="width: 35%; border: 1px solid #ddd;">${studentData.guardian_name || '-'}</td>
                    <td style="width: 15%; border: 1px solid #ddd;"><strong>Relation</strong></td>
                    <td style="width: 35%; border: 1px solid #ddd;">${studentData.guardian_relation || '-'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd;"><strong>Guardian Phone</strong></td>
                    <td colspan="3" style="border: 1px solid #ddd;">${studentData.guardian_phone || '-'}</td>
                </tr>
            </table>
            
            <table class="print-table" style="margin-bottom: 12px; width: 100%;">
                <tr>
                    <th colspan="5" style="background: #f8f9fa; color: #000; text-align: center; border: 1px solid #ddd; padding: 8px;"><strong>ATTENDANCE SUMMARY (Total Days: ${studentData.total_days})</strong></th>
                </tr>
                <tr style="text-align: center;">
                    <td style="width: 20%; border: 1px solid #ddd;"><strong>Present</strong><br>${studentData.total_present}</td>
                    <td style="width: 20%; border: 1px solid #ddd;"><strong>Late</strong><br>${studentData.total_leave}</td>
                    <td style="width: 20%; border: 1px solid #ddd;"><strong>Absent</strong><br>${studentData.total_absent}</td>
                    <td style="width: 20%; border: 1px solid #ddd;"><strong>Half Day</strong><br>${studentData.total_halfday}</td>
                    <td style="width: 20%; border: 1px solid #ddd;"><strong>Attendance %</strong><br>${studentData.attendance_percentage}%</td>
                </tr>
            </table>
            
            <table class="print-table" style="width: 100%;">
                <tr>
                    <th colspan="5" style="background: #f8f9fa; color: #000; text-align: center; border: 1px solid #ddd; padding: 8px;"><strong>FEE RECORDS</strong></th>
                </tr>
                <tr>
                    <th style="background: #f8f9fa; color: #000; width: 8%;">#</th>
                    <th style="background: #f8f9fa; color: #000; width: 25%;">Type</th>
                    <th style="background: #f8f9fa; color: #000; width: 22%;">Amount</th>
                    <th style="background: #f8f9fa; color: #000; width: 25%;">Status</th>
                </tr>
                ${feeRows || '<tr><td colspan="5" style="text-align: center;">No fee records</td></tr>'}
                <tr style="background: #f5f5f5;">
                    <td colspan="2" style="text-align: right;"><strong>Total:</strong></td>
                    <td style="border: 1px solid #ddd;"><strong>Rs. ${(totalAmount + (includeRegistrationFee ? 5000 : 0)).toFixed(2)}</strong></td>
                    <td style="border: 1px solid #ddd;"><strong>Balance: Rs. ${totalBalance}</strong></td>
                </tr>
            </table>
            
            <div class="print-footer" style="margin-top: 12px; padding-top: 8px;">
                <p style="margin: 0;">© ${new Date().getFullYear()} Filenod Academy. Computer-generated document.</p>
            </div>
        </div>
    `;
    
    openPrintModal('Student Profile', content);
}

// Calendar data from PHP
const attendanceData = <?php echo json_encode($attendance_data); ?>;
let currentMonth = <?php echo date('n'); ?>;
let currentYear = <?php echo date('Y'); ?>;

function renderCalendar() {
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"];
    
    document.getElementById('current-month-year').textContent = 
        monthNames[currentMonth - 1] + ' ' + currentYear;
    
    const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
    
    let calendarHTML = '<div class="calendar-grid">';
    
    // Day headers
    const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    dayHeaders.forEach(day => {
        calendarHTML += `<div class="calendar-day-header">${day}</div>`;
    });
    
    // Empty cells before first day
    for (let i = 0; i < firstDay; i++) {
        calendarHTML += '<div class="calendar-day empty"></div>';
    }
    
    // Days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const attendance = attendanceData[dateStr];
        
        let statusClass = '';
        let statusText = '';
        
        if (attendance) {
            statusText = attendance.status;
            switch (attendance.status) {
                case 'Present':
                    statusClass = 'present';
                    statusText = 'P';
                    if (attendance.note && attendance.note.includes('[Late]')) {
                        statusClass += ' late-present';
                        statusText += '<span class="late-indicator">L</span>';
                    }
                    break;
                case 'Leave':
                    statusClass = 'leave';
                    statusText = 'L';
                    break;
                case 'Absent':
                    statusClass = 'absent';
                    statusText = 'A';
                    break;
                case 'Holiday':
                    statusClass = 'holiday';
                    statusText = 'H';
                    break;
                case 'Half Day':
                    statusClass = 'halfday';
                    statusText = 'F';
                    break;
            }
        }
        
        calendarHTML += `
            <div class="calendar-day ${statusClass}" 
                 title="${attendance ? attendance.status : 'No record'}"
                 onclick="showAttendanceDetails('${dateStr}')">
                <div class="calendar-day-number">${day}</div>
                ${statusText ? `<div class="calendar-day-status">${statusText}</div>` : ''}
            </div>
        `;
    }
    
    calendarHTML += '</div>';
    document.getElementById('attendance-calendar').innerHTML = calendarHTML;
}

function changeMonth(delta) {
    currentMonth += delta;
    
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
    } else if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
    }
    
    // Fetch attendance for new month
    fetchMonthlyAttendance();
}

function fetchMonthlyAttendance() {
    $.ajax({
        url: 'fetch_monthly_attendance.php',
        type: 'POST',
        data: {
            student_id: studentId,
            session_id: sessionId,
            month: currentMonth,
            year: currentYear
        },
        dataType: 'json',
        success: function(data) {
            Object.assign(attendanceData, data);
            renderCalendar();
        }
    });
}

function showAttendanceDetails(dateStr) {
    const attendance = attendanceData[dateStr];
    if (attendance) {
        let details = `Date: ${dateStr}\nStatus: ${attendance.status}`;
        
        if (attendance.late_minutes > 0) {
            details += `\nLate: ${attendance.late_minutes} mins`;
        }
        
        details += `\nEntry: ${attendance.entry_time || 'N/A'}\nExit: ${attendance.exit_time || 'N/A'}\nNote: ${attendance.note || 'N/A'}`;
        
        alert(details);
    }
}


$(document).ready(function () {
    // Auto-load timetable for student on page load
    <?php if (($session->userlevel == 1 || $session->userlevel == 4) && !empty($session_id)): ?>
        $.ajax({
            url: 'fetch_student_timetable.php',
            type: 'POST',
            data: { 
                session_id: sessionId,
                student_id: studentId 
            },
            success: function (data) {
                $("#timetable_result").html(data);
            },
            error: function() {
                $("#timetable_result").html('<div class="alert alert-danger">Error loading timetable.</div>');
            }
        });
    <?php endif; ?>
});


// Initialize calendar
$(document).ready(function() {
    renderCalendar();
});


// Close modal on outside click
document.getElementById('printModal').addEventListener('click', function(e) {
    if (e.target === this) closePrintModal();
});

$(document).on('submit', '#markPaidForm, .markUnpaidForm', function(e) {
    const isPaid = $(this).find('input[name="status"]').val() === 'Paid';
    const message = isPaid ? 'Are you sure you want to mark this fee as PAID?' : 'Are you sure you want to mark this fee as UNPAID? This will remove the payment record.';
    if (!confirm(message)) {
        e.preventDefault();
    }
});

// Education field toggling
function toggleEducationFields() {
    const status = $('#edit_education_status').val();
    $('.edu-field').hide();
    
    if (['Primary', 'Secondary', 'Intermediate'].includes(status)) {
        $('#field_current_grade').show();
    } else if (status === 'Undergraduate') {
        $('#field_current_semester').show();
    } else if (status === 'Graduate') {
        $('#field_graduation_year').show();
    } else if (status === 'Other') {
        $('#field_other_degree').show();
    }
}

// Initialize education fields on modal show
$('#editProfileModal').on('show.bs.modal', function () {
    toggleEducationFields();
});

$('#editProfileForm').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#saveProfileBtn');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
    
    $.ajax({
        url: 'save_student_edit.php',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            btn.prop('disabled', false).html('Save Changes');
            if (response.success) {
                alert('Profile updated successfully');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            btn.prop('disabled', false).html('Save Changes');
            alert('An error occurred while saving the profile');
        }
    });
});
</script>

<?php else: 
    header("Location: index.php");
    exit();
endif; ?>

</body>
</html>