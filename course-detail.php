<?php 
include 'include/classes/session.php';

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get course ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: courses.php");
    exit;
}

$id = intval($_GET['id']);

// Fetch course data
$course_query = "SELECT * FROM courses WHERE id = '$id' LIMIT 1";
$course_result = mysqli_query($conn, $course_query);

if (!$course_result || mysqli_num_rows($course_result) == 0) {
    header("Location: courses.php?error=notfound");
    exit;
}

$course = mysqli_fetch_assoc($course_result);

// Fetch category
$category_id = $course['category_id'];
$cat_query = "SELECT * FROM categories WHERE id = '$category_id' LIMIT 1";
$cat_result = mysqli_query($conn, $cat_query);
$category = mysqli_fetch_assoc($cat_result);
$category_name = $category['category'] ?? 'General';

// Fetch fee details
$fee_query = "SELECT * FROM fees WHERE course_id = '$id' LIMIT 1";
$fee_result = mysqli_query($conn, $fee_query);
$fee_data = mysqli_fetch_assoc($fee_result);

// Count students enrolled
$students_query = "SELECT COUNT(*) as total FROM students WHERE course_id = '$id'";
$students_result = mysqli_query($conn, $students_query);
$students_data = mysqli_fetch_assoc($students_result);
$total_students = $students_data['total'] ?? 0;

// Count tasks
$tasks_query = "SELECT COUNT(*) as total FROM tasks WHERE course_id = '$id'";
$tasks_result = mysqli_query($conn, $tasks_query);
$tasks_data = mysqli_fetch_assoc($tasks_result);
$total_tasks = $tasks_data['total'] ?? 0;

// Course details
$course_title = htmlspecialchars($course['course_title']);
$description = $course['description'] ?? '';
$duration = $course['duration'] ?? 2;
$monthly_fee = $fee_data['monthly'] ?? 0;
$total_fee = $fee_data['amount'] ?? 0;

// Course curriculum - Standard modules for all courses
$modules = [
    [
        'title' => 'Foundation & Setup',
        'icon' => 'fa-flag-checkered',
        'weeks' => '1-2',
        'lessons' => 8,
        'description' => 'Introduction to the course, environment setup, and fundamental concepts',
        'topics' => ['Course Overview', 'Tool Installation', 'Basic Concepts', 'First Project Setup']
    ],
    [
        'title' => 'Core Concepts',
        'icon' => 'fa-cubes',
        'weeks' => '3-4',
        'lessons' => 12,
        'description' => 'Deep dive into core principles and theoretical foundations',
        'topics' => ['Key Principles', 'Best Practices', 'Design Patterns', 'Architecture Basics']
    ],
    [
        'title' => 'Practical Implementation',
        'icon' => 'fa-laptop-code',
        'weeks' => '5-6',
        'lessons' => 15,
        'description' => 'Hands-on coding sessions and practical exercises',
        'topics' => ['Live Coding', 'Exercises', 'Mini Projects', 'Code Reviews']
    ],
    [
        'title' => 'Advanced Techniques',
        'icon' => 'fa-rocket',
        'weeks' => '7-8',
        'lessons' => 10,
        'description' => 'Advanced concepts and professional-level techniques',
        'topics' => ['Advanced Features', 'Optimization', 'Performance', 'Security']
    ],
    [
        'title' => 'Real-World Projects',
        'icon' => 'fa-briefcase',
        'weeks' => '9-10',
        'lessons' => 8,
        'description' => 'Build complete portfolio-worthy projects',
        'topics' => ['Client Projects', 'Team Work', 'Version Control', 'Deployment']
    ],
    [
        'title' => 'Career Preparation',
        'icon' => 'fa-user-tie',
        'weeks' => '11-12',
        'lessons' => 6,
        'description' => 'Get ready for the job market and freelancing',
        'topics' => ['Portfolio Building', 'Resume Writing', 'Interview Prep', 'Freelancing Guide']
    ]
];

