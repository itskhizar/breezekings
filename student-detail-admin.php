<?php
include("include/classes/session.php");

// Check if user is logged in
if ($session->logged_in) {
    $username = $session->username;
    $result = $database->getUserInfo($username);
    $userlevel = ($result['userlevel']);
    $display_name = $result['display_name'];
    $email = $result['email'];
    $registration_no = $result['registration_no'];

    // Admin authentication
    if ($session->userlevel == 1 OR $session->userlevel == 4) {
        // Admin is authorized
    } else {
        header('location: index.php');
        exit();
    }
} else {
    header('location: index.php');
    exit();
}

// Get student ID and task ID from URL
if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    header('location: numberofstudents.php');
    exit();
}

if (!isset($_GET['task_id']) || empty($_GET['task_id'])) {
    header('location: task-overview-admin.php?student_id=' . $_GET['student_id']);
    exit();
}

$student_id = mysqli_real_escape_string($database->connection, $_GET['student_id']);
$task_id = mysqli_real_escape_string($database->connection, $_GET['task_id']);

// Fetch student details
$student_query = "SELECT * FROM students WHERE id = '$student_id' LIMIT 1";
$student_result = mysqli_query($database->connection, $student_query);

if (mysqli_num_rows($student_result) == 0) {
    header('location: numberofstudents.php');
    exit();
}

$student = mysqli_fetch_assoc($student_result);
$course_id = $student['course_id'];

