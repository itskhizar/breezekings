<?php
ob_start();
include 'include/classes/session.php';

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}

$username = $session->username;
$result1 = $database->getUserInfo($username);
$ulevel = ($result1['userlevel']);
$display_name = $result1['display_name'];
$email = $result1['email'];
$phone = $result1['phone'];
$image = $result1['parent_directory'];
$password = $result1['password'];
$reg_no = $result1['registration_no'];

$result2 = $database->getstudentbyreg($reg_no);
$student_id = $result2['id'];
$admission_no = ($result2['admission_no']);
$registration_no = ($result2['registration_no']);
$name = ($result2['name']);
$gender = ($result2['gender']);
$dob = ($result2['dob']);
$mobile_no = ($result2['mobile_no']);
$cnic = ($result2['cnic']);
$student_email = ($result2['email']);
$current_grade = ($result2['current_grade']);
$current_semester = ($result2['current_semester']);
$field_education = ($result2['field_education']);
$institute = ($result2['institute']);
$education_status = ($result2['education_status']);
$other_degree = ($result2['other_degree']);
$education_label = "";
$education_value = "";

switch ($education_status) {
    case "Undergraduate":
        $education_label = "Current Semester";
        $education_value = $current_semester;
        break;
    case "Graduate":
        $education_label = "Graduation Year";
        $education_value = $graduation_year ?? '';
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
    default:
        $education_label = "Education Detail";
        $education_value = "-";
}

$address = ($result2['address']);
$category_id = ($result2['category_id']);
$course_id = ($result2['course_id']);
$session_id = ($result2['session_id']);
$slot = ($result2['slot']);
$guardian_name = ($result2['guardian_name']);
$guardian_relation = ($result2['guardian_relation']);
$guardian_phone = ($result2['guardian_phone']);
$registration_date = ($result2['registration_date']);
$months = ($result2['months']);
$fee_status = ($result2['fee_status']);
$student_status = ($result2['status']);

if ($fee_status == "Unpaid") {
    $badge = "<span class='badge badge-danger'>Unpaid</span>";
} elseif ($fee_status == "Paid") {
    $badge = "<span class='badge badge-success'>Paid</span>";
} else {
    $badge = "<span class='badge badge-secondary'>" . htmlspecialchars($fee_status) . "</span>";
}

if ($student_status == "Active") {
    $studentbadge = "<span class='badge badge-success'>Active</span>";
} elseif ($student_status == "Inactive") {
    $studentbadge = "<span class='badge badge-danger'>Inactive</span>";
} else {
    $studentbadge = "<span class='badge badge-secondary'>" . htmlspecialchars($student_status) . "</span>";
}

$result4 = $database->getcoursebyid($course_id);
$course_title = ($result4['course_title']);
$duration = ($result4['duration']);

$result5 = $database->getcategorybyid($category_id);
$category = ($result5['category']);