// What you'll learn
$learning_outcomes = [
    'Master fundamental and advanced concepts from scratch',
    'Build real-world projects for your professional portfolio',
    'Develop strong problem-solving and debugging skills',
    'Learn industry best practices and coding standards',
    'Prepare for job interviews and freelancing opportunities',
    'Get hands-on experience with latest tools and technologies',
    'Receive personalized mentorship and career guidance',
    'Join our active alumni network for ongoing support'
];

// Course features
$features = [
    ['icon' => 'fa-chalkboard-teacher', 'title' => 'Sessions', 'desc' => 'Interactive sessions with expert instructors'],
    // ['icon' => 'fa-tasks', 'title' => 'Practical Tasks', 'desc' => 'Hands-on assignments after each module'],
    ['icon' => 'fa-certificate', 'title' => 'Certification', 'desc' => 'Industry-recognized certificate'],
    ['icon' => 'fa-headset', 'title' => 'Support', 'desc' => 'Dedicated support & mentorship'],
    ['icon' => 'fa-users', 'title' => 'Community', 'desc' => 'Active student community access'],
    ['icon' => 'fa-infinity', 'title' => 'Lifetime Access', 'desc' => 'Access course materials anytime'],
    ['icon' => 'fa-project-diagram', 'title' => 'Real Projects', 'desc' => 'Work on industry-level projects'],
    ['icon' => 'fa-handshake', 'title' => 'Career Support', 'desc' => 'Job placement assistance']
];

// Requirements
$requirements = [
    'Basic computer literacy and internet access',
    // 'A laptop or PC with minimum 4GB RAM',
    'Dedication to complete tasks on time',
    'Willingness to learn and practice regularly',
    'No prior programming experience required'
];

