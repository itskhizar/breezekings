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
$institute = ($result2['institute']);
$address = ($result2['address']);
$category_id = ($result2['category_id']);
$course_id = ($result2['course_id']);
$session_id = ($result2['session_id']);
$guardian_name = ($result2['guardian_name']);
$guardian_relation = ($result2['guardian_relation']);
$guardian_phone = ($result2['guardian_phone']);
$registration_date = ($result2['registration_date']);
$months = ($result2['months']);
$fee_status = ($result2['fee_status']);
$student_status = ($result2['status']);

        if ($fee_status == "Unpaid") {
            $badge = "<span class='badge badge-danger'>Unpaid</span>";
        } 
        elseif ($fee_status == "Paid") {
            $badge = "<span class='badge badge-success'>Paid</span>";
        } 
        else {
            $badge = "<span class='badge badge-secondary'>" . htmlspecialchars($fee_status) . "</span>";
        }

        if ($student_status == "Active") {
            $studentbadge = "<span class='badge badge-success'>Active</span>";
        } 
        elseif ($student_status == "Inactive") {
            $studentbadge = "<span class='badge badge-danger'>Inactive</span>";
        } 
        else {
            $studentbadge = "<span class='badge badge-secondary'>" . htmlspecialchars($student_status) . "</span>";
        }


$result4 = $database->getcoursebyid($course_id);
$course_title = ($result4['course_title']);
$duration = ($result4['duration']);


$result5 = $database->getcategorybyid($category_id);
$category = ($result5['category']);

