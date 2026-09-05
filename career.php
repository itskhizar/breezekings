<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers at Filenod | Join Our Growing Tech Team</title>
    <meta name="description" content="Explore exciting career opportunities at Filenod IT Center. Join our team of innovative professionals and grow your tech career in Abbottabad, Pakistan.">
   <link href="nod.png" rel="icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }

        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hover-scale { transition: transform 0.3s ease; }
        .hover-scale:hover { transform: translateY(-5px); }
        .card-shadow { box-shadow: 0 10px 40px rgba(0,0,0,0.1); transition: all 0.3s ease; }
        .card-shadow:hover { box-shadow: 0 20px 60px rgba(102, 126, 234, 0.15); }
        
        .fade-in { animation: fadeIn 0.8s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
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

        .hero-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative; overflow: hidden; }
        .hero-gradient::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>'); opacity: 0.3; }

        .job-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }
        .job-card:hover {
            transform: translateX(10px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }

        .benefit-icon {
            width: 80px;
            height: 80px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
                    <a href="login.php"
                       style="padding: 10px 22px; border-radius: 999px; font-weight: 600; color: #6b21a8; border: 2px solid #6b21a8; background: transparent; transition: all 0.3s ease;"
                       onmouseover="this.style.background='#f3e8ff'; this.style.transform='translateY(-1px)'"
                       onmouseout="this.style.background='transparent'; this.style.transform='translateY(0)'">
                        Portal Login
                    </a>
                    <a href="admission.php"
                       style="padding: 12px 30px; border-radius: 999px; font-weight: 700; color: #ffffff; background: linear-gradient(135deg, #1e40af, #9333ea); box-shadow: 0 8px 20px rgba(124, 58, 237, 0.35); transition: all 0.3s ease;"
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
                <a href="login.php" style="display:block; text-align:center; padding:12px; border-radius:12px; font-weight:600; color:#6b21a8; border:2px solid #6b21a8;">Portal Login</a>
                <a href="admission.php" style="display:block; text-align:center; padding:14px; border-radius:12px; font-weight:700; color:#fff; background:linear-gradient(135deg,#1e40af,#9333ea); box-shadow:0 8px 20px rgba(124,58,237,0.35);">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-white pt-32 pb-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center fade-in">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-6">Build Your Career at Filenod</h1>
                <p class="text-xl md:text-2xl text-purple-100 max-w-3xl mx-auto leading-relaxed mb-8">
                    Join a team of passionate innovators shaping the future of technology education in Pakistan
                </p>
                <a href="#openings" class="inline-block bg-white text-purple-600 px-8 py-4 rounded-lg font-bold text-lg hover:shadow-2xl transition transform hover:scale-105">
                    View Open Positions <i class="fas fa-arrow-down ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Why Work With Us -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <span class="text-purple-600 font-semibold text-sm uppercase tracking-wider">Join Our Team</span>
                <h2 class="text-3xl md:text-5xl font-bold mt-4 mb-6">Why Work at Filenod?</h2>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                    We're not just building courses – we're building careers, communities, and the future of tech education in Pakistan.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center fade-in">
                    <div class="benefit-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Growth & Learning</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Continuous professional development, training opportunities, and mentorship from industry experts.
                    </p>
                </div>

                <div class="text-center fade-in">
                    <div class="benefit-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Collaborative Culture</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Work with talented professionals in a supportive environment that values innovation and creativity.
                    </p>
                </div>

                <div class="text-center fade-in">
                    <div class="benefit-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Work-Life Balance</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Flexible working hours, remote work options, and a healthy approach to productivity.
                    </p>
                </div>

                <div class="text-center fade-in">
                    <div class="benefit-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Impactful Work</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Make a real difference by empowering the next generation of tech professionals.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Open Positions -->
    <section id="openings" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 fade-in">
            <span class="text-purple-600 font-semibold text-sm uppercase tracking-wider">Current Openings</span>
            <h2 class="text-3xl md:text-5xl font-bold mt-4 mb-6">Join Our Growing Team</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Explore exciting career opportunities across different roles and departments.
            </p>
        </div>

        <div class="space-y-6">
            <!-- Job Card 1 - Assistant Supervisor -->
            <div class="job-card fade-in">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="gradient-bg text-white px-3 py-1 rounded-full text-sm font-semibold">Full-Time</span>
                            <span class="text-gray-500 text-sm"><i class="fas fa-map-marker-alt mr-1"></i>Abbottabad, Pakistan</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Assistant Supervisor</h3>
                        <p class="text-gray-600 mb-4">
                            Support daily academy operations, coordinate between departments, manage administrative tasks, and ensure smooth workflow. Assist the management team in strategic planning and execution.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Leadership</span>
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Coordination</span>
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Problem Solving</span>
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Management</span>
                        </div>
                    </div>
                    <div>
                        <a href="joinascareer.php?position=Assistant Supervisor" class="gradient-bg text-white px-8 py-3 rounded-lg font-bold hover:shadow-xl transition transform hover:scale-105 inline-block">
                            Apply Now <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Job Card 2 - Admission Advisor -->
            <div class="job-card fade-in">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="gradient-bg text-white px-3 py-1 rounded-full text-sm font-semibold">Full-Time</span>
                            <span class="text-gray-500 text-sm"><i class="fas fa-map-marker-alt mr-1"></i>Abbottabad, Pakistan</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Admission Advisor</h3>
                        <p class="text-gray-600 mb-4">
                            Guide prospective students through the admission process, provide course counseling, conduct campus tours, and help students make informed decisions about their educational journey.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Communication</span>
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Counseling</span>
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Sales</span>
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">CRM</span>
                        </div>
                    </div>
                    <div>
                        <a href="joinascareer.php?position=Admission Advisor" class="gradient-bg text-white px-8 py-3 rounded-lg font-bold hover:shadow-xl transition transform hover:scale-105 inline-block">
                            Apply Now <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Job Card 3 - Coach -->
            <div class="job-card fade-in">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="gradient-bg text-white px-3 py-1 rounded-full text-sm font-semibold">Full-Time</span>
                            <span class="text-gray-500 text-sm"><i class="fas fa-map-marker-alt mr-1"></i>Abbottabad, Pakistan</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Coach / Instructor</h3>
                        <p class="text-gray-600 mb-4">
                            Teach and mentor students in IT courses, provide one-on-one coaching, guide students through projects, and help them develop practical skills for the industry.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Teaching</span>
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Mentorship</span>
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Technical Skills</span>
                            <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Project Guidance</span>
                        </div>
                    </div>
                    <div>
                        <a href="joinascareer.php?position=Coach" class="gradient-bg text-white px-8 py-3 rounded-lg font-bold hover:shadow-xl transition transform hover:scale-105 inline-block">
                            Apply Now <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="job-card fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-3">
                <span class="gradient-bg text-white px-3 py-1 rounded-full text-sm font-semibold">Full-Time</span>
                <span class="text-gray-500 text-sm">
                    <i class="fas fa-map-marker-alt mr-1"></i>Abbottabad, Pakistan
                </span>
            </div>

            <h3 class="text-2xl font-bold mb-2">Office Boy</h3>

            <p class="text-gray-600 mb-4">
                Responsible for maintaining office cleanliness, assisting staff with daily tasks, handling documents, serving refreshments, and supporting smooth day-to-day office operations.
            </p>

            <div class="flex flex-wrap gap-2">
                <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Office Support</span>
                <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Cleaning & Maintenance</span>
                <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Document Handling</span>
                <span class="bg-purple-50 text-purple-600 px-3 py-1 rounded-full text-sm">Team Assistance</span>
            </div>
        </div>

        <div>
            <a href="joinascareer.php?position=OfficeBoy" 
               class="gradient-bg text-white px-8 py-3 rounded-lg font-bold hover:shadow-xl transition transform hover:scale-105 inline-block">
                Apply Now <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

        </div>
    </div>
</section>

    <!-- Perks & Benefits -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in">
                <span class="text-purple-600 font-semibold text-sm uppercase tracking-wider">Perks & Benefits</span>
                <h2 class="text-3xl md:text-5xl font-bold mt-4 mb-6">What We Offer</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-purple-50 to-white p-8 rounded-2xl card-shadow fade-in">
                    <div class="gradient-bg text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-money-bill-wave text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Competitive Salary</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Industry-standard compensation packages with performance-based incentives and annual reviews.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl card-shadow fade-in">
                    <div class="gradient-bg text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Flexible Hours</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Work-life balance with flexible scheduling and remote work options when applicable.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-white p-8 rounded-2xl card-shadow fade-in">
                    <div class="gradient-bg text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Learning Budget</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Annual budget for courses, conferences, and professional development resources.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl card-shadow fade-in">
                    <div class="gradient-bg text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-laptop text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Latest Equipment</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Modern workstations, software licenses, and all the tools you need to excel.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-white p-8 rounded-2xl card-shadow fade-in">
                    <div class="gradient-bg text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-plane text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Paid Time Off</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Generous vacation days, sick leave, and public holidays to recharge and refresh.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl card-shadow fade-in">
                    <div class="gradient-bg text-white w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-coffee text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Great Environment</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Modern office space, team events, and a culture that celebrates achievements.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 hero-gradient text-white relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-3xl md:text-5xl font-bold mb-6">Don't See Your Perfect Role?</h2>
            <p class="text-xl text-purple-100 mb-8 leading-relaxed">
                We're always looking for talented individuals. Send us your CV and let's explore how you can contribute to our mission.
            </p>
            <a href="joinascareer.php?position=General Application" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-bold text-lg hover:shadow-2xl transition transform hover:scale-105 inline-block">
                Submit General Application <i class="fas fa-paper-plane ml-2"></i>
            </a>
        </div>
    </section>

    <!-- WhatsApp Button -->
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
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-bold mb-4">Contact Us</h4>
                    <ul class="space-y-2">
                        <li class="text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i>Abbottabad, KPK, Pakistan</li>
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

        // Mobile programs dropdown
        const mobileProgramsBtn = document.getElementById('mobile-programs-btn');
        const mobileProgramsMenu = document.getElementById('mobile-programs-menu');
        mobileProgramsBtn.addEventListener('click', () => {
            mobileProgramsMenu.classList.toggle('hidden');
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    document.getElementById('mobile-menu').classList.add('hidden');
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