// FAQs
$faqs = [
    [
        'question' => 'What are the class timings?',
        'answer' => 'We offer multiple batches with flexible timings. Morning, afternoon, and evening batches are available. You can choose the timing that suits you best during admission.'
    ],
    // [
    //     'question' => 'Is there any installment plan available?',
    //     'answer' => 'Yes! We offer easy monthly installment plans. You can pay the course fee in monthly installments throughout the course duration.'
    // ],
    [
        'question' => 'Will I get a certificate after completion?',
        'answer' => 'Absolutely! Upon successful completion of the course and all assignments, you will receive an industry-recognized certificate from Filenod Academy.'
    ],
    [
        'question' => 'Is there any job placement support?',
        'answer' => 'Yes, we provide career support including resume building, interview preparation, and job placement assistance. We also guide students for freelancing opportunities.'
    ],
    [
        'question' => 'Can I attend classes online?',
        'answer' => 'Currently, our classes are primarily on-site at our Abbottabad campus. However, recorded sessions and resources are available for revision.'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $course_title; ?> | Filenod Academy</title>
    <meta name="description" content="<?php echo substr(strip_tags($description), 0, 160); ?>... Learn <?php echo $course_title; ?> at Filenod Academy with hands-on training and expert mentorship.">
    <meta name="keywords" content="<?php echo $course_title; ?>, Filenod Academy, <?php echo $category_name; ?>, IT training Abbottabad, programming course">
    
    <link href="assets/img/favicon-filenod.png" rel="icon" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $course_title; ?> | Filenod Academy">
    <meta property="og:description" content="Master <?php echo $course_title; ?> with hands-on training at Filenod Academy. <?php echo $duration; ?> months course with expert mentorship.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://filenod.com/academy/course-detail.php?id=<?php echo $id; ?>">
    <meta property="og:image" content="https://filenod.com/assets/img/filenod-academy-banner.jpg">

    <style>
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

        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);;
        }
        
        .hover-scale {
            transition: all 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: translateY(-5px);
        }
        
        .card-shadow {
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

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

        .module-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .module-card:hover {
            border-left-color: #667eea;
            transform: translateX(5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
        }

        .faq-item {
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            padding-top: 16px;
        }

        .faq-item .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .sticky-sidebar {
            position: sticky;
            top: 100px;
        }

        .price-tag {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .topic-tag {
            background: linear-gradient(135deg, #e0e7ff 0%, #f3e8ff 100%);
            color: #4c1d95;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-gray-50">
    
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
               <!--  <div class="relative group">
                    <button class="nav-link text-gray-700 hover:text-purple-600 font-medium flex items-center gap-1">
                        Programs <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    <div class="absolute left-0 top-full mt-1 w-52 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-300 z-50">
                        <a href="programs.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">All Programs</a>
                        <a href="mentorship-program.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">Mentorship Program</a>
                        <a href="internship-program.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">Internship Program</a>
                        <a href="freelancing-program.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">Freelancing Track</a>
                        <a href="structured-program.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50">Structured Roadmap</a>
                    </div>
                </div> -->

                <a href="index.php#features" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Features</a>
                <a href="index.php#contact" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Contact</a>
            </div>

            <!-- CTA Buttons -->
            <div class="hidden md:flex items-center" style="gap:14px;">

    <!-- Portal Login -->
    <a href="login.php"
       style="
        padding: 10px 22px;
        border-radius: 999px;
        font-weight: 600;
        color: #6b21a8;
        border: 2px solid #6b21a8;
        background: transparent;
        transition: all 0.3s ease;
       "
       onmouseover="this.style.background='#f3e8ff'; this.style.transform='translateY(-1px)'"
       onmouseout="this.style.background='transparent'; this.style.transform='translateY(0)'">
        Portal Login
    </a>

    <!-- Get Started -->
    <a href="admission.php"
       style="
        padding: 12px 30px;
        border-radius: 999px;
        font-weight: 700;
        color: #ffffff;
        background: linear-gradient(135deg, #1e40af, #9333ea);
        box-shadow: 0 8px 20px rgba(124, 58, 237, 0.35);
        transition: all 0.3s ease;
       "
       onmouseover="this.style.transform='translateY(-2px) scale(1.03)'; this.style.boxShadow='0 12px 28px rgba(124,58,237,0.45)'"
       onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 8px 20px rgba(124,58,237,0.35)'">
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
                    <a href="mentorship-program.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Mentorship Program</a>
                    <a href="internship-program.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Internship Program</a>
                    <a href="freelancing-program.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Freelancing Track</a>
                    <a href="structuredroadmap-program.php" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Structured Roadmap</a>
                </div>
            </div> -->

            <a href="index.php#features" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Features</a>
            <a href="index.php#contact" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Contact</a>
           <a href="login.php"
   style="
    display:block;
    text-align:center;
    padding:12px;
    border-radius:12px;
    font-weight:600;
    color:#6b21a8;
    border:2px solid #6b21a8;
   ">
   Portal Login
</a>

<a href="admission.php"
   style="
    display:block;
    text-align:center;
    padding:14px;
    border-radius:12px;
    font-weight:700;
    color:#fff;
    background:linear-gradient(135deg,#1e40af,#9333ea);
    box-shadow:0 8px 20px rgba(124,58,237,0.35);
   ">
   Get Started
</a>

        </div>
    </div>
</nav>

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-16 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 border-2 border-white rounded-full"></div>
            <div class="absolute top-40 right-20 w-24 h-24 border-2 border-white rounded-full"></div>
            <div class="absolute bottom-20 left-1/4 w-16 h-16 border-2 border-white rounded-full"></div>
            <div class="absolute bottom-10 right-1/3 w-20 h-20 border-2 border-white rounded-full"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumb -->
            

            <div class="grid lg:grid-cols-3 gap-12 items-start">
                <!-- Left Content -->
                <div class="lg:col-span-2">
                    <!-- Category Badge -->
                    <span class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-semibold mb-4">
                        <i class="fas fa-folder"></i> <?php echo $category_name; ?>
                    </span>

                    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                        <?php echo $course_title; ?>
                    </h1>

                    <p class="text-lg text-white/90 mb-8 leading-relaxed max-w-2xl">
                        <?php echo !empty($description) ? htmlspecialchars(substr($description, 0, 250)) . '...' : 'Master ' . $course_title . ' with our comprehensive curriculum designed by industry experts. Learn practical skills through hands-on projects and real-world applications with personalized mentorship.'; ?>
                    </p>

                    <!-- Quick Stats -->
                    <!-- <div class="flex flex-wrap gap-6 mb-8"> -->
                        <!-- <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold"><?php echo $duration; ?></p>
                                <p class="text-sm text-white/80">Months</p>
                            </div>
                        </div> -->
                        
                       <!--  <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-tasks text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold"><?php echo $total_tasks > 0 ? $total_tasks : '20+'; ?></p>
                                <p class="text-sm text-white/80">Tasks</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-bold"><?php echo $total_students > 0 ? $total_students . '+' : '50+'; ?></p>
                                <p class="text-sm text-white/80">Students</p>
                            </div>
                        </div> -->
                    <!-- </div> -->

                    <!-- Features Tags -->
                    <div class="flex flex-wrap gap-3">
                        <span class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full text-sm">
                            <i class="fas fa-check-circle text-green-400"></i> Certification Included
                        </span>
                        <span class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full text-sm">
                            <i class="fas fa-check-circle text-green-400"></i> Hands-on Projects
                        </span>
                        <span class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full text-sm">
                            <i class="fas fa-check-circle text-green-400"></i> Expert Mentorship
                        </span>
                        <span class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full text-sm">
                            <i class="fas fa-check-circle text-green-400"></i> Career Support
                        </span>
                    </div>
                </div>

                <!-- Right - Price Card (Mobile Hidden, shown in sidebar) -->
                <div class="hidden lg:block">
                    <!-- Placeholder for alignment -->
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                
                <!-- Left Content -->
                <div class="lg:col-span-2 space-y-12">
                    
                    <!-- About This Course -->
                    <div class="bg-white rounded-2xl p-8 card-shadow">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-info-circle text-purple-600"></i>
                            </span>
                            About This Course
                        </h2>
                        <div class="prose max-w-none text-gray-600 leading-relaxed">
                            <?php if (!empty($description)) { ?>
                                <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
                            <?php } else { ?>
                                <p>This comprehensive <?php echo $course_title; ?> course is designed to take you from beginner to professional level in <?php echo $duration; ?> months. Through a combination of theoretical knowledge and practical hands-on projects, you will develop the skills needed to succeed in the industry.</p>
                                <p class="mt-4">Our expert instructors provide personalized guidance and mentorship throughout your learning journey. You'll work on real-world projects that will form the foundation of your professional portfolio.</p>
                                <p class="mt-4">By the end of this course, you'll have the confidence and skills to pursue job opportunities or start your freelancing career in <?php echo $course_title; ?>.</p>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- What You'll Learn -->
                    <div class="bg-white rounded-2xl p-8 card-shadow">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-bullseye text-green-600"></i>
                            </span>
                            What You'll Learn
                        </h2>
                        <div class="grid md:grid-cols-2 gap-4">
                            <?php foreach ($learning_outcomes as $outcome) { ?>
                            <div class="flex items-start gap-3 p-4 bg-green-50 rounded-xl hover:bg-green-100 transition">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <p class="text-gray-700"><?php echo $outcome; ?></p>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Course Curriculum -->
                    <div class="bg-white rounded-2xl p-8 card-shadow">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book-open text-blue-600"></i>
                            </span>
                            Course Curriculum
                        </h2>
                        <p class="text-gray-600 mb-8">Our structured curriculum ensures progressive learning with practical application at every stage.</p>
                        
                        <div class="space-y-4">
                            <?php foreach ($modules as $index => $module) { ?>
                            <div class="module-card bg-gray-50 rounded-xl p-6 cursor-pointer" onclick="toggleModule(<?php echo $index; ?>)">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center text-white">
                                            <i class="fas <?php echo $module['icon']; ?> text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 text-lg">Module <?php echo $index + 1; ?>: <?php echo $module['title']; ?></h3>
                                            <p class="text-gray-500 text-sm mt-1"><?php echo $module['description']; ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <!-- <div class="text-right hidden sm:block">
                                            <p class="text-sm font-semibold text-purple-600">Week <?php echo $module['weeks']; ?></p>
                                            <p class="text-xs text-gray-500"><?php echo $module['lessons']; ?> Lessons</p>
                                        </div> -->
                                        <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300" id="module-icon-<?php echo $index; ?>"></i>
                                    </div>
                                </div>
                                
                                <div class="module-content hidden mt-4 pt-4 border-t border-gray-200" id="module-content-<?php echo $index; ?>">
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($module['topics'] as $topic) { ?>
                                        <span class="topic-tag px-4 py-2 rounded-full text-sm font-medium">
                                            <i class="fas fa-check-circle mr-1 text-purple-500"></i> <?php echo $topic; ?>
                                        </span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Course Features -->
                    <div class="bg-white rounded-2xl p-8 card-shadow">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-star text-yellow-600"></i>
                            </span>
                            What's Included
                        </h2>
                        <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-4">
                            <?php foreach ($features as $feature) { ?>
                            <div class="feature-card text-center p-6 bg-gray-50 rounded-xl">
                                <div class="w-14 h-14 gradient-bg rounded-xl flex items-center justify-center mx-auto mb-4 text-white">
                                    <i class="fas <?php echo $feature['icon']; ?> text-xl"></i>
                                </div>
                                <h4 class="font-bold text-gray-900 mb-1"><?php echo $feature['title']; ?></h4>
                                <p class="text-gray-500 text-sm"><?php echo $feature['desc']; ?></p>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="bg-white rounded-2xl p-8 card-shadow">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-red-600"></i>
                            </span>
                            Requirements
                        </h2>
                        <div class="space-y-3">
                            <?php foreach ($requirements as $req) { ?>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-check-circle text-blue-500"></i>
                                <span class="text-gray-700"><?php echo $req; ?></span>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- FAQs -->
                    <div class="bg-white rounded-2xl p-8 card-shadow">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-question-circle text-indigo-600"></i>
                            </span>
                            Frequently Asked Questions
                        </h2>
                        <div class="space-y-4">
                            <?php foreach ($faqs as $index => $faq) { ?>
                            <div class="faq-item border border-gray-200 rounded-xl overflow-hidden" onclick="toggleFaq(<?php echo $index; ?>)">
                                <div class="flex items-center justify-between p-5 cursor-pointer hover:bg-gray-50 transition">
                                    <h4 class="font-semibold text-gray-900"><?php echo $faq['question']; ?></h4>
                                    <i class="fas fa-chevron-down faq-icon text-gray-400 transition-transform duration-300" id="faq-icon-<?php echo $index; ?>"></i>
                                </div>
                                <div class="faq-answer px-5 pb-5 text-gray-600" id="faq-answer-<?php echo $index; ?>">
                                    <?php echo $faq['answer']; ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky-sidebar space-y-6">
                        
                        <!-- Price Card -->
                        <div class="bg-white rounded-2xl overflow-hidden card-shadow">
                            <!-- <div class="price-tag p-6 text-white text-center">
                                <p class="text-sm opacity-90 mb-1">Course Fee</p>
                                <div class="text-4xl font-bold mb-1">
                                    Rs. <?php echo $monthly_fee > 0 ? number_format($monthly_fee) : 'Contact Us'; ?>
                                </div>
                                <p class="text-sm opacity-90">per month</p>
                                <?php if ($total_fee > 0) { ?>
                                <div class="mt-4 pt-4 border-t border-white/20">
                                    <p class="text-sm opacity-90">Total Course Fee</p>
                                    <p class="text-2xl font-bold">Rs. <?php echo number_format($total_fee); ?></p>
                                </div>
                                <?php } ?>
                            </div> -->
                            <div class="p-6 space-y-4">
                                <a href="admission.php?course_id=<?php echo $id; ?>" 
                                   class="block w-full py-4 text-center gradient-bg text-white font-bold rounded-xl hover:shadow-lg transition transform hover:scale-105 pulse-animation">
                                    <i class="fas fa-user-plus mr-2"></i> Enroll Now
                                </a>
                                <a href="https://wa.me/923454955590?text=Hi, I'm interested in the <?php echo urlencode($course_title); ?> course. Please share more details." 
                                   target="_blank"
                                   class="block w-full py-4 text-center bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition">
                                    <i class="fab fa-whatsapp mr-2"></i> Ask on WhatsApp
                                </a>
                                <a href="tel:+923454955590" 
                                   class="block w-full py-3 text-center border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:border-purple-500 hover:text-purple-600 transition">
                                    <i class="fas fa-phone mr-2"></i> Call Us
                                </a>
                            </div>
                        </div>

                        <!-- Course Info -->
                        <div class="bg-white rounded-2xl p-6 card-shadow">
                            <h3 class="font-bold text-gray-900 mb-4 pb-3 border-b-2 border-purple-500">Course Information</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-folder w-5 text-purple-500"></i> Category
                                    </span>
                                    <span class="font-semibold text-gray-900"><?php echo $category_name; ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-clock w-5 text-purple-500"></i> Duration
                                    </span>
                                    <span class="font-semibold text-gray-900">Minimum <?php echo $duration; ?> Months</span>
                                </div>
                                <!-- <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-book w-5 text-purple-500"></i> Modules
                                    </span>
                                    <span class="font-semibold text-gray-900"><?php echo count($modules); ?></span>
                                </div> -->
                                <!-- <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-tasks w-5 text-purple-500"></i> Tasks
                                    </span>
                                    <span class="font-semibold text-gray-900"><?php echo $total_tasks > 0 ? $total_tasks : '20+'; ?></span>
                                </div> -->
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-certificate w-5 text-purple-500"></i> Certificate
                                    </span>
                                    <span class="font-semibold text-green-600">Yes</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-language w-5 text-purple-500"></i> Language
                                    </span>
                                    <span class="font-semibold text-gray-900">Urdu/English</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-signal w-5 text-purple-500"></i> Level
                                    </span>
                                    <span class="font-semibold text-gray-900">Beginner to Advanced</span>
                                </div>
                            </div>
                        </div>

                        <!-- Share -->
                        <div class="bg-white rounded-2xl p-6 card-shadow">
                            <h3 class="font-bold text-gray-900 mb-4">Share This Course</h3>
                            <div class="flex gap-3">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://filenod.com/academy/course-detail.php?id=' . $id); ?>" 
                                   target="_blank"
                                   class="flex-1 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode('Check out this course: ' . $course_title); ?>&url=<?php echo urlencode('https://filenod.com/academy/course-detail.php?id=' . $id); ?>" 
                                   target="_blank"
                                   class="flex-1 py-3 bg-sky-500 text-white text-center rounded-lg hover:bg-sky-600 transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://wa.me/?text=<?php echo urlencode('Check out this course: ' . $course_title . ' - https://filenod.com/academy/course-detail.php?id=' . $id); ?>" 
                                   target="_blank"
                                   class="flex-1 py-3 bg-green-500 text-white text-center rounded-lg hover:bg-green-600 transition">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode('https://filenod.com/academy/course-detail.php?id=' . $id); ?>" 
                                   target="_blank"
                                   class="flex-1 py-3 bg-blue-700 text-white text-center rounded-lg hover:bg-blue-800 transition">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 gradient-bg text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Ready to Start Your Journey in <?php echo $course_title; ?>?
            </h2>
            <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                Join hundreds of successful students who transformed their careers with Filenod Academy. Limited seats available!
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="admission.php?course_id=<?php echo $id; ?>" 
                   class="bg-white text-purple-600 px-8 py-4 rounded-xl font-bold text-lg hover:shadow-2xl transition transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i> Enroll Now
                </a>
                <a href="https://wa.me/923454955590" target="_blank"
                   class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white hover:text-purple-600 transition">
                    <i class="fab fa-whatsapp mr-2"></i> Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Related Courses -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Explore More Courses</h2>
                <p class="text-gray-600">Discover other courses that might interest you</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                // Fetch related courses (same category or random)
                $related_query = "SELECT * FROM courses WHERE id != '$id' ORDER BY RAND() LIMIT 3";
                $related_result = mysqli_query($conn, $related_query);
                
                if ($related_result && mysqli_num_rows($related_result) > 0) {
                    while ($related = mysqli_fetch_assoc($related_result)) {
                        $r_cat_query = "SELECT category FROM categories WHERE id = '{$related['category_id']}' LIMIT 1";
                        $r_cat_result = mysqli_query($conn, $r_cat_query);
                        $r_cat = mysqli_fetch_assoc($r_cat_result);
                ?>
                <div class="bg-gray-50 rounded-2xl overflow-hidden hover-scale card-shadow">
                    <div class="h-40 gradient-bg flex items-center justify-center">
                        <i class="fas fa-laptop-code text-white text-5xl"></i>
                    </div>
                    <div class="p-6">
                        <span class="inline-block bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                            <?php echo htmlspecialchars($r_cat['category'] ?? 'General'); ?>
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($related['course_title']); ?></h3>
                        <p class="text-gray-600 text-sm mb-4">Duration: <?php echo $related['duration']; ?> Months</p>
                        <a href="course-detail.php?id=<?php echo $related['id']; ?>" 
                           class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-800 transition">
                            View Course <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <?php 
                    }
                }
                ?>
            </div>
            <div class="text-center mt-10">
                <a href="courses.php" class="inline-flex items-center gap-2 border-2 border-purple-600 text-purple-600 px-8 py-3 rounded-xl font-bold hover:bg-purple-600 hover:text-white transition">
                    View All Courses <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

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

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Toggle module content
        function toggleModule(index) {
            const content = document.getElementById('module-content-' + index);
            const icon = document.getElementById('module-icon-' + index);
            
            if (content.classList.contains('hidden')) {
                // Close all other modules first
                document.querySelectorAll('[id^="module-content-"]').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('[id^="module-icon-"]').forEach(el => el.classList.remove('rotate-180'));
                
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        // Toggle FAQ
        function toggleFaq(index) {
            const item = document.querySelectorAll('.faq-item')[index];
            const answer = document.getElementById('faq-answer-' + index);
            const icon = document.getElementById('faq-icon-' + index);
            
            if (item.classList.contains('active')) {
                item.classList.remove('active');
                answer.style.maxHeight = '0';
                answer.style.paddingTop = '0';
                answer.style.paddingBottom = '0';
                icon.classList.remove('rotate-180');
            } else {
                // Close all other FAQs
                document.querySelectorAll('.faq-item').forEach((el, i) => {
                    el.classList.remove('active');
                    document.getElementById('faq-answer-' + i).style.maxHeight = '0';
                    document.getElementById('faq-answer-' + i).style.paddingTop = '0';
                    document.getElementById('faq-answer-' + i).style.paddingBottom = '0';
                    document.getElementById('faq-icon-' + i).classList.remove('rotate-180');
                });
                
                item.classList.add('active');
                answer.style.maxHeight = answer.scrollHeight + 'px';
                answer.style.paddingTop = '0';
                answer.style.paddingBottom = '20px';
                icon.classList.add('rotate-180');
            }
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-xl');
            } else {
                nav.classList.remove('shadow-xl');
            }
        });
    </script>
</body>
</html>