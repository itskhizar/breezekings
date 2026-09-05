<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filenod Academy | Transform Your Future with Technology Education in Abbottabad</title>
    <meta name="description" content="Filenod Academy - Pakistan's premier technology education institute in Abbottabad. Learn Full Stack Development, Web Design, and Digital Skills with expert instructors and hands-on training.">
    <meta name="keywords" content="Filenod Academy, coding bootcamp Pakistan, web development Abbottabad, programming courses, full stack developer training, technology education Pakistan">
    <meta name="author" content="Filenod Academy">
    <link rel="canonical" href="https://filenod.com/academy">

    <!-- Favicon -->
    <link href="assets/img/favicon-filenod.png" rel="icon" type="image/png">

    <!-- Styles & Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Open Graph / Social Sharing -->
    <meta property="og:title" content="Filenod Academy | Transform Your Future with Technology Education in Abbottabad">
    <meta property="og:description" content="Join Filenod Academy in Abbottabad and gain hands-on training in Full Stack Development, Web Design, and Digital Skills with expert instructors.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://filenod.com/academy">
    <meta property="og:image" content="https://filenod.com/assets/img/filenod-academy-banner.jpg">
    <meta property="og:site_name" content="Filenod Academy">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Filenod Academy | Transform Your Future with Technology Education in Abbottabad">
    <meta name="twitter:description" content="Learn Full Stack Development, Web Design, and Digital Skills at Filenod Academy in Abbottabad. Hands-on courses with expert instructors.">
    <meta name="twitter:image" content="https://filenod.com/assets/img/filenod-academy-banner.jpg">
    <meta name="twitter:site" content="@FilenodAcademy">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name": "Filenod Academy",
      "url": "https://filenod.com/academy",
      "logo": "https://filenod.com/assets/img/filenod-academy-logo.png",
      "sameAs": [
        "https://www.facebook.com/filenodIT",
        "https://www.linkedin.com/in/asad-hussain-shah-7b1220187",
        "https://www.instagram.com/filenod_/"
      ],
      "description": "Filenod Academy offers technology education in Abbottabad including Full Stack Development, Web Design, and Digital Skills courses.",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Opposite UBL Mandian",
        "addressLocality": "Abbottabad",
        "postalCode": "22044",
        "addressCountry": "PK"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+92 345 4955590",
        "contactType": "customer service",
        "email": "info@filenod.com"
      }
    }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
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

        /* New Styles for Enhanced Design */
        .program-card {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .program-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transition: left 0.5s;
        }

        .program-card:hover::before {
            left: 100%;
        }

        .program-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.2);
        }

        .icon-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: inline-flex;
            padding: 1rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
        }

        .benefit-item {
            transition: all 0.3s ease;
            padding-left: 1.5rem;
            position: relative;
        }

        .benefit-item::before {
            content: '→';
            position: absolute;
            left: 0;
            color: #667eea;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .benefit-item:hover::before {
            transform: translateX(5px);
        }

        .benefit-item:hover {
            color: #667eea;
            transform: translateX(5px);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #667eea 50%, #764ba2 100%);
        }

        .process-step {
            position: relative;
        }

        .process-line {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: translateY(-50%);
            z-index: 0;
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(102, 126, 234, 0.4);
            }
            50% {
                box-shadow: 0 0 40px rgba(102, 126, 234, 0.8);
            }
        }

        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }

        .timeline-connector {
            background: linear-gradient(to bottom, #667eea, #764ba2);
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

                <!-- Programs Dropdown -->
                <div class="relative group">
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
                </div>

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

            <!-- Mobile Programs Dropdown -->
            <div class="relative">
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
            </div>

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
<!-- <section class="hero-gradient pt-32 pb-20 px-4">
    <div class="max-w-7xl mx-auto text-center">
        <div class="fade-in">
            <span class="inline-block bg-white/20 backdrop-blur-sm text-white px-6 py-2 rounded-full text-sm font-semibold mb-6">
                🚀 Build Your Future with Filenod Academy
            </span>
            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">
                Learn, Execute, and Launch<br/>
                <span class="text-white/90">Your IT Career</span>
            </h1>
            <p class="text-xl md:text-2xl text-white/80 max-w-4xl mx-auto mb-10 leading-relaxed">
                Filenod Academy offers mentorship, real-world projects, structured learning roadmaps, and career guidance to make you industry-ready, freelancing-ready, and job-ready. Gain hands-on experience, build a strong portfolio, and step confidently into the IT world.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#programs" class="bg-white text-purple-600 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition transform hover:scale-105 shadow-2xl">
                    Explore Programs
                </a>
                <a href="admission.php" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-purple-600 transition transform hover:scale-105">
                    Enroll Today
                </a>
            </div>
        </div>
    </div>
</section> -->
 <section class="hero-gradient text-white pt-32 pb-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center fade-in">
                
             <h1 class="text-4xl md:text-6xl font-extrabold mb-6">Learn, Execute, and Launch<br/>
                <span class="text-white/90">Your IT Career</span></h1>
                <p class="text-xl md:text-2xl text-purple-100 max-w-3xl mx-auto leading-relaxed">
                    Filenod Academy offers mentorship, real-world projects, structured learning roadmaps, and career guidance to make you industry-ready, freelancing-ready, and job-ready. Gain hands-on experience, build a strong portfolio, and step confidently into the IT world.
                </p>
            </div>
        </div>
    </section>

   

    <!-- Programs Section -->
    <section id="programs" class="py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-20">
                <span class="inline-block bg-purple-100 text-purple-600 px-6 py-2 rounded-full text-sm font-bold mb-4">
                    OUR PROGRAMS
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
                    Choose Your Path to <span class="gradient-text">Success</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    From personalized mentorship to hands-on internships, we've designed programs that bridge the gap between learning and real-world success.
                </p>
            </div>

            <!-- Programs Grid -->
            <div class="grid lg:grid-cols-2 gap-8 mb-16">

                <!-- Mentorship Program -->
                <div id="mentorship" class="program-card bg-white rounded-3xl p-8 shadow-lg">
                    <div class="icon-wrapper">
                        <i class="fas fa-users text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-black mb-4 text-gray-900">
                        Mentorship Program
                    </h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Get 1-on-1 guidance from industry experts who will help you navigate your career journey from skill development to job readiness.
                    </p>
                    
                    <div class="space-y-3 mb-6">
                        <div class="benefit-item text-gray-700">Personalized learning roadmap</div>
                        <div class="benefit-item text-gray-700">Skill assessment and development plan</div>
                        <div class="benefit-item text-gray-700">Career strategy sessions</div>
                        <div class="benefit-item text-gray-700">Portfolio evaluation and improvement</div>
                        <div class="benefit-item text-gray-700">CV building and LinkedIn optimization</div>
                        <div class="benefit-item text-gray-700">Mock interviews and prep sessions</div>
                        <div class="benefit-item text-gray-700">Understanding industry expectations</div>
                    </div>
                    
                    <div class="bg-purple-50 border-l-4 border-purple-600 p-4 rounded-r-lg">
                        <p class="text-purple-900 font-bold flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Outcome: Clear direction and execution-ready skills
                        </p>
                    </div>
                </div>

                <!-- Internship Program -->
                <div id="internship" class="program-card bg-white rounded-3xl p-8 shadow-lg">
                    <div class="icon-wrapper">
                        <i class="fas fa-briefcase text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-black mb-4 text-gray-900">
                        Internship Program
                    </h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Work on real client projects under professional supervision and gain the practical experience employers are looking for.
                    </p>
                    
                    <div class="space-y-3 mb-6">
                        <div class="benefit-item text-gray-700">Real client projects from Filenod</div>
                        <div class="benefit-item text-gray-700">Collaborative team environment</div>
                        <div class="benefit-item text-gray-700">Professional task management</div>
                        <div class="benefit-item text-gray-700">Industry-standard workflows</div>
                        <div class="benefit-item text-gray-700">Deadline-driven deliverables</div>
                        <div class="benefit-item text-gray-700">Performance feedback and reviews</div>
                        <div class="benefit-item text-gray-700">Completion certificate</div>
                    </div>
                    
                    <div class="bg-purple-50 border-l-4 border-purple-600 p-4 rounded-r-lg">
                        <p class="text-purple-900 font-bold flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Outcome: Real-world experience on your resume
                        </p>
                    </div>
                </div>

                <!-- Freelancing Track -->
                <div id="freelancing" class="program-card bg-white rounded-3xl p-8 shadow-lg">
                    <div class="icon-wrapper">
                        <i class="fas fa-laptop-code text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-black mb-4 text-gray-900">
                        Freelancing Track
                    </h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Master the art of freelancing and start earning as an independent professional in the global digital marketplace.
                    </p>
                    
                    <div class="space-y-3 mb-6">
                        <div class="benefit-item text-gray-700">Platform setup and optimization</div>
                        <div class="benefit-item text-gray-700">Profile creation that converts</div>
                        <div class="benefit-item text-gray-700">Gig design and pricing strategies</div>
                        <div class="benefit-item text-gray-700">Market research and niche selection</div>
                        <div class="benefit-item text-gray-700">Winning proposal techniques</div>
                        <div class="benefit-item text-gray-700">Client communication mastery</div>
                        <div class="benefit-item text-gray-700">First project to payment guidance</div>
                    </div>
                    
                    <div class="bg-purple-50 border-l-4 border-purple-600 p-4 rounded-r-lg">
                        <p class="text-purple-900 font-bold flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Outcome: Start earning as a digital freelancer
                        </p>
                    </div>
                </div>

                <!-- Structured Roadmap -->
                <div id="roadmap" class="program-card bg-white rounded-3xl p-8 shadow-lg">
                    <div class="icon-wrapper">
                        <i class="fas fa-route text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-black mb-4 text-gray-900">
                        Structured Learning Roadmap
                    </h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Follow a proven step-by-step path from beginner to advanced, with clear milestones and measurable progress.
                    </p>
                    
                    <div class="space-y-3 mb-6">
                        <div class="benefit-item text-gray-700">Beginner to advanced progression</div>
                        <div class="benefit-item text-gray-700">Industry-standard tools training</div>
                        <div class="benefit-item text-gray-700">Daily practice assignments</div>
                        <div class="benefit-item text-gray-700">Weekly project milestones</div>
                        <div class="benefit-item text-gray-700">Progress tracking portal</div>
                        <div class="benefit-item text-gray-700">Personalized feedback</div>
                        <div class="benefit-item text-gray-700">Continuous skill assessment</div>
                    </div>
                    
                    <div class="bg-purple-50 border-l-4 border-purple-600 p-4 rounded-r-lg">
                        <p class="text-purple-900 font-bold flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>
                            Outcome: Measurable growth to market readiness
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Your Journey to <span class="gradient-text">Success</span>
                </h2>
                <p class="text-xl text-gray-600">Simple steps to kickstart your tech career</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8 relative">
                <!-- Step 1 -->
                <div class="text-center process-step">
                    <div class="relative inline-block mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center pulse-glow">
                            <span class="text-3xl font-black text-white">1</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Choose Program</h3>
                    <p class="text-gray-600">Select the program that aligns with your career goals</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center process-step">
                    <div class="relative inline-block mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center pulse-glow">
                            <span class="text-3xl font-black text-white">2</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Get Enrolled</h3>
                    <p class="text-gray-600">Complete registration and meet your mentor</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center process-step">
                    <div class="relative inline-block mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center pulse-glow">
                            <span class="text-3xl font-black text-white">3</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Learn & Build</h3>
                    <p class="text-gray-600">Master skills through hands-on projects</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center process-step">
                    <div class="relative inline-block mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center pulse-glow">
                            <span class="text-3xl font-black text-white">4</span>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Start Career</h3>
                    <p class="text-gray-600">Launch your career as an IT professional</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 gradient-bg">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
                Ready to Start Your Tech Journey?
            </h2>
            <p class="text-xl text-white/90 mb-10 leading-relaxed">
                Join hundreds of students who have transformed their careers with Filenod Academy. Get personalized mentorship, real project experience, and the skills employers are looking for.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="admission.php" class="bg-white text-purple-600 px-10 py-5 rounded-full font-bold text-lg hover:bg-gray-100 transition transform hover:scale-105 shadow-2xl">
                    Enroll Now <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="index.php#contact" class="bg-transparent border-2 border-white text-white px-10 py-5 rounded-full font-bold text-lg hover:bg-white hover:text-purple-600 transition transform hover:scale-105">
                    Talk to Advisor
                </a>
            </div>
        </div>
    </section>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/923454955590" target="_blank" 
       class="fixed bottom-6 right-6 bg-green-500 text-white px-4 py-3 rounded-full shadow-lg flex items-center gap-2 hover:scale-110 transition z-50">
        <i class="fab fa-whatsapp text-2xl"></i>
    </a>

     <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                   <div class="flex items-center mb-4">
    <a href="index.php">
        <h2 class="text-4xl md:text-5xl font-extrabold bg-clip-text text-transparent
                   bg-gradient-to-r from-blue-900 via-blue-700 to-blue-500
                   drop-shadow-lg">
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
                        <li><a href="#home" class="text-gray-400 hover:text-white transition">Home</a></li>
                        <li><a href="about.php" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        <li><a href="courses.php" class="text-gray-400 hover:text-white transition">Courses</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Features</a></li>
                        <li><a href="programs.php" class="text-gray-400 hover:text-white transition">Programs</a></li>
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

                
                <!-- <div>
                    <h4 class="text-lg font-bold mb-4">Newsletter</h4>
                    <p class="text-gray-400 mb-4">Subscribe to get updates on new courses and offers.</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email" class="flex-1 px-4 py-2 rounded-l-lg bg-gray-800 border border-gray-700 focus:border-purple-600 outline-none">
                        <button class="gradient-bg px-6 py-2 rounded-r-lg font-semibold hover:shadow-lg transition">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div> -->
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">
                    © 2024 Filenod Academy. All rights reserved. | Powered by <a href="https://filenod.com" class="text-purple-400 hover:text-purple-300">Filenod.com</a>
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

    // Mobile programs dropdown toggle
    const mobileProgramsBtn = document.getElementById('mobile-programs-btn');
    const mobileProgramsMenu = document.getElementById('mobile-programs-menu');

    mobileProgramsBtn.addEventListener('click', () => {
        mobileProgramsMenu.classList.toggle('hidden');
    });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    document.getElementById('mobile-menu').classList.add('hidden');
                }
            });
        });
        
        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-xl');
            } else {
                nav.classList.remove('shadow-xl');
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#studentRegistrationForm').on('submit', function(e) {
        e.preventDefault(); // prevent default form submit

        var formData = new FormData(this);

       $.ajax({
    url: '', // Submit to same page
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    success: function(response) {
        try {
            var res = JSON.parse(response);
            if(res.success) {
                $('#formAlert').html(`
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-center" role="alert">
                        ${res.message}
                    </div>
                `);
                $('#studentRegistrationForm')[0].reset();
            } else {
                $('#formAlert').html(`
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-center" role="alert">
                        ${res.message}
                    </div>
                `);
            }
        } catch (e) {
            $('#formAlert').html(`
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-center" role="alert">
                    Unexpected server response.
                </div>
            `);
        }
    },
    error: function() {
        $('#formAlert').html(`
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-center" role="alert">
                Could not submit form. Try again later.
            </div>
        `);
    }
});


    });
});
</script>
</body>
</html>