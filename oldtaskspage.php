<?php 
include("include/classes/session.php");

$username = $session->username;
$result = $database->getUserInfo($username);
$userlevel = ($result['userlevel']);
$display_name = $result['display_name'];
$email = $result['email'];
$phone = $result['phone'];
$image = $result['parent_directory'];
$password = $result['password'];
$reg_no = $result['registration_no'];

if ($session->userlevel == 0) {
    $result2 = $database->getstudentbyreg($reg_no);
    $admission_no = ($result2['admission_no']);
    $student_id = ($result2['id']);
    $registration_no = ($result2['registration_no']);
    $category_id = ($result2['category_id']);
    $course_id = ($result2['course_id']);
    $session_id = ($result2['session_id']);
}

if (!$session->logged_in) {
    header("location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks - Filenod Academy</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts - DM Sans for clean, modern look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Favicon -->
    <link href="assets/img/favicon-filenod.png" rel="icon">
    
    <style>
        /* =============================================
           CSS CUSTOM PROPERTIES (Design Tokens)
        ============================================= */
        :root {
            /* Primary Brand Colors */
            --primary: #173663;
            --primary-hover: #0f2444;
            --primary-light: #1e4a8a;
            --primary-50: rgba(23, 54, 99, 0.05);
            --primary-100: rgba(23, 54, 99, 0.1);
            
            /* Accent Colors */
            --accent: #4f7cac;
            --accent-light: #7ba3cc;
            
            /* Semantic Colors */
            --success: #059669;
            --success-bg: #ecfdf5;
            --success-border: #a7f3d0;
            
            --warning: #d97706;
            --warning-bg: #fffbeb;
            --warning-border: #fde68a;
            
            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            
            --info: #0284c7;
            --info-bg: #f0f9ff;
            --info-border: #bae6fd;
            
            /* Neutral Palette */
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
            --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            
            /* Border Radius */
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --radius-2xl: 20px;
            --radius-full: 9999px;
            
            /* Typography */
            --font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            
            /* Transitions */
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 200ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 300ms cubic-bezier(0.4, 0, 0.2, 1);
            
            /* Layout */
            --sidebar-width: 380px;
            --header-height: 70px;
        }

        /* =============================================
           BASE STYLES
        ============================================= */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.5;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Remove default margins */
        .dashboard-wrapper {
            margin-left: 0 !important;
        }

        .dashboard-main-wrapper {
            padding-top: 0 !important;
        }

        /* =============================================
           MAIN LAYOUT STRUCTURE
        ============================================= */
        .task-app {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: var(--header-height);
        }

        .task-app-container {
            display: flex;
            flex: 1;
            width: 100%;
            margin: 0 auto;
            padding: 24px;
            gap: 24px;
            width: 100%;
        }

        /* =============================================
           PAGE HEADER
        ============================================= */
        .page-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: 20px 24px;
            position: sticky;
            top: var(--header-height);
            z-index: 100;
        }

        .page-header-content {
            max-width: 1600px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 18px;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--gray-500);
            margin: 0;
            padding-left: 52px;
        }

        /* Search Bar */
        .search-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-input-wrapper {
            position: relative;
            width: 280px;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 14px;
        }

        .search-input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-family: var(--font-family);
            background: var(--white);
            transition: var(--transition-base);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-100);
        }

        .search-input::placeholder {
            color: var(--gray-400);
        }

        /* Filter Select */
        .filter-select {
            padding: 10px 36px 10px 14px;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-family: var(--font-family);
            background: var(--white) url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") right 10px center/16px no-repeat;
            appearance: none;
            cursor: pointer;
            transition: var(--transition-base);
            min-width: 140px;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-100);
        }

        /* =============================================
           LEFT SIDEBAR - TASK LIST
        ============================================= */
        .task-sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .stat-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 16px;
            text-align: center;
            transition: var(--transition-base);
        }

        .stat-card:hover {
            border-color: var(--gray-300);
            box-shadow: var(--shadow-sm);
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-size: 16px;
        }

        .stat-icon.total {
            background: var(--primary-100);
            color: var(--primary);
        }

        .stat-icon.pending {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .stat-icon.completed {
            background: var(--success-bg);
            color: var(--success);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--gray-500);
            margin-top: 4px;
            font-weight: 500;
        }

        /* Task List Container */
        .task-list-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            overflow: hidden;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .task-list-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task-list-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
        }

        .task-count-badge {
            background: var(--primary-100);
            color: var(--primary);
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: var(--radius-full);
        }

        .task-list-body {
            flex: 1;
            overflow-y: auto;
            max-height: calc(100vh - 380px);
        }

        .task-list-body::-webkit-scrollbar {
            width: 6px;
        }

        .task-list-body::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        .task-list-body::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }

        .task-list-body::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* Task Item */
        .task-item {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-100);
            cursor: pointer;
            transition: var(--transition-base);
            position: relative;
        }

        .task-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: transparent;
            transition: var(--transition-base);
        }

        .task-item:hover {
            background: var(--gray-50);
        }

        .task-item.active {
            background: var(--primary-50);
        }

        .task-item.active::before {
            background: var(--primary);
        }

        .task-item:last-child {
            border-bottom: none;
        }

        .task-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 8px;
        }

        .task-item-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
            line-height: 1.4;
        }

        .task-item-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .task-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--gray-500);
        }

        .task-meta-item i {
            font-size: 11px;
            color: var(--gray-400);
        }

        .task-item-progress {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .progress-bar-mini {
            flex: 1;
            height: 4px;
            background: var(--gray-200);
            border-radius: var(--radius-full);
            overflow: hidden;
        }

        .progress-bar-mini-fill {
            height: 100%;
            background: var(--primary);
            border-radius: var(--radius-full);
            transition: width 0.5s ease;
        }

        .progress-text-mini {
            font-size: 11px;
            color: var(--gray-500);
            font-weight: 500;
            white-space: nowrap;
        }

        /* =============================================
           MAIN CONTENT - TASK DETAILS
        ============================================= */
        .task-main {
            flex: 1;
            min-width: 0;
        }

        .task-detail-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            overflow: hidden;
            min-height: calc(100vh - 200px);
        }

        /* Task Detail Header */
        .task-detail-header {
            padding: 24px;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-25);
        }

        .task-detail-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }

        .task-detail-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
            line-height: 1.3;
        }

        .task-detail-actions {
            display: flex;
            gap: 8px;
        }

        .btn-icon-sm {
            width: 36px;
            height: 36px;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            background: var(--white);
            color: var(--gray-600);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-base);
        }

        .btn-icon-sm:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }

        .task-detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-full);
            font-size: 13px;
            color: var(--gray-700);
            font-weight: 500;
        }

        .meta-badge i {
            font-size: 12px;
            color: var(--gray-400);
        }

        /* Progress Section */
        .progress-section {
            padding: 20px 24px;
            background: linear-gradient(135deg, var(--primary-50) 0%, var(--gray-50) 100%);
            border-bottom: 1px solid var(--gray-200);
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .progress-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
        }

        .progress-percentage {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
        }

        .progress-bar-main {
            height: 8px;
            background: var(--gray-200);
            border-radius: var(--radius-full);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .progress-bar-main-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: var(--radius-full);
            transition: width 0.6s ease;
        }

        .progress-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .progress-stat {
            text-align: center;
            padding: 12px;
            background: var(--white);
            border-radius: var(--radius-md);
            border: 1px solid var(--gray-200);
        }

        .progress-stat-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .progress-stat-label {
            font-size: 11px;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Task Detail Body */
        .task-detail-body {
            padding: 24px;
        }

        /* Info Grid */
        .info-section {
            margin-bottom: 32px;
        }

        .section-heading {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0 0 16px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-heading i {
            color: var(--primary);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .info-item {
            padding: 14px 16px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            border-left: 3px solid var(--primary);
        }

        .info-item-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-item-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
        }

        .description-box {
            padding: 16px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
        }

        .description-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .description-text {
            font-size: 14px;
            color: var(--gray-700);
            line-height: 1.6;
        }

        /* =============================================
           EXERCISES SECTION
        ============================================= */
        .exercises-section {
            margin-bottom: 32px;
        }

        .exercises-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .btn-expand-all {
            padding: 8px 14px;
            background: var(--white);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-600);
            cursor: pointer;
            transition: var(--transition-base);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-expand-all:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }

        /* Exercise Item */
        .exercise-item {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            margin-bottom: 12px;
            overflow: hidden;
            transition: var(--transition-base);
        }

        .exercise-item:hover {
            border-color: var(--gray-300);
            box-shadow: var(--shadow-sm);
        }

        .exercise-item:last-child {
            margin-bottom: 0;
        }

        .exercise-header {
            padding: 16px 20px;
            background: var(--gray-50);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: var(--white);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .exercise-title-group {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .exercise-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
            margin: 0;
        }

        .exercise-subtitle {
            font-size: 12px;
            color: var(--gray-500);
        }

        .exercise-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-icon {
            color: var(--gray-400);
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .exercise-item.expanded .toggle-icon {
            transform: rotate(180deg);
        }

        /* Exercise Body */
        .exercise-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
        }

        .exercise-item.expanded .exercise-body {
            max-height: 1500px;
        }

        .exercise-content {
            padding: 20px;
            border-top: 1px solid var(--gray-200);
        }

        .exercise-section {
            margin-bottom: 20px;
        }

        .exercise-section:last-child {
            margin-bottom: 0;
        }

        .exercise-section-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .exercise-description {
            font-size: 14px;
            color: var(--gray-700);
            line-height: 1.6;
        }

        /* Attachment Button */
        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            background: var(--white);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            color: var(--gray-700);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition-base);
        }

        .btn-download:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--white);
        }

        .btn-download i {
            font-size: 14px;
        }

        /* File Upload */
        .file-upload-area {
            border: 2px dashed var(--gray-300);
            border-radius: var(--radius-lg);
            padding: 24px;
            text-align: center;
            background: var(--gray-50);
            transition: var(--transition-base);
            cursor: pointer;
        }

        .file-upload-area:hover {
            border-color: var(--primary);
            background: var(--primary-50);
        }

        .file-upload-area.dragover {
            border-color: var(--primary);
            background: var(--primary-50);
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .file-upload-label i {
            font-size: 32px;
            color: var(--gray-400);
        }

        .file-upload-label span {
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
        }

        .file-upload-label small {
            font-size: 12px;
            color: var(--gray-500);
        }

        .file-selected {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--success);
        }

        .file-selected i {
            font-size: 18px;
        }

        /* Comment Textarea */
        .comment-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-family: var(--font-family);
            resize: vertical;
            min-height: 80px;
            transition: var(--transition-base);
        }

        .comment-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-100);
        }

        .comment-textarea::placeholder {
            color: var(--gray-400);
        }

        /* Submit Button */
        .btn-submit {
            padding: 12px 24px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            font-family: var(--font-family);
            cursor: pointer;
            transition: var(--transition-base);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-submit.btn-resubmit {
            background: var(--warning);
        }

        .btn-submit.btn-resubmit:hover {
            background: #b45309;
        }

        /* Exercise Meta */
        .exercise-meta {
            display: flex;
            gap: 16px;
            padding-top: 12px;
            border-top: 1px solid var(--gray-200);
            margin-top: 16px;
        }

        .exercise-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--gray-500);
        }

        .exercise-meta-item i {
            color: var(--gray-400);
        }

        /* =============================================
           STATUS BADGES
        ============================================= */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-badge.assigned {
            background: var(--info-bg);
            color: var(--info);
        }

        .status-badge.pending {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .status-badge.submitted,
        .status-badge.turned-in {
            background: var(--success-bg);
            color: var(--success);
        }

        .status-badge.returned,
        .status-badge.reviewed {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .status-badge.rejected {
            background: var(--danger-bg);
            color: var(--danger);
        }

        /* =============================================
           GRADES SECTION
        ============================================= */
        .grades-section {
            background: linear-gradient(135deg, var(--primary-50) 0%, var(--gray-50) 100%);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 20px;
        }

        .grades-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .grade-item {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .grade-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: var(--white);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .grade-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .grade-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .grade-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .remarks-box {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            padding: 14px;
            margin-top: 12px;
        }

        .remarks-text {
            font-size: 14px;
            color: var(--gray-700);
            line-height: 1.6;
            margin-top: 8px;
        }

        /* =============================================
           EMPTY & LOADING STATES
        ============================================= */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: var(--gray-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .empty-state-icon i {
            font-size: 32px;
            color: var(--gray-400);
        }

        .empty-state h4 {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-700);
            margin: 0 0 8px 0;
        }

        .empty-state p {
            font-size: 14px;
            color: var(--gray-500);
            margin: 0;
        }

        .loading-spinner {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--gray-200);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* =============================================
           TOAST NOTIFICATIONS
        ============================================= */
        .toast-container {
            position: fixed;
            top: 90px;
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
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 320px;
            animation: slideIn 0.3s ease;
        }

        .toast.success {
            border-left: 4px solid var(--success);
        }

        .toast.error {
            border-left: 4px solid var(--danger);
        }

        .toast-icon {
            font-size: 20px;
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
            color: var(--gray-700);
        }

        .toast-close {
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            padding: 4px;
        }

        .toast-close:hover {
            color: var(--gray-600);
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
           RESPONSIVE DESIGN
        ============================================= */
        @media (max-width: 1200px) {
            .task-app-container {
                flex-direction: column;
            }

            .task-sidebar {
                width: 100%;
            }

            .task-list-body {
                max-height: 300px;
            }
        }

        @media (max-width: 768px) {
            .task-app-container {
                padding: 16px;
            }

            .page-header {
                padding: 16px;
            }

            .page-header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-bar {
                width: 100%;
            }

            .search-input-wrapper {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .progress-stats {
                grid-template-columns: 1fr;
            }

            .task-detail-header {
                padding: 16px;
            }

            .task-detail-body {
                padding: 16px;
            }

            .task-detail-title {
                font-size: 18px;
            }

            .meta-badge {
                font-size: 12px;
                padding: 5px 10px;
            }

            .exercise-header {
                padding: 14px 16px;
            }

            .exercise-content {
                padding: 16px;
            }

            .grades-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 20px;
            }

            .page-title-icon {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }

            .page-subtitle {
                padding-left: 48px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                display: flex;
                align-items: center;
                gap: 12px;
                text-align: left;
                padding: 12px 16px;
            }

            .stat-icon {
                margin: 0;
            }

            .task-detail-meta {
                flex-direction: column;
                align-items: flex-start;
            }

            .exercise-header-left {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .exercise-number {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
        }

        /* =============================================
           PRINT STYLES
        ============================================= */
        @media print {
            .task-sidebar,
            .page-header,
            .btn-icon-sm,
            .btn-submit,
            .file-upload-area {
                display: none !important;
            }

            .task-app-container {
                display: block;
            }

            .task-main {
                width: 100%;
            }

            .task-detail-card {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-main-wrapper">
        <?php include("navbar.php"); ?>
        
        <div class="dashboard-wrapper">
            <!-- Toast Container -->
            <div class="toast-container" id="toastContainer"></div>
            
            <!-- Page Header -->
            <header class="page-header">
                <div class="page-header-content">
                    <div class="page-title-group">
                        <h1 class="page-title">
                            <span class="page-title-icon">
                                <i class="fas fa-tasks"></i>
                            </span>
                            My Tasks
                        </h1>
                        <p class="page-subtitle">View and submit your assigned tasks and exercises</p>
                    </div>
                    
                    <div class="search-bar">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input 
                                type="text" 
                                id="searchInput" 
                                class="search-input" 
                                placeholder="Search tasks..."
                                aria-label="Search tasks"
                            >
                        </div>
                        <select id="statusFilter" class="filter-select" aria-label="Filter by status">
                            <option value="">All Status</option>
                            <option value="assigned">Assigned</option>
                            <option value="pending">Pending</option>
                            <option value="submitted">Submitted</option>
                            <option value="reviewed">Reviewed</option>
                        </select>
                    </div>
                </div>
            </header>

            <!-- Main App Container -->
            <div class="task-app">
                <div class="task-app-container">
                    
                    <!-- Left Sidebar - Task List -->
                    <aside class="task-sidebar">
                        <!-- Stats Cards -->
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon total">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div>
                                    <div class="stat-value" id="statTotal">0</div>
                                    <div class="stat-label">Total</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon pending">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <div class="stat-value" id="statPending">0</div>
                                    <div class="stat-label">Pending</div>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon completed">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <div class="stat-value" id="statCompleted">0</div>
                                    <div class="stat-label">Done</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Task List -->
                        <div class="task-list-card">
                            <div class="task-list-header">
                                <h2 class="task-list-title">All Tasks</h2>
                                <span class="task-count-badge" id="taskCountBadge">0 tasks</span>
                            </div>
                            <div class="task-list-body" id="taskListContainer">
                                <div class="loading-spinner">
                                    <div class="spinner"></div>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <!-- Main Content - Task Details -->
                    <main class="task-main">
                        <div class="task-detail-card" id="taskDetailContainer">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-hand-pointer"></i>
                                </div>
                                <h4>Select a Task</h4>
                                <p>Choose a task from the list to view details</p>
                            </div>
                        </div>
                    </main>

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
    const APP_STATE = {
        tasks: [],
        filteredTasks: [],
        currentTaskId: null,
        studentId: '<?php echo $student_id; ?>',
        courseId: '<?php echo $course_id; ?>'
    };

    // ============================================
    // INITIALIZATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        loadTasks();
        initializeEventListeners();
    });

    function initializeEventListeners() {
        // Search input
        document.getElementById('searchInput').addEventListener('input', debounce(filterTasks, 300));
        
        // Status filter
        document.getElementById('statusFilter').addEventListener('change', filterTasks);
        
        // Keyboard navigation
        document.addEventListener('keydown', handleKeyboardNav);
    }

    // ============================================
    // LOAD TASKS
    // ============================================
    function loadTasks() {
        showTaskListLoading();
        
        $.ajax({
            url: 'ajax-load-student-tasks.php',
            type: 'POST',
            data: { course_id: APP_STATE.courseId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    APP_STATE.tasks = response.tasks || [];
                    APP_STATE.filteredTasks = [...APP_STATE.tasks];
                    updateStats();
                    renderTaskList();
                    
                    // Auto-select first task
                    if (APP_STATE.tasks.length > 0) {
                        loadTaskDetails(APP_STATE.tasks[0].id);
                    }
                } else {
                    showTaskListError('Failed to load tasks');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading tasks:', error);
                showTaskListError('Error loading tasks. Please refresh.');
            }
        });
    }

    // ============================================
    // UPDATE STATS
    // ============================================
    function updateStats() {
        const total = APP_STATE.tasks.length;
        const pending = APP_STATE.tasks.filter(t => 
            t.status.toLowerCase() === 'pending' || 
            t.status.toLowerCase() === 'assigned'
        ).length;
        const completed = APP_STATE.tasks.filter(t => 
            t.status.toLowerCase() === 'submitted' || 
            t.status.toLowerCase() === 'reviewed' ||
            t.status.toLowerCase() === 'returned'
        ).length;
        
        document.getElementById('statTotal').textContent = total;
        document.getElementById('statPending').textContent = pending;
        document.getElementById('statCompleted').textContent = completed;
        document.getElementById('taskCountBadge').textContent = `${APP_STATE.filteredTasks.length} tasks`;
    }

    // ============================================
    // RENDER TASK LIST
    // ============================================
    function renderTaskList() {
        const container = document.getElementById('taskListContainer');
        
        if (APP_STATE.filteredTasks.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h4>No Tasks Found</h4>
                    <p>No tasks match your search criteria</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        APP_STATE.filteredTasks.forEach(task => {
            const isActive = task.id === APP_STATE.currentTaskId ? 'active' : '';
            const progress = calculateProgress(task.completed_exercises || 0, task.total_exercises || 0);
            const statusClass = task.status.toLowerCase().replace(' ', '-');
            
            html += `
                <div class="task-item ${isActive}" data-task-id="${task.id}" onclick="loadTaskDetails(${task.id})">
                    <div class="task-item-header">
                        <h3 class="task-item-title">${escapeHtml(task.title)}</h3>
                        <span class="status-badge ${statusClass}">${escapeHtml(task.status)}</span>
                    </div>
                    <div class="task-item-meta">
                        <span class="task-meta-item">
                            <i class="fas fa-book"></i>
                            ${escapeHtml(task.course_title || 'N/A')}
                        </span>
                        <span class="task-meta-item">
                            <i class="fas fa-calendar"></i>
                            ${escapeHtml(task.created_at)}
                        </span>
                    </div>
                    <div class="task-item-progress">
                        <div class="progress-bar-mini">
                            <div class="progress-bar-mini-fill" style="width: ${progress}%"></div>
                        </div>
                        <span class="progress-text-mini">${task.completed_exercises || 0}/${task.total_exercises || 0}</span>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    // ============================================
    // LOAD TASK DETAILS
    // ============================================
    function loadTaskDetails(taskId) {
        APP_STATE.currentTaskId = taskId;
        
        // Update active state in list
        document.querySelectorAll('.task-item').forEach(item => {
            item.classList.remove('active');
        });
        const activeItem = document.querySelector(`.task-item[data-task-id="${taskId}"]`);
        if (activeItem) {
            activeItem.classList.add('active');
        }
        
        // Show loading
        showDetailLoading();
        
        $.ajax({
            url: 'ajax-get-task-details.php',
            type: 'POST',
            data: { 
                task_id: taskId,
                student_id: APP_STATE.studentId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderTaskDetails(response.task, response.exercises);
                } else {
                    showDetailError('Failed to load task details');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading task details:', error);
                showDetailError('Error loading task details');
            }
        });
    }

    // ============================================
    // RENDER TASK DETAILS
    // ============================================
    function renderTaskDetails(task, exercises) {
        const container = document.getElementById('taskDetailContainer');
        const progress = calculateProgress(task.completed_exercises || 0, exercises.length);
        
        let html = `
            <!-- Task Header -->
            <div class="task-detail-header">
                <div class="task-detail-header-top">
                    <h1 class="task-detail-title">${escapeHtml(task.title)}</h1>
                    <div class="task-detail-actions">
                        <button class="btn-icon-sm" onclick="refreshTaskDetails()" title="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="task-detail-meta">
                    <span class="meta-badge">
                        <i class="fas fa-layer-group"></i>
                        ${escapeHtml(task.category || 'N/A')}
                    </span>
                    <span class="meta-badge">
                        <i class="fas fa-book"></i>
                        ${escapeHtml(task.course_title || 'N/A')}
                    </span>
                    <span class="meta-badge">
                        <i class="fas fa-clock"></i>
                        ${task.max_time || 'N/A'} Days
                    </span>
                    <span class="meta-badge">
                        <i class="fas fa-star"></i>
                        ${task.credit_hours || 'N/A'} Credit Hours
                    </span>
                </div>
            </div>
            
            <!-- Progress Section -->
            <div class="progress-section">
                <div class="progress-header">
                    <span class="progress-label">Overall Progress</span>
                    <span class="progress-percentage">${progress}%</span>
                </div>
                <div class="progress-bar-main">
                    <div class="progress-bar-main-fill" style="width: ${progress}%"></div>
                </div>
                <div class="progress-stats">
                    <div class="progress-stat">
                        <div class="progress-stat-value">${exercises.length}</div>
                        <div class="progress-stat-label">Total Exercises</div>
                    </div>
                    <div class="progress-stat">
                        <div class="progress-stat-value">${task.submitted_exercises || 0}</div>
                        <div class="progress-stat-label">Submitted</div>
                    </div>
                    <div class="progress-stat">
                        <div class="progress-stat-value">${task.reviewed_exercises || 0}</div>
                        <div class="progress-stat-label">Reviewed</div>
                    </div>
                </div>
            </div>
            
            <!-- Task Body -->
            <div class="task-detail-body">
                <!-- Task Info Section -->
                <div class="info-section">
                    <h3 class="section-heading">
                        <i class="fas fa-info-circle"></i>
                        Task Information
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-item-label">Created On</div>
                            <div class="info-item-value">${escapeHtml(task.created_at)}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Duration</div>
                            <div class="info-item-value">${task.max_time || 'N/A'} Days</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Credit Hours</div>
                            <div class="info-item-value">${task.credit_hours || 'N/A'}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item-label">Status</div>
                            <div class="info-item-value">${escapeHtml(task.status || 'Assigned')}</div>
                        </div>
                    </div>
                    ${task.description ? `
                        <div class="description-box">
                            <div class="description-label">Description</div>
                            <p class="description-text">${escapeHtml(task.description)}</p>
                        </div>
                    ` : ''}
                </div>
                
                <!-- Exercises Section -->
                <div class="exercises-section">
                    <div class="exercises-header">
                        <h3 class="section-heading">
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
                
                <!-- Grades Section -->
                <div class="grades-section">
                    <h3 class="section-heading">
                        <i class="fas fa-trophy"></i>
                        Grades & Credits
                    </h3>
                    <div class="grades-grid">
                        <div class="grade-item">
                            <div class="grade-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="grade-info">
                                <div class="grade-label">Credits Earned</div>
                                <div class="grade-value">${task.earned_credits || 0} / ${task.credit_hours || 0}</div>
                            </div>
                        </div>
                        <div class="grade-item">
                            <div class="grade-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="grade-info">
                                <div class="grade-label">Current Grade</div>
                                <div class="grade-value">${escapeHtml(task.grade || 'Not Graded')}</div>
                            </div>
                        </div>
                    </div>
                    ${task.remarks ? `
                        <div class="remarks-box">
                            <div class="grade-label">Instructor Remarks</div>
                            <p class="remarks-text">${escapeHtml(task.remarks)}</p>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        
        container.innerHTML = html;
    }

    // ============================================
    // RENDER EXERCISES
    // ============================================
    function renderExercises(exercises, taskId) {
        if (!exercises || exercises.length === 0) {
            return `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-clipboard"></i>
                    </div>
                    <h4>No Exercises</h4>
                    <p>No exercises have been added to this task yet</p>
                </div>
            `;
        }
        
        let html = '';
        exercises.forEach((exercise, index) => {
            const statusClass = (exercise.status || 'assigned').toLowerCase().replace(' ', '-');
            const isSubmitted = ['turned in', 'submitted', 'returned', 'reviewed'].includes(exercise.status.toLowerCase());
            const canResubmit = exercise.status.toLowerCase() === 'turned in';
            
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
                            <span class="status-badge ${statusClass}">${escapeHtml(exercise.status)}</span>
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </div>
                    </div>
                    
                    <div class="exercise-body">
                        <div class="exercise-content">
                            ${exercise.excercise_description ? `
                                <div class="exercise-section">
                                    <div class="exercise-section-title">Description</div>
                                    <p class="exercise-description">${escapeHtml(exercise.excercise_description)}</p>
                                </div>
                            ` : ''}
                            
                            ${exercise.excercise_attachment ? `
                                <div class="exercise-section">
                                    <div class="exercise-section-title">Attachment</div>
                                    <a href="excercises/${encodeURIComponent(exercise.excercise_attachment)}" 
                                       class="btn-download" download>
                                        <i class="fas fa-file-alt"></i>
                                        <span>${escapeHtml(exercise.excercise_attachment)}</span>
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            ` : ''}
                            
                            ${!isSubmitted || canResubmit ? `
                                <div class="exercise-section">
                                    <div class="exercise-section-title">${canResubmit ? 'Resubmit Your Work' : 'Submit Your Work'}</div>
                                    <form onsubmit="submitExercise(event, ${exercise.id}, ${taskId})" id="form-${exercise.id}">
                                        <div class="file-upload-area" 
                                             ondrop="handleDrop(event, ${exercise.id})"
                                             ondragover="handleDragOver(event)"
                                             ondragleave="handleDragLeave(event)">
                                            <input type="file" 
                                                   id="file-${exercise.id}" 
                                                   name="submitted_file"
                                                   hidden 
                                                   onchange="handleFileSelect(${exercise.id})"
                                                   accept="image/*,application/pdf,video/*,.zip,.rar,.7z,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                                            <label for="file-${exercise.id}" class="file-upload-label" id="label-${exercise.id}">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <span>Click to upload or drag & drop</span>
                                                <small>PDF, Images, Videos, Documents, ZIP (Max 50MB)</small>
                                            </label>
                                        </div>
                                        <textarea 
                                            class="comment-textarea" 
                                            id="comment-${exercise.id}" 
                                            placeholder="Add any comments (optional)..."
                                            style="margin-top: 12px;"
                                        ></textarea>
                                        <button type="submit" class="btn-submit ${canResubmit ? 'btn-resubmit' : ''}" style="margin-top: 12px;">
                                            <i class="fas fa-paper-plane"></i>
                                            ${canResubmit ? 'Resubmit' : 'Submit Exercise'}
                                        </button>
                                    </form>
                                </div>
                            ` : `
                                <div class="exercise-section">
                                    <div class="exercise-section-title">Submission Status</div>
                                    <p class="exercise-description">
                                        <i class="fas fa-check-circle" style="color: var(--success);"></i>
                                        You have submitted this exercise.
                                    </p>
                                </div>
                            `}
                            
                            <div class="exercise-meta">
                                <span class="exercise-meta-item">
                                    <i class="fas fa-calendar-plus"></i>
                                    Created: ${escapeHtml(exercise.created_at)}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        return html;
    }

    // ============================================
    // EXERCISE TOGGLE
    // ============================================
    function toggleExercise(exerciseId) {
        const item = document.querySelector(`.exercise-item[data-exercise-id="${exerciseId}"]`);
        if (item) {
            item.classList.toggle('expanded');
        }
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
            if (allExpanded) {
                btn.innerHTML = '<i class="fas fa-expand-alt"></i> Expand All';
            } else {
                btn.innerHTML = '<i class="fas fa-compress-alt"></i> Collapse All';
            }
        }
    }

    // ============================================
    // FILE HANDLING
    // ============================================
    function handleFileSelect(exerciseId) {
        const fileInput = document.getElementById(`file-${exerciseId}`);
        const label = document.getElementById(`label-${exerciseId}`);
        
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            label.innerHTML = `
                <div class="file-selected">
                    <i class="fas fa-check-circle"></i>
                    <span>${escapeHtml(file.name)} (${fileSize} MB)</span>
                </div>
            `;
        }
    }

    function handleDragOver(event) {
        event.preventDefault();
        event.currentTarget.classList.add('dragover');
    }

    function handleDragLeave(event) {
        event.currentTarget.classList.remove('dragover');
    }

    function handleDrop(event, exerciseId) {
        event.preventDefault();
        event.currentTarget.classList.remove('dragover');
        
        const fileInput = document.getElementById(`file-${exerciseId}`);
        if (event.dataTransfer.files.length > 0) {
            fileInput.files = event.dataTransfer.files;
            handleFileSelect(exerciseId);
        }
    }

    // ============================================
    // SUBMIT EXERCISE
    // ============================================
    function submitExercise(event, exerciseId, taskId) {
        event.preventDefault();
        
        const fileInput = document.getElementById(`file-${exerciseId}`);
        const commentBox = document.getElementById(`comment-${exerciseId}`);
        const submitBtn = event.target.querySelector('.btn-submit');
        
        if (!fileInput.files.length) {
            showToast('Please select a file to upload', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('excercise_id', exerciseId);
        formData.append('task_id', taskId);
        formData.append('student_id', APP_STATE.studentId);
        formData.append('submitted_file', fileInput.files[0]);
        formData.append('comments', commentBox.value);
        formData.append('submit_work', '1');
        
        // Disable button and show loading
        submitBtn.disabled = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        
        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showToast('Exercise submitted successfully!', 'success');
                // Refresh task details
                loadTaskDetails(APP_STATE.currentTaskId);
                // Refresh task list to update progress
                loadTasks();
            },
            error: function(xhr, status, error) {
                console.error('Submission error:', error);
                showToast('Failed to submit. Please try again.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    // ============================================
    // FILTER TASKS
    // ============================================
    function filterTasks() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        
        APP_STATE.filteredTasks = APP_STATE.tasks.filter(task => {
            const matchesSearch = !searchTerm || 
                task.title.toLowerCase().includes(searchTerm) ||
                (task.course_title && task.course_title.toLowerCase().includes(searchTerm));
            
            const matchesStatus = !statusFilter || 
                task.status.toLowerCase() === statusFilter;
            
            return matchesSearch && matchesStatus;
        });
        
        updateStats();
        renderTaskList();
    }

    // ============================================
    // HELPER FUNCTIONS
    // ============================================
    function refreshTaskDetails() {
        if (APP_STATE.currentTaskId) {
            loadTaskDetails(APP_STATE.currentTaskId);
        }
    }

    function calculateProgress(completed, total) {
        if (total === 0) return 0;
        return Math.round((completed / total) * 100);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function handleKeyboardNav(event) {
        // Escape to close expanded exercises
        if (event.key === 'Escape') {
            document.querySelectorAll('.exercise-item.expanded').forEach(item => {
                item.classList.remove('expanded');
            });
        }
    }

    // ============================================
    // LOADING & ERROR STATES
    // ============================================
    function showTaskListLoading() {
        document.getElementById('taskListContainer').innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
            </div>
        `;
    }

    function showTaskListError(message) {
        document.getElementById('taskListContainer').innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4>Error</h4>
                <p>${escapeHtml(message)}</p>
            </div>
        `;
    }

    function showDetailLoading() {
        document.getElementById('taskDetailContainer').innerHTML = `
            <div class="loading-spinner" style="min-height: 400px;">
                <div class="spinner"></div>
            </div>
        `;
    }

    function showDetailError(message) {
        document.getElementById('taskDetailContainer').innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4>Error</h4>
                <p>${escapeHtml(message)}</p>
            </div>
        `;
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
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }
    </script>
</body>
</html>