$result6 = $database->getsessionyid($session_id);
if ($result6) {
	$session_name = $result6['title'];
$start_time = $result6['start_time'];
$end_time = $result6['end_time'];
}else{
	$session_name = "";
$start_time = "";
$end_time = "";
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
    <link rel="stylesheet" href="assetss/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="assetss/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assetss/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <title>Student Profile - <?php echo htmlspecialchars($name); ?></title>
    
    <!-- Include html2pdf library for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <style>
        /* Main Layout Styles */
        .dashboard-content {
            padding: 20px;
            background: #f5f7fa;
        }
        
        .profile-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.06);
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .profile-sidebar {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.06);
            padding: 25px 20px;
            text-align: center;
            position: sticky;
            top: 80px;
        }
        
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 15px;
            border: 4px solid #173663;
            object-fit: cover;
            display: block;
        }
        
        .student-name {
            font-size: 20px;
            font-weight: 700;
            color: #173663;
            margin-bottom: 5px;
        }
        
        .student-id {
            color: #666;
            font-size: 13px;
            margin-bottom: 15px;
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #173663;
            font-size: 12px;
        }
        
        .info-value {
            color: #17a2b8;
            font-weight: 600;
            font-size: 12px;
            text-align: right;
        }
        
        .qr-section, .score-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .score-section h2 {
            color: #17a2b8;
            font-size: 32px;
            font-weight: 700;
            margin: 8px 0 0 0;
        }
        
        .action-btn {
            width: 100%;
            margin-bottom: 8px;
            border-radius: 6px;
            padding: 10px;
            font-weight: 600;
            font-size: 13px;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 25px;
        }
        
        .nav-tabs .nav-link {
            color: #666;
            border: none;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .nav-tabs .nav-link:hover {
            color: #173663;
            border-bottom: 3px solid #e9ecef;
        }
        
        .nav-tabs .nav-link.active {
            color: #173663;
            background: transparent;
            border-bottom: 3px solid #173663;
        }
        
        .section-header {
            color: #173663;
            border-bottom: 3px solid #173663;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 17px;
        }
        
        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: 600;
            color: #173663;
            min-width: 180px;
            font-size: 13px;
        }
        
        .detail-value {
            color: #555;
            font-size: 13px;
            flex: 1;
        }
        
        /* Fee Table Styles */
        .fee-table-wrapper {
            overflow-x: auto;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 100%;
            background: white;
        }
        
        .fee-table th {
            background: #173663;
            color: white;
            padding: 12px 10px;
            font-weight: 600;
            border: 1px solid #173663;
            font-size: 12px;
            white-space: nowrap;
            text-align: left;
        }
        
        .fee-table td {
            padding: 12px 10px;
            border: 1px solid #dee2e6;
            font-size: 12px;
        }
        
        .fee-table tfoot td {
            background: #f8f9fa;
            font-weight: 700;
            color: #173663;
            font-size: 13px;
        }
        fade
        .badge-paid {
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
        }
        
        .badge-unpaid {
            background: #dc3545;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
        }
        
        .badge-partial {
            background: #ffc107;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
        }
        
        /* Attendance Styles */
        .attendance-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-box {
            background: white;
            padding: 18px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .stat-box.present { border-left-color: #28a745; }
        .stat-box.late { border-left-color: #ffc107; }
        .stat-box.absent { border-left-color: #dc3545; }
        .stat-box.halfday { border-left-color: #17a2b8; }
        .stat-box.holiday { border-left-color: #6c757d; }
        
        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #173663;
            margin: 8px 0;
        }
        
        .stat-label {
            color: #666;
            font-size: 12px;
            font-weight: 600;
        }
        
        /* Print Challan Styles */
        .challan-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 9999;
            overflow-y: auto;
            padding: 20px 0;
        }
        
        .challan-content {
            background: white;
            max-width: 900px;
            margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .challan-header {
            background: linear-gradient(135deg, #173663 0%, #2d5a8c 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .challan-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.1); }
        }
        
        .challan-logo {
            font-size: 56px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .challan-title {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            letter-spacing: 1px;
            color: #fff;
        }
        
        .challan-subtitle {
            font-size: 15px;
            margin: 8px 0 0 0;
            opacity: 0.95;
            letter-spacing: 0.5px;
        }
        
        .challan-body {
            padding: 35px;
        }
        
        .challan-section {
            margin-bottom: 30px;
        }
        
        .challan-section-title {
            color: #173663;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 3px solid #173663;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .challan-section-title i {
            font-size: 22px;
        }
        
        .challan-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .challan-info-item {
            display: flex;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 3px solid #173663;
        }
        
        .challan-info-label {
            font-weight: 600;
            color: #173663;
            font-size: 13px;
            min-width: 140px;
        }
        
        .challan-info-value {
            color: #333;
            font-size: 13px;
            font-weight: 500;
        }
        
        .challan-fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .challan-fee-table th,
        .challan-fee-table td {
            padding: 14px 12px;
            text-align: left;
            border: 1px solid #dee2e6;
            font-size: 13px;
        }
        
        .challan-fee-table th {
            background: #173663;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        .challan-fee-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .challan-fee-table tfoot td {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-weight: 700;
            color: #173663;
            font-size: 14px;
        }
        
        .challan-footer {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px 35px;
            border-top: 3px solid #173663;
        }
        
        .challan-notes {
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }
        
        .challan-notes h6 {
            color: #173663;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .challan-notes ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .challan-notes li {
            margin-bottom: 5px;
        }
        
        .challan-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 25px;
            flex-wrap: wrap;
        }
        
        .challan-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .challan-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }
        
        .challan-btn-primary {
            background: #173663;
            color: white;
        }
        
        .challan-btn-primary:hover {
            background: #0f2444;
        }
        
        .challan-btn-success {
            background: #28a745;
            color: white;
        }
        
        .challan-btn-success:hover {
            background: #218838;
        }
        
        .challan-btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .challan-btn-secondary:hover {
            background: #5a6268;
        }
        
        .challan-btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .challan-btn-danger:hover {
            background: #c82333;
        }
        
        /* Print-specific styles */
        @media print {
            body * {
                visibility: hidden;
            }
            
            .challan-content,
            .challan-content * {
                visibility: visible;
            }
            
            .challan-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                box-shadow: none;
                border-radius: 0;
            }
            
            .challan-actions,
            .no-print {
                display: none !important;
            }
            
            .challan-modal {
                display: block !important;
                position: static;
                background: white;
            }
            
            .challan-header {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .challan-fee-table th {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            @page {
                margin: 0.5cm;
                size: A4 portrait;
            }
        }
        
        @media (max-width: 768px) {
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label {
                min-width: 100%;
                margin-bottom: 5px;
            }
            
            .challan-info-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-sidebar {
                position: relative !important;
                top: 0;
            }
            
            .challan-body {
                padding: 20px;
            }
            
            .challan-actions {
                flex-direction: column;
            }
            
            .challan-btn {
                width: 100%;
                justify-content: center;
            }
            
            .fee-table-wrapper {
                margin-left: -25px;
                margin-right: -25px;
                border-radius: 0;
            }
        }
        
        @media (max-width: 576px) {
            .challan-info-item {
                flex-direction: column;
                gap: 5px;
            }
            
            .challan-info-label {
                min-width: 100%;
            }
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
                    <!-- Left Sidebar -->
                    <div class="col-lg-3 col-md-4 mb-4">
                        <div class="profile-sidebar">
                            <img src="images/avatar.png" alt="Student" class="profile-image">
                            
                            <h3 class="student-name"><?php echo strtoupper(htmlspecialchars($name)); ?></h3>
                            <p class="student-id">
                                <strong>Admission No:</strong> <?php echo htmlspecialchars($admission_no); ?>
                            </p>
                            
                            <div style="border-top: 2px solid #e9ecef; padding-top: 15px; margin-top: 15px;">
                                <div class="info-item">
                                    <span class="info-label">Category</span>
                                    <span class="info-value"><?php echo htmlspecialchars($category); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Course</span>
                                    <span class="info-value"><?php echo htmlspecialchars($course_title); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Session</span>
                                    <span class="info-value">
                                        <?php echo htmlspecialchars($session_name); ?> 
                                        (<?php echo $start_time; ?> - <?php echo $end_time; ?>)
                                    </span>
                                </div>


                               
                                <div class="info-item">
                                    <span class="info-label">Joining Date</span>
                                    <span class="info-value"><?php
										echo date("d M Y", strtotime($registration_date));
										?>
										</span>
                                </div>
                            </div>
                            
                            <br>
                            <!-- Action Buttons -->
                            <div class="no-print">
                                <button class="btn action-btn" style="background: #173663; color: white;">
                                    <i class="fas fa-edit"></i> Edit Profile
                                </button>
                                <!-- <button class="btn action-btn btn-success" onclick="printStudentProfile()">
                                    <i class="fas fa-print"></i> Print Profile
                                </button> -->
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="col-lg-9 col-md-8">
                        
                        <!-- Tabs -->
                        <ul class="nav nav-tabs no-print" id="profileTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="fees-tab" data-toggle="tab" href="#fees">
                                    <i class="fas fa-dollar-sign"></i> Fees
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="attendance-tab" data-toggle="tab" href="#attendance">
                                    <i class="fas fa-calendar-check"></i> Attendance
                                </a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            
                            <!-- Profile Tab -->
                            <div class="tab-pane  show active" id="profile">
                                
                                <!-- Student Information -->
                                <div class="profile-card">
							    <h5 class="section-header">
							        <i class="fas fa-user-circle"></i> Student Information
							    </h5>
							    <div class="row">
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Admission No</span>
							                <span class="detail-value"><?php echo htmlspecialchars($admission_no); ?></span>
							            </div>
							        </div>
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Registration No</span>
							                <span class="detail-value"><?php echo htmlspecialchars($registration_no); ?></span>
							            </div>
							        </div>
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Name</span>
							                <span class="detail-value"><?php echo htmlspecialchars($name); ?></span>
							            </div>
							        </div>
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Gender</span>
							                <span class="detail-value"><?php echo htmlspecialchars($gender); ?></span>
							            </div>
							        </div>
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Date of Birth</span>
							                <span class="detail-value"><?php echo htmlspecialchars($dob); ?></span>
							            </div>
							        </div>
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Mobile No</span>
							                <span class="detail-value"><?php echo htmlspecialchars($mobile_no); ?></span>
							            </div>
							        </div>
                                    <div class="col-md-6">
                                        <div class="detail-row">
                                            <span class="detail-label">CNIC</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($cnic); ?></span>
                                        </div>
                                    </div>
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Email</span>
							                <span class="detail-value"><?php echo htmlspecialchars($student_email); ?></span>
							            </div>
							        </div>
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Current Grade</span>
							                <span class="detail-value"><?php echo htmlspecialchars($current_grade); ?></span>
							            </div>
							        </div>
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Institute</span>
							                <span class="detail-value"><?php echo htmlspecialchars($institute); ?></span>
							            </div>
							        </div>
							        <div class="col-md-12">
							            <div class="detail-row">
							                <span class="detail-label">Address</span>
							                <span class="detail-value"><?php echo htmlspecialchars($address); ?></span>
							            </div>
							        </div>
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Category</span>
							                <span class="detail-value"><?php echo htmlspecialchars($category); ?></span>
							            </div>
							        </div>
							        <div class="col-md-6">
							            <div class="detail-row">
							                <span class="detail-label">Course</span>
							                <span class="detail-value"><?php echo htmlspecialchars($course_title); ?></span>
							            </div>
							        </div>
							    </div>
							</div>


                                <!-- Guardian Details -->
                                <div class="profile-card">
                                    <h5 class="section-header">
                                        <i class="fas fa-users"></i> Parent Guardian Detail
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="detail-row">
                                                <span class="detail-label">Guardian Name</span>
                                                <span class="detail-value"><?php echo htmlspecialchars($guardian_name); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-row">
                                                <span class="detail-label">Guardian Relation</span>
                                                <span class="detail-value"><?php echo htmlspecialchars($guardian_relation); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-row">
                                                <span class="detail-label">Guardian Phone</span>
                                                <span class="detail-value"><?php echo htmlspecialchars($guardian_phone); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <!-- Fees Tab -->
                            <div class="tab-pane " id="fees">
                                <div class="profile-card">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                                        <h5 class="section-header" style="margin-bottom: 0; border: none; padding: 0;">
                                            <i class="fas fa-file-invoice-dollar"></i> Fee Details
                                        </h5>
                                        <button class="btn btn-success no-print" onclick="openChallanModal()" style="display: inline-flex; align-items: center; gap: 8px;">
                                            <i class="fas fa-print"></i> Print All Fees
                                        </button>
                                    </div>
                                    
                                    <div class="fee-table-wrapper">
                                        <table class="fee-table">
                                            <thead>
                                                <tr>
                                                    <th>Fees</th>
                                                    <th>Amount ($)</th>
                                                    <th>Discount ($)</th>
                                                    <th>Fine ($)</th>
                                                    <th>Due Date</th>
                                                    <th>Status</th>
                                                    <th>Balance ($)</th>
                                                    <th class="no-print">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $fees = $database->getfeesbycourseid($course_id);

                                                // Initialize totals
                                                $total_amount = 0;
                                                $total_discount = 0;
                                                $total_fine = 0;
                                                $total_paid = 0;
                                                $total_balance = 0;

                                                if (!empty($fees)) {
                                                    foreach ($fees as $index => $row) {
                                                        $type        = $row['type'];
                                                        $monthly     = floatval($row['monthly']);
                                                        $amount      = floatval($row['amount']);
                                                        $month       = $row['month'];
                                                        $due_date    = $row['due_date'];
                                                        $description = $row['description'];

                                                        $discount = 0;
                                                        $fine = 0;
                                                        $paid = 0;
                                                        $balance = $monthly - $paid + $fine - $discount;

                                                        // Add to totals
                                                        $total_amount += $monthly;
                                                        $total_discount += $discount;
                                                        $total_fine += $fine;
                                                        $total_paid += $paid;
                                                        $total_balance += $balance;

                                                        // Format date
        $due_date_formatted = !empty($due_date) ? date('d-M-Y', strtotime($due_date)) : "-";

        // Get student-specific fee status from student_fees table
       $student_fee_res = $database->query(
            "SELECT status, paid_amount FROM student_fees WHERE student_id = '$student_id' AND fee_id = '{$row['id']}'"
        );

$student_fee_row = mysqli_fetch_assoc($student_fee_res);

$student_fee_status = $student_fee_row['status'] ?? 'Unpaid';
$paid_amount = $student_fee_row['paid_amount'] ?? 0;


        // Determine badge class
        $badge_class = 'secondary';
        if ($student_fee_status == 'Paid') $badge_class = 'success';
        elseif ($student_fee_status == 'Unpaid') $badge_class = 'danger';
                                                        
                                                        // Create JSON data for this fee
                                                        $feeData = json_encode([
                                                            'type' => $type,
                                                            'monthly' => $monthly,
                                                            'discount' => $discount,
                                                            'fine' => $fine,
                                                            'balance' => $balance,
                                                            'due_date' => $due_date_formatted
                                                        ]);

                                                        echo "<tr>
                                                                <td>{$type}</td>
                                                                <td>" . number_format($monthly,2) . "</td>
                                                                <td>" . number_format($discount,2) . "</td>
                                                                <td>" . number_format($fine,2) . "</td>
                                                                <td>{$due_date_formatted}</td>
                                                                <td><span class=''>".$badge."</span></td>
                                                                <td>" . number_format($balance,2) . "</td>
                                                                <td class='no-print'>
                                                                    <button class='btn btn-sm btn-primary' onclick='printIndividualChallan(" . htmlspecialchars($feeData, ENT_QUOTES) . ")' 
                                                                            style='padding: 5px 10px; font-size: 12px;'>
                                                                        <i class='fas fa-print'></i> Print
                                                                    </button>
                                                                </td>
                                                              </tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='8'>No Fees Found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan='1' style='text-align: right; font-weight:bold;'>TOTAL:</td>
                                                    <td><?php echo number_format($total_amount,2); ?></td>
                                                    <td><?php echo number_format($total_discount,2); ?></td>
                                                    <td><?php echo number_format($total_fine,2); ?></td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td><?php echo number_format($total_balance,2); ?></td>
                                                    <td class="no-print">-</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Individual Fee Challan Modal -->
                            <div id="individualChallanModal" class="challan-modal" style="display: none;">
                                <div class="challan-content" id="individualChallanPrintArea" style="max-width: 900px;">
                                    <!-- This will be populated by JavaScript -->
                                </div>
                            </div>

                            <style>
                            @media print {
                                .no-print {
                                    display: none !important;
                                }
                                
                                .challan-copy {
                                    page-break-after: always;
                                }
                                
                                .challan-copy:last-child {
                                    page-break-after: auto;
                                }
                                
                                body * {
                                    visibility: hidden;
                                }
                                
                                #individualChallanPrintArea,
                                #individualChallanPrintArea * {
                                    visibility: visible;
                                }
                                
                                #individualChallanPrintArea {
                                    position: absolute;
                                    left: 0;
                                    top: 0;
                                    width: 100%;
                                }
                            }

                            .challan-modal {
                                display: none;
                                position: fixed;
                                z-index: 9999;
                                left: 0;
                                top: 0;
                                width: 100%;
                                height: 100%;
                                overflow: auto;
                                background-color: rgba(0,0,0,0.5);
                            }

                            .challan-copy {
                                background: white;
                                padding: 30px;
                                /*margin-bottom: 30px;*/
                                border: 2px solid #333;
                            }

                            .challan-header-simple {
                                text-align: center;
                                border-bottom: 2px solid #333;
                                padding-bottom: 15px;
                                /*margin-bottom: 20px;*/
                            }

                            .challan-logo-simple {
                                margin-bottom: 10px;
                            }

                            .challan-logo-simple img {
                                width: 120px;
                                height: auto;
                            }

                            .challan-title-simple {
                                font-size: 24px;
                                font-weight: bold;
                                margin: 10px 0;
                                text-transform: uppercase;
                            }

                            .challan-contact-simple {
                                font-size: 11px;
                                color: #555;
                                line-height: 1.6;
                            }

                            .copy-label {
                                background: #f0f0f0;
                                padding: 8px 15px;
                                text-align: center;
                                font-weight: bold;
                                font-size: 14px;
                                border: 1px solid #333;
                                /*margin-bottom: 15px;*/
                            }

                            .challan-info-row {
                                display: flex;
                                justify-content: space-between;
                                padding: 8px 0;
                                border-bottom: 1px solid #ddd;
                            }

                            .challan-info-label-simple {
                                font-weight: bold;
                                width: 40%;
                            }

                            .challan-info-value-simple {
                                width: 60%;
                                text-align: right;
                            }

                            .challan-table-simple {
                                width: 100%;
                                border-collapse: collapse;
                                margin: 10px 0;
                            }

                            .challan-table-simple th,
                            .challan-table-simple td {
                                border: 1px solid #333;
                                padding: 10px;
                                text-align: left;
                            }

                            .challan-table-simple th {
                                background: #f5f5f5;
                                font-weight: bold;
                            }

                            .challan-table-simple tfoot td {
                                font-weight: bold;
                                background: #f9f9f9;
                            }

                            .challan-notes-simple {
                                margin-top: 20px;
                                font-size: 11px;
                                line-height: 1.6;
                            }

                            .challan-notes-simple ul {
                                margin: 5px 0;
                                padding-left: 20px;
                            }

                            .challan-footer-simple {
                                margin-top: 30px;
                                padding-top: 15px;
                                border-top: 1px solid #333;
                                text-align: center;
                                font-size: 10px;
                            }

                            .challan-actions-simple {
                                text-align: center;
                                margin-top: 20px;
                                padding: 20px;
                                background: #f5f5f5;
                            }

                            .challan-btn-simple {
                                padding: 10px 20px;
                                margin: 0 5px;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                                font-size: 14px;
                            }

                            .challan-btn-print {
                                background: #007bff;
                                color: white;
                            }

                            .challan-btn-close {
                                background: #6c757d;
                                color: white;
                            }
                            </style>

                           <?php
// Add this PHP code at the top of your student profile page (after getting $student_id and $session_id)

// Fetch attendance statistics
$attendance_stats_query = "SELECT 
    COUNT(CASE WHEN attendance = 'Present' THEN 1 END) as total_present,
    COUNT(CASE WHEN attendance = 'Late' THEN 1 END) as total_late,
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
$total_late = $stats['total_late'] ?? 0;
$total_absent = $stats['total_absent'] ?? 0;
$total_halfday = $stats['total_halfday'] ?? 0;
$total_holiday = $stats['total_holiday'] ?? 0;
$total_days = $stats['total_days'] ?? 0;

// Calculate attendance percentage
$attendance_percentage = $total_days > 0 ? round(($total_present + $total_late) / $total_days * 100, 2) : 0;

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

<!-- Attendance Tab HTML -->
<div class="tab-pane fade" id="attendance">
    <div class="profile-card">
        <h5 class="section-header">
            <i class="fas fa-chart-bar"></i> Attendance Statistics
        </h5>
        
        <!-- Attendance Stats -->
        <div class="attendance-stats">
            <div class="stat-box present">
                <div class="stat-label">Present</div>
                <div class="stat-number"><?php echo $total_present; ?></div>
            </div>
            <div class="stat-box late">
                <div class="stat-label">Late</div>
                <div class="stat-number"><?php echo $total_late; ?></div>
            </div>
            <div class="stat-box absent">
                <div class="stat-label">Absent</div>
                <div class="stat-number"><?php echo $total_absent; ?></div>
            </div>
            <div class="stat-box halfday">
                <div class="stat-label">Half Day</div>
                <div class="stat-number"><?php echo $total_halfday; ?></div>
            </div>
            <div class="stat-box holiday">
                <div class="stat-label">Holidays</div>
                <div class="stat-number"><?php echo $total_holiday; ?></div>
            </div>
        </div>

        <!-- Attendance Percentage -->
        <div class="attendance-percentage mb-4">
            <h6>Overall Attendance: <strong class="text-success"><?php echo $attendance_percentage; ?>%</strong></h6>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-success" role="progressbar" 
                     style="width: <?php echo $attendance_percentage; ?>%" 
                     aria-valuenow="<?php echo $attendance_percentage; ?>" 
                     aria-valuemin="0" aria-valuemax="100">
                    <?php echo $attendance_percentage; ?>%
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="legend mb-4">
            <div class="legend-item">
                <div class="legend-box" style="background: #28a745;"></div>
                <span>P - Present</span>
            </div>
            <div class="legend-item">
                <div class="legend-box" style="background: #ffc107;"></div>
                <span>L - Late</span>
            </div>
            <div class="legend-item">
                <div class="legend-box" style="background: #dc3545;"></div>
                <span>A - Absent</span>
            </div>
            <div class="legend-item">
                <div class="legend-box" style="background: #6c757d;"></div>
                <span>H - Holiday</span>
            </div>
            <div class="legend-item">
                <div class="legend-box" style="background: #ff8c00;"></div>
                <span>F - Half Day</span>
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
            
            <div class="text-center mt-3">
                <a href="view-attendance-student.php" class="btn btn-info">
                    <i class="fas fa-calendar-check"></i> View Full Calendar
                </a>
            </div>
        </div>

        <!-- Recent Attendance Table -->
        <div class="recent-attendance mt-4">
            <h5 class="section-header">
                <i class="fas fa-history"></i> Recent Attendance (Last 10 Days)
            </h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Entry Time</th>
                            <th>Exit Time</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent_query = "SELECT 
                            DATE(created_at) as attendance_date,
                            attendance,
                            entry_time,
                            exit_time,
                            note
                        FROM student_attendance 
                        WHERE student_id = '$student_id' 
                        AND session_id = '$session_id'
                        ORDER BY created_at DESC 
                        LIMIT 10";
                        
                        $recent_result = mysqli_query($conn, $recent_query);
                        
                        if (mysqli_num_rows($recent_result) > 0) {
                            while ($attendance = mysqli_fetch_assoc($recent_result)) {
                                $status_class = '';
                                $status_icon = '';
                                
                                switch ($attendance['attendance']) {
                                    case 'Present':
                                        $status_class = 'badge-success';
                                        $status_icon = 'fa-check-circle';
                                        break;
                                    case 'Late':
                                        $status_class = 'badge-warning';
                                        $status_icon = 'fa-clock';
                                        break;
                                    case 'Absent':
                                        $status_class = 'badge-danger';
                                        $status_icon = 'fa-times-circle';
                                        break;
                                    case 'Holiday':
                                        $status_class = 'badge-secondary';
                                        $status_icon = 'fa-calendar';
                                        break;
                                    case 'Half Day':
                                        $status_class = 'badge-info';
                                        $status_icon = 'fa-adjust';
                                        break;
                                }
                        ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($attendance['attendance_date'])); ?></td>
                            <td>
                                <span class="badge <?php echo $status_class; ?>">
                                    <i class="fas <?php echo $status_icon; ?>"></i>
                                    <?php echo $attendance['attendance']; ?>
                                </span>
                            </td>
                            <td><?php echo $attendance['entry_time'] ? date('h:i A', strtotime($attendance['entry_time'])) : '-'; ?></td>
                            <td><?php echo $attendance['exit_time'] ? date('h:i A', strtotime($attendance['exit_time'])) : '-'; ?></td>
                            <td><?php echo htmlspecialchars($attendance['note']) ?: '-'; ?></td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center">No attendance records found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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

.calendar-day.late {
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


                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Challan Modal -->
    <div id="challanModal" class="challan-modal">
        <div class="challan-content" id="challanPrintArea">
            <div class="challan-header">
     <div class="challan-logo">
        <img src="images/favicon-filenod.webp" alt="Filenod Academy Logo">
    </div>
<!-- 🎓 -->
    <div class="challan-title-wrapper">
        <h1 class="challan-title">Filenod Academy</h1>
        <p class="challan-subtitle">FEE CHALLAN</p>

        <p class="challan-contact">
            <span><i class="fas fa-map-marker-alt"></i>   Filenod office opposite UBL Mandian, Abbottabad, 22044</span><br>
            <span><i class="fas fa-envelope"></i> info@filenod.com</span>
        </p>
    </div>
</div>

            
            <div class="challan-body">
                <!-- Student Information Section -->
                <div class="challan-section">
                    <h5 class="challan-section-title">
                        <i class="fas fa-user-graduate"></i> Student Information
                    </h5>
                    <div class="challan-info-grid">
                        <div class="challan-info-item">
                            <span class="challan-info-label">Admission No:</span>
                            <span class="challan-info-value"><?php echo htmlspecialchars($admission_no); ?></span>
                        </div>
                        <div class="challan-info-item">
                            <span class="challan-info-label">Student Name:</span>
                            <span class="challan-info-value"><?php echo htmlspecialchars($name); ?></span>
                        </div>
                        <div class="challan-info-item">
                            <span class="challan-info-label">Category:</span>
                            <span class="challan-info-value"><?php echo htmlspecialchars($category); ?></span>
                        </div>
                        <div class="challan-info-item">
                            <span class="challan-info-label">Course:</span>
                            <span class="challan-info-value"><?php echo htmlspecialchars($course_title); ?></span>
                        </div>
                        <div class="challan-info-item">
                            <span class="challan-info-label">Session:</span>
                            <span class="info-value">
                                        <?php echo htmlspecialchars($session_name); ?> 
                                        (<?php echo $start_time; ?> - <?php echo $end_time; ?>)
                                    </span>
                        </div>


                        <div class="challan-info-item">
                            <span class="challan-info-label">Registration Date:</span>
                            <span class="challan-info-value"><?php echo htmlspecialchars($registration_date); ?></span>
                        </div>
                        <div class="challan-info-item">
						    <span class="challan-info-label">Challan Date:</span>
						    <span class="challan-info-value">
						        <?php echo date('d M Y'); ?>
						    </span>
						</div>

                    </div>
                </div>

                

                <!-- Fee Details Section -->
                <div class="challan-section">
                    <h5 class="challan-section-title">
                        <i class="fas fa-dollar-sign"></i> Fee Details
                    </h5>
                    <table class="challan-fee-table">
                        <thead>
                            <tr>
                                <th>Fee Type</th>
                                <th>Due Date</th>
                                <th>Registration Fee</th>
                                <th>Monthly Fee</th>
                                <th>Amount 2 Moths ($)</th>
                                <th>Discount ($)</th>
                                <th>Fine ($)</th>
                                <th>Total ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Course Fee</td>
                                <td>20/11/2025</td>
                                <td><?php echo number_format($registration_fee, 2); ?></td>
                                <td><?php echo number_format($monthly_fee, 2); ?></td>
                                <td><?php echo number_format($course_fee, 2); ?></td>
                                <td><?php echo number_format($discount, 2); ?></td>
                                <td><?php echo number_format($fine, 2); ?></td>
                                <td><?php echo number_format($balance, 2); ?></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" style="text-align: right; font-size: 16px;">TOTAL PAYABLE:</td>
                                <td style="font-size: 16px; color: #dc3545;">$<?php echo number_format($balance, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="challan-footer">
                <div class="challan-notes">
                    <h6>Important Notes:</h6>
                    <ul>
                        <li>Please pay your fee before the due date to avoid late payment charges.</li>
                        <li>Keep this challan for your records and present it during payment.</li>
                        <li>For any queries, contact the accounts department during office hours.</li>
                        <li>This is a computer-generated challan and does not require a signature.</li>
                    </ul>
                </div>
                
                <div class="challan-actions no-print">
                    <button class="challan-btn challan-btn-primary" onclick="printChallan()">
                        <i class="fas fa-print"></i> Print Challan
                    </button>
                    <button class="challan-btn challan-btn-success" onclick="downloadChallanPDF()">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </button>
                    <button class="challan-btn challan-btn-secondary" onclick="closeChallanModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
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
// Store student information globally
const studentInfo = {
    admission_no: '<?php echo htmlspecialchars($admission_no); ?>',
    name: '<?php echo htmlspecialchars($name); ?>',
    category: '<?php echo htmlspecialchars($category); ?>',
    course_title: '<?php echo htmlspecialchars($course_title); ?>',
    session_name: '<?php echo htmlspecialchars($session_name); ?>',
    start_time: '<?php echo $start_time; ?>',
    end_time: '<?php echo $end_time; ?>',
    registration_date: '<?php echo htmlspecialchars($registration_date); ?>'
};

function printIndividualChallan(feeData) {
    const modal = document.getElementById('individualChallanModal');
    const printArea = document.getElementById('individualChallanPrintArea');
    
    const currentDate = new Date().toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
    
    // Generate challan HTML with office and student copies
    const challanHTML = `
        <!-- Office Copy -->
        <div class="challan-copy">
            <div class="copy-label">OFFICE COPY</div>
            
            <div class="challan-header-simple">
                <div class="challan-logo-simple">
                    <img src="images/logo.png" alt="Filenod Academy Logo">
                </div>
                <div class="challan-title-simple">Fee Challan</div>
                <div class="challan-contact-simple">
                    Filenod office opposite UBL Mandian, Abbottabad, 22044<br>
                    Email: info@filenod.com
                </div>
            </div>
            
            <div class="challan-body-simple">
            <?php if ($fee_status == "Paid") {?>
                <div class="text-center mt-2">
                    <span class="challan-info-label-simple"><?php echo $badge; ?></span>
                </div>
            <?php } ?>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Admission No:</span>
                    <span class="challan-info-value-simple">${studentInfo.admission_no}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Student Name:</span>
                    <span class="challan-info-value-simple">${studentInfo.name}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Category:</span>
                    <span class="challan-info-value-simple">${studentInfo.category}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Course:</span>
                    <span class="challan-info-value-simple">${studentInfo.course_title}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Session:</span>
                    <span class="challan-info-value-simple">${studentInfo.session_name} (${studentInfo.start_time} - ${studentInfo.end_time})</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Challan Date:</span>
                    <span class="challan-info-value-simple">${currentDate}</span>
                </div>
                
                <table class="challan-table-simple">
                    <thead>
                        <tr>
                            <th>Fee Type</th>
                            <th>Due Date</th>
                            <th>Amount ($)</th>
                            <th>Discount ($)</th>
                            <th>Fine ($)</th>
                            <th>Total ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${feeData.type}</td>
                            <td>${feeData.due_date}</td>
                            <td>$${parseFloat(feeData.monthly).toFixed(2)}</td>
                            <td>$${parseFloat(feeData.discount).toFixed(2)}</td>
                            <td>$${parseFloat(feeData.fine).toFixed(2)}</td>
                            <td>$${parseFloat(feeData.balance).toFixed(2)}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right;">TOTAL PAYABLE:</td>
                            <td>$${parseFloat(feeData.balance).toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="challan-notes-simple">
                    <strong>Important Notes:</strong>
                    <ul>
                        <li>Please pay your fee before the due date to avoid late payment charges.</li>
                        <li>Keep this challan for your records and present it during payment.</li>
                        <li>This is a computer-generated challan and does not require a signature.</li>
                    </ul>
                </div>
            </div>
            
            <div class="challan-footer-simple">
                Generated on ${currentDate} | Filenod Academy
            </div>
        </div>
        
        <!-- Student Copy -->
        <div class="challan-copy">
            <div class="copy-label">STUDENT COPY</div>
            
            <div class="challan-header-simple">
                <div class="challan-logo-simple">
                    <img src="images/logo.png" alt="Filenod Academy Logo">
                </div>
                <div class="challan-title-simple">Fee Challan</div>
                <div class="challan-contact-simple">
                    Filenod office opposite UBL Mandian, Abbottabad, 22044<br>
                    Email: info@filenod.com
                </div>
            </div>
            
            <div class="challan-body-simple">
            <?php if ($fee_status == "Paid") {?>
                <div class="text-center mt-2">
                    <span class="challan-info-label-simple"><?php echo $badge; ?></span>
                </div>
            <?php } ?>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Admission No:</span>
                    <span class="challan-info-value-simple">${studentInfo.admission_no}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Student Name:</span>
                    <span class="challan-info-value-simple">${studentInfo.name}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Category:</span>
                    <span class="challan-info-value-simple">${studentInfo.category}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Course:</span>
                    <span class="challan-info-value-simple">${studentInfo.course_title}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Session:</span>
                    <span class="challan-info-value-simple">${studentInfo.session_name} (${studentInfo.start_time} - ${studentInfo.end_time})</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Challan Date:</span>
                    <span class="challan-info-value-simple">${currentDate}</span>
                </div>
                
                <table class="challan-table-simple">
                    <thead>
                        <tr>
                            <th>Fee Type</th>
                            <th>Due Date</th>
                            <th>Amount ($)</th>
                            <th>Discount ($)</th>
                            <th>Fine ($)</th>
                            <th>Total ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${feeData.type}</td>
                            <td>${feeData.due_date}</td>
                            <td>$${parseFloat(feeData.monthly).toFixed(2)}</td>
                            <td>$${parseFloat(feeData.discount).toFixed(2)}</td>
                            <td>$${parseFloat(feeData.fine).toFixed(2)}</td>
                            <td>$${parseFloat(feeData.balance).toFixed(2)}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right;">TOTAL PAYABLE:</td>
                            <td>$${parseFloat(feeData.balance).toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="challan-notes-simple">
                    <strong>Important Notes:</strong>
                    <ul>
                        <li>Please pay your fee before the due date to avoid late payment charges.</li>
                        <li>Keep this challan for your records and present it during payment.</li>
                        <li>This is a computer-generated challan and does not require a signature.</li>
                    </ul>
                </div>
            </div>
            
            <div class="challan-footer-simple">
                Generated on ${currentDate} | Filenod Academy
            </div>
        </div>
        
        <div class="challan-actions-simple no-print">
            <button class="challan-btn-simple challan-btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Print Challan
            </button>
            <button class="challan-btn-simple challan-btn-close" onclick="closeIndividualChallan()">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    `;
    
    printArea.innerHTML = challanHTML;
    modal.style.display = 'block';
}

function closeIndividualChallan() {
    document.getElementById('individualChallanModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('individualChallanModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Function for Print All Fees button
function openChallanModal() {
    // Collect all fees data
    const allFees = [];
    <?php 
    $fees = $database->getfeesbycourseid($course_id);
    if (!empty($fees)) {
        foreach ($fees as $row) {
            $type = $row['type'];
            $monthly = floatval($row['monthly']);
            $due_date = $row['due_date'];
            $discount = 0;
            $fine = 0;
            $balance = $monthly - 0 + $fine - $discount;
            $due_date_formatted = !empty($due_date) ? date('d-M-Y', strtotime($due_date)) : "-";
            
            echo "allFees.push({
                type: '" . addslashes($type) . "',
                monthly: " . $monthly . ",
                discount: " . $discount . ",
                fine: " . $fine . ",
                balance: " . $balance . ",
                due_date: '" . $due_date_formatted . "'
            });";
        }
    }
    ?>
    
    if (allFees.length === 0) {
        alert('No fees found to print');
        return;
    }
    
    printAllFeesChallan(allFees);
}

function printAllFeesChallan(allFees) {
    const modal = document.getElementById('individualChallanModal');
    const printArea = document.getElementById('individualChallanPrintArea');
    
    const currentDate = new Date().toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
    
    // Calculate totals
    let totalAmount = 0;
    let totalDiscount = 0;
    let totalFine = 0;
    let totalBalance = 0;
    
    let feeRows = '';
    allFees.forEach(fee => {
        totalAmount += parseFloat(fee.monthly);
        totalDiscount += parseFloat(fee.discount);
        totalFine += parseFloat(fee.fine);
        totalBalance += parseFloat(fee.balance);
        
        feeRows += `
            <tr>
                <td>${fee.type}</td>
                <td>${fee.due_date}</td>
                <td>${parseFloat(fee.monthly).toFixed(2)}</td>
                <td>${parseFloat(fee.discount).toFixed(2)}</td>
                <td>${parseFloat(fee.fine).toFixed(2)}</td>
                <td>${parseFloat(fee.balance).toFixed(2)}</td>
            </tr>
        `;
    });
    
    // Generate challan HTML with office and student copies
    const challanHTML = `
        <!-- Office Copy -->
        <div class="challan-copy">
            <div class="copy-label">OFFICE COPY</div>
            
            <div class="challan-header-simple">
                <div class="challan-logo-simple">
                    <img src="images/logo.png" alt="Filenod Academy Logo">
                </div>
                <div class="challan-title-simple">Fee Challan</div>
                <div class="challan-contact-simple">
                    Filenod office opposite UBL Mandian, Abbottabad, 22044<br>
                    Email: info@filenod.com
                </div>
            </div>
            
            <div class="challan-body-simple">
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Admission No:</span>
                    <span class="challan-info-value-simple">${studentInfo.admission_no}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Student Name:</span>
                    <span class="challan-info-value-simple">${studentInfo.name}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Category:</span>
                    <span class="challan-info-value-simple">${studentInfo.category}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Course:</span>
                    <span class="challan-info-value-simple">${studentInfo.course_title}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Session:</span>
                    <span class="challan-info-value-simple">${studentInfo.session_name} (${studentInfo.start_time} - ${studentInfo.end_time})</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Challan Date:</span>
                    <span class="challan-info-value-simple">${currentDate}</span>
                </div>
                
                <table class="challan-table-simple">
                    <thead>
                        <tr>
                            <th>Fee Type</th>
                            <th>Due Date</th>
                            <th>Amount ($)</th>
                            <th>Discount ($)</th>
                            <th>Fine ($)</th>
                            <th>Total ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${feeRows}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right;">TOTAL PAYABLE:</td>
                            <td>${totalBalance.toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="challan-notes-simple">
                    <strong>Important Notes:</strong>
                    <ul>
                        <li>Please pay your fee before the due date to avoid late payment charges.</li>
                        <li>Keep this challan for your records and present it during payment.</li>
                        <li>This is a computer-generated challan and does not require a signature.</li>
                    </ul>
                </div>
            </div>
            
            <div class="challan-footer-simple">
                Generated on ${currentDate} | Filenod Academy
            </div>
        </div>
        
        <!-- Student Copy -->
        <div class="challan-copy">
            <div class="copy-label">STUDENT COPY</div>
            
            <div class="challan-header-simple">
                <div class="challan-logo-simple">
                    <img src="images/logo.png" alt="Filenod Academy Logo">
                </div>
                <div class="challan-title-simple">Fee Challan</div>
                <div class="challan-contact-simple">
                    Filenod office opposite UBL Mandian, Abbottabad, 22044<br>
                    Email: info@filenod.com
                </div>
            </div>
            
            <div class="challan-body-simple">
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Admission No:</span>
                    <span class="challan-info-value-simple">${studentInfo.admission_no}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Student Name:</span>
                    <span class="challan-info-value-simple">${studentInfo.name}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Category:</span>
                    <span class="challan-info-value-simple">${studentInfo.category}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Course:</span>
                    <span class="challan-info-value-simple">${studentInfo.course_title}</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Session:</span>
                    <span class="challan-info-value-simple">${studentInfo.session_name} (${studentInfo.start_time} - ${studentInfo.end_time})</span>
                </div>
                <div class="challan-info-row">
                    <span class="challan-info-label-simple">Challan Date:</span>
                    <span class="challan-info-value-simple">${currentDate}</span>
                </div>
                
                <table class="challan-table-simple">
                    <thead>
                        <tr>
                            <th>Fee Type</th>
                            <th>Due Date</th>
                            <th>Amount ($)</th>
                            <th>Discount ($)</th>
                            <th>Fine ($)</th>
                            <th>Total ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${feeRows}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" style="text-align: right;">TOTAL PAYABLE:</td>
                            <td>${totalBalance.toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="challan-notes-simple">
                    <strong>Important Notes:</strong>
                    <ul>
                        <li>Please pay your fee before the due date to avoid late payment charges.</li>
                        <li>Keep this challan for your records and present it during payment.</li>
                        <li>This is a computer-generated challan and does not require a signature.</li>
                    </ul>
                </div>
            </div>
            
            <div class="challan-footer-simple">
                Generated on ${currentDate} | Filenod Academy
            </div>
        </div>
        
        <div class="challan-actions-simple no-print">
            <button class="challan-btn-simple challan-btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> Print Challan
            </button>
            <button class="challan-btn-simple challan-btn-close" onclick="closeIndividualChallan()">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    `;
    
    printArea.innerHTML = challanHTML;
    modal.style.display = 'block';
}



// Calendar data from PHP
const attendanceData = <?php echo json_encode($attendance_data); ?>;
let currentMonth = <?php echo date('n'); ?>;
let currentYear = <?php echo date('Y'); ?>;
const studentId = '<?php echo $student_id; ?>';
const sessionId = '<?php echo $session_id; ?>';

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
                    break;
                case 'Late':
                    statusClass = 'late';
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
        alert(`Date: ${dateStr}\nStatus: ${attendance.status}\nEntry: ${attendance.entry_time || 'N/A'}\nExit: ${attendance.exit_time || 'N/A'}\nNote: ${attendance.note || 'N/A'}`);
    }
}

// Initialize calendar
$(document).ready(function() {
    renderCalendar();
});

function printStudentProfile() {
    // Get all the data from PHP variables
    const profileData = {
        // Personal Info
        image: '<?php echo (!empty($image) && file_exists("images/" . $image)) ? "images/" . $image : "images/avatar.png"; ?>',
        admission_no: '<?php echo htmlspecialchars($admission_no); ?>',
        registration_no: '<?php echo htmlspecialchars($registration_no); ?>',
        name: '<?php echo htmlspecialchars($name); ?>',
        gender: '<?php echo htmlspecialchars($gender); ?>',
        dob: '<?php echo htmlspecialchars($dob); ?>',
        mobile_no: '<?php echo htmlspecialchars($mobile_no); ?>',
        cnic: '<?php echo htmlspecialchars($cnic); ?>',
        email: '<?php echo htmlspecialchars($student_email); ?>',
        institute: '<?php echo htmlspecialchars($institute); ?>',
        address: '<?php echo htmlspecialchars($address); ?>',
        category: '<?php echo htmlspecialchars($category); ?>',
        course_title: '<?php echo htmlspecialchars($course_title); ?>',
        duration: '<?php echo htmlspecialchars($duration); ?>',
        session_name: '<?php echo htmlspecialchars($session_name); ?>',
        start_time: '<?php echo $start_time; ?>',
        end_time: '<?php echo $end_time; ?>',
        slot: '<?php echo $slot; ?>',
        registration_date: '<?php echo date("d M Y", strtotime($registration_date)); ?>',
        student_status: '<?php echo htmlspecialchars($student_status); ?>',
        fee_status: '<?php echo htmlspecialchars($fee_status); ?>',
        
        // Guardian Info
        guardian_name: '<?php echo htmlspecialchars($guardian_name); ?>',
        guardian_relation: '<?php echo htmlspecialchars($guardian_relation); ?>',
        guardian_phone: '<?php echo htmlspecialchars($guardian_phone); ?>',
        
        // Attendance Stats
        total_present: <?php echo $total_present; ?>,
        total_late: <?php echo $total_late; ?>,
        total_absent: <?php echo $total_absent; ?>,
        total_halfday: <?php echo $total_halfday; ?>,
        total_holiday: <?php echo $total_holiday; ?>,
        attendance_percentage: <?php echo $attendance_percentage; ?>
    };

    // Get fees data
    const feesData = [
        <?php 
        $fees = $database->getfeesbycourseid($course_id);
        if (!empty($fees)) {
            foreach ($fees as $row) {
                $type = addslashes($row['type']);
                $monthly = floatval($row['monthly']);
                $due_date = !empty($row['due_date']) ? date('d-M-Y', strtotime($row['due_date'])) : "-";
                $discount = 0;
                $fine = 0;
                $balance = $monthly - 0 + $fine - $discount;
                
                echo "{
                    type: '" . $type . "',
                    monthly: " . $monthly . ",
                    discount: " . $discount . ",
                    fine: " . $fine . ",
                    balance: " . $balance . ",
                    due_date: '" . $due_date . "',
                    status: '" . addslashes($fee_status) . "'
                },";
            }
        }
        ?>
    ];

    // Calculate fee totals
    let totalAmount = 0, totalDiscount = 0, totalFine = 0, totalBalance = 0;
    feesData.forEach(fee => {
        totalAmount += fee.monthly;
        totalDiscount += fee.discount;
        totalFine += fee.fine;
        totalBalance += fee.balance;
    });

    // Generate fees table rows
    let feeRows = '';
    feesData.forEach(fee => {
        const statusBadge = fee.status === 'Paid' 
            ? '<span style="background: #28a745; color: white; padding: 3px 10px; border-radius: 12px; font-size: 10px;">Paid</span>'
            : '<span style="background: #dc3545; color: white; padding: 3px 10px; border-radius: 12px; font-size: 10px;">Unpaid</span>';
        
        feeRows += `
            <tr>
                <td>${fee.type}</td>
                <td>$${fee.monthly.toFixed(2)}</td>
                <td>$${fee.discount.toFixed(2)}</td>
                <td>$${fee.fine.toFixed(2)}</td>
                <td>${fee.due_date}</td>
                <td>${statusBadge}</td>
                <td>$${fee.balance.toFixed(2)}</td>
            </tr>
        `;
    });

    // Get recent attendance
    const recentAttendance = [
        <?php
        $recent_query = "SELECT 
            DATE(created_at) as attendance_date,
            attendance,
            entry_time,
            exit_time,
            note
        FROM student_attendance 
        WHERE student_id = '$student_id' 
        AND session_id = '$session_id'
        ORDER BY created_at DESC 
        LIMIT 10";
        
        $recent_result = mysqli_query($conn, $recent_query);
        if (mysqli_num_rows($recent_result) > 0) {
            while ($att = mysqli_fetch_assoc($recent_result)) {
                echo "{
                    date: '" . date('d M Y', strtotime($att['attendance_date'])) . "',
                    status: '" . addslashes($att['attendance']) . "',
                    entry_time: '" . ($att['entry_time'] ? date('h:i A', strtotime($att['entry_time'])) : '-') . "',
                    exit_time: '" . ($att['exit_time'] ? date('h:i A', strtotime($att['exit_time'])) : '-') . "',
                    note: '" . addslashes($att['note']) . "'
                },";
            }
        }
        ?>
    ];

    let attendanceRows = '';
    recentAttendance.forEach(att => {
        let statusBadge = '';
        switch(att.status) {
            case 'Present':
                statusBadge = '<span style="background: #28a745; color: white; padding: 3px 10px; border-radius: 12px; font-size: 10px;"><i class="fas fa-check-circle"></i> Present</span>';
                break;
            case 'Late':
                statusBadge = '<span style="background: #ffc107; color: white; padding: 3px 10px; border-radius: 12px; font-size: 10px;"><i class="fas fa-clock"></i> Late</span>';
                break;
            case 'Absent':
                statusBadge = '<span style="background: #dc3545; color: white; padding: 3px 10px; border-radius: 12px; font-size: 10px;"><i class="fas fa-times-circle"></i> Absent</span>';
                break;
            case 'Holiday':
                statusBadge = '<span style="background: #6c757d; color: white; padding: 3px 10px; border-radius: 12px; font-size: 10px;"><i class="fas fa-calendar"></i> Holiday</span>';
                break;
            case 'Half Day':
                statusBadge = '<span style="background: #17a2b8; color: white; padding: 3px 10px; border-radius: 12px; font-size: 10px;"><i class="fas fa-adjust"></i> Half Day</span>';
                break;
        }
        
        attendanceRows += `
            <tr>
                <td>${att.date}</td>
                <td>${statusBadge}</td>
                <td>${att.entry_time}</td>
                <td>${att.exit_time}</td>
                <td>${att.note || '-'}</td>
            </tr>
        `;
    });

    const currentDate = new Date().toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });

    // Status badges
    const statusBadge = profileData.student_status === 'Active'
        ? '<span style="background: #28a745; color: white; padding: 5px 15px; border-radius: 15px; font-size: 11px; font-weight: 600;">Active</span>'
        : '<span style="background: #dc3545; color: white; padding: 5px 15px; border-radius: 15px; font-size: 11px; font-weight: 600;">Inactive</span>';

    const feeStatusBadge = profileData.fee_status === 'Paid'
        ? '<span style="background: #28a745; color: white; padding: 5px 15px; border-radius: 15px; font-size: 11px; font-weight: 600;">Paid</span>'
        : '<span style="background: #dc3545; color: white; padding: 5px 15px; border-radius: 15px; font-size: 11px; font-weight: 600;">Unpaid</span>';

    // Create print window content
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Student Profile - ${profileData.name}</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <style>
                @page {
                    size: A4;
                    margin: 15mm;
                }
                
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: Arial, sans-serif;
                    font-size: 11px;
                    line-height: 1.4;
                    color: #333;
                }
                
                .print-container {
                    width: 100%;
                    max-width: 210mm;
                    margin: 0 auto;
                }
                
                .header {
                    text-align: center;
                    padding: 20px 0;
                    border-bottom: 3px solid #173663;
                    margin-bottom: 20px;
                }
                
                .logo {
                    width: 100px;
                    height: auto;
                    margin-bottom: 10px;
                }
                
                .header h1 {
                    color: #173663;
                    font-size: 24px;
                    margin: 10px 0 5px 0;
                    font-weight: 700;
                }
                
                .header p {
                    color: #666;
                    font-size: 11px;
                    margin: 3px 0;
                }
                
                .profile-header {
                    display: flex;
                    align-items: center;
                    padding: 15px;
                    background: linear-gradient(135deg, #173663 0%, #2d5a8c 100%);
                    color: white;
                    border-radius: 8px;
                    margin-bottom: 20px;
                }
                
                .profile-image {
                    width: 100px;
                    height: 100px;
                    border-radius: 50%;
                    border: 4px solid white;
                    object-fit: cover;
                    margin-right: 20px;
                }
                
                .profile-info h2 {
                    font-size: 20px;
                    margin-bottom: 5px;
                }
                
                .profile-info p {
                    font-size: 11px;
                    opacity: 0.9;
                    margin: 2px 0;
                }
                
                .section {
                    margin-bottom: 20px;
                    page-break-inside: avoid;
                }
                
                .section-title {
                    background: #173663;
                    color: white;
                    padding: 8px 15px;
                    font-size: 13px;
                    font-weight: 700;
                    border-radius: 4px;
                    margin-bottom: 10px;
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 10px;
                    margin-bottom: 15px;
                }
                
                .info-item {
                    display: flex;
                    padding: 8px 10px;
                    background: #f8f9fa;
                    border-left: 3px solid #173663;
                    border-radius: 4px;
                }
                
                .info-label {
                    font-weight: 700;
                    color: #173663;
                    min-width: 120px;
                    font-size: 10px;
                }
                
                .info-value {
                    color: #333;
                    font-size: 10px;
                }
                
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(5, 1fr);
                    gap: 10px;
                    margin-bottom: 15px;
                }
                
                .stat-box {
                    text-align: center;
                    padding: 12px;
                    border-radius: 6px;
                    border-left: 4px solid;
                }
                
                .stat-box.present { background: #d4edda; border-left-color: #28a745; }
                .stat-box.late { background: #fff3cd; border-left-color: #ffc107; }
                .stat-box.absent { background: #f8d7da; border-left-color: #dc3545; }
                .stat-box.halfday { background: #d1ecf1; border-left-color: #17a2b8; }
                .stat-box.holiday { background: #e2e3e5; border-left-color: #6c757d; }
                
                .stat-number {
                    font-size: 20px;
                    font-weight: 700;
                    color: #173663;
                    margin: 5px 0;
                }
                
                .stat-label {
                    font-size: 9px;
                    color: #666;
                    font-weight: 600;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 10px;
                    font-size: 10px;
                }
                
                table th {
                    background: #173663;
                    color: white;
                    padding: 8px 6px;
                    text-align: left;
                    font-weight: 600;
                    border: 1px solid #173663;
                }
                
                table td {
                    padding: 8px 6px;
                    border: 1px solid #dee2e6;
                }
                
                table tbody tr:nth-child(even) {
                    background: #f8f9fa;
                }
                
                table tfoot td {
                    background: #e9ecef;
                    font-weight: 700;
                    color: #173663;
                }
                
                .footer {
                    margin-top: 30px;
                    padding-top: 15px;
                    border-top: 2px solid #173663;
                    text-align: center;
                    color: #666;
                    font-size: 10px;
                }
                
                .progress-bar-container {
                    background: #e9ecef;
                    height: 20px;
                    border-radius: 10px;
                    overflow: hidden;
                    margin: 10px 0;
                }
                
                .progress-bar {
                    background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: 700;
                    font-size: 11px;
                }
                
                @media print {
                    body {
                        print-color-adjust: exact;
                        -webkit-print-color-adjust: exact;
                    }
                    
                    .section {
                        page-break-inside: avoid;
                    }
                }
            </style>
        </head>
        <body>
            <div class="print-container">
                <!-- Header -->
                <div class="header">
                    <img src="images/logo.png" alt="Filenod Academy" class="logo">
                    <h1>FILENOD ACADEMY</h1>
                    <p>Filenod office opposite UBL Mandian, Abbottabad, 22044</p>
                    <p>Email: info@filenod.com | Phone: +92 345 4955590</p>
                    <p style="margin-top: 10px; font-weight: 600; color: #173663;">STUDENT PROFILE REPORT</p>
                    <p style="font-size: 10px;">Generated on: ${currentDate}</p>
                </div>

                <!-- Profile Header -->
                <div class="profile-header">
                    <img src="${profileData.image}" alt="Student Photo" class="profile-image">
                    <div class="profile-info">
                        <h2>${profileData.name}</h2>
                        <p><strong>Admission No:</strong> ${profileData.admission_no} | <strong>Registration No:</strong> ${profileData.registration_no}</p>
                        <p><strong>Course:</strong> ${profileData.course_title} | <strong>Category:</strong> ${profileData.category}</p>
                        <p><strong>Status:</strong> ${statusBadge} | <strong>Fee Status:</strong> ${feeStatusBadge}</p>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="section">
                    <div class="section-title"><i class="fas fa-user"></i> Personal Information</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Gender:</span>
                            <span class="info-value">${profileData.gender}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date of Birth:</span>
                            <span class="info-value">${profileData.dob}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Mobile No:</span>
                            <span class="info-value">${profileData.mobile_no}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">CNIC:</span>
                            <span class="info-value">${profileData.cnic}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value">${profileData.email}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Current Grade:</span>
                            <span class="info-value">${profileData.current_grade}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Institute:</span>
                            <span class="info-value">${profileData.institute}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Joining Date:</span>
                            <span class="info-value">${profileData.registration_date}</span>
                        </div>
                    </div>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <span class="info-label">Address:</span>
                        <span class="info-value">${profileData.address}</span>
                    </div>
                </div>

                <!-- Course Information -->
                <div class="section">
                    <div class="section-title"><i class="fas fa-book"></i> Course Information</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Course:</span>
                            <span class="info-value">${profileData.course_title}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Duration:</span>
                            <span class="info-value">${profileData.duration} Months</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Session:</span>
                            <span class="info-value">${profileData.session_name}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Timing:</span>
                            <span class="info-value">${profileData.start_time} - ${profileData.end_time}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Slot:</span>
                            <span class="info-value">${profileData.slot}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Category:</span>
                            <span class="info-value">${profileData.category}</span>
                        </div>
                    </div>
                </div>

                <!-- Guardian Information -->
                <div class="section">
                    <div class="section-title"><i class="fas fa-users"></i> Guardian Information</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Guardian Name:</span>
                            <span class="info-value">${profileData.guardian_name}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Relation:</span>
                            <span class="info-value">${profileData.guardian_relation}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Phone:</span>
                            <span class="info-value">${profileData.guardian_phone}</span>
                        </div>
                    </div>
                </div>

                <!-- Attendance Statistics -->
                <div class="section">
                    <div class="section-title"><i class="fas fa-chart-bar"></i> Attendance Statistics</div>
                    <div class="stats-grid">
                        <div class="stat-box present">
                            <div class="stat-label">Present</div>
                            <div class="stat-number">${profileData.total_present}</div>
                        </div>
                        <div class="stat-box late">
                            <div class="stat-label">Late</div>
                            <div class="stat-number">${profileData.total_late}</div>
                        </div>
                        <div class="stat-box absent">
                            <div class="stat-label">Absent</div>
                            <div class="stat-number">${profileData.total_absent}</div>
                        </div>
                        <div class="stat-box halfday">
                            <div class="stat-label">Half Day</div>
                            <div class="stat-number">${profileData.total_halfday}</div>
                        </div>
                        <div class="stat-box holiday">
                            <div class="stat-label">Holiday</div>
                            <div class="stat-number">${profileData.total_holiday}</div>
                        </div>
                    </div>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px;">
                        <p style="font-weight: 700; margin-bottom: 8px; color: #173663;">Overall Attendance: ${profileData.attendance_percentage}%</p>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: ${profileData.attendance_percentage}%">
                                ${profileData.attendance_percentage}%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Attendance -->
                ${recentAttendance.length > 0 ? `
                <div class="section">
                    <div class="section-title"><i class="fas fa-history"></i> Recent Attendance (Last 10 Days)</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Entry Time</th>
                                <th>Exit Time</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${attendanceRows}
                        </tbody>
                    </table>
                </div>
                ` : ''}

                <!-- Fee Details -->
                ${feesData.length > 0 ? `
                <div class="section">
                    <div class="section-title"><i class="fas fa-dollar-sign"></i> Fee Details</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Fee Type</th>
                                <th>Amount ($)</th>
                                <th>Discount ($)</th>
                                <th>Fine ($)</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Balance ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${feeRows}
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><strong>TOTAL</strong></td>
                                <td><strong>$${totalAmount.toFixed(2)}</strong></td>
                                <td><strong>$${totalDiscount.toFixed(2)}</strong></td>
                                <td><strong>$${totalFine.toFixed(2)}</strong></td>
                                <td>-</td>
                                <td>-</td>
                                <td><strong>$${totalBalance.toFixed(2)}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                ` : ''}

                <!-- Footer -->
                <div class="footer">
                    <p><strong>Filenod Academy</strong></p>
                    <p>This is a computer-generated document. For any queries, please contact the administration.</p>
                    <p>© ${new Date().getFullYear()} Filenod Academy. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
    `;

    // Open print window
    const printWindow = window.open('', '', 'width=900,height=700');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    
    // Wait for images to load then print
    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);
    };
}

</script>

<?php } else { 
    header("Location: login.php");
    exit();
} ?>

</body>
</html>