<?php
ob_start();
include("include/classes/session.php");

function urlEncoder($value) {
    return urlencode(base64_encode($value));
}

// Database connection
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
} 

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
    $data = $database->getVisitorsDetails($id);
    
    // Get category name
    if (!empty($data['category_id'])) {
        $q2 = "SELECT category FROM categories WHERE id = '{$data['category_id']}' LIMIT 1";
        $result2 = mysqli_query($conn, $q2);
        $catarray = mysqli_fetch_assoc($result2);
        $category = $catarray ? $catarray['category'] : "Not specified";
    } else {
        $category = "Not specified";
    }

    // Get course name
    if (!empty($data['course_id'])) {
        $q3 = "SELECT course_title FROM courses WHERE id = '{$data['course_id']}' LIMIT 1";
        $result3 = mysqli_query($conn, $q3);
        $crsarray = mysqli_fetch_assoc($result3);
        $course = $crsarray ? $crsarray['course_title'] : "Not specified";
    } else {
        $course = "Not specified";
    }
}
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
    <link href="assets/img/favicon-filenod.png" rel="icon">
    <title>Visitor Details - <?php echo htmlspecialchars($data['name']); ?></title>
    
    <style>
        :root {
            --primary: #173663;
            --primary-light: #1f4c8f;
            --primary-dark: #0f2444;
            --accent: #4A90E2;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
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
            --white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --radius-lg: 16px;
        }

        body {
            background-color: var(--gray-50);
            font-family: 'Circular Std', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .dashboard-main-wrapper {
            padding-top: 0;
        }

        /* Page Container */
        .page-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 24px;
        }

        .page-header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }

        .page-title-icon i {
            font-size: 20px;
            color: var(--white);
        }

        .page-title h1 {
            font-size: 24px;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }

        .page-title p {
            font-size: 14px;
            color: var(--gray-500);
            margin: 4px 0 0 0;
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .breadcrumb-nav a {
            color: var(--gray-500);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .breadcrumb-nav a:hover {
            color: var(--primary);
        }

        .breadcrumb-nav span {
            color: var(--gray-400);
        }

        .breadcrumb-nav .current {
            color: var(--gray-700);
            font-weight: 500;
        }

        /* Profile Header Card */
        .profile-header-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: var(--radius-lg);
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .profile-header-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .profile-header-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }

        .profile-header-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(255, 255, 255, 0.3);
            flex-shrink: 0;
        }

        .profile-avatar i {
            font-size: 40px;
            color: var(--white);
        }

        .profile-info {
            flex: 1;
            color: var(--white);
        }

        .profile-name {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
        }

        .profile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 12px;
        }

        .profile-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            opacity: 0.9;
        }

        .profile-meta-item i {
            font-size: 14px;
            opacity: 0.8;
        }

        .profile-actions {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
        }

        .btn-edit {
            background: rgba(255, 255, 255, 0.2);
            color: var(--white);
            backdrop-filter: blur(10px);
        }

        .btn-edit:hover {
            background: rgba(255, 255, 255, 0.3);
            color: var(--white);
            transform: translateY(-2px);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.9);
            color: var(--white);
        }

        .btn-delete:hover {
            background: var(--danger);
            color: var(--white);
            transform: translateY(-2px);
        }

        /* Status Badge */
        .visitor-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .visitor-badge i {
            font-size: 10px;
        }

        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Detail Card */
        .detail-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .detail-card:hover {
            box-shadow: var(--shadow-md);
        }

        .detail-card.full-width {
            grid-column: span 2;
        }

        @media (max-width: 768px) {
            .detail-card.full-width {
                grid-column: span 1;
            }
        }

        .detail-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .detail-card-icon {
            width: 40px;
            height: 40px;
            background: var(--gray-100);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .detail-card-icon i {
            font-size: 16px;
            color: var(--primary);
        }

        .detail-card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
        }

        .detail-card-body {
            padding: 24px;
        }

        /* Detail Items */
        .detail-items {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 15px;
            color: var(--gray-800);
            padding: 12px 16px;
            background: var(--gray-50);
            border-radius: 8px;
            border-left: 3px solid var(--primary);
        }

        .detail-value.empty {
            color: var(--gray-400);
            font-style: italic;
            border-left-color: var(--gray-300);
        }

        .detail-value.highlight {
            background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
            border-left-color: var(--accent);
        }

        /* Inline Detail Row */
        .detail-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        @media (max-width: 576px) {
            .detail-row {
                grid-template-columns: 1fr;
            }
        }

        /* Timestamp Card */
        .timestamp-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left-color: var(--success);
        }

        .timestamp-card .timestamp-icon {
            color: var(--success);
            font-size: 18px;
            margin-right: 8px;
        }

        /* Quick Actions Card */
        .quick-actions-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            padding: 24px;
            margin-top: 24px;
        }

        .quick-actions-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quick-actions-title i {
            color: var(--primary);
        }

        .quick-actions-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn-quick {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: 1px solid var(--gray-300);
            background: var(--white);
            color: var(--gray-700);
        }

        .btn-quick:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--gray-50);
        }

        .btn-quick.primary {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        .btn-quick.primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: var(--white);
        }

        .btn-quick.success {
            background: var(--success);
            color: var(--white);
            border-color: var(--success);
        }

        .btn-quick.success:hover {
            background: #059669;
            border-color: #059669;
            color: var(--white);
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: var(--white);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            padding: 20px 24px;
            border-bottom: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--gray-200);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-container {
                padding: 16px;
            }

            .profile-header-card {
                padding: 24px;
            }

            .profile-header-content {
                flex-direction: column;
                text-align: center;
            }

            .profile-meta {
                justify-content: center;
            }

            .profile-actions {
                width: 100%;
                justify-content: center;
            }

            .profile-name {
                font-size: 22px;
            }

            .page-header-content {
                flex-direction: column;
                align-items: flex-start;
            }
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
            
            <div class="page-container">
                
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-header-content">
                        <div class="page-title">
                            <div class="page-title-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <h1>Visitor Details</h1>
                                <p>Complete information about the visitor</p>
                            </div>
                        </div>
                        <div class="breadcrumb-nav">
                            <a href="dashboard.php"><i class="fas fa-home"></i></a>
                            <span>/</span>
                            <a href="numberofvisitors.php">Visitors</a>
                            <span>/</span>
                            <span class="current"><?php echo htmlspecialchars($data['name']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Profile Header Card -->
                <div class="profile-header-card">
                    <div class="profile-header-content">
                        <div class="profile-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="profile-info">
                            <div class="visitor-badge">
                                <i class="fas fa-circle"></i>
                                Visitor
                            </div>
                            <h2 class="profile-name"><?php echo htmlspecialchars($data['name']); ?></h2>
                            <div class="profile-meta">
                                <div class="profile-meta-item">
                                    <i class="fas fa-phone"></i>
                                    <?php echo htmlspecialchars($data['mobile_no']); ?>
                                </div>
                                <div class="profile-meta-item">
                                    <i class="fas fa-envelope"></i>
                                    <?php echo htmlspecialchars($data['email']); ?>
                                </div>
                                <div class="profile-meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    Visited: <?php echo date("d M Y", strtotime($data['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="profile-actions">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editVisitorModal" class="btn-action btn-edit">
                                <i class="fas fa-edit"></i>
                                Edit
                            </a>
                            <a href="delete-visitor.php?id=<?php echo $data['id']; ?>"
                               onclick="return confirm('Are you sure you want to delete this visitor record?');"
                               class="btn-action btn-delete">
                                <i class="fas fa-trash-alt"></i>
                                Delete
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="details-grid">
                    
                    <!-- Personal Information -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <div class="detail-card-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <h3 class="detail-card-title">Personal Information</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="detail-items">
                                <div class="detail-item">
                                    <span class="detail-label">Full Name</span>
                                    <div class="detail-value"><?php echo htmlspecialchars($data['name']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Gender</span>
                                    <div class="detail-value"><?php echo ucfirst(htmlspecialchars($data['gender'])); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <div class="detail-card-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <h3 class="detail-card-title">Contact Information</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="detail-items">
                                <div class="detail-item">
                                    <span class="detail-label">Mobile Number</span>
                                    <div class="detail-value"><?php echo htmlspecialchars($data['mobile_no']); ?></div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Email Address</span>
                                    <div class="detail-value"><?php echo htmlspecialchars($data['email']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Interest -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <div class="detail-card-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h3 class="detail-card-title">Course Interest</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="detail-items">
                                <div class="detail-item">
                                    <span class="detail-label">Category</span>
                                    <div class="detail-value <?php echo ($category == 'Not specified') ? 'empty' : 'highlight'; ?>">
                                        <?php echo htmlspecialchars($category); ?>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Course</span>
                                    <div class="detail-value <?php echo ($course == 'Not specified') ? 'empty' : 'highlight'; ?>">
                                        <?php echo htmlspecialchars($course); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Education Information -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <div class="detail-card-icon">
                                <i class="fas fa-school"></i>
                            </div>
                            <h3 class="detail-card-title">Education Information</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="detail-items">
                                <div class="detail-item">
                                    <span class="detail-label">Current Grade</span>
                                    <div class="detail-value <?php echo empty($data['current_grade']) ? 'empty' : ''; ?>">
                                        <?php echo !empty($data['current_grade']) ? htmlspecialchars($data['current_grade']) : 'Not specified'; ?>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Institute</span>
                                    <div class="detail-value <?php echo empty($data['institute']) ? 'empty' : ''; ?>">
                                        <?php echo !empty($data['institute']) ? htmlspecialchars($data['institute']) : 'Not specified'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address & Visit Info (Full Width) -->
                    <div class="detail-card full-width">
                        <div class="detail-card-header">
                            <div class="detail-card-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 class="detail-card-title">Address & Visit Information</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="detail-items">
                                <div class="detail-item">
                                    <span class="detail-label">Complete Address</span>
                                    <div class="detail-value <?php echo empty($data['address']) ? 'empty' : ''; ?>">
                                        <?php echo !empty($data['address']) ? htmlspecialchars($data['address']) : 'Not specified'; ?>
                                    </div>
                                </div>
                                <div class="detail-row" style="margin-top: 16px;">
                                    <div class="detail-item">
                                        <span class="detail-label">Visit Date & Time</span>
                                        <div class="detail-value timestamp-card">
                                            <i class="fas fa-clock timestamp-icon"></i>
                                            <?php echo date("l, d F Y \a\\t h:i A", strtotime($data['created_at'])); ?>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Quick Actions -->
                <div class="quick-actions-card">
                    <div class="quick-actions-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </div>
                    <div class="quick-actions-buttons">
                        <a href="add-student.php?visitor_id=<?php echo $data['id']; ?>" class="btn-quick success">
                            <i class="fas fa-user-graduate"></i>
                            Convert to Student
                        </a>
                        <a href="tel:<?php echo htmlspecialchars($data['mobile_no']); ?>" class="btn-quick primary">
                            <i class="fas fa-phone"></i>
                            Call Visitor
                        </a>
                        <a href="mailto:<?php echo htmlspecialchars($data['email']); ?>" class="btn-quick">
                            <i class="fas fa-envelope"></i>
                            Send Email
                        </a>
                        <a href="numberofvisitors.php" class="btn-quick">
                            <i class="fas fa-arrow-left"></i>
                            Back to List
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Edit Visitor Modal -->
<div class="modal fade" id="editVisitorModal" tabindex="-1" aria-labelledby="editVisitorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVisitorModalLabel">
                    <i class="fas fa-edit"></i>
                    Edit Visitor Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="process.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="visitor_id" value="<?php echo $data['id']; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($data['name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="male" <?php echo ($data['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo ($data['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo ($data['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_no" class="form-control" 
                                   value="<?php echo htmlspecialchars($data['mobile_no']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($data['email']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Current Grade</label>
                            <input type="text" name="current_grade" class="form-control" 
                                   value="<?php echo htmlspecialchars($data['current_grade'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Institute</label>
                            <input type="text" name="institute" class="form-control" 
                                   value="<?php echo htmlspecialchars($data['institute'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($data['address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" name="update_visitor" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php } else { header("Location: index.php?not_logged_in"); exit(); } ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="assetss/vendor/slimscroll/jquery.slimscroll.js"></script>
<script src="assetss/libs/js/main-js.js"></script>

</body>
</html>