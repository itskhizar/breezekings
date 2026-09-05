<?php
ob_start();
include("include/classes/session.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // make sure path is correct
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}



if ($session->logged_in) {
$username = $session->username;
$result1 = $database->getUserInfo($username);
$ulevel = ($result1['userlevel']);
$display_name = $result1['display_name'];
$email = $result1['email'];
$phone = $result1['phone'];
$image = $result1['parent_directory'];
$password = $result1['password'];


}else{
    header('location: index.php');
}


// Check if course_id is passed via GET
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : null;
$course_title = '';
$course_data = null;

if ($course_id) {
    $course_query = "SELECT * FROM courses WHERE id = '$course_id' LIMIT 1";
    $course_result = mysqli_query($conn, $course_query);
    if ($course_result && mysqli_num_rows($course_result) > 0) {
        $course_data = mysqli_fetch_assoc($course_result);
        $course_title = $course_data['course_title'];
    }
}

// Fetch all courses for dropdown (when no course_id is provided)
$all_courses_query = "SELECT id, course_title FROM courses ORDER BY course_title ASC";
$all_courses_result = mysqli_query($conn, $all_courses_query);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {

    // Sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $mobile_no = mysqli_real_escape_string($conn, $_POST['mobile_no']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $institute = mysqli_real_escape_string($conn, $_POST['institute']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $education_status = mysqli_real_escape_string($conn, $_POST['education_status']);
    $field_education = isset($_POST['field_education']) ? mysqli_real_escape_string($conn, $_POST['field_education']) : "";
    $additional_info = isset($_POST['additional_info']) ? mysqli_real_escape_string($conn, $_POST['additional_info']) : "";
    
    // Course ID from form
    $selected_course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : null;

    // Field of Interest (checkboxes) - Now optional
    $field_interest = isset($_POST['field_interest']) ? $_POST['field_interest'] : [];
    $field_interest_str = !empty($field_interest) ? mysqli_real_escape_string($conn, implode(", ", $field_interest)) : NULL;

    // Other field of interest (text input)
    $other_interest = isset($_POST['other_interest']) && !empty(trim($_POST['other_interest'])) 
        ? mysqli_real_escape_string($conn, trim($_POST['other_interest'])) 
        : NULL;

    // Combine field_interest and other_interest
    if ($other_interest) {
        $field_interest_str = $field_interest_str 
            ? $field_interest_str . ", " . $other_interest 
            : $other_interest;
    }

    // Conditional fields
    $grade = in_array($education_status, ['Primary', 'Secondary', 'Intermediate']) 
        ? mysqli_real_escape_string($conn, $_POST['grade']) : NULL;

    $current_semester = ($education_status === 'Undergraduate') 
        ? mysqli_real_escape_string($conn, $_POST['current_semester']) : NULL;

    $graduation_year = ($education_status === 'Graduate') 
        ? mysqli_real_escape_string($conn, $_POST['graduation_year']) : NULL;

    $other_degree = ($education_status === 'Other') 
        ? mysqli_real_escape_string($conn, $_POST['other_degree']) : NULL;
        
    $visit_date = isset($_POST['visit_date']) ? mysqli_real_escape_string($conn, $_POST['visit_date']) : NULL;
    $visit_time = isset($_POST['visit_time']) && !empty($_POST['visit_time']) 
        ? date("h:i A", strtotime($_POST['visit_time'])) 
        : NULL;

    $reference = "website";

    // Check if email already exists
    $emailCheckQuery = "SELECT email FROM admissions WHERE email = '$email'";
    $emailCheckResult = mysqli_query($conn, $emailCheckQuery);

    if (mysqli_num_rows($emailCheckResult) > 0) {
        echo json_encode(['success' => false, 'message' => 'This email is already registered.']);
        exit;
    }

    // Insert query with course_id
   $query = "INSERT INTO `admissions`
    (`name`, `gender`, `mobile_no`, `email`, `institute`, `field_education`, `field_interest`,
     `education_status`, `grade`, `current_semester`, `graduation_year`, `other_degree`, `address`, `additional_info`, `visit_date`, `visit_time`, `course_id`, `timestamp`, `reference`) 
    VALUES 
    ('$name', '$gender', '$mobile_no', '$email', '$institute', '$field_education', '$field_interest_str',
     '$education_status', '$grade', '$current_semester', '$graduation_year', '$other_degree', '$address', '$additional_info', '$visit_date', '$visit_time', " . ($selected_course_id ? "'$selected_course_id'" : "NULL") . ", NOW(), '$reference')";


if (mysqli_query($conn, $query)) {

    // Get course details safely
    $course = null;
    if (!empty($selected_course_id)) {
        $course_query = "SELECT * FROM courses WHERE id = '".intval($selected_course_id)."' LIMIT 1";
        $course_result = mysqli_query($conn, $course_query);
        $course = mysqli_fetch_assoc($course_result);
    }

    // ---------------------- SMTP SETTINGS ----------------------
    $smtpHost = "smtp.hostinger.com"; // Hostinger SMTP host
    $smtpPort = 587;                  // or 465 for SSL
    $smtpUser = "admin@filenod.com"; // your email
    $smtpPass = "Asad@1234A"; // your email password
    // -----------------------------------------------------------

    // ---------------------- ADMIN EMAIL ------------------------
    $mailAdmin = new PHPMailer(true);
    try {
        $mailAdmin->isSMTP();
        $mailAdmin->Host       = $smtpHost;
        $mailAdmin->SMTPAuth   = true;
        $mailAdmin->Username   = $smtpUser;
        $mailAdmin->Password   = $smtpPass;
        $mailAdmin->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailAdmin->Port       = $smtpPort;

        $mailAdmin->setFrom($smtpUser, 'Filenod Academy');
        $mailAdmin->addAddress('asadhussain3300@gmail.com'); // admin email
        $mailAdmin->addCC('khizarahmad0920@gmail.com');

        $mailAdmin->isHTML(true);
        $mailAdmin->Subject = "New Admission Enquiry Submitted";
        $mailAdmin->Body = "
            <h2>New Admission Enquiry</h2>
            <p><strong>Name:</strong> ".htmlspecialchars($name)."</p>
      <p><strong>Email:</strong> ".htmlspecialchars($email)."</p>
      <p><strong>Mobile No:</strong> ".htmlspecialchars($mobile_no)."</p>
      <p><strong>Institute:</strong> ".htmlspecialchars($institute)."</p>
      <p><strong>Field of Education:</strong> ".htmlspecialchars($field_education)."</p>
      <p><strong>Applied Course:</strong> ".($course ? htmlspecialchars($course['course_title']) : 'N/A')."</p>
      <p><strong>Other Field of Interest:</strong> ".htmlspecialchars($field_interest_str)."</p>
      <p><strong>Education Status:</strong> ".htmlspecialchars($education_status)."</p>
      <p><strong>Grade:</strong> ".htmlspecialchars($grade)."</p>
      <p><strong>Current Semester:</strong> ".htmlspecialchars($current_semester)."</p>
      <p><strong>Graduation Year:</strong> ".htmlspecialchars($graduation_year)."</p>
      <p><strong>Other Degree:</strong> ".htmlspecialchars($other_degree)."</p>
      <p><strong>Address:</strong> ".htmlspecialchars($address)."</p>
      <p><strong>Additional Info:</strong> ".htmlspecialchars($additional_info)."</p>
      <p><strong>Visit Date:</strong> ".htmlspecialchars($visit_date)."</p>
      <p><strong>Visit Time:</strong> ".htmlspecialchars($visit_time)."</p>
      <p><strong>Reference:</strong> ".htmlspecialchars($reference)."</p>
        ";

        $mailAdmin->send();
    } catch (Exception $e) {
        error_log("Admin Email Error: " . $mailAdmin->ErrorInfo);
    }

    // ---------------------- USER EMAIL -------------------------
    $mailUser = new PHPMailer(true);
    try {
        $mailUser->isSMTP();
        $mailUser->Host       = $smtpHost;
        $mailUser->SMTPAuth   = true;
        $mailUser->Username   = $smtpUser;
        $mailUser->Password   = $smtpPass;
        $mailUser->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mailUser->Port       = $smtpPort;

        $mailUser->setFrom($smtpUser, 'Filenod Academy');
        $mailUser->addAddress($email, $name); // user email

        $mailUser->isHTML(true);
        $mailUser->Subject = "Thank You for Your Enquiry at Filenod Academy";
        $mailUser->Body = "
            <p>Hi <strong>".htmlspecialchars($name)."</strong>,</p>
            <p>
        Thank you for reaching out to <strong>Filenod Academy</strong> regarding our
        <strong>".htmlspecialchars($courseName)."</strong> program.
        Your enquiry has been successfully received.
      </p>

      <p>A dedicated career counsellor will contact you soon to guide you about:</p>

      <ul>
        <li>Course structure</li>
        <li>Fees & duration</li>
        <li>Class schedules</li>
        <li>Job & freelancing roadmap</li>
        <li>Admission process</li>
      </ul>

      <p>
        In the meantime, you can explore our programs here:<br>
        👉 <a href='https://academy.filenod.com'>academy.filenod.com</a>
      </p>

      <p>
        If you prefer, you may also contact us directly:<br>
        📞 +92-344-6541668<br>
        📧 info@filenod.com
      </p>

      <p>
        We look forward to helping you start your tech journey!
      </p>

      <p>
        —<br>
        <strong>Filenod Academy</strong><br>
        <em>Empowering Pakistan’s youth through modern education and real-world training.</em>
      </p>
        ";

        $mailUser->send();
    } catch (Exception $e) {
        error_log("User Email Error: " . $mailUser->ErrorInfo);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Your application has been submitted successfully! We will contact you soon.'
    ]);

} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to submit application: ' . mysqli_error($conn)
    ]);
}
exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assetss/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assetss/libs/css/style.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="assetss/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="assetss/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assetss/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="assetss/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <link href="assets/img/favicon-filenod.png" rel="icon">
    <title>Admission Form | Filenod Academy</title>
    <style>
        .course-highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .course-highlight-title {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .course-highlight-name {
            font-size: 24px;
            font-weight: 700;
        }

        .interest-section {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: all 0.4s ease;
        }

        .interest-section.open {
            max-height: 1000px;
            opacity: 1;
            margin-top: 16px;
        }

        .interest-checkbox {
             cursor: pointer;
             padding: 10px;
             border: 1px solid #e9ecef;
             border-radius: 8px;
             display: block;
             transition: all 0.2s;
        }
        
        .interest-checkbox:hover {
            background-color: #f8f9fa;
            border-color: #667eea;
        }

        .interest-checkbox input {
            margin-right: 10px;
        }

        .interest-tag {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        .interest-tag .remove-tag {
            cursor: pointer;
            margin-left: 5px;
        }

        .dashboard-wrapper {
            margin-left: 264px;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .dashboard-wrapper {
                margin-left: 0;
            }
        }
         .dashboard-main-wrapper {
            min-height: 100vh;
            position: relative;
            padding-top: 0px;
            overflow-x: hidden;
            max-width: 100%;
        }

        .dashboard-wrapper {
            background: var(--gray-50);
            min-height: 100vh;
            padding-top: 0;
            overflow-x: hidden;
            max-width: 100%;
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
                


                <!-- Alert Container -->
                <div id="formAlert"></div>

                <div class="row">
                    <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 col-12 mx-auto">
                        <div class="card shadow-sm">
                            <h5 class="card-header border-bottom">Admission Form</h5>
                            <div class="card-body">
                                
                                <?php if ($course_data): ?>
                                <div class="course-highlight">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="me-3">
                                            <i class="fas fa-book-open fa-2x"></i>
                                        </div>
                                        <div>
                                            <p class="course-highlight-title mb-1">
                                                <i class="fas fa-check-circle mr-1"></i> You are applying for:
                                            </p>
                                            <p class="course-highlight-name mb-0"><?php echo htmlspecialchars($course_title); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <form id="studentRegistrationForm" method="POST">
                                    <?php if ($course_id): ?>
                                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                                    <?php endif; ?>

                                    <div class="row g-3">
                                        
                                        <!-- Course Selection -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Course <span class="text-danger">*</span></label>
                                            <?php if ($course_id && $course_data): ?>
                                                <input type="text" value="<?php echo htmlspecialchars($course_title); ?>" readonly class="form-control bg-light">
                                                <small class="text-muted">Course pre-selected.</small>
                                            <?php else: ?>
                                                <select name="course_id" required class="form-select">
                                                    <option value="" disabled selected>Select a Course</option>
                                                    <?php 
                                                    if ($all_courses_result && mysqli_num_rows($all_courses_result) > 0) {
                                                        while ($course_row = mysqli_fetch_assoc($all_courses_result)) {
                                                            echo '<option value="' . $course_row['id'] . '">' . htmlspecialchars($course_row['course_title']) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Personal Info -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" required class="form-control" placeholder="Enter full name">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Gender <span class="text-danger">*</span></label>
                                            <select name="gender" required class="form-select">
                                                <option value="" disabled selected>Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Mobile Number <span class="text-danger">*</span></label>
                                            <input type="text" name="mobile_no" required class="form-control" placeholder="03xxxxxxxxx">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" required class="form-control" placeholder="Enter email">
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Address <span class="text-danger">*</span></label>
                                            <input type="text" name="address" required class="form-control" placeholder="Enter complete address">
                                        </div>

                                        <!-- Education -->
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Education Status <span class="text-danger">*</span></label>
                                            <select name="education_status" id="education_status" onchange="toggleEducationFields()" required class="form-select">
                                                <option value="" disabled selected>Select Status</option>
                                                <option value="Primary">Primary</option>
                                                <option value="Secondary">Secondary</option>
                                                <option value="Intermediate">Intermediate</option>
                                                <option value="Undergraduate">Undergraduate</option>
                                                <option value="Graduate">Graduate</option>
                                                <option value="Other">Other / Diploma</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3 d-none" id="gradeField">
                                            <label class="form-label fw-bold">Grade <span class="text-danger">*</span></label>
                                            <input type="text" name="grade" class="form-control" placeholder="Enter class">
                                        </div>

                                        <div class="col-md-6 mb-3 d-none" id="semesterField">
                                            <label class="form-label fw-bold">Current Semester <span class="text-danger">*</span></label>
                                            <input type="text" name="current_semester" class="form-control" placeholder="e.g., 3rd, 5th">
                                        </div>

                                        <div class="col-md-6 mb-3 d-none" id="graduationYear">
                                            <label class="form-label fw-bold">Graduation Year <span class="text-danger">*</span></label>
                                            <input type="text" name="graduation_year" class="form-control" placeholder="e.g., 2024">
                                        </div>

                                        <div class="col-12 mb-3 d-none" id="otherField">
                                            <label class="form-label fw-bold">Specify Degree / Diploma</label>
                                            <input type="text" name="other_degree" class="form-control" placeholder="e.g., Diploma in IT">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Institute <span class="text-danger">*</span></label>
                                            <input type="text" name="institute" required class="form-control" placeholder="University / College">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">Field of Education <span class="text-danger">*</span></label>
                                            <input type="text" name="field_education" required class="form-control" placeholder="e.g., Computer Science">
                                        </div>

                                        <!-- Interests -->
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-bold mb-2">Field of Interest <span class="text-muted fw-normal">(Optional)</span></label>
                                            
                                            <div id="selectedInterestsDisplay" class="mb-3"></div>

                                            <button type="button" id="toggleInterestBtn" class="btn btn-outline-secondary w-100" onclick="toggleInterestSection()">
                                                <i class="fas fa-plus-circle mr-2"></i> <span id="toggleBtnText">Select Fields of Interest</span>
                                            </button>

                                            <div id="interestSection" class="interest-section mt-3">
                                                <div class="card card-body bg-light border-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="interest-checkbox mb-2">
                                                                <input type="checkbox" name="field_interest[]" value="Web Development" onchange="updateSelectedInterests()"> Web Development
                                                            </label>
                                                            <label class="interest-checkbox mb-2">
                                                                <input type="checkbox" name="field_interest[]" value="Graphic Designing" onchange="updateSelectedInterests()"> Graphic Designing
                                                            </label>
                                                            <label class="interest-checkbox mb-2">
                                                                <input type="checkbox" name="field_interest[]" value="Mobile App Development" onchange="updateSelectedInterests()"> Mobile App Development
                                                            </label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="interest-checkbox mb-2">
                                                                <input type="checkbox" name="field_interest[]" value="Digital Marketing" onchange="updateSelectedInterests()"> Digital Marketing
                                                            </label>
                                                            <label class="interest-checkbox mb-2">
                                                                <input type="checkbox" name="field_interest[]" value="Amazon VA" onchange="updateSelectedInterests()"> Amazon VA
                                                            </label>
                                                            <label class="interest-checkbox mb-2">
                                                                <input type="checkbox" name="field_interest[]" value="Freelancing" onchange="updateSelectedInterests()"> Freelancing
                                                            </label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-3">
                                                        <label class="form-label">Other Field of Interest</label>
                                                        <input type="text" name="other_interest" class="form-control" placeholder="Type here...">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Visit Info -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Planning to Visit Academy?</label>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Date</label>
                                                    <input type="date" name="visit_date" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Time</label>
                                                    <input type="time" name="visit_time" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Additional Info -->
                                        <div class="col-12 mt-3 mb-3">
                                            <label class="form-label fw-bold">Additional Information</label>
                                            <textarea name="additional_info" class="form-control" rows="3" placeholder="Any queries or comments..."></textarea>
                                        </div>

                                        <!-- Submit -->
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">
                                                <i class="fas fa-paper-plane mr-2"></i> Submit Application
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
<?php } else { header("Location: index.php?not_logged_in"); } ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assetss/libs/js/main-js.js"></script>

    <script>
        // Toggle Education Fields
        function toggleEducationFields() {
            let status = document.getElementById('education_status').value;
            
            ['gradeField', 'semesterField', 'graduationYear', 'otherField'].forEach(id => {
                document.getElementById(id).classList.add('d-none');
            });

            if (['Primary', 'Secondary', 'Intermediate'].includes(status)) {
                document.getElementById('gradeField').classList.remove('d-none');
            } else if (status === "Undergraduate") {
                document.getElementById('semesterField').classList.remove('d-none');
            } else if (status === "Graduate") {
                document.getElementById('graduationYear').classList.remove('d-none');
            } else if (status === "Other") {
                document.getElementById('otherField').classList.remove('d-none');
            }
        }

        // Toggle Interest Section
        function toggleInterestSection() {
            const section = document.getElementById('interestSection');
            const btnText = document.getElementById('toggleBtnText');

            if (section.classList.contains('open')) {
                section.classList.remove('open');
                btnText.textContent = 'Select Fields of Interest';
            } else {
                section.classList.add('open');
                btnText.textContent = 'Hide Fields of Interest';
            }
        }

        // Update Selected Interests
        function updateSelectedInterests() {
            const checkboxes = document.querySelectorAll('input[name="field_interest[]"]:checked');
            const displayContainer = document.getElementById('selectedInterestsDisplay');
            
            displayContainer.innerHTML = '';

            checkboxes.forEach(checkbox => {
                const tag = document.createElement('span');
                tag.className = 'interest-tag';
                tag.innerHTML = `${checkbox.value} <span class="remove-tag" onclick="removeInterest('${checkbox.value}')">&times;</span>`;
                displayContainer.appendChild(tag);
            });
        }

        function removeInterest(value) {
            const checkbox = document.querySelector(`input[name="field_interest[]"][value="${value}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateSelectedInterests();
            }
        }

        // AJAX Submission
        $(document).ready(function() {
            $('#studentRegistrationForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        try {
                            var res = JSON.parse(response);
                            var alertClass = res.success ? 'alert-success' : 'alert-danger';
                            var alertHTML = `
                                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                                    <strong>${res.success ? 'Success!' : 'Error!'}</strong> ${res.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                            $('#formAlert').html(alertHTML);
                            $('html, body').animate({ scrollTop: 0 }, 'fast');

                            if (res.success) {
                                $('#studentRegistrationForm')[0].reset();
                                $('#selectedInterestsDisplay').empty();
                            }
                        } catch (e) {
                            console.error("Parse Error", e);
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</body>
</html>