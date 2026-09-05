<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // make sure path is correct
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
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

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ====== Meta SEO ====== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admission Form – Filenod Academy | Register Online</title>
    <meta name="description" content="Apply for admission at Filenod Academy. Fill out the online registration form to enroll in professional IT training and skill-based programs.">

    <!-- Open Graph / Social Meta -->
    <meta property="og:title" content="Filenod Academy – Admission Form">
    <meta property="og:description" content="Register online and secure your admission at Filenod Academy.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://filenod.com/logo.png">
    <meta property="og:url" content="https://academy.filenod.com/admission">

    <!-- Schema Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name": "Filenod Academy",
      "url": "https://academy.filenod.com",
      "logo": "https://filenod.com/logo.png",
      "sameAs": [
        "https://facebook.com/filenod",
        "https://instagram.com/filenod"
      ]
    }
    </script>

    <!-- Tailwind / Fonts / Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="nod.png" rel="icon">
    
    <style>
        body { font-family: 'Inter', sans-serif; background: #f5f7fb; }
        .header-bg { background: linear-gradient(135deg, #173663, #2e4b8a); }
        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.1);
            padding: 40px 32px;
        }
        .fade { animation: fadeIn .7s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hover-scale {
            transition: transform 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: translateY(-5px);
        }
        
        .card-shadow {
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .fade-in {
            animation: fadeIn 1s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Desktop hover underline for nav links */
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: #667eea;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        .nav-link:hover::after {
            width: 100%;
        }
        
        .stats-number {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
        }
        
        .content-wrapper {
            margin-top: 120px;
        }

        /* Course Card Styles */
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

        /* Toggle Button Styles */
        .toggle-btn {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border: 2px solid #d1d5db;
            color: #374151;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-btn:hover {
            background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
            border-color: #9ca3af;
        }

        .toggle-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }

        .toggle-btn .icon {
            transition: transform 0.3s ease;
        }

        .toggle-btn.active .icon {
            transform: rotate(180deg);
        }

        /* Interest Section Styles */
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

        .interest-grid {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
        }

        .interest-checkbox {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .interest-checkbox:hover {
            border-color: #667eea;
            background: #f5f3ff;
        }

        .interest-checkbox.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, #ede9fe 0%, #f3e8ff 100%);
        }

        .interest-checkbox input[type="checkbox"] {
            accent-color: #667eea;
        }

        /* Other Interest Input */
        .other-interest-input {
            margin-top: 16px;
            padding: 16px;
            background: #fff7ed;
            border: 2px dashed #fb923c;
            border-radius: 10px;
        }

        /* Read-only course input */
        .readonly-course {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            color: #0369a1;
            font-weight: 600;
        }

        /* Selected interests display */
        .selected-interests-display {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
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
        }

        .interest-tag .remove-tag {
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .interest-tag .remove-tag:hover {
            opacity: 1;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
   <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logo -->
                <div class="flex items-center">
                    <a href="index.php">
                        <img src="images/logo.png" alt="Filenod Logo" class="w-40 h-40 md:w-34 md:h-34 object-contain">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Home</a>
                    <a href="about.php" class="nav-link text-gray-700 hover:text-purple-600 font-medium">About</a>
                    <a href="courses.php" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Courses</a>
                    <a href="career.php" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Career</a>

                    <!-- Programs Dropdown -->
                    <!-- <div class="relative group">
                        <button class="nav-link text-gray-700 hover:text-purple-600 font-medium flex items-center gap-1">
                            Programs <i class="fas fa-chevron-down text-sm"></i>
                        </button>
                        <div class="absolute left-0 top-full mt-1 w-52 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-300 z-50">
                            <a href="programs.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">All Programs</a>
                            <a href="programs.php#mentorship" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">Mentorship Program</a>
                            <a href="programs.php#internship" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">Internship Program</a>
                            <a href="programs.php#freelancing" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">Freelancing Track</a>
                            <a href="programs.php#roadmap" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">Structured Roadmap</a>
                        </div>
                    </div> -->

                    <a href="index.php#features" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Features</a>
                    <a href="index.php#contact" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Contact</a>
                </div>

                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="login.php" 
                       class="text-purple-600 hover:text-purple-700 font-semibold px-4 py-2 rounded-lg border border-purple-600 hover:bg-purple-50 transition">
                        Portal Login
                    </a>
                    <a href="admission.php" class="gradient-bg text-white px-6 py-2.5 rounded-lg font-semibold hover:shadow-lg transition transform hover:scale-105">
                        Get Started
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 pt-2 pb-4 space-y-2">
                <a href="index.php#home" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Home</a>
                <a href="about.php" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">About</a>
                <a href="courses.php" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Courses</a>
            <a href="career.php" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Career</a>

                <!-- Mobile Programs Dropdown -->
                <!-- <div class="relative">
                    <button id="mobile-programs-btn" class="w-full text-left flex justify-between items-center px-3 py-2 text-gray-700 font-medium hover:bg-purple-50 rounded-lg">
                        Programs <i class="fas fa-chevron-down"></i>
                    </button>
                    <div id="mobile-programs-menu" class="hidden pl-4 mt-1 space-y-1">
                        <a href="programs.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">All Programs</a>
                        <a href="programs.php#mentorship" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Mentorship Program</a>
                        <a href="programs.php#internship" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Internship Program</a>
                        <a href="programs.php#freelancing" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Freelancing Track</a>
                        <a href="programs.php#roadmap" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Structured Roadmap</a>
                    </div>
                </div> -->

                <a href="index.php#features" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Features</a>
                <a href="index.php#contact" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Contact</a>
                <a href="login.php" class="block px-3 py-2 text-purple-600 hover:bg-purple-50 rounded-lg font-semibold">Portal Login</a>
                <a href="admission.php" class="block gradient-bg text-white px-3 py-2 rounded-lg font-semibold">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- ========== Page Container ========== -->
    <main class="content-wrapper max-w-5xl mx-auto px-4 md:px-0 mb-16 fade" role="main">

        <section class="form-card max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-16">
            <div id="formAlert"></div>
            
            <h2 class="text-3xl font-bold mb-4 text-gray-800 text-center">
                <i class="fas fa-graduation-cap text-purple-600 mr-2"></i>
                Admission Form
            </h2>

            <p class="text-gray-600 text-center mb-8">
                Please fill out the form below to apply for admission. Fields marked with <span class="text-red-500">*</span> are required.
            </p>

            <?php if ($course_data): ?>
            <!-- Course Highlight Card (when course_id is provided) -->
            <div class="course-highlight">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>
                    <div>
                        <p class="course-highlight-title">
                            <i class="fas fa-check-circle mr-1"></i> You are applying for:
                        </p>
                        <p class="course-highlight-name"><?php echo htmlspecialchars($course_title); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ========== Form Start ========== -->
            <form id="studentRegistrationForm" method="POST" class="space-y-6" aria-label="Admission Form">

                <!-- Hidden course_id field -->
                <?php if ($course_id): ?>
                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Course Selection / Display -->
                    <div class="md:col-span-2">
                        <label class="font-semibold text-gray-700">
                            <i class="fas fa-book text-purple-500 mr-1"></i>
                            Course <?php echo $course_id ? '' : '<span class="text-red-500">*</span>'; ?>
                        </label>
                        
                        <?php if ($course_id && $course_data): ?>
                        <!-- Read-only course display -->
                        <input type="text" 
                               value="<?php echo htmlspecialchars($course_title); ?>" 
                               readonly
                               class="w-full mt-2 px-4 py-3 border rounded-lg readonly-course cursor-not-allowed">
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Course pre-selected from course page. 
                            <a href="courses.php" class="text-purple-600 hover:underline">Change course?</a>
                        </p>
                        <?php else: ?>
                        <!-- Course dropdown -->
                        <select name="course_id" required
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
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

                    <!-- Full Name -->
                    <div>
                        <label class="font-semibold text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="Enter your full name">
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="font-semibold text-gray-700">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" required
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <!-- Mobile -->
                    <div>
                        <label class="font-semibold text-gray-700">Mobile Number <span class="text-red-500">*</span></label>
                        <input type="text" name="mobile_no" required
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="03xxxxxxxxx">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="font-semibold text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="Enter your email">
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="font-semibold text-gray-700">Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address" required
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="Enter your complete address">
                    </div>

                    <!-- Education Status -->
                    <div>
                        <label class="font-semibold text-gray-700">Education Status <span class="text-red-500">*</span></label>
                        <select name="education_status" id="education_status" onchange="toggleEducationFields()" required
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                            <option value="" disabled selected>Select Status</option>
                            <option value="Primary">Primary</option>
                            <option value="Secondary">Secondary</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Undergraduate">Undergraduate</option>
                            <option value="Graduate">Graduate</option>
                            <option value="Other">Other / Diploma / Other Degree</option>
                        </select>
                    </div>

                    <!-- Grade -->
                    <div id="gradeField" class="hidden">
                        <label class="font-semibold text-gray-700">Grade <span class="text-red-500">*</span></label>
                        <input type="text" name="grade"
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="Enter class">
                    </div>

                    <!-- Current Semester -->
                    <div id="semesterField" class="hidden">
                        <label class="font-semibold text-gray-700">Current Semester <span class="text-red-500">*</span></label>
                        <input type="text" name="current_semester"
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="e.g., 3rd, 5th">
                    </div>

                    <div id="graduationYear" class="hidden">
                        <label class="font-semibold text-gray-700">Graduation Year <span class="text-red-500">*</span></label>
                        <input type="text" name="graduation_year"
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="e.g., 2024, 2025">
                    </div>

                    <!-- Other Degree -->
                    <div id="otherField" class="hidden md:col-span-2">
                        <label class="font-semibold text-gray-700">Specify Degree / Diploma</label>
                        <input type="text" name="other_degree"
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="e.g., Diploma in IT">
                    </div>

                    <!-- Institute -->
                    <div>
                        <label class="font-semibold text-gray-700">Institute <span class="text-red-500">*</span></label>
                        <input type="text" name="institute" required
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="University / College / School">
                    </div>

                    <!-- Field of Education -->
                    <div>
                        <label class="font-semibold text-gray-700">Field of Education <span class="text-red-500">*</span></label>
                        <input type="text" name="field_education" required
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="e.g., Computer Science, Medical">
                    </div>

                    <!-- Field of Interest Section with Toggle Button -->
                    <div class="md:col-span-2">
                        <label class="font-semibold text-gray-700 mb-3 block">
                            <i class="fas fa-heart text-pink-500 mr-1"></i>
                            Field of Interest 
                            <span class="text-gray-400 text-sm font-normal">(Optional)</span>
                        </label>
                        
                        <!-- Selected Interests Display -->
                        <div id="selectedInterestsDisplay" class="selected-interests-display mb-3">
                            <!-- Tags will be dynamically added here -->
                        </div>

                        <!-- Toggle Button -->
                        <button type="button" id="toggleInterestBtn" class="toggle-btn" onclick="toggleInterestSection()">
                            <i class="fas fa-plus-circle"></i>
                            <span id="toggleBtnText">Select Fields of Interest</span>
                            <i class="fas fa-chevron-down icon"></i>
                        </button>

                        <!-- Collapsible Interest Section -->
                        <div id="interestSection" class="interest-section">
                            <div class="interest-grid">
                                <p class="text-sm text-gray-500 mb-4">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Select one or more areas you're interested in learning:
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <!-- Left Column -->
                                    <div class="flex flex-col space-y-3">
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Web Development" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-code text-blue-500"></i> Web Development
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Graphic Designing" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-palette text-pink-500"></i> Graphic Designing
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Mobile App Development" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-mobile-alt text-green-500"></i> Mobile App Development
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="AI / Machine Learning" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-robot text-purple-500"></i> AI / Machine Learning
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Data Science" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-chart-bar text-cyan-500"></i> Data Science
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Cyber Security" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-shield-alt text-red-500"></i> Cyber Security
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="UI / UX Design" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-pencil-ruler text-orange-500"></i> UI / UX Design
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Digital Marketing" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-bullhorn text-yellow-500"></i> Digital Marketing
                                            </span>
                                        </label>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="flex flex-col space-y-3">
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Cloud Computing" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-cloud text-blue-400"></i> Cloud Computing
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Blockchain Development" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-link text-indigo-500"></i> Blockchain Development
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="DevOps & Cloud Engineering" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-cogs text-gray-500"></i> DevOps & Cloud Engineering
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Game Development" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-gamepad text-green-600"></i> Game Development
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="AR / VR Development" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-vr-cardboard text-teal-500"></i> AR / VR Development
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Robotics & Automation" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fas fa-robot text-gray-600"></i> Robotics & Automation
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Amazon Web Services (AWS)" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fab fa-aws text-orange-500"></i> Amazon Web Services (AWS)
                                            </span>
                                        </label>
                                        <label class="interest-checkbox flex items-center space-x-3">
                                            <input type="checkbox" name="field_interest[]" value="Amazon eCommerce / Marketplace" 
                                                   class="form-checkbox h-5 w-5 text-purple-600 rounded" onchange="updateSelectedInterests()">
                                            <span class="flex items-center gap-2">
                                                <i class="fab fa-amazon text-yellow-600"></i> Amazon eCommerce / Marketplace
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Other Interest Input -->
                                <div class="other-interest-input">
                                    <label class="font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                        <i class="fas fa-plus-circle text-orange-500"></i>
                                        Other Field of Interest
                                    </label>
                                    <input type="text" name="other_interest" id="otherInterestInput"
                                        class="w-full px-4 py-3 border-2 border-orange-200 rounded-lg focus:ring-2 focus:ring-orange-300 focus:border-orange-400"
                                        placeholder="Enter any other field you're interested in...">
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-lightbulb mr-1"></i>
                                        Can't find what you're looking for? Type it here!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Planning to Visit Academy -->
                    <div class="md:col-span-2 mt-4">
                        <label class="font-semibold text-gray-700">
                            <i class="fas fa-calendar-check text-green-500 mr-1"></i>
                            Planning to Visit Academy?
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <!-- Visit Date -->
                            <div>
                                <label class="text-gray-700">Select Date</label>
                                <input type="date" name="visit_date"
                                    class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                            </div>
                            <!-- Visit Time -->
                            <div>
                                <label class="text-gray-700">Select Time</label>
                                <input type="time" name="visit_time"
                                    class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="md:col-span-2 mt-1">
                        <label class="font-semibold text-gray-700" for="additional_info">
                            <i class="fas fa-comment-alt text-blue-500 mr-1"></i>
                            Additional Information / Queries
                        </label>
                        <textarea name="additional_info" id="additional_info" rows="4"
                            class="w-full mt-2 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
                            placeholder="Enter any questions, comments, or additional information you want us to know"></textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-4 mt-6 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-semibold text-lg rounded-lg hover:from-purple-700 hover:to-blue-700 transition transform hover:scale-[1.02] shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Admission Application
                </button>

            </form>
        </section>

    </main>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/923454955590" target="_blank" 
       class="fixed bottom-6 right-6 bg-green-500 text-white px-4 py-3 rounded-full shadow-lg flex items-center gap-2 hover:scale-110 transition z-50">
        <i class="fab fa-whatsapp text-2xl"></i>
    </a>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center mb-4">
                        <a href="index.php">
                            <h2 class="text-4xl md:text-5xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-900 via-blue-700 to-blue-500 drop-shadow-lg">
                                Filenod
                            </h2>
                        </a>
                    </div>
                    <p class="text-white leading-relaxed">
                        Empowering Pakistan's future tech leaders with world-class education and hands-on training.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="about.php" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        <li><a href="index.php#features" class="text-gray-400 hover:text-white transition">Features</a></li>
                        <li><a href="courses.php" class="text-gray-400 hover:text-white transition">Courses</a></li>
                        <li><a href="career.php" class="text-gray-400 hover:text-white transition">Career</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-bold mb-4">Popular Courses</h4>
                    <ul class="space-y-2">
                        <li><a href="courses.php" class="text-gray-400 hover:text-white transition">Full Stack Web Development</a></li>
                        <li><a href="courses.php" class="text-gray-400 hover:text-white transition">Frontend Development</a></li>
                        <li><a href="courses.php" class="text-gray-400 hover:text-white transition">Backend Development</a></li>
                        <li><a href="courses.php" class="text-gray-400 hover:text-white transition">WordPress & CMS Development</a></li>
                        <li><a href="courses.php" class="text-gray-400 hover:text-white transition">Shopify Store Development</a></li>
                        <li><a href="courses.php" class="text-gray-400 hover:text-white transition">Wix Website Development</a></li>
                        <!-- <li><a href="#courses" class="text-gray-400 hover:text-white transition">SEO</a></li> -->
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-4">Contact Us</h4>
                    <ul class="space-y-2">
                        <li class="text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i>Filenod Office — Opposite UBL Mandian, Abbottabad, 22044, Pakistan</li>
                        <li class="text-gray-400"><i class="fas fa-phone mr-2"></i>+92 345 4955590</li>
                        <li class="text-gray-400"><i class="fas fa-envelope mr-2"></i>info@filenod.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">
                    © <?php echo date('Y') ;?> Filenod Academy. All rights reserved. | Powered by <a href="https://filenod.com" class="text-purple-400 hover:text-purple-300">Filenod.com</a>
                </p>
                <div class="flex space-x-6">
                    <a href="privacy-policy.php" class="text-gray-400 hover:text-white transition">Privacy Policy</a>
                    <a href="termsofservices.php" class="text-gray-400 hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Mobile programs dropdown toggle
        const mobileProgramsBtn = document.getElementById('mobile-programs-btn');
        const mobileProgramsMenu = document.getElementById('mobile-programs-menu');

        mobileProgramsBtn.addEventListener('click', () => {
            mobileProgramsMenu.classList.toggle('hidden');
        });

        // Toggle Education Fields based on status
        function toggleEducationFields() {
            let status = document.getElementById('education_status').value;

            // Hide all fields first
            document.getElementById('gradeField').classList.add('hidden');
            document.getElementById('semesterField').classList.add('hidden');
            document.getElementById('graduationYear').classList.add('hidden');
            document.getElementById('otherField').classList.add('hidden');

            // Show fields based on status
            if (status === "Primary" || status === "Secondary" || status === "Intermediate") {
                document.getElementById('gradeField').classList.remove('hidden');
            } else if (status === "Undergraduate") {
                document.getElementById('semesterField').classList.remove('hidden');
            } else if (status === "Graduate") {
                document.getElementById('graduationYear').classList.remove('hidden');
            } else if (status === "Other") {
                document.getElementById('otherField').classList.remove('hidden');
            }
        }

        // Toggle Interest Section
        function toggleInterestSection() {
            const section = document.getElementById('interestSection');
            const btn = document.getElementById('toggleInterestBtn');
            const btnText = document.getElementById('toggleBtnText');

            if (section.classList.contains('open')) {
                section.classList.remove('open');
                btn.classList.remove('active');
                btnText.textContent = 'Select Fields of Interest';
            } else {
                section.classList.add('open');
                btn.classList.add('active');
                btnText.textContent = 'Hide Fields of Interest';
            }
        }

        // Update Selected Interests Display
        function updateSelectedInterests() {
            const checkboxes = document.querySelectorAll('input[name="field_interest[]"]:checked');
            const displayContainer = document.getElementById('selectedInterestsDisplay');
            
            displayContainer.innerHTML = '';

            checkboxes.forEach(checkbox => {
                const tag = document.createElement('span');
                tag.className = 'interest-tag';
                tag.innerHTML = `
                    ${checkbox.value}
                    <span class="remove-tag" onclick="removeInterest('${checkbox.value}')">
                        <i class="fas fa-times"></i>
                    </span>
                `;
                displayContainer.appendChild(tag);
            });

            // Update checkbox parent styling
            document.querySelectorAll('.interest-checkbox').forEach(label => {
                const checkbox = label.querySelector('input[type="checkbox"]');
                if (checkbox.checked) {
                    label.classList.add('selected');
                } else {
                    label.classList.remove('selected');
                }
            });
        }

        // Remove Interest Tag
        function removeInterest(value) {
            const checkbox = document.querySelector(`input[name="field_interest[]"][value="${value}"]`);
            if (checkbox) {
                checkbox.checked = false;
                updateSelectedInterests();
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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
                            var alertClass = res.success ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                            var iconClass = res.success ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500';
                            var alertHTML = `
                                <div id="ajaxAlert" class="${alertClass} border px-4 py-4 rounded-lg mb-4 flex items-center gap-3" role="alert">
                                    <i class="fas ${iconClass} text-2xl"></i>
                                    <div>
                                        <p class="font-semibold">${res.success ? 'Success!' : 'Error!'}</p>
                                        <p>${res.message}</p>
                                    </div>
                                </div>
                            `;

                            $('#formAlert').html(alertHTML);

                            // Scroll to top to show alert
                            $('html, body').animate({
                                scrollTop: $('#formAlert').offset().top - 150
                            }, 500);

                            if (res.success) {
                                $('#studentRegistrationForm')[0].reset();
                                // Clear selected interests display
                                document.getElementById('selectedInterestsDisplay').innerHTML = '';
                                // Reset checkbox styles
                                document.querySelectorAll('.interest-checkbox').forEach(label => {
                                    label.classList.remove('selected');
                                });
                            }

                            // Auto-dismiss alert after 10 seconds
                            setTimeout(function() {
                                $('#ajaxAlert').fadeOut('slow', function() {
                                    $(this).remove();
                                });
                            }, 10000);

                        } catch (e) {
                            $('#formAlert').html(`
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded-lg mb-4 flex items-center gap-3" role="alert">
                                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                                    <div>
                                        <p class="font-semibold">Error!</p>
                                        <p>Unexpected server response. Please try again.</p>
                                    </div>
                                </div>
                            `);
                        }
                    },
                    error: function() {
                        $('#formAlert').html(`
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded-lg mb-4 flex items-center gap-3" role="alert">
                                <i class="fas fa-exclamation-triangle text-2xl"></i>
                                <div>
                                    <p class="font-semibold">Connection Error!</p>
                                    <p>Could not submit form. Please try again later.</p>
                                </div>
                            </div>
                        `);
                    }
                });
            });
        });
    </script>
</body>

</html>