$result6 = $database->getsessionbyid($session_id);
if ($result6) {
    $session_name = $result6['title'];
    $start_time = $result6['start_time'];
    $end_time = $result6['end_time'];
} else {
    $session_name = "";
    $start_time = "";
    $end_time = "";
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
$course_title = $result4['course_title'] ?? '';
$duration = $result4['duration'] ?? '';

// Get category info
$result5 = $database->getcategorybyid($category_id);
$category = $result5['category'] ?? '';

// Get session info
$result6 = $database->getsessionbyid($session_id);
$session_name = $result6['title'] ?? '';
$start_time = $result6['start_time'] ?? '';
$end_time = $result6['end_time'] ?? '';

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

// Fetch monthly attendance data for calendar
$current_month = date('m');
$current_year = date('Y');

$monthly_attendance_query = "SELECT 
    DATE(created_at) as attendance_date,
    attendance,
    entry_time,
    exit_time,
    note
FROM student_attendance 
WHERE student_id = '$student_id' 
AND session_id = '$session_id'
AND YEAR(created_at) = '$current_year'
ORDER BY created_at ASC";

$monthly_result = mysqli_query($conn, $monthly_attendance_query);
$attendance_data = [];

while ($row = mysqli_fetch_assoc($monthly_result)) {
    $attendance_data[$row['attendance_date']] = [
        'status' => $row['attendance'],
        'entry_time' => $row['entry_time'],
        'exit_time' => $row['exit_time'],
        'note' => $row['note']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - <?php echo htmlspecialchars($name); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="assets/img/favicon-filenod.png" rel="icon">

    <style>
        :root {
            --primary: #1E3A5F;
            --primary-dark: #152C4A;
            --primary-light: #2D5A8A;
            --primary-soft: #E8EEF4;
            --primary-glow: rgba(30, 58, 95, 0.12);

            --accent-blue: #3B82F6;
            --accent-indigo: #6366F1;
            --accent-purple: #8B5CF6;
            --accent-cyan: #06B6D4;
            --accent-teal: #14B8A6;

            --success: #10B981;
            --success-soft: #D1FAE5;
            --warning: #F59E0B;
            --warning-soft: #FEF3C7;
            --danger: #EF4444;
            --danger-soft: #FEE2E2;
            --info: #3B82F6;
            --info-soft: #DBEAFE;

            --white: #FFFFFF;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;

            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;
            --radius-2xl: 24px;
            --radius-full: 9999px;

            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-display: 'Poppins', sans-serif;

            --transition-fast: 150ms ease;
            --transition-base: 250ms ease;
            --transition-smooth: 350ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-sans);
            background: linear-gradient(135deg, #F0F4F8 0%, #E8EEF4 50%, #F5F7FA 100%);
            color: var(--gray-800);
            line-height: 1.6;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: var(--font-display);
            font-weight: 600;
        }

        .dashboard-wrapper {
            margin-left: 0;
        }

        .dashboard-main-wrapper {
            padding-top: 0 !important;
        }

        .fn-page-container {
            padding: 20px;
            max-width: 1400px;
            margin: 20px auto 20px;
        }

        @media (max-width: 768px) {
            .fn-page-container {
                padding: 15px;
                margin-top: 20px;
            }
        }

        /* Profile Header */
        .fn-profile-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: var(--radius-2xl);
            padding: 0;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            position: relative;
        }

        .fn-profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .fn-profile-content {
            display: flex;
            align-items: center;
            gap: 28px;
            padding: 28px 32px;
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .fn-profile-content {
                flex-direction: column;
                text-align: center;
                padding: 24px 20px;
            }
        }

        .fn-avatar-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .fn-avatar {
            width: 110px;
            height: 110px;
            border-radius: var(--radius-xl);
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.25);
            box-shadow: var(--shadow-lg);
        }

        .fn-avatar-badge {
            position: absolute;
            bottom: -6px;
            right: -6px;
            width: 32px;
            height: 32px;
            background: var(--success);
            border: 3px solid var(--primary);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
        }

        .fn-avatar-badge.inactive {
            background: var(--danger);
        }

        .fn-profile-info {
            flex: 1;
            min-width: 280px;
        }

        .fn-profile-name {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin: 0 0 4px 0;
            line-height: 1.2;
        }

        .fn-profile-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 14px;
        }

        .fn-profile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 14px;
        }

        @media (max-width: 768px) {
            .fn-profile-meta {
                justify-content: center;
            }
        }

        .fn-meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
        }

        .fn-meta-chip i {
            font-size: 0.75rem;
            opacity: 0.85;
        }

        .fn-status-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .fn-status-badges {
                justify-content: center;
            }
        }

        .fn-status-badge {
            padding: 5px 14px;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .fn-status-badge.active {
            background: rgba(16, 185, 129, 0.2);
            color: #6EE7B7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .fn-status-badge.inactive {
            background: rgba(239, 68, 68, 0.2);
            color: #FCA5A5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .fn-status-badge.slot {
            background: rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .fn-quick-stats {
            display: flex;
            gap: 14px;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .fn-quick-stats {
                width: 100%;
                justify-content: center;
            }
        }

        .fn-quick-stat {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: var(--radius-lg);
            padding: 16px 24px;
            text-align: center;
            min-width: 100px;
        }

        .fn-quick-stat-value {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            line-height: 1;
        }

        .fn-quick-stat-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* Tabs */
        .fn-tabs-card {
            background: var(--white);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .fn-tabs-nav {
            display: flex;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            overflow-x: auto;
        }

        .fn-tabs-nav::-webkit-scrollbar {
            display: none;
        }

        .fn-tab-btn {
            padding: 16px 28px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-500);
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            transition: var(--transition-base);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .fn-tab-btn:hover {
            color: var(--primary);
            background: rgba(30, 58, 95, 0.04);
        }

        .fn-tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: var(--white);
        }

        .fn-tab-btn i {
            font-size: 1rem;
        }

        .fn-tab-content {
            display: none;
            padding: 28px;
        }

        .fn-tab-content.active {
            display: block;
        }

        @media (max-width: 768px) {
            .fn-tab-content {
                padding: 20px;
            }
        }

        /* Section */
        .fn-section {
            margin-bottom: 28px;
        }

        .fn-section:last-child {
            margin-bottom: 0;
        }

        .fn-section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--gray-100);
        }

        .fn-section-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .fn-section-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }

        /* Info Grid */
        .fn-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0;
        }

        @media (max-width: 768px) {
            .fn-info-grid {
                grid-template-columns: 1fr;
            }
        }

        .fn-info-item {
            display: flex;
            padding: 14px 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .fn-info-item:last-child {
            border-bottom: none;
        }

        .fn-info-label {
            min-width: 150px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray-500);
        }

        .fn-info-value {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--gray-800);
        }

        /* Attendance Stats */
        .fn-att-stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }

        @media (max-width: 992px) {
            .fn-att-stats {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 576px) {
            .fn-att-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .fn-att-stat {
            padding: 18px;
            border-radius: var(--radius-lg);
            text-align: center;
            border: 1px solid;
            transition: var(--transition-base);
        }

        .fn-att-stat:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .fn-att-stat.present {
            background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
            border-color: #6EE7B7;
        }

        .fn-att-stat.late {
            background: linear-gradient(135deg, #FEF3C7, #FDE68A);
            border-color: #FCD34D;
        }

        .fn-att-stat.absent {
            background: linear-gradient(135deg, #FEE2E2, #FECACA);
            border-color: #FCA5A5;
        }

        .fn-cal-day.present.late-present {
            background: linear-gradient(135deg, #10B981 60%, #F59E0B 60%);
        }
        
        .late-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            font-size: 8px;
            font-weight: bold;
            color: #d97706;
            background: rgba(255, 255, 255, 0.9);
            padding: 0 3px;
            border-radius: 3px;
            line-height: 1;
        }

        .fn-att-stat.halfday {
            background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
            border-color: #93C5FD;
        }

        .fn-att-stat.holiday {
            background: linear-gradient(135deg, #E5E7EB, #D1D5DB);
            border-color: #9CA3AF;
        }

        .fn-att-stat-value {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-800);
            line-height: 1;
        }

        .fn-att-stat-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 6px;
        }

        /* Progress Bar */
        .fn-progress-section {
            background: linear-gradient(135deg, var(--primary-soft), #F0F4F8);
            padding: 20px;
            border-radius: var(--radius-lg);
            margin-bottom: 24px;
            border: 1px solid var(--gray-200);
        }

        .fn-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .fn-progress-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-700);
        }

        .fn-progress-value {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
        }

        .fn-progress-bar {
            height: 14px;
            background: var(--gray-200);
            border-radius: var(--radius-full);
            overflow: hidden;
        }

        .fn-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--accent-blue), var(--accent-cyan));
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            transition: width 0.6s ease;
            position: relative;
        }

        .fn-progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* Calendar */
        .fn-calendar-nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 18px;
        }

        .fn-cal-nav-btn {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-base);
        }

        .fn-cal-nav-btn:hover {
            background: var(--primary-light);
            transform: scale(1.05);
        }

        .fn-cal-month {
            font-family: var(--font-display);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .fn-calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            background: var(--white);
            padding: 16px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
        }

        .fn-cal-header {
            text-align: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: white;
            padding: 10px 4px;
            background: var(--primary);
            border-radius: var(--radius-sm);
        }

        .fn-cal-day {
            aspect-ratio: 1;
            border-radius: var(--radius-sm);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-base);
            border: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .fn-cal-day:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .fn-cal-day.empty {
            background: transparent;
            border: none;
            cursor: default;
        }

        .fn-cal-day.empty:hover {
            transform: none;
            box-shadow: none;
        }

        .fn-cal-day.present {
            background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
            border-color: #6EE7B7;
            color: #047857;
        }

        .fn-cal-day.late {
            background: linear-gradient(135deg, #FEF3C7, #FDE68A);
            border-color: #FCD34D;
            color: #B45309;
        }

        .fn-cal-day.absent {
            background: linear-gradient(135deg, #FEE2E2, #FECACA);
            border-color: #FCA5A5;
            color: #B91C1C;
        }

        .fn-cal-day.halfday {
            background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
            border-color: #93C5FD;
            color: #1D4ED8;
        }

        .fn-cal-day.holiday {
            background: linear-gradient(135deg, #E5E7EB, #D1D5DB);
            border-color: #9CA3AF;
            color: #4B5563;
        }

        .fn-cal-day-num {
            font-size: 0.8rem;
            line-height: 1;
        }

        .fn-cal-day-status {
            font-size: 0.6rem;
            font-weight: 700;
            margin-top: 2px;
        }

        /* Legend */
        .fn-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            padding: 14px;
            background: var(--gray-50);
            border-radius: var(--radius-md);
            margin-bottom: 18px;
            border: 1px solid var(--gray-200);
        }

        .fn-legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            color: var(--gray-700);
        }

        .fn-legend-box {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1px solid var(--gray-300);
        }

        .fn-legend-box.present {
            background: #A7F3D0;
            border-color: #6EE7B7;
        }

        .fn-legend-box.late {
            background: #FDE68A;
            border-color: #FCD34D;
        }

        .fn-legend-box.absent {
            background: #FECACA;
            border-color: #FCA5A5;
        }

        .fn-legend-box.halfday {
            background: #BFDBFE;
            border-color: #93C5FD;
        }

        .fn-legend-box.holiday {
            background: #D1D5DB;
            border-color: #9CA3AF;
        }

        /* Table */
        .fn-table-wrapper {
            overflow-x: auto;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
        }

        .fn-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .fn-table th {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .fn-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .fn-table tbody tr {
            transition: var(--transition-base);
        }

        .fn-table tbody tr:hover {
            background: var(--primary-soft);
        }

        .fn-table tbody tr:last-child td {
            border-bottom: none;
        }

        .fn-table tfoot td {
            background: var(--gray-50);
            font-weight: 700;
            color: var(--gray-800);
            border-top: 2px solid var(--gray-300);
        }

        .fn-fee-status {
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
        }

        .fn-fee-status.paid {
            background: var(--success-soft);
            color: #059669;
        }

        .fn-fee-status.unpaid {
            background: var(--danger-soft);
            color: #DC2626;
        }

        /* Buttons */
        .fn-btn {
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition-base);
            text-decoration: none;
        }

        .fn-btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
        }

        .fn-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 58, 95, 0.3);
            color: white;
        }

        .fn-btn-outline {
            background: var(--white);
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .fn-btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .fn-btn-sm {
            padding: 5px 12px;
            font-size: 0.75rem;
        }

        .fn-btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            justify-content: center;
        }

        .fn-fee-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .fn-fee-actions {
            display: flex;
            gap: 6px;
        }

        .fn-ref-text {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .fn-recent-header {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-700);
            margin: 24px 0 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fn-recent-header i {
            color: var(--primary);
        }

        /* Print Modal */
        .fn-print-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            overflow-y: auto;
            padding: 20px;
        }

        .fn-print-modal-content {
            background: white;
            max-width: 800px;
            margin: 20px auto;
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        .fn-print-modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .fn-print-modal-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .fn-print-modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            font-size: 1.25rem;
            color: white;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-base);
        }

        .fn-print-modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .fn-print-modal-body {
            padding: 24px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .fn-print-modal-footer {
            background: var(--gray-50);
            padding: 16px 24px;
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        /* Print Document */
        .print-doc {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .print-header {
            text-align: center;
            border-bottom: 2px solid #1E3A5F;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .print-logo {
            width: 60px;
            height: auto;
            margin-bottom: 8px;
        }

        .print-title {
            font-size: 16px;
            font-weight: bold;
            color: #1E3A5F;
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
            background: #E8EEF4;
            color: #1E3A5F;
            padding: 6px 10px;
            margin-bottom: 8px;
            border-left: 3px solid #1E3A5F;
        }

        .print-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .print-table th,
        .print-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        .print-table th {
            background: #E8EEF4;
            font-weight: bold;
            color: #1E3A5F;
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

        .print-info-value {
            color: #333;
        }

        .print-footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .fn-empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--gray-500);
        }

        .fn-empty-state i {
            font-size: 48px;
            color: var(--gray-300);
            margin-bottom: 16px;
        }

        .fn-empty-state h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--gray-700);
            margin: 0 0 6px 0;
        }

        .fn-empty-state p {
            font-size: 0.9rem;
            margin: 0;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printArea,
            #printArea * {
                visibility: visible;
            }

            #printArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 15mm;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 10mm;
            }
        }
    </style>
