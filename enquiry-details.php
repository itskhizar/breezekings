<?php
include("include/classes/session.php");
if (!$session->logged_in) {
    header('location: index.php');
    exit;
}

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$enquiry_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($enquiry_id == 0) {
    header('Location: enquiry-list.php');
    exit;
}

$query = "SELECT a.* FROM admissions a WHERE a.id = $enquiry_id";
$result = mysqli_query($conn, $query);
$enquiry = mysqli_fetch_assoc($result);

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: enquiry-list.php');
    exit;
}

$call_logs_query = "SELECT * FROM call_logs 
                    WHERE enquiry_id = $enquiry_id 
                    ORDER BY date DESC, created_at DESC";
$call_logs_result = mysqli_query($conn, $call_logs_query);

$username = $session->username;
$user_result = $database->getUserInfo($username);
$display_name = $user_result['display_name'];
$image = $user_result['parent_directory'];
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

    <title>Enquiry Details - <?php echo htmlspecialchars($enquiry['name']); ?></title>
    
    <style>
        :root {
            --primary-dark: #0e0c28;
            --primary-gradient: linear-gradient(135deg, #0e0c28 0%, #1a1742 50%, #2d2463 100%);
            --accent-color: #6c5ce7;
            --success: #00b894;
            --danger: #ff7675;
            --warning: #fdcb6e;
        }

        body {
            background: linear-gradient(135deg, #f8f9fd 0%, #eef1f9 100%);
            font-family: 'Circular Std', sans-serif;
        }
 .dashboard-wrapper{
            margin-left: 0px;
        }
        .dashboard-main-wrapper{
            padding-top: 0px !important;
        }
        .page-header-modern {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(14, 12, 40, 0.15);
            color: white;
        }

        .detail-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f2f8;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f8f9fd;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--primary-dark);
            min-width: 150px;
            font-size: 14px;
        }

        .info-value {
            color: #555;
            font-size: 13px;
        }

        /* Call Action Buttons */
        .call-action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .btn-action {
            flex: 1;
            padding: 15px 20px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn-timer-call {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
        }

        .btn-timer-call:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.4);
        }

        .btn-manual-log {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-manual-log:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Call Timer Widget */
        .call-timer-widget {
            background: linear-gradient(135deg, #00b894, #00cec9);
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0, 184, 148, 0.3);
            display: none;
        }

        .call-timer-widget.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .timer-display {
            font-size: 48px;
            font-weight: 700;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .btn-call {
            padding: 15px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .btn-start-call {
            background: white;
            color: var(--success);
        }

        .btn-start-call:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        .btn-end-call {
            background: var(--danger);
            color: white;
        }

        .btn-end-call:hover {
            background: #d63031;
            transform: translateY(-2px);
        }

        .btn-whatsapp-large {
            background: #25D366;
            color: white;
            padding: 12px 30px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .btn-whatsapp-large:hover {
            background: #128C7E;
            color: white;
            transform: translateY(-2px);
        }

        /* Call Log Form */
        .call-log-form {
            background: #f8f9fd;
            border-radius: 12px;
            padding: 25px;
            margin-top: 20px;
            display: none;
        }

        .call-log-form.active {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .form-mode-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .form-mode-badge.timer-mode {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: white;
        }

        .form-mode-badge.manual-mode {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e0e0e0;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent-color);
            border: 3px solid white;
            box-shadow: 0 0 0 2px var(--accent-color);
        }

        .timeline-content {
            background: white;
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .timeline-date {
            font-size: 13px;
            color: #888;
        }

        .timeline-outcome {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            background: var(--success);
            color: white;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 13px;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e8ecf4;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-badge.new { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .status-badge.connected { background: linear-gradient(135deg, #48dbfb, #0abde3); color: white; }
        .status-badge.interested { background: linear-gradient(135deg, #1dd1a1, #10ac84); color: white; }



        /* Manual Duration Input */
        .duration-input-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .duration-input-group input {
            width: 70px;
        }

        .duration-input-group span {
            font-size: 14px;
            color: #888;
        }
        .timeline-content {
    background: white;
    border-radius: 12px;
    padding: 15px 20px;  /* <- this should work, but maybe overridden */
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.timeline-content {
    padding: 20px 25px;
}
.timeline-content {
    max-width: 600px;  /* or whatever fits your layout */
    width: 100%;
}
*, *::before, *::after {
    box-sizing: border-box;
}
.badge {
    font-size: 11px;
    padding: 3px 3px;
    border-radius: 12px;
    font-weight: 600;
    line-height: 1.2;
}
.info-value .badge {
    margin-right: 6px;
    margin-bottom: 4px;
    display: inline-block;
}

    </style>
</head>

<body>
    <?php if ($session->logged_in) { ?>
        <div class="dashboard-main-wrapper">
            <?php include("navbar.php"); ?>
            <?php include("leftbar.php"); ?>
            
            <div class="dashboard-wrapper">
                <div class="container-fluid dashboard-content">

                    <div class="row">
                        
                        <!-- LEFT COLUMN: Student Details -->
                        <div class="col-lg-4">
                            
                            <!-- Student Info Card -->
                            <div class="detail-card">
                                <div class="section-title">
                                    <i class="fas fa-user"></i>
                                    Student Information
                                </div>
                                
                                <div class="info-row">
                                    <div class="info-label">Name:</div>
                                    <div class="info-value"><?php echo htmlspecialchars($enquiry['name']); ?></div>
                                </div>
                                
                                <div class="info-row">
                                    <div class="info-label">Phone:</div>
                                    <div class="info-value">
                                        <a href="tel:<?php echo htmlspecialchars($enquiry['mobile_no']); ?>" style="color: var(--accent-color); text-decoration: none; font-weight: 600;">
                                            <i class="fas fa-phone-alt me-1"></i><?php echo htmlspecialchars($enquiry['mobile_no']); ?>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <div class="info-label">Email:</div>
                                    <div class="info-value"><?php echo htmlspecialchars($enquiry['email']); ?></div>
                                </div>
                                
                                <div class="info-row">
                                    <div class="info-label">Gender:</div>
                                    <div class="info-value"><?php echo htmlspecialchars($enquiry['gender']); ?></div>
                                </div>

                                <?php if (!empty($enquiry['address'])): ?>
                                <div class="info-row">
                                    <div class="info-label">Address:</div>
                                    <div class="info-value"><?php echo htmlspecialchars($enquiry['address']); ?></div>
                                </div>
                                <?php endif; ?>

                                <div class="info-row">
                                    <div class="info-label">Education Status:</div>
                                    <div class="info-value"><?php echo htmlspecialchars($enquiry['education_status'] ?? 'N/A'); ?></div>
                                </div>

                                <?php 
                                $education_label = "Education Status";
                                $education_value = "-";
                                switch ($enquiry['education_status']) {
                                    case "Undergraduate":
                                        $education_label = "Current Semester";
                                        $education_value = $enquiry['current_semester'];
                                        break;
                                    case "Graduate":
                                        $education_label = "Graduation Year";
                                        $education_value = $enquiry['graduation_year'];
                                        break;
                                    case "Primary":
                                    case "Secondary":
                                    case "Intermediate":
                                        $education_label = "Current Grade";
                                        $education_value = $enquiry['grade'];
                                        break;
                                    case "Other":
                                        $education_label = "Other Degree";
                                        $education_value = $enquiry['other_degree'];
                                        break;
                                } 
                                ?>
                                
                                <div class="info-row">
                                    <div class="info-label"><?php echo htmlspecialchars($education_label); ?>:</div>
                                    <div class="info-value"><?php echo htmlspecialchars($education_value ?? 'N/A'); ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Field of Education:</div>
                                    <div class="info-value"><?php echo htmlspecialchars($enquiry['field_education']); ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">Institute:</div>
                                    <div class="info-value"><?php echo htmlspecialchars($enquiry['institute']); ?></div>
                                </div>

                                <?php 
                                    $course_id = $enquiry['course_id'];
                                    $course_query = "SELECT * FROM courses WHERE id = '$course_id' LIMIT 1";
                                    $course_result = mysqli_query($conn, $course_query);
                                    $course = mysqli_fetch_assoc($course_result); 
                                ?>
                                <div class="info-row align-items-center mb-3">
                                    <div class="info-label">
                                        Course:
                                    </div>
                                    <div class="info-value">
                                        <span class="badge bg-primary px-3 py-1 rounded">
                                            <?php echo htmlspecialchars($course['course_title']); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Field of Interest:</div>
                                    <div class="info-value">
                                        <?php
                                        if (!empty($enquiry['field_interest'])) {
                                            $interests = explode(',', $enquiry['field_interest']);
                                            foreach ($interests as $interest) {
                                                echo '<span class="badge badge-info me-1">'
                                                     . htmlspecialchars(trim($interest)) .
                                                     '</span>&nbsp;';
                                            }
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Visit Date:</div>
                                    <div class="info-value"><?php echo htmlspecialchars($enquiry['visit_date'] ?? 'N/A'); ?></div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Visit Time:</div>
                                    <div class="info-value"><?php echo htmlspecialchars($enquiry['visit_time'] ?? 'N/A'); ?></div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Status:</div>
                                    <div class="info-value">
                                        <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $enquiry['enquiry_status'])); ?>">
                                            <?php echo htmlspecialchars($enquiry['enquiry_status'] ?? 'New'); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <div class="info-label">Source:</div>
                                    <div class="info-value">
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($enquiry['enquiry_source'] ?? 'website'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="info-row">
                                    <div class="info-label">Enquiry Date:</div>
                                    <div class="info-value"><?php echo date('d M Y, h:i A', strtotime($enquiry['timestamp'])); ?></div>
                                </div>
                            </div>

                            <!-- WhatsApp Button -->
                            <?php
                            $whatsapp_number = preg_replace('/[^0-9]/', '', $enquiry['mobile_no']);
                            if (substr($whatsapp_number, 0, 1) == '0') {
                                $whatsapp_number = '92' . substr($whatsapp_number, 1);
                            }
                            ?>
                            <a href="https://wa.me/<?php echo $whatsapp_number; ?>" 
                               target="_blank" 
                               class="btn-whatsapp-large">
                                <i class="fab fa-whatsapp me-2"></i>Open WhatsApp Chat
                            </a>
                            <!-- Remarks Section -->
                            <?php if (!empty($enquiry['remarks'])): ?>
                            <div class="detail-card mt-3">
                                <div class="section-title">
                                    <i class="fas fa-sticky-note"></i>
                                    Remarks
                                </div>
                                <p style="color: #555; line-height: 1.6;">
                                    <?php echo nl2br(htmlspecialchars($enquiry['remarks'])); ?>
                                </p>
                            </div>
                            <?php endif; ?>
                            
                        </div>

                        <!-- RIGHT COLUMN: Call Center -->
                        <div class="col-lg-8">
                            
                            <!-- Call Action Buttons -->
                            <div class="call-action-buttons" id="callActionButtons">
                                <button class="btn-action btn-timer-call" onclick="showTimerCall()">
                                    <i class="fas fa-stopwatch me-2"></i>Start Timed Call
                                </button>
                                <button class="btn-action btn-manual-log" onclick="showManualLog()">
                                    <i class="fas fa-edit me-2"></i>Add Manual Call Log
                                </button>
                            </div>

                            <!-- Call Timer Widget -->
                            <div class="call-timer-widget" id="callTimerWidget">
                                <h4><i class="fas fa-phone-alt me-2"></i>Timed Call in Progress</h4>
                                <div class="timer-display" id="timerDisplay">00:00:00</div>
                                
                                <button class="btn-call btn-start-call" id="startCallBtn" onclick="startCall()">
                                    <i class="fas fa-play me-2"></i>Start Timer
                                </button>
                                
                                <button class="btn-call btn-end-call" id="endCallBtn" onclick="endCall()" style="display: none;">
                                    <i class="fas fa-stop me-2"></i>Stop & Log Call
                                </button>

                                <button class="btn btn-secondary rounded-full" onclick="cancelTimedCall()">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </button>
                            </div>

                            <!-- Call Log Form -->
                            <!-- Call Log Form -->
                            <div class="call-log-form" id="callLogForm">
                                <span class="form-mode-badge" id="formModeBadge"></span>
                                <h5 class="mb-3"><i class="fas fa-edit me-2"></i><span id="formTitle">Log Call Details</span></h5>
                                
                                <form id="saveCallForm">
                                    <input type="hidden" name="enquiry_id" value="<?php echo $enquiry_id; ?>">
                                    <input type="hidden" name="call_mode" id="callMode" value="">
                                    <input type="hidden" name="duration_seconds" id="durationSeconds" value="">
                                    <input type="hidden" name="counsellor" value="<?php echo htmlspecialchars($display_name); ?>">
                                    <input type="hidden" name="call_duration" id="callDurationSubmit">
                                    
                                    <div class="row">
                                        <!-- Communication Type Selection -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Communication Type *</label>
                                            <select name="communication_type" id="communicationType" class="form-select" required>
                                                <option value="">Select Type</option>
                                                <option value="call">Call</option>
                                                <option value="whatsapp">WhatsApp</option>
                                            </select>
                                        </div>

                                        <!-- Call Type (Only for Call) -->
                                        <div class="col-md-6 mb-3" id="callTypeWrapper" style="display:none;">
                                            <label class="form-label">Call Type</label>
                                            <select name="call_type" id="callType" class="form-select">
                                                <option value="Outgoing">Outgoing</option>
                                                <option value="Incoming">Incoming</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Call Duration (Only for Call) -->
                                        <div class="col-md-6 mb-3" id="durationWrapper" style="display:none;">
                                            <label class="form-label">
                                                Call Duration 
                                                <span id="durationRequired">*</span>
                                            </label>
                                            
                                            <!-- Timer Mode: Read-only display -->
                                            <input type="text" 
                                                   id="callDurationDisplay" 
                                                   class="form-control" 
                                                   readonly 
                                                   style="display: none;"
                                                   placeholder="Auto-calculated">
                                            
                                            <!-- Manual Mode: Input fields -->
                                            <div id="manualDurationInputs" class="duration-input-group" style="display: none;">
                                                <input type="number" 
                                                       id="manualMinutes" 
                                                       class="form-control" 
                                                       min="0" 
                                                       max="999" 
                                                       placeholder="Min"
                                                       value="0">
                                                <span>:</span>
                                                <input type="number" 
                                                       id="manualSeconds" 
                                                       class="form-control" 
                                                       min="0" 
                                                       max="59" 
                                                       placeholder="Sec"
                                                       value="0">
                                            </div>
                                        </div>

                                        <!-- Call Outcome (Only for Call) -->
                                        <div class="col-md-6 mb-3" id="callOutcomeWrapper" style="display:none;">
                                            <label class="form-label">Call Outcome *</label>
                                            <select name="call_outcome" id="callOutcome" class="form-select">
                                                <option value="">Select Outcome</option>
                                                <option value="Connected">Connected</option>
                                                <option value="No Answer">No Answer</option>
                                                <option value="Busy">Busy</option>
                                                <option value="Voicemail">Voicemail</option>
                                                <option value="Wrong Number">Wrong Number</option>
                                                <option value="Interested">Interested</option>
                                                <option value="Not Interested">Not Interested</option>
                                                <option value="Callback Requested">Callback Requested</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Update Status (For both Call and WhatsApp) -->
                                        <div class="col-md-6 mb-3" id="statusWrapper" style="display:none;">
                                            <label class="form-label">Update Status</label>
                                            <select name="new_status" id="newStatus" class="form-select">
                                                <option value="">Keep Current Status</option>
                                                <option value="Interested">Interested</option>
                                                <option value="Not Interested">Not Interested</option>
                                                <option value="Follow-up Required">Follow-up Required</option>
                                                <option value="Enrolled">Enrolled</option>
                                            </select>
                                        </div>

                                        <!-- Next Follow-up Date (Dynamic based on status) -->
                                        <div class="col-md-6 mb-3" id="followUpWrapper" style="display:none;">
                                            <label class="form-label">Next Follow-up Date</label>
                                            <input type="date" name="next_follow_up" id="nextFollowUp" class="form-control">
                                        </div>
                                        
                                        <!-- Next Follow-up Time -->
                                        <div class="col-md-6 mb-3" id="followUpTimeWrapper" style="display:none;">
                                            <label class="form-label">Next Follow-up Time</label>
                                            <input type="time" name="next_follow_up_time" id="nextFollowUpTime" class="form-control">
                                        </div>
                                        
                                        <!-- Notes (For both Call and WhatsApp) -->
                                        <div class="col-md-12 mb-3" id="notesWrapper" style="display:none;">
                                            <label class="form-label">Notes</label>
                                            <textarea name="note" id="noteField" class="form-control" rows="3" placeholder="Enter notes..."></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                            <i class="fas fa-save me-2"></i>Save Log
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-lg" onclick="cancelForm()">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Call History Timeline -->
                            <div class="detail-card mt-4">
                                <div class="section-title">
                                    <i class="fas fa-history"></i>
                                    Call History
                                </div>
                                
                                <div class="timeline" id="callHistoryTimeline">
                                    <?php if (mysqli_num_rows($call_logs_result) > 0): ?>
                                        <?php while($call = mysqli_fetch_assoc($call_logs_result)): ?>
                                            <div class="timeline-item">
                                                <div class="timeline-content">
                                                    <div class="timeline-header">
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($call['call_outcome'] ?? 'Call Made'); ?></strong>
                                                            <span class="timeline-date ms-2">
                                                                <i class="far fa-clock me-1"></i>
                                                                <?php echo date('d M Y, h:i A', strtotime($call['created_at'])); ?>
                                                            </span>
                                                        </div>
                                                        <span class="timeline-outcome">
                                                            <i class="fas fa-phone-alt me-1"></i>
                                                            <?php echo htmlspecialchars($call['call_duration'] ?? '0:00'); ?>
                                                        </span>
                                                    </div>
                                                    
                                                    <?php if (!empty($call['note'])): ?>
                                                        <p class="mb-2" style="color: #555; font-size: 14px;">
                                                            <strong>Notes:</strong> <?php echo nl2br(htmlspecialchars($call['note'])); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($call['new_status'])): ?>
                                                        <p class="mb-0">
                                                            <small class="text-muted">
                                                                <i class="fas fa-exchange-alt me-1"></i>
                                                                Status Updated: 
                                                                <span class="badge bg-info"><?php echo htmlspecialchars($call['previous_status']); ?></span>
                                                                →
                                                                <span class="badge bg-success"><?php echo htmlspecialchars($call['new_status']); ?></span>
                                                            </small>
                                                        </p>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($call['next_follow_up'])): ?>
                                                        <p class="mb-2 mt-2">
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar-check me-1"></i>
                                                                Follow-up Scheduled: <strong><?php echo date('d M Y', strtotime($call['next_follow_up'])); 
                                                                if (!empty($call['next_follow_up_time'])) {
                                                                    echo ' at ' . date('h:i A', strtotime($call['next_follow_up_time']));
                                                                }
                                                                ?></strong>
                                                            </small>
                                                        </p>
                                                    <?php endif; ?>
                                                    
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($call['counsellor'] ?? 'Admin'); ?>
                                                        <span class="mx-2">•</span>
                                                        <span class="badge badge-secondary" style="background: #6c757d;"><?php echo htmlspecialchars($call['call_type']); ?></span>
                                                    </small>
                                                    
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-outline-primary" onclick="editCallLog(<?php echo $call['id']; ?>, <?php echo htmlspecialchars(json_encode($call), ENT_QUOTES, 'UTF-8'); ?>)">
                                                            <i class="fas fa-edit me-1"></i>Edit
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <p class="text-muted text-center py-4">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            No call history yet. Log your first call above!
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assetss/libs/js/main-js.js"></script>

        <script>
            let callStartTime = null;
let timerInterval = null;
let elapsedSeconds = 0;
let currentMode = null; // 'timer' or 'manual'

// Show Timed Call Section
function showTimerCall() {
    currentMode = 'timer';
    document.getElementById('callActionButtons').style.display = 'none';
    document.getElementById('callTimerWidget').classList.add('active');
    document.getElementById('callLogForm').classList.remove('active');
}

// Show Manual Log Form
function showManualLog() {
    currentMode = 'manual';
    document.getElementById('callActionButtons').style.display = 'none';
    document.getElementById('callTimerWidget').classList.remove('active');
    document.getElementById('callLogForm').classList.add('active');
    
    // Configure form for manual mode
    document.getElementById('formModeBadge').textContent = '✍️ Manual Entry';
    document.getElementById('formModeBadge').className = 'form-mode-badge manual-mode';
    document.getElementById('formTitle').textContent = 'Add Manual Log';
    document.getElementById('callMode').value = 'manual';
    
    // Reset form
    document.getElementById('saveCallForm').reset();
    document.getElementById('manualMinutes').value = '0';
    document.getElementById('manualSeconds').value = '0';
    
    // Hide all dynamic fields initially
    hideAllDynamicFields();
}

// Cancel Timed Call
function cancelTimedCall() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
    document.getElementById('callTimerWidget').classList.remove('active');
    document.getElementById('callActionButtons').style.display = 'flex';
    document.getElementById('timerDisplay').textContent = '00:00:00';
    document.getElementById('startCallBtn').style.display = 'inline-block';
    document.getElementById('endCallBtn').style.display = 'none';
    elapsedSeconds = 0;
}

// Cancel Form
function cancelForm() {
    document.getElementById('callLogForm').classList.remove('active');
    document.getElementById('callActionButtons').style.display = 'flex';
    document.getElementById('saveCallForm').reset();
    hideAllDynamicFields();
    
    // Reset edit mode
    isEditMode = false;
    editingCallLogId = null;
    const submitBtn = document.querySelector('#saveCallForm button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Log';
}

// Hide all dynamic fields
function hideAllDynamicFields() {
    document.getElementById('callTypeWrapper').style.display = 'none';
    document.getElementById('durationWrapper').style.display = 'none';
    document.getElementById('callOutcomeWrapper').style.display = 'none';
    document.getElementById('statusWrapper').style.display = 'none';
    document.getElementById('followUpWrapper').style.display = 'none';
    document.getElementById('followUpTimeWrapper').style.display = 'none';
    document.getElementById('notesWrapper').style.display = 'none';
}

// Start Call Timer
function startCall() {
    callStartTime = new Date();
    elapsedSeconds = 0;
    
    document.getElementById('startCallBtn').style.display = 'none';
    document.getElementById('endCallBtn').style.display = 'inline-block';
    
    timerInterval = setInterval(updateTimer, 1000);
}

// Update Timer Display
function updateTimer() {
    elapsedSeconds++;
    const hours = Math.floor(elapsedSeconds / 3600);
    const minutes = Math.floor((elapsedSeconds % 3600) / 60);
    const seconds = elapsedSeconds % 60;
    
    const display = 
        String(hours).padStart(2, '0') + ':' +
        String(minutes).padStart(2, '0') + ':' +
        String(seconds).padStart(2, '0');
    
    document.getElementById('timerDisplay').textContent = display;
}

// End Call Timer
function endCall() {
    if (!callStartTime) return;
    
    clearInterval(timerInterval);
    
    const minutes = Math.floor(elapsedSeconds / 60);
    const seconds = elapsedSeconds % 60;
    const durationText = minutes + ':' + String(seconds).padStart(2, '0');
    
    // Configure form for timer mode
    document.getElementById('formModeBadge').textContent = '⏱️ Timed Call';
    document.getElementById('formModeBadge').className = 'form-mode-badge timer-mode';
    document.getElementById('formTitle').textContent = 'Log Timed Call Details';
    document.getElementById('callMode').value = 'timer';
    
    // Set duration fields
    document.getElementById('callDurationDisplay').value = durationText;
    document.getElementById('durationSeconds').value = elapsedSeconds;
    document.getElementById('callDurationSubmit').value = durationText;
    
    // Pre-select "Call" in communication type
    document.getElementById('communicationType').value = 'call';
    handleCommunicationTypeChange();
    
    // Show timer duration display
    document.getElementById('callDurationDisplay').style.display = 'block';
    document.getElementById('manualDurationInputs').style.display = 'none';
    
    // Hide timer widget, show form
    document.getElementById('callTimerWidget').classList.remove('active');
    document.getElementById('callLogForm').classList.add('active');
    
    // Reset buttons
    document.getElementById('endCallBtn').style.display = 'none';
    document.getElementById('startCallBtn').style.display = 'inline-block';
    document.getElementById('timerDisplay').textContent = '00:00:00';
}

// Handle Communication Type Change
function handleCommunicationTypeChange() {
    const commType = document.getElementById('communicationType').value;
    
    // Hide all fields first
    hideAllDynamicFields();
    
    if (commType === 'call') {
        // Show Call-specific fields
        document.getElementById('callTypeWrapper').style.display = 'block';
        document.getElementById('durationWrapper').style.display = 'block';
        document.getElementById('callOutcomeWrapper').style.display = 'block';
        document.getElementById('statusWrapper').style.display = 'block';
        document.getElementById('notesWrapper').style.display = 'block';
        
        // Make call outcome required
        document.getElementById('callOutcome').required = true;
        
        // Show appropriate duration input based on mode
        if (currentMode === 'manual') {
            document.getElementById('manualDurationInputs').style.display = 'flex';
            document.getElementById('callDurationDisplay').style.display = 'none';
        } else {
            document.getElementById('callDurationDisplay').style.display = 'block';
            document.getElementById('manualDurationInputs').style.display = 'none';
        }
        
    } else if (commType === 'whatsapp') {
        // Show WhatsApp-specific fields
        document.getElementById('statusWrapper').style.display = 'block';
        document.getElementById('notesWrapper').style.display = 'block';
        
        // Remove call outcome requirement
        document.getElementById('callOutcome').required = false;
    }
}

// Handle Status Change for Follow-up
function handleStatusChange() {
    const commType = document.getElementById('communicationType').value;
    const callOutcome = document.getElementById('callOutcome').value;
    const newStatus = document.getElementById('newStatus').value;
    const followUpWrapper = document.getElementById('followUpWrapper');
    const followUpTimeWrapper = document.getElementById('followUpTimeWrapper');
    
    // Show follow-up field if conditions met
    if (commType === 'call') {
        if (callOutcome === 'Interested' || 
            callOutcome === 'Callback Requested' || 
            newStatus === 'Interested' || 
            newStatus === 'Follow-up Required') {
            followUpWrapper.style.display = 'block';
            followUpTimeWrapper.style.display = 'block';
        } else {
            followUpWrapper.style.display = 'none';
            followUpTimeWrapper.style.display = 'none';
        }
    } else if (commType === 'whatsapp') {
        if (newStatus === 'Interested' || newStatus === 'Follow-up Required') {
            followUpWrapper.style.display = 'block';
            followUpTimeWrapper.style.display = 'block';
        } else {
            followUpWrapper.style.display = 'none';
            followUpTimeWrapper.style.display = 'none';
        }
    }
}

// Edit Call Log Function
let isEditMode = false;
let editingCallLogId = null;

function editCallLog(callLogId, callData) {
    isEditMode = true;
    editingCallLogId = callLogId;
    currentMode = 'manual'; // Set mode for duration handling
    
    // Hide action buttons and show form
    document.getElementById('callActionButtons').style.display = 'none';
    document.getElementById('callTimerWidget').classList.remove('active');
    document.getElementById('callLogForm').classList.add('active');
    
    // Configure form for edit mode
    document.getElementById('formModeBadge').textContent = '✏️ Edit Mode';
    document.getElementById('formModeBadge').className = 'form-mode-badge manual-mode';
    document.getElementById('formTitle').textContent = 'Edit Call Log';
    document.getElementById('callMode').value = 'edit';
    
    // Pre-fill form with existing data
    document.getElementById('communicationType').value = callData.communication_type || 'call';
    handleCommunicationTypeChange();
    
    if (callData.communication_type === 'call') {
        document.getElementById('callType').value = callData.call_type || 'Outgoing';
        document.getElementById('callOutcome').value = callData.call_outcome || '';
        
        // Parse duration
        if (callData.call_duration) {
            const parts = callData.call_duration.split(':');
            if (parts.length === 2) {
                document.getElementById('manualMinutes').value = parseInt(parts[0]) || 0;
                document.getElementById('manualSeconds').value = parseInt(parts[1]) || 0;
            }
        }
    }
    
    document.getElementById('newStatus').value = callData.new_status || '';
    document.getElementById('noteField').value = callData.note || '';
    document.getElementById('nextFollowUp').value = callData.next_follow_up || '';
    document.getElementById('nextFollowUpTime').value = callData.next_follow_up_time || '';
    
    // Trigger status change to show follow-up fields if needed
    handleStatusChange();
    
    // Update submit button text
    const submitBtn = document.querySelector('#saveCallForm button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Log';
}

// Calculate manual duration before form submit
function calculateManualDuration() {
    const commType = document.getElementById('communicationType').value;
    
    if (currentMode === 'manual' && commType === 'call') {
        const minutes = parseInt(document.getElementById('manualMinutes').value) || 0;
        const seconds = parseInt(document.getElementById('manualSeconds').value) || 0;
        
        const totalSeconds = (minutes * 60) + seconds;
        const durationText = minutes + ':' + String(seconds).padStart(2, '0');
        
        document.getElementById('callDurationSubmit').value = durationText;
        document.getElementById('durationSeconds').value = totalSeconds;
    } else if (commType === 'whatsapp') {
        // For WhatsApp, set duration to 0
        document.getElementById('callDurationSubmit').value = '0:00';
        document.getElementById('durationSeconds').value = 0;
    }
}

// Save Call/WhatsApp Log via AJAX
$('#saveCallForm').on('submit', function(e) {
    e.preventDefault();
    
    // Calculate manual duration if needed
    calculateManualDuration();
    
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);
    
    // Determine endpoint and add call_log_id if editing
    let url = 'save-call-log.php';
    let formData = $(this).serialize();
    
    if (isEditMode && editingCallLogId) {
        url = 'update-call-log.php';
        formData += '&call_log_id=' + editingCallLogId;
    }
    
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('✅ Log saved successfully!');
                location.reload();
            } else {
                alert('❌ Error: ' + response.message);
                submitBtn.html(originalText).prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            alert('❌ Error saving log. Please try again.');
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});

// Event Listeners
document.addEventListener('DOMContentLoaded', function () {
    const communicationType = document.getElementById('communicationType');
    const callOutcome = document.getElementById('callOutcome');
    const newStatus = document.getElementById('newStatus');

    communicationType.addEventListener('change', handleCommunicationTypeChange);
    callOutcome.addEventListener('change', handleStatusChange);
    newStatus.addEventListener('change', handleStatusChange);
});
        </script>

    <?php } else {
        header("location: index.php");
    } ?>
</body>
</html>