// Fetch course name
$course_query = "SELECT * FROM courses WHERE id = '{$student['course_id']}' LIMIT 1";
$course_result = mysqli_query($database->connection, $course_query);
$course = mysqli_fetch_assoc($course_result);
$student_course_name = $course ? $course['course_title'] : 'N/A';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details - <?php echo htmlspecialchars($student['name']); ?> - Filenod Academy</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts - Inter for premium look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="assetss/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assetss/libs/css/style.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/fontawesome/css/fontawesome-all.css">

    <!-- Favicon -->
    <link href="assets/img/favicon-filenod.png" rel="icon">

    <style>
        /* =============================================
           CSS CUSTOM PROPERTIES - PREMIUM DESIGN SYSTEM
        ============================================= */
        :root {
            /* Filenod Brand Colors */
            --primary: #173663;
            --primary-hover: #0f2444;
            --primary-light: #2d4a73;
            --primary-50: rgba(23, 54, 99, 0.05);
            --primary-100: rgba(23, 54, 99, 0.1);

            /* Accent Colors */
            --accent-lavender: #A4AADB;
            --accent-blue: #5B7FBF;

            /* Semantic Colors */
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --info-light: #dbeafe;

            /* Neutrals */
            --white: #ffffff;
            --gray-25: #fcfcfd;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;

            /* Shadows */
            --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);

            /* Border Radius */
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;
            --radius-full: 9999px;

            /* Typography */
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

            /* Transitions */
            --transition-base: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* =============================================
           BASE STYLES & RESETS
        ============================================= */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-sans);
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
            color: var(--gray-900);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            padding-top: 0 !important;
        }

        .dashboard-wrapper {
            padding: 0 30px 30px 30px !important;
            transition: var(--transition-smooth);
        }

        .dashboard-main-wrapper {
            padding-top: 0 !important;
        }

        /* Premium Status Badges & Graded UI */
        .graded-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, var(--success), #10b981);
            color: white;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeInScale 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .graded-badge i {
            font-size: 16px;
        }

        .badge-grading-in-progress {
            background: linear-gradient(135deg, var(--warning), #f59e0b) !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        /* =============================================
           STUDENT INFO BAR - FULL WIDTH
        ============================================= */
        .student-info-bar {
            margin: 80px auto 12px auto;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            max-width: 1200px;
            width: 100%;
        }

        .student-info-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .student-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .student-avatar-placeholder {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .student-info-text h3 {
            color: white;
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 4px 0;
        }

        .student-info-text p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .student-info-text p span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .student-info-text p i {
            font-size: 12px;
        }

        .btn-back-to-tasks {
            padding: 10px 22px;
            background: rgba(255, 255, 255, 0.2);
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-md);
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition-base);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
        }

        .btn-back-to-tasks:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-2px);
        }

        /* =============================================
           TASK DETAILS PANEL - FULL WIDTH
        ============================================= */
        .task-details-container {
            margin: 0 auto 16px auto;
            max-width: 1200px;
            width: 100%;
        }

        .task-details-panel {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        /* Task Details Header */
        .task-details-header {
            padding: 16px 24px;
            border-bottom: 1px solid var(--gray-200);
            background: linear-gradient(to bottom, var(--white), #f9fafb);
        }

        .header-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            gap: 16px;
        }

        .task-name {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            margin: 0;
            line-height: 1.3;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            border: 1.5px solid var(--gray-300);
            background: var(--white);
            color: var(--gray-600);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
        }

        .btn-icon:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
            transform: rotate(180deg);
        }

        .header-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-size: 12px;
            color: var(--gray-600);
            font-weight: 500;
            transition: var(--transition-base);
        }

        .meta-chip:hover {
            border-color: var(--accent-blue);
            background: var(--gray-50);
        }

        .meta-chip i {
            color: var(--accent-blue);
            font-size: 12px;
        }

        /* Progress Section */
        .task-progress-section {
            background: linear-gradient(135deg, rgba(164, 170, 219, 0.1), rgba(91, 127, 191, 0.08));
            padding: 16px 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .progress-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
        }

        .progress-percentage {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
        }

        .progress-bar-modern {
            height: 12px;
            background: var(--white);
            border-radius: var(--radius-full);
            overflow: hidden;
            position: relative;
            margin-bottom: 18px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--accent-blue), var(--accent-lavender));
            border-radius: var(--radius-full);
            position: relative;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5));
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {

            0%,
            100% {
                opacity: 0;
                transform: translateX(-100%);
            }

            50% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .progress-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .progress-stat {
            text-align: center;
            padding: 14px;
            background: var(--white);
            border-radius: var(--radius-md);
            border: 1px solid var(--gray-200);
        }

        .progress-stat .stat-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--gray-900);
        }

        .progress-stat .stat-label {
            font-size: 11px;
            color: var(--gray-600);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* Task Details Body */
        .task-details-body {
            padding: 20px;
        }

        .task-details-body::-webkit-scrollbar {
            width: 6px;
        }

        .task-details-body::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        .task-details-body::-webkit-scrollbar-thumb {
            background: var(--accent-lavender);
            border-radius: 10px;
        }

        /* Section Title */
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 18px 0;
        }

        .section-title i {
            font-size: 18px;
            color: var(--accent-lavender);
        }

        /* Info Grid */
        .task-info-section {
            margin-bottom: 24px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .info-item {
            padding: 16px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            border-left: 4px solid var(--accent-lavender);
            transition: var(--transition-base);
        }

        .info-item:hover {
            background: var(--white);
            box-shadow: var(--shadow-sm);
        }

        .info-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
        }

        .info-description {
            padding: 18px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
        }

        .description-text {
            font-size: 14px;
            color: var(--gray-700);
            line-height: 1.7;
            margin-top: 8px;
        }

        /* =============================================
           EXERCISES SECTION
        ============================================= */
        .exercises-section {
            margin-bottom: 32px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .btn-expand-all {
            padding: 10px 18px;
            background: var(--white);
            border: 1.5px solid var(--gray-300);
            border-radius: var(--radius-md);
            color: var(--gray-700);
            font-size: 13px;
            font-weight: 600;
            font-family: var(--font-sans);
            cursor: pointer;
            transition: var(--transition-base);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-expand-all:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }

        /* Exercise Item */
        .exercise-item {
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-lg);
            margin-bottom: 14px;
            overflow: hidden;
            transition: var(--transition-smooth);
        }

        .exercise-item:hover {
            border-color: var(--accent-lavender);
            box-shadow: var(--shadow-md);
        }

        .exercise-item:last-child {
            margin-bottom: 0;
        }

        .exercise-header {
            padding: 18px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--gray-50), var(--white));
            transition: var(--transition-base);
            user-select: none;
        }

        .exercise-header:hover {
            background: var(--gray-100);
        }

        .exercise-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
        }

        .exercise-number {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, var(--primary), var(--accent-blue));
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            flex-shrink: 0;
        }

        .exercise-title-group {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .exercise-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
        }

        .exercise-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toggle-icon {
            color: var(--gray-500);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 14px;
        }

        .exercise-item.expanded .toggle-icon {
            transform: rotate(180deg);
        }

        /* Exercise Body */
        .exercise-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .exercise-item.expanded .exercise-body {
            max-height: 2000px;
        }

        .exercise-content {
            padding: 24px;
            border-top: 1px solid var(--gray-200);
            background: var(--white);
        }

        .exercise-section {
            margin-bottom: 24px;
        }

        .exercise-section:last-child {
            margin-bottom: 0;
        }

        .exercise-section-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .exercise-description {
            font-size: 14px;
            color: var(--gray-700);
            line-height: 1.7;
        }

        /* Attachment Button */
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            background: var(--gray-100);
            border: 1.5px solid var(--gray-300);
            border-radius: var(--radius-md);
            color: var(--gray-700);
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        .btn-download:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .badge-grading-in-progress {
            background: var(--info-light);
            color: var(--info);
            border: 1px solid var(--info);
        }

        .badge-graded {
            background: var(--success-light);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .badge-in-progress {
            background: var(--warning-light);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .badge-assigned {
            background: var(--primary-50);
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        /* VIEW Submitted File Button */
        .btn-view-submission {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            background: linear-gradient(135deg, var(--info), #60a5fa);
            border: none;
            border-radius: var(--radius-md);
            color: white;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: var(--transition-smooth);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-view-submission:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            color: white;
        }

        /* Grade Button */
        .btn-grade-task {
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--success), #34d399);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 13px;
            font-family: var(--font-sans);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition-smooth);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-grade-task:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
        }

        /* Graded Badge */
        .graded-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, var(--success), #34d399);
            color: white;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .graded-badge i {
            font-size: 14px;
        }

        /* Compact Grade Banner */
        .compact-grade-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 20px;
            background: #ecfdf5; /* success-light */
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            margin-top: 20px;
        }

        .grade-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .grade-pill {
            background: #059669; /* success-dark */
            color: white;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .grade-sentiment {
            font-weight: 700;
            color: #047857;
            font-size: 14px;
        }

        .grade-feedback-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #065f46;
            max-width: 500px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            border-left: 2px solid #a7f3d0;
            padding-left: 16px;
        }

        @media (max-width: 768px) {
            .compact-grade-banner {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .grade-feedback-preview {
                border-left: none;
                padding-left: 0;
                border-top: 1px solid #a7f3d0;
                padding-top: 12px;
                width: 100%;
                white-space: normal;
                display: block;
            }
        }

        /* Exercise Meta */
        .exercise-meta {
            display: flex;
            gap: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--gray-200);
            margin-top: 20px;
        }

        .meta-date {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--gray-600);
        }

        .meta-date i {
            color: var(--accent-lavender);
        }

        /* =============================================
           STATUS BADGES
        ============================================= */
        .badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            height: 24px;
        }

        .badge-assigned {
            background: var(--info-light);
            color: var(--info);
        }

        .badge-pending,
        .badge-in-progress {
            background: var(--warning-light);
            color: #d97706;
        }

        .badge-submitted,
        .badge-turned-in {
            background: var(--success-light);
            color: #059669;
        }

        .badge-reviewed,
        .badge-returned {
            background: #e9d5ff;
            color: #9333ea;
        }

        .badge-not-submitted {
            background: var(--gray-200);
            color: var(--gray-600);
        }

        /* =============================================
           EMPTY & LOADING STATES
        ============================================= */
        .empty-state {
            text-align: center;
            padding: 60px 24px;
        }

        .empty-state i {
            font-size: 64px;
            color: var(--gray-300);
            margin-bottom: 20px;
        }

        .empty-state h4 {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
            color: var(--gray-500);
        }

        /* =============================================
           TOAST NOTIFICATIONS
        ============================================= */
        .toast-container {
            position: fixed;
            top: 100px;
            right: 24px;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 16px 20px;
            box-shadow: var(--shadow-xl);
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 340px;
            animation: slideIn 0.3s ease;
        }

        .toast.success {
            border-left: 4px solid var(--success);
        }

        .toast.error {
            border-left: 4px solid var(--danger);
        }

        .toast-icon {
            font-size: 22px;
        }

        .toast.success .toast-icon {
            color: var(--success);
        }

        .toast.error .toast-icon {
            color: var(--danger);
        }

        .toast-message {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
        }

        .toast-close {
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            padding: 4px;
            transition: var(--transition-base);
        }

        .toast-close:hover {
            color: var(--gray-700);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* =============================================
           GRADING MODAL STYLES
        ============================================= */
        .modal-content {
            border: none;
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--success), #34d399);
            color: white;
            padding: 24px 28px;
            border: none;
        }

        .modal-header .modal-title {
            font-weight: 700;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.9;
        }

        .modal-body {
            padding: 28px;
        }

        .modal-footer {
            padding: 18px 28px;
            border-top: 2px solid var(--gray-200);
            background: var(--gray-50);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control,
        .form-select {
            padding: 12px 14px;
            border: 1.5px solid var(--gray-300);
            border-radius: var(--radius-md);
            font-size: 14px;
            transition: var(--transition-base);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--success);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
            outline: none;
        }

        /* =============================================
           RESPONSIVE DESIGN
        ============================================= */
        @media (max-width: 768px) {
            .student-info-bar {
                margin: 80px 16px 16px 16px;
                flex-direction: column;
                padding: 20px;
            }

            .student-info-left {
                flex-direction: column;
                text-align: center;
            }

            .student-info-text p {
                flex-direction: column;
                gap: 8px;
            }

            .task-details-container {
                margin: 0 16px 16px 16px;
            }

            .task-details-header {
                padding: 20px;
            }

            .task-name {
                font-size: 20px;
            }

            .task-progress-section {
                padding: 20px;
            }

            .task-details-body {
                padding: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .progress-stats {
                grid-template-columns: 1fr;
            }

            .header-meta {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .student-info-bar {
                margin: 70px 12px 12px 12px;
            }

            .task-details-container {
                margin: 0 12px 12px 12px;
            }

            .exercise-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .exercise-header-right {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>

<body>
    <?php if ($session->logged_in) { ?>
        <div class="dashboard-main-wrapper">
            <?php include("navbar.php"); ?>
            <?php include("leftbar.php"); ?>
            <div class="dashboard-wrapper">
                <!-- Toast Container -->
                <div class="toast-container" id="toastContainer"></div>

                <!-- Student Info Bar - Full Width -->
                <div class="student-info-bar">
                    <div class="student-info-left">
                        <?php if (!empty($student['parent_directory']) && file_exists($student['parent_directory'])): ?>
                            <img src="<?php echo htmlspecialchars($student['parent_directory']); ?>" alt="Student Avatar"
                                class="student-avatar">
                        <?php else: ?>
                            <div class="student-avatar-placeholder">
                                <?php echo strtoupper(substr($student['name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>

                        <div class="student-info-text">
                            <h3><?php echo htmlspecialchars($student['name']); ?></h3>
                            <p>
                                <span><i class="fas fa-envelope"></i>
                                    <?php echo htmlspecialchars($student['email']); ?></span>
                                <span><i class="fas fa-graduation-cap"></i>
                                    <?php echo htmlspecialchars($student_course_name); ?></span>
                                <span><i class="fas fa-id-card"></i>
                                    <?php echo htmlspecialchars($student['registration_no']); ?></span>
                            </p>
                        </div>
                    </div>

                    <a href="task-overview-admin.php?student_id=<?php echo $student_id; ?>" class="btn-back-to-tasks">
                        <i class="fas fa-arrow-left"></i>
                        Back to All Tasks
                    </a>
                </div>

                <!-- Task Details Container -->
                <div class="task-details-container">
                    <div class="task-details-panel" id="taskDetailsPanel">
                        <div class="empty-state">
                            <i class="fas fa-spinner fa-spin"></i>
                            <h4>Loading Task Details...</h4>
                            <p>Please wait while we fetch the task information</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            // ============================================
            // GLOBAL STATE
            // ============================================
            const studentId = '<?php echo $student_id; ?>';
            const taskId = '<?php echo $task_id; ?>';

            // ============================================
            // INITIALIZATION - AUTO-LOAD TASK ON PAGE LOAD
            // ============================================
            document.addEventListener('DOMContentLoaded', function () {
                loadTaskDetails();
            });

            // ============================================
            // LOAD TASK DETAILS - AJAX
            // ============================================
            function loadTaskDetails() {
                const detailsPanel = document.getElementById('taskDetailsPanel');
                showLoading(detailsPanel);

                $.ajax({
                    url: 'ajax-get-admin-task-details.php',
                    type: 'POST',
                    data: {
                        task_id: taskId,
                        student_id: studentId
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            renderTaskDetails(response.task, response.exercises);
                        } else {
                            showError(detailsPanel, response.message || 'Failed to load task details');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error loading task details:', error);
                        showError(detailsPanel, 'Error loading task details. Please try again.');
                    }
                });
            }

            // ============================================
            // RENDER TASK DETAILS
            // ============================================
            function renderTaskDetails(task, exercises) {
                const statusStr = task.status || 'Pending';
                const statusClass = statusStr.toLowerCase().replace(/ /g, '-');
                const isTaskGraded = task.task_grade !== null && task.task_grade !== undefined && task.task_grade !== '';
                
                const detailsPanel = document.getElementById('taskDetailsPanel');
                detailsPanel.innerHTML = `
            <!-- Header Section -->
            <div class="task-details-header">
                <div class="header-main">
                    <div>
                        <h1 class="task-name">${escapeHtml(task.title)}</h1>
                        <div class="header-meta mt-2">
                            <span class="meta-chip" title="Category">
                                <i class="fas fa-layer-group"></i>
                                ${escapeHtml(task.category || 'N/A')}
                            </span>
                            <span class="meta-chip" title="Course">
                                <i class="fas fa-book"></i>
                                ${escapeHtml(task.course_title || 'N/A')}
                            </span>
                            <span class="meta-chip" title="Duration">
                                <i class="fas fa-clock"></i>
                                ${task.max_time || 'N/A'} Days
                            </span>
                            <span class="meta-chip" title="Assigned Date">
                                <i class="fas fa-calendar"></i>
                                ${task.created_at || 'N/A'} 
                            </span>
                        </div>
                    </div>
                    <div class="header-actions">
                        ${isTaskGraded ? `
                            <div class="graded-badge me-2" style="background: linear-gradient(135deg, var(--success), #059669); padding: 4px 12px; border-radius: 6px; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-check-double text-white"></i>
                                <span class="text-white fw-bold">${task.task_grade} / ${task.total_marks} Marks</span>
                            </div>
                        ` : `
                            <span class="badge badge-assigned me-2">
                                <i class="fas fa-award me-1"></i> ${task.total_marks} Marks
                            </span>
                        `}
                        <span class="badge badge-${statusClass}">${escapeHtml(task.status || 'Pending')}</span>
                        <button class="btn-icon ms-2" onclick="loadTaskDetails()" title="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                
                ${isTaskGraded ? `
                    <div class="compact-grade-banner">
                        <div class="grade-left">
                            <span class="grade-sentiment">
                                <i class="fas fa-medal me-1"></i>
                                ${
                                    (task.task_grade / task.total_marks * 100) >= 90 ? "Excellent Performance!" :
                                    (task.task_grade / task.total_marks * 100) >= 80 ? "Great Learning Progress!" :
                                    (task.task_grade / task.total_marks * 100) >= 70 ? "Good Effort" :
                                    "Task Graded Successfully"
                                }
                            </span>
                        </div>
                        ${task.task_feedback ? `
                            <div class="grade-feedback-preview" title="${escapeHtml(task.task_feedback)}">
                                <i class="fas fa-comment-dots"></i>
                                <span>${escapeHtml(task.task_feedback)}</span>
                            </div>
                        ` : ''}
                    </div>
                ` : ''}
            </div>
            
            <!-- Task Details Body -->
            <div class="task-details-body">
                ${task.description ? `
                    <div class="task-info-section">
                        <h3 class="section-title"><i class="fas fa-info-circle"></i> Instructions</h3>
                        <div class="info-description">
                            <div class="description-text">${task.description}</div>
                        </div>
                    </div>
                ` : ''}

                <!-- Exercises Section -->
                <div class="exercises-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-list-check"></i>
                            Exercises (${exercises.length})
                        </h3>
                        ${exercises.length > 1 ? `
                            <button class="btn-expand-all" onclick="toggleAllExercises()">
                                <i class="fas fa-expand-alt"></i>
                                Expand All
                            </button>
                        ` : ''}
                    </div>
                    ${renderExercises(exercises, task.id)}
                </div>
            </div>
            `;
            }

            // ============================================
            // RENDER EXERCISES
            // ============================================
            function renderExercises(exercises, taskId) {
                if (!exercises || exercises.length === 0) {
                    return `
                                    <div class="empty-state">
                                        <i class="fas fa-clipboard"></i>
                                        <h4>No Exercises</h4>
                                        <p>No exercises have been added to this task yet</p>
                                    </div>
                                `;
                }

                let html = '';
                exercises.forEach((exercise, index) => {
                    const statusClass = (exercise.submission_status || 'not-submitted').toLowerCase().replace(/ /g, '-');
                    const isSubmitted = exercise.submission_status && ['turned in', 'returned', 'graded'].includes(exercise.submission_status.toLowerCase());

                    html += `
                                    <div class="exercise-item" data-exercise-id="${exercise.id}">
                                        <div class="exercise-header" onclick="toggleExercise(${exercise.id})">
                                            <div class="exercise-header-left">
                                                <span class="exercise-number">${index + 1}</span>
                                                <div class="exercise-title-group">
                                                    <h4 class="exercise-title">${escapeHtml(exercise.excercise_title)}</h4>
                                                </div>
                                            </div>
                                            <div class="exercise-header-right">
                                                <span class="badge badge-${statusClass}">${escapeHtml(exercise.submission_status || 'Not Submitted')}</span>
                                                <i class="fas fa-chevron-down toggle-icon"></i>
                                            </div>
                                        </div>
                    
                                        <div class="exercise-body">
                                            <div class="exercise-content">
                                       
                            
                                                ${exercise.excercise_assets ? `
                                                    <div class="exercise-section">
                                                        <div class="exercise-section-title">Teacher's Attachment</div>
                                    <a href="excercises/${encodeURIComponent(exercise.excercise_assets)}" class="btn-download" download>
                                        <i class="fas fa-file-alt"></i>
                                        <span>${escapeHtml(exercise.excercise_assets)}</span>
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            ` : ''}
                            
                            ${isSubmitted ? `
                                <div class="exercise-section">
                                    <div class="exercise-section-title">Student Submission</div>
                                    <p class="exercise-description" style="color: var(--success); margin-bottom: 12px;">
                                        <i class="fas fa-check-circle"></i>
                                        Student submitted this exercise${exercise.submitted_at ? ` on ${escapeHtml(exercise.submitted_at)}` : ''}.
                                    </p>
                                    ${exercise.submitted_file ? `
                                        <a href="submitted_excercises/${encodeURIComponent(exercise.submitted_file)}" class="btn-view-submission" download>
                                            <i class="fas fa-file-alt"></i>
                                            <span>${escapeHtml(exercise.submitted_file)}</span>
                                            <i class="fas fa-download"></i>
                                        </a>
                                    ` : '<p class="text-muted">No file attached</p>'}
                                </div>
                            ` : `
                                <div class="exercise-section">
                                    <div class="exercise-section-title">Submission Status</div>
                                    <p class="exercise-description" style="color: var(--warning);">
                                        <i class="fas fa-clock"></i>
                                        Student has not submitted this exercise yet.
                                    </p>
                                </div>
                            `}
                            
                            <div class="exercise-meta">
                                <span class="meta-date">
                                    <i class="fas fa-calendar-plus"></i>
                                    Created: ${escapeHtml(exercise.created_at || 'N/A')}
                                </span>
                                ${exercise.submitted_at ? `
                                    <span class="meta-date">
                                        <i class="fas fa-paper-plane"></i>
                                        Submitted: ${escapeHtml(exercise.submitted_at)}
                                    </span>
                                ` : ''}
                            </div>

                            <div class="exercise-footer mt-3 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        ${exercise.obtained_marks !== null ? `
                                            <div class="text-success fw-bold">
                                                <i class="fas fa-check-circle"></i> Graded: ${exercise.obtained_marks}/${exercise.max_marks}
                                            </div>
                                        ` : '<span class="text-muted small italic">Waiting for review</span>'}
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            data-task-id="${taskId}"
                                            data-exercise-id="${exercise.id}"
                                            data-exercise-title="${escapeHtml(exercise.excercise_title)}"
                                            data-max-marks="${exercise.max_marks}"
                                            data-obtained-marks="${exercise.obtained_marks || ''}"
                                            data-feedback="${escapeHtml(exercise.grade_feedback || '')}"
                                            onclick="handleGradeClick(this)">
                                        <i class="fas fa-edit"></i> ${exercise.obtained_marks !== null ? 'Edit Grade' : 'Grade Exercise'}
                                    </button>
                                </div>
                                ${exercise.grade_feedback ? `
                                    <div class="mt-2 p-2 bg-light rounded text-muted small border-start border-4 border-primary">
                                        <strong>Feedback:</strong> ${escapeHtml(exercise.grade_feedback)}
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
                });

                return html;
            }

            // ============================================
            // TOGGLE EXERCISE
            // ============================================
            function toggleExercise(exerciseId) {
                const item = document.querySelector(`.exercise-item[data-exercise-id="${exerciseId}"]`);
                if (item) item.classList.toggle('expanded');
            }

            function toggleAllExercises() {
                const exercises = document.querySelectorAll('.exercise-item');
                const allExpanded = Array.from(exercises).every(ex => ex.classList.contains('expanded'));

                exercises.forEach(ex => {
                    if (allExpanded) {
                        ex.classList.remove('expanded');
                    } else {
                        ex.classList.add('expanded');
                    }
                });

                const btn = document.querySelector('.btn-expand-all');
                if (btn) {
                    btn.innerHTML = allExpanded
                        ? '<i class="fas fa-expand-alt"></i> Expand All'
                        : '<i class="fas fa-compress-alt"></i> Collapse All';
                }
            }

            // ============================================
            // GRADING MODAL SETUP
            // ============================================
            function handleGradeClick(btn) {
                const taskId = btn.getAttribute('data-task-id');
                const exerciseId = btn.getAttribute('data-exercise-id');
                const exerciseTitle = btn.getAttribute('data-exercise-title');
                const maxMarks = parseInt(btn.getAttribute('data-max-marks'));
                const currentMarks = btn.getAttribute('data-obtained-marks');
                const currentFeedback = btn.getAttribute('data-feedback');
                
                openGradeModal(taskId, exerciseId, exerciseTitle, maxMarks, currentMarks, currentFeedback);
            }

            function openGradeModal(taskId, exerciseId, exerciseTitle, maxMarks, currentMarks, currentFeedback) {
                const modalEl = document.getElementById('gradeModal');
                
                // Set hidden inputs
                modalEl.querySelector('#modal_task_id').value = taskId;
                modalEl.querySelector('#modal_excercise_id').value = exerciseId;
                
                // Set visible inputs
                modalEl.querySelector('#modal_task_title').value = exerciseTitle;
                
                const marksInput = modalEl.querySelector('#marks');
                const totalCreditsSpan = modalEl.querySelector('#totalCredits');
                const marksHelp = modalEl.querySelector('#marksHelp');
                const marksLabel = modalEl.querySelector('#marksLabel');

                marksInput.value = (currentMarks !== 'null' && currentMarks !== '') ? currentMarks : '';
                marksInput.max = maxMarks;
                marksInput.placeholder = `0 - ${maxMarks}`;
                
                modalEl.querySelector('#feedback').value = (currentFeedback !== 'null' && currentFeedback !== '') ? currentFeedback : '';
                
                totalCreditsSpan.textContent = `/ ${maxMarks}`;
                marksHelp.textContent = `Max allowed marks for this exercise: ${maxMarks}`;
                marksLabel.innerHTML = `<i class="fas fa-award"></i> Exercise Grade (out of ${maxMarks})`;

                const modalTitle = modalEl.querySelector('#gradeModalLabel');
                if (currentMarks !== 'null' && currentMarks !== '') {
                    modalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Exercise Grade';
                } else {
                    modalTitle.innerHTML = '<i class="fas fa-star"></i> Grade Exercise';
                }
                
                // Use Bootstrap 5 properly
                let gradeModal = bootstrap.Modal.getInstance(modalEl);
                if (!gradeModal) {
                    gradeModal = new bootstrap.Modal(modalEl);
                }
                gradeModal.show();
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Task grade modal listener (not used anymore for task-level, kept for compatibility if needed elsewhere)
                const gradeModalEl = document.getElementById('gradeModal');
                if (gradeModalEl) {
                    // Cleanup if needed
                }
            });

            // ============================================
            // UTILITIES
            // ============================================
            function calculateProgress(completed, total) {
                if (total === 0) return 0;
                return Math.round((completed / total) * 100);
            }

            function showLoading(container) {
                container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-spinner fa-spin"></i>
                <h4>Loading...</h4>
                <p>Please wait while we fetch the data</p>
            </div>
        `;
            }

            function showError(container, message) {
                container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle"></i>
                <h4>Error</h4>
                <p>${escapeHtml(message)}</p>
                <button class="btn-expand-all" onclick="loadTaskDetails()" style="margin-top: 16px;">
                    <i class="fas fa-redo"></i> Retry
                </button>
            </div>
        `;
            }

            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // ============================================
            // TOAST NOTIFICATIONS
            // ============================================
            function showToast(message, type = 'success') {
                const container = document.getElementById('toastContainer');
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.innerHTML = `
            <i class="toast-icon fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span class="toast-message">${escapeHtml(message)}</span>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

                container.appendChild(toast);

                setTimeout(() => {
                    if (toast.parentElement) toast.remove();
                }, 5000);
            }
        </script>

        <!-- Grading Modal -->
        <div class="modal fade" id="gradeModal" tabindex="-1" aria-labelledby="gradeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="gradeModalLabel">
                            <i class="fas fa-star"></i> Grade Task
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="process-teacher-grade-task.php" method="POST" id="gradeForm">
                        <div class="modal-body">
                            <input type="hidden" name="action" value="grade_task">
                            <input type="hidden" name="student_id" id="modal_student_id" value="<?php echo $student_id; ?>">
                            <input type="hidden" name="task_id" id="modal_task_id">
                            <input type="hidden" name="excercise_id" id="modal_excercise_id">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="fas fa-user"></i> Student Name
                                    </label>
                                    <input type="text" class="form-control"
                                        value="<?php echo htmlspecialchars($student['name']); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="fas fa-clipboard-list"></i> Task Title
                                    </label>
                                    <input type="text" class="form-control" id="modal_task_title" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="marks" class="form-label" id="marksLabel">
                                    <i class="fas fa-award"></i> Task Grade
                                </label>

                                <div class="input-group">
                                    <input type="number" class="form-control" id="marks" name="marks" min="0" required>
                                    <span class="input-group-text" id="totalCredits">/ 0</span>
                                </div>

                                <small class="text-muted" id="marksHelp">
                                    Enter marks within allowed credit hours
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="feedback" class="form-label">
                                    <i class="fas fa-comment-dots"></i> Feedback
                                </label>
                                <textarea class="form-control" id="feedback" name="feedback" rows="5"
                                    placeholder="Provide detailed feedback about the overall task performance..."></textarea>
                                <small class="text-muted">Optional: Share constructive feedback with the student about their
                                    overall task performance</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-success"
                                style="background: linear-gradient(135deg, var(--success), #34d399); border: none;">
                                <i class="fas fa-check"></i> Submit Grade
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Toast Container for Notifications -->
        <div id="toastContainer" class="toast-container"></div>
    <?php } else {
        header("location: index.php");
    } ?>
</body>

</html>