</head>

<body>
    <?php if ($session->logged_in): ?>
        <div class="dashboard-main-wrapper">
            <?php include('navbar.php'); ?>

            <div class="dashboard-wrapper">
                <div class="fn-page-container">

                    <!-- Profile Header -->
                    <div class="fn-profile-header">
                        <div class="fn-profile-content">
                            <div class="fn-avatar-wrapper">
                                <img src="<?php echo (!empty($image) && file_exists('images/' . $image)) ? 'images/' . $image : 'images/avatar.png'; ?>"
                                    alt="<?php echo htmlspecialchars($name); ?>" class="fn-avatar">
                                <div class="fn-avatar-badge <?php echo $student_status == 'Active' ? '' : 'inactive'; ?>">
                                    <i class="fas fa-<?php echo $student_status == 'Active' ? 'check' : 'times'; ?>"></i>
                                </div>
                            </div>

                            <div class="fn-profile-info">
                                <h1 class="fn-profile-name"><?php echo htmlspecialchars($name); ?></h1>
                                <p class="fn-profile-subtitle">Admission No: <?php echo htmlspecialchars($admission_no); ?>
                                    | Reg No: <?php echo htmlspecialchars($registration_no); ?></p>

                                <div class="fn-profile-meta">
                                    <span class="fn-meta-chip"><i class="fas fa-book"></i>
                                        <?php echo htmlspecialchars($course_title); ?></span>
                                    <span class="fn-meta-chip"><i class="fas fa-layer-group"></i>
                                        <?php echo htmlspecialchars($category); ?></span>
                                    <span class="fn-meta-chip"><i class="fas fa-clock"></i>
                                        <?php echo htmlspecialchars($session_name); ?> (<?php echo $start_time; ?> -
                                        <?php echo $end_time; ?>)</span>
                                    <span class="fn-meta-chip"><i class="fas fa-calendar"></i> Joined:
                                        <?php echo date("d M Y", strtotime($registration_date)); ?></span>
                                </div>

                                <div class="fn-status-badges">
                                    <span
                                        class="fn-status-badge <?php echo $student_status == 'Active' ? 'active' : 'inactive'; ?>">
                                        <?php echo htmlspecialchars($student_status); ?>
                                    </span>
                                    <?php if ($slot): ?>
                                        <span class="fn-status-badge slot"><i class="fas fa-clock"></i> Slot:
                                            <?php echo htmlspecialchars($slot); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="fn-quick-stats">
                                <div class="fn-quick-stat">
                                    <div class="fn-quick-stat-value"><?php echo $attendance_percentage; ?>%</div>
                                    <div class="fn-quick-stat-label">Attendance</div>
                                </div>
                                <div class="fn-quick-stat">
                                    <div class="fn-quick-stat-value"><?php echo count($fee_records); ?></div>
                                    <div class="fn-quick-stat-label">Fee Months</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs Card -->
                    <div class="fn-tabs-card">
                        <div class="fn-tabs-nav no-print">
                            <button class="fn-tab-btn active" data-tab="profile"><i class="fas fa-user"></i>
                                Profile</button>
                            <button class="fn-tab-btn" data-tab="fees"><i class="fas fa-money-bill-wave"></i> Fees</button>
                            <button class="fn-tab-btn" data-tab="attendance"><i class="fas fa-calendar-check"></i>
                                Attendance</button>
                            <button class="fn-tab-btn" data-tab="timetable"><i class="fas fa-calendar-times"></i>
                                Timetable</button>
                        </div>

                        <!-- Profile Tab -->
                        <style>

                        </style>
                        <div class="fn-tab-content active" id="tab-profile">
                            <div class="fn-section">
                                <div class="fn-section-header">
                                    <div class="fn-section-icon"><i class="fas fa-user"></i></div>
                                    <h3 class="fn-section-title">Personal Information</h3>
                                </div>
                                <div class="fn-info-grid">
                                    <div>
                                        <div class="fn-info-item"><span class="fn-info-label">Full Name</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($name); ?></span></div>
                                        <div class="fn-info-item"><span class="fn-info-label">Gender</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($gender); ?></span></div>
                                        <div class="fn-info-item"><span class="fn-info-label">Date of Birth</span><span
                                                class="fn-info-value"><?php echo $dob ? date("d M Y", strtotime($dob)) : '-'; ?></span>
                                        </div>
                                        <div class="fn-info-item"><span class="fn-info-label">CNIC</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($cnic) ?: '-'; ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fn-info-item"><span class="fn-info-label">Mobile No</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($mobile_no); ?></span>
                                        </div>
                                        <div class="fn-info-item"><span class="fn-info-label">Email</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($student_email) ?: '-'; ?></span>
                                        </div>
                                        <div class="fn-info-item"><span class="fn-info-label">Address</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($address) ?: '-'; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fn-section">
                                <div class="fn-section-header">
                                    <div class="fn-section-icon"><i class="fas fa-graduation-cap"></i></div>
                                    <h3 class="fn-section-title">Education Information</h3>
                                </div>
                                <div class="fn-info-grid">
                                    <div>
                                        <div class="fn-info-item"><span class="fn-info-label">Institute</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($institute) ?: '-'; ?></span>
                                        </div>
                                        <div class="fn-info-item"><span class="fn-info-label">Field of Education</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($field_education) ?: '-'; ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fn-info-item"><span class="fn-info-label">Education Status</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($education_status) ?: '-'; ?></span>
                                        </div>
                                        <div class="fn-info-item"><span
                                                class="fn-info-label"><?php echo htmlspecialchars($education_label); ?></span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($education_value) ?: '-'; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fn-section">
                                <div class="fn-section-header">
                                    <div class="fn-section-icon"><i class="fas fa-book"></i></div>
                                    <h3 class="fn-section-title">Course Information</h3>
                                </div>
                                <div class="fn-info-grid">
                                    <div>
                                        <div class="fn-info-item"><span class="fn-info-label">Course</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($course_title); ?></span>
                                        </div>
                                        <div class="fn-info-item"><span class="fn-info-label">Category</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($category); ?></span>
                                        </div>
                                        <div class="fn-info-item"><span class="fn-info-label">Duration</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($duration); ?>
                                                Months</span></div>
                                    </div>
                                    <div>
                                        <div class="fn-info-item"><span class="fn-info-label">Session</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($session_name); ?></span>
                                        </div>
                                        <div class="fn-info-item"><span class="fn-info-label">Timing</span><span
                                                class="fn-info-value"><?php echo $start_time; ?> -
                                                <?php echo $end_time; ?></span></div>
                                        <div class="fn-info-item"><span class="fn-info-label">Slot</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($slot) ?: '-'; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fn-section">
                                <div class="fn-section-header">
                                    <div class="fn-section-icon"><i class="fas fa-users"></i></div>
                                    <h3 class="fn-section-title">Guardian Information</h3>
                                </div>
                                <div class="fn-info-grid">
                                    <div>
                                        <div class="fn-info-item"><span class="fn-info-label">Guardian Name</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($guardian_name) ?: '-'; ?></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fn-info-item"><span class="fn-info-label">Relation</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($guardian_relation) ?: '-'; ?></span>
                                        </div>
                                        <div class="fn-info-item"><span class="fn-info-label">Phone</span><span
                                                class="fn-info-value"><?php echo htmlspecialchars($guardian_phone) ?: '-'; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fees Tab -->
                        <div class="fn-tab-content" id="tab-fees">
                            <div class="fn-fee-header">
                                <div class="fn-section-header"
                                    style="margin-bottom: 0; padding-bottom: 0; border-bottom: none;">
                                    <div class="fn-section-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    <h3 class="fn-section-title">Fee Records</h3>
                                </div>
                                <!-- <button class="fn-btn fn-btn-outline" onclick="printAllFees()"><i class="fas fa-print"></i> Print All</button> -->
                            </div>

                            <div class="fn-table-wrapper">
                                <table class="fn-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Fee Type</th>
                                            <th>Month</th>
                                            <th>Amount</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Paid By</th>
                                            <!-- <th class="no-print">Actions</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($fee_records)): ?>
                                            <?php foreach ($fee_records as $index => $fee): ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td><?php echo htmlspecialchars($fee['type']); ?></td>
                                                    <td><?php echo htmlspecialchars($fee['fee_month']); ?></td>
                                                    <td><strong>Rs. <?php echo number_format($fee['amount'], 2); ?></strong></td>
                                                    <td><?php echo $fee['due_date'] ? date('d M Y', strtotime($fee['due_date'])) : '-'; ?>
                                                    </td>
                                                    <td><span
                                                            class="fn-fee-status <?php echo $fee['status'] == 'Paid' ? 'paid' : 'unpaid'; ?>"><?php echo $fee['status']; ?></span>
                                                    </td>
                                                    <td><?php echo ($fee['status'] == 'Paid' && $fee['ref_name']) ? '<span class="fn-ref-text">' . htmlspecialchars($fee['ref_name']) . '</span>' : '-'; ?>
                                                    </td>
                                                    <!-- <td class="no-print">
                                            <button class="fn-btn fn-btn-primary fn-btn-sm fn-btn-icon" onclick='printIndividualFee(<?php echo json_encode($fee); ?>)' title="Print"><i class="fas fa-print"></i></button>
                                        </td> -->
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8">
                                                    <div class="fn-empty-state"><i class="fas fa-receipt"></i>
                                                        <h4>No Fee Records</h4>
                                                        <p>No fee records found</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <?php if (!empty($fee_records)): ?>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                                                <td><strong>Rs. <?php echo number_format($total_amount, 2); ?></strong></td>
                                                <td></td>
                                                <td><strong style="color: var(--success);">Paid: Rs.
                                                        <?php echo number_format($total_paid, 2); ?></strong></td>
                                                <td><strong style="color: var(--danger);">Balance: Rs.
                                                        <?php echo number_format($total_balance, 2); ?></strong></td>
                                                <!-- <td class="no-print"></td> -->
                                            </tr>
                                        </tfoot>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>

                        <!-- Attendance Tab -->
                        <div class="fn-tab-content" id="tab-attendance">
                            <div class="fn-section">
                                <div class="fn-section-header">
                                    <div class="fn-section-icon"><i class="fas fa-chart-bar"></i></div>
                                    <h3 class="fn-section-title">Attendance Overview</h3>
                                </div>

                                <div class="fn-att-stats">
                                    <div class="fn-att-stat present">
                                        <div class="fn-att-stat-value"><?php echo $total_present; ?></div>
                                        <div class="fn-att-stat-label">Present</div>
                                    </div>
                                    <div class="fn-att-stat late">
                                        <div class="fn-att-stat-value"><?php echo $total_leave; ?></div>
                                        <div class="fn-att-stat-label">Leave</div>
                                    </div>
                                    <div class="fn-att-stat absent">
                                        <div class="fn-att-stat-value"><?php echo $total_absent; ?></div>
                                        <div class="fn-att-stat-label">Absent</div>
                                    </div>
                                    <div class="fn-att-stat halfday">
                                        <div class="fn-att-stat-value"><?php echo $total_halfday; ?></div>
                                        <div class="fn-att-stat-label">Half Day</div>
                                    </div>
                                    <div class="fn-att-stat holiday">
                                        <div class="fn-att-stat-value"><?php echo $total_holiday; ?></div>
                                        <div class="fn-att-stat-label">Holiday</div>
                                    </div>
                                </div>

                                <div class="fn-progress-section">
                                    <div class="fn-progress-header">
                                        <span class="fn-progress-label">Overall Attendance</span>
                                        <span class="fn-progress-value"><?php echo $attendance_percentage; ?>%</span>
                                    </div>
                                    <div class="fn-progress-bar">
                                        <div class="fn-progress-fill"
                                            style="width: <?php echo $attendance_percentage; ?>%;">
                                            <?php echo $attendance_percentage; ?>%</div>
                                    </div>
                                </div>
                            </div>

                            <div class="fn-section">
                                <div class="fn-section-header">
                                    <div class="fn-section-icon"><i class="fas fa-calendar-alt"></i></div>
                                    <h3 class="fn-section-title">Monthly Attendance Calendar</h3>
                                </div>

                                <div class="fn-legend">
                                    <div class="fn-legend-item">
                                        <div class="fn-legend-box present"></div><span>Present</span>
                                    </div>
                                    <div class="fn-legend-item">
                                        <div class="fn-legend-box late"></div><span>Leave</span>
                                    </div>
                                    <div class="fn-legend-item">
                                        <div class="fn-legend-box absent"></div><span>Absent</span>
                                    </div>
                                    <div class="fn-legend-item">
                                        <div class="fn-legend-box halfday"></div><span>Half Day</span>
                                    </div>
                                    <div class="fn-legend-item">
                                        <div class="fn-legend-box holiday"></div><span>Holiday</span>
                                    </div>
                                </div>

                                <div class="fn-calendar-nav">
                                    <button class="fn-cal-nav-btn" onclick="changeMonth(-1)"><i
                                            class="fas fa-chevron-left"></i></button>
                                    <span class="fn-cal-month" id="current-month-year"><?php echo date('F Y'); ?></span>
                                    <button class="fn-cal-nav-btn" onclick="changeMonth(1)"><i
                                            class="fas fa-chevron-right"></i></button>
                                </div>

                                <div id="attendance-calendar"></div>
                            </div>

                            <div class="fn-section">
                                <h4 class="fn-recent-header"><i class="fas fa-history"></i> Recent Attendance (Last 10 Days)
                                </h4>
                                <div class="fn-table-wrapper">
                                    <table class="fn-table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Late Time</th>
                                                <th>Entry Time</th>
                                                <th>Exit Time</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $recent_query = "SELECT DATE(sa.created_at) as attendance_date, sa.attendance, sa.entry_time, sa.exit_time, sa.note, s.start_time
                                            FROM student_attendance sa
                                            JOIN sessions s ON sa.session_id = s.id 
                                            WHERE sa.student_id = '$student_id' AND sa.session_id = '$session_id' 
                                            ORDER BY sa.created_at DESC LIMIT 10";
                                            $recent_result = mysqli_query($conn, $recent_query);
                                            if (mysqli_num_rows($recent_result) > 0):
                                                while ($att = mysqli_fetch_assoc($recent_result)):
                                                    ?>
                                                    <tr>
                                                        <td><?php echo date('d M Y', strtotime($att['attendance_date'])); ?></td>
                                                        <td>
                                                            <span
                                                                class="fn-fee-status <?php echo $att['attendance'] == 'Present' ? 'paid' : ($att['attendance'] == 'Absent' ? 'unpaid' : ''); ?>"
                                                                <?php if ($att['attendance'] == 'Leave')
                                                                    echo 'style="background: #FEF3C7; color: #B45309;"'; ?>             <?php if ($att['attendance'] == 'Half Day')
                                                                                       echo 'style="background: #DBEAFE; color: #1D4ED8;"'; ?>             <?php if ($att['attendance'] == 'Holiday')
                                                                                                          echo 'style="background: #E5E7EB; color: #4B5563;"'; ?>>
                                                                <?php echo $att['attendance']; ?>
                                                            </span>
                                                            </span>
                                                        </td>
                                                        <td>
                                                        <?php 
                                                            $late_display = '-';
                                                            if ($att['attendance'] == 'Present' && $att['entry_time'] && $att['start_time']) {
                                                                $entry = strtotime($att['entry_time']);
                                                                $start = strtotime($att['start_time']);
                                                                if ($entry > $start) {
                                                                    $diff = round(($entry - $start) / 60);
                                                                    if ($diff > 0) {
                                                                        $late_display = "<span style='color: #DC2626; font-weight: bold;'>{$diff} mins</span>";
                                                                    }
                                                                }
                                                            }
                                                            echo $late_display;
                                                        ?>
                                                        </td>
                                                        <td><?php echo $att['entry_time'] ? date('h:i A', strtotime($att['entry_time'])) : '-'; ?>
                                                        </td>
                                                        <td><?php echo $att['exit_time'] ? date('h:i A', strtotime($att['exit_time'])) : '-'; ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($att['note']) ?: '-'; ?></td>
                                                    </tr>
                                                <?php endwhile; else: ?>
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="fn-empty-state"><i class="fas fa-calendar-times"></i>
                                                            <h4>No Records</h4>
                                                            <p>No attendance records found</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <!-- tab timetable  -->
                        <div class="fn-tab-content" id="tab-timetable">
                            <div class="fn-section">
                                <div class="fn-section-header">
                                    <div class="fn-section-icon"><i class="fas fa-calendar-times"></i></div>
                                    <h3 class="fn-section-title">Timetable</h3>
                                </div>


                            </div>

                            <div class="fn-section">
                                <div id="timetable_result"></div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Modal -->
        <div class="fn-print-modal" id="printModal">
            <div class="fn-print-modal-content">
                <div class="fn-print-modal-header no-print">
                    <h3 class="fn-print-modal-title" id="printModalTitle">Print Preview</h3>
                    <button class="fn-print-modal-close" onclick="closePrintModal()">&times;</button>
                </div>
                <div class="fn-print-modal-body" id="printArea"></div>
                <div class="fn-print-modal-footer no-print">
                    <button class="fn-btn fn-btn-primary" onclick="window.print()"><i class="fas fa-print"></i>
                        Print</button>
                    <button class="fn-btn fn-btn-outline" onclick="closePrintModal()">Close</button>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            const studentData = {
                admission_no: '<?php echo htmlspecialchars($admission_no); ?>',
                registration_no: '<?php echo htmlspecialchars($registration_no); ?>',
                name: '<?php echo htmlspecialchars($name); ?>',
                course_title: '<?php echo htmlspecialchars($course_title); ?>',
                session_name: '<?php echo htmlspecialchars($session_name); ?>'
            };

            const feeRecords = <?php echo json_encode($fee_records); ?>;
            const totalAmount = <?php echo $total_amount; ?>;
            const totalPaid = <?php echo $total_paid; ?>;
            const totalBalance = <?php echo $total_balance; ?>;

            // Tabs
            document.querySelectorAll('.fn-tab-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.fn-tab-btn').forEach(b => b.classList.remove('active'));
                    document.querySelectorAll('.fn-tab-content').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('tab-' + this.dataset.tab).classList.add('active');
                });
            });

            // Print Modal
            function openPrintModal(title, content) {
                document.getElementById('printModalTitle').textContent = title;
                document.getElementById('printArea').innerHTML = content;
                document.getElementById('printModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function closePrintModal() {
                document.getElementById('printModal').style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            function printIndividualFee(fee) {
                const currentDate = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                const dueDate = fee.due_date ? new Date(fee.due_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
                const paidDate = fee.created_at ? new Date(fee.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';

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
                    <div class="print-info-row"><span class="print-info-label">Student Name:</span><span class="print-info-value">${studentData.name}</span></div>
                    <div class="print-info-row"><span class="print-info-label">Admission No:</span><span class="print-info-value">${studentData.admission_no}</span></div>
                    <div class="print-info-row"><span class="print-info-label">Course:</span><span class="print-info-value">${studentData.course_title}</span></div>
                    <div class="print-info-row"><span class="print-info-label">Session:</span><span class="print-info-value">${studentData.session_name}</span></div>
                </div>
            </div>
            <div class="print-section">
                <div class="print-section-title">Fee Details</div>
                <table class="print-table">
                    <tr><th style="width: 30%;">Field</th><th>Details</th></tr>
                    <tr><td>Fee Type</td><td>${fee.type}</td></tr>
                    <tr><td>Fee Month</td><td>${fee.fee_month || '-'}</td></tr>
                    <tr><td>Amount</td><td><strong>Rs. ${parseFloat(fee.amount).toFixed(2)}</strong></td></tr>
                    <tr><td>Due Date</td><td>${dueDate}</td></tr>
                    <tr><td>Status</td><td><strong style="color: ${fee.status === 'Paid' ? '#059669' : '#DC2626'};">${fee.status}</strong></td></tr>
                    ${fee.status === 'Paid' ? `<tr><td>Paid Date</td><td>${paidDate}</td></tr><tr><td>Received By</td><td>${fee.ref_name || '-'}</td></tr>` : ''}
                </table>
            </div>
            <div class="print-footer"><p>Receipt generated on: ${currentDate}</p><p>Computer-generated receipt. For queries, contact the administration.</p></div>
        </div>
    `;
                openPrintModal('Fee Receipt', content);
            }

            function printAllFees() {
                const currentDate = new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                let feeRows = '';
                feeRecords.forEach((fee, index) => {
                    const dueDate = fee.due_date ? new Date(fee.due_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
                    feeRows += `<tr><td>${index + 1}</td><td>${fee.type}</td><td>${fee.fee_month || '-'}</td><td>Rs. ${parseFloat(fee.amount).toFixed(2)}</td><td>${dueDate}</td><td style="color: ${fee.status === 'Paid' ? '#059669' : '#DC2626'};">${fee.status}</td><td>${fee.ref_name || '-'}</td></tr>`;
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
                    <div class="print-info-row"><span class="print-info-label">Student Name:</span><span class="print-info-value">${studentData.name}</span></div>
                    <div class="print-info-row"><span class="print-info-label">Admission No:</span><span class="print-info-value">${studentData.admission_no}</span></div>
                    <div class="print-info-row"><span class="print-info-label">Course:</span><span class="print-info-value">${studentData.course_title}</span></div>
                    <div class="print-info-row"><span class="print-info-label">Session:</span><span class="print-info-value">${studentData.session_name}</span></div>
                </div>
            </div>
            <div class="print-section">
                <div class="print-section-title">Fee Records</div>
                <table class="print-table">
                    <thead><tr><th>#</th><th>Type</th><th>Month</th><th>Amount</th><th>Due Date</th><th>Status</th><th>Paid By</th></tr></thead>
                    <tbody>${feeRows}</tbody>
                    <tfoot><tr><td colspan="3" style="text-align: right;"><strong>Totals:</strong></td><td><strong>Rs. ${totalAmount.toFixed(2)}</strong></td><td></td><td><strong style="color: #059669;">Paid: Rs. ${totalPaid.toFixed(2)}</strong></td><td><strong style="color: #DC2626;">Balance: Rs. ${totalBalance.toFixed(2)}</strong></td></tr></tfoot>
                </table>
            </div>
            <div class="print-footer"><p>Report generated on: ${currentDate}</p><p>Computer-generated document. For queries, contact the administration.</p></div>
        </div>
    `;
                openPrintModal('All Fee Records', content);
            }

            // Calendar
            const attendanceData = <?php echo json_encode($attendance_data); ?>;
            let currentMonth = <?php echo date('n'); ?>;
            let currentYear = <?php echo date('Y'); ?>;
            const studentId = '<?php echo $student_id; ?>';
            const sessionId = '<?php echo $session_id; ?>';

            function renderCalendar() {
                const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                document.getElementById('current-month-year').textContent = monthNames[currentMonth - 1] + ' ' + currentYear;

                const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
                const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();

                let calendarHTML = '<div class="fn-calendar-grid">';
                const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                dayHeaders.forEach(day => { calendarHTML += `<div class="fn-cal-header">${day}</div>`; });

                for (let i = 0; i < firstDay; i++) { calendarHTML += '<div class="fn-cal-day empty"></div>'; }

                for (let day = 1; day <= daysInMonth; day++) {
                    const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const attendance = attendanceData[dateStr];
                    let statusClass = '', statusText = '';

                    if (attendance) {
                        switch (attendance.status) {
                            case 'Present': 
                                statusClass = 'present'; 
                                statusText = 'P'; 
                                if (attendance.note && attendance.note.includes('[Late]')) {
                                    statusClass += ' late-present';
                                    statusText += '<span class="late-indicator">L</span>';
                                }
                                break;
                            case 'Leave': statusClass = 'late'; statusText = 'L'; break;
                            case 'Absent': statusClass = 'absent'; statusText = 'A'; break;
                            case 'Holiday': statusClass = 'holiday'; statusText = 'H'; break;
                            case 'Half Day': statusClass = 'halfday'; statusText = 'HD'; break;
                        }
                    }

                    calendarHTML += `
            <div class="fn-cal-day ${statusClass}" title="${attendance ? attendance.status : 'No record'}" onclick="showAttendanceDetails('${dateStr}')">
                <div class="fn-cal-day-num">${day}</div>
                ${statusText ? `<div class="fn-cal-day-status">${statusText}</div>` : ''}
            </div>
        `;
                }

                calendarHTML += '</div>';
                document.getElementById('attendance-calendar').innerHTML = calendarHTML;
            }

            function changeMonth(delta) {
                currentMonth += delta;
                if (currentMonth > 12) { currentMonth = 1; currentYear++; }
                else if (currentMonth < 1) { currentMonth = 12; currentYear--; }
                fetchMonthlyAttendance();
            }

            function fetchMonthlyAttendance() {
                $.ajax({
                    url: 'fetch_monthly_attendance.php',
                    type: 'POST',
                    data: { student_id: studentId, session_id: sessionId, month: currentMonth, year: currentYear },
                    dataType: 'json',
                    success: function (data) { Object.assign(attendanceData, data); renderCalendar(); },
                    error: function () { renderCalendar(); }
                });
            }

            function showAttendanceDetails(dateStr) {
                const attendance = attendanceData[dateStr];
                if (attendance) {
                    alert(`Date: ${dateStr}\nStatus: ${attendance.status}\nEntry: ${attendance.entry_time || 'N/A'}\nExit: ${attendance.exit_time || 'N/A'}\nNote: ${attendance.note || 'N/A'}`);
                }
            }

            $(document).ready(function () { renderCalendar(); });

            document.getElementById('printModal').addEventListener('click', function (e) { if (e.target === this) closePrintModal(); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePrintModal(); });


            $(document).ready(function () {
                // Auto-load timetable for student on page load
                <?php if ($session->userlevel == 0 && !empty($session_id)) { ?>
                    var session_id = <?php echo $session_id; ?>;

                    if (session_id > 0) {
                        // Load timetable immediately
                        $.ajax({
                            url: 'fetch_student_timetable.php',
                            type: 'POST',
                            data: { session_id: session_id },
                            success: function (data) {
                                $("#timetable_result").html(data);
                            },
                            error: function () {
                                $("#timetable_result").html('<div class="alert alert-danger">Error loading timetable.</div>');
                            }
                        });
                    } else {
                        $("#timetable_result").html('<div class="alert alert-info">No active session found for your enrollment.</div>');
                    }
                <?php } ?>

                // Manual session change (for admin view)
                $('#session_id').change(function () {
                    var session_id = $(this).val();

                    $.ajax({
                        url: 'fetch_student_timetable.php',
                        type: 'POST',
                        data: { session_id: session_id },
                        success: function (data) {
                            $("#timetable_result").html(data);
                        }
                    });
                });
            });
        </script>

    <?php else:
        header("Location: index.php");
        exit(); endif; ?>
</body>

</html>