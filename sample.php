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

/* CTA Button gradient background */
.gradient-bg {
    background: linear-gradient(90deg, #667eea, #764ba2);
}
        
        .stats-number {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
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
                <a href="programs.php" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Programs</a>
                <a href="index.php#features" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Features</a>
                <a href="index.php#contact" class="nav-link text-gray-700 hover:text-purple-600 font-medium">Contact</a>
            </div>

            <!-- CTA Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="login.php" class="text-purple-600 hover:text-purple-700 font-semibold px-4 py-2 rounded-lg hover:bg-purple-50 transition">
                    Portal Login
                </a>
                <a href="admission.php" class="gradient-bg text-white px-6 py-2.5 rounded-lg font-semibold hover:shadow-lg transition transform hover:scale-105">
                    Get Started
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-gray-700">
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
            <a href="programs.php" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Programs</a>
            <a href="index.php#features" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Features</a>
            <a href="index.php#contact" class="block px-3 py-2 text-gray-700 hover:bg-purple-50 rounded-lg">Contact</a>
            <a href="login.php" class="w-full text-left px-3 py-2 text-purple-600 hover:bg-purple-50 rounded-lg font-semibold">Portal Login</a>
            <a href="admission.php" class="w-full gradient-bg text-white px-3 py-2 rounded-lg font-semibold">Get Started</a>
        </div>
    </div>
</nav>






<a href="https://wa.me/923454955590" target="_blank" 
   class="fixed bottom-6 right-6 bg-green-500 text-white px-4 py-3 rounded-full shadow-lg flex items-center gap-2 hover:scale-110 transition">
    <i class="fab fa-whatsapp text-2xl"></i>
    
</a>
    <!-- Footer -->
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
                        <li><a href="#about" class="text-gray-400 hover:text-white transition">About Us</a></li>
                        <!--<li><a href="#courses" class="text-gray-400 hover:text-white transition">Courses</a></li>-->
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Features</a></li>
                        <li><a href="#testimonials" class="text-gray-400 hover:text-white transition">Testimonials</a></li>
                    </ul>
                </div>
                
                <div>
    <h4 class="text-lg font-bold mb-4">Popular Courses</h4>
    <ul class="space-y-2">
        <li><a href="#courses" class="text-gray-400 hover:text-white transition">Full Stack Web Development</a></li>
        <li><a href="#courses" class="text-gray-400 hover:text-white transition">Frontend Development</a></li>
        <li><a href="#courses" class="text-gray-400 hover:text-white transition">Backend Development</a></li>
        <li><a href="#courses" class="text-gray-400 hover:text-white transition">CMS</a></li>
        <li><a href="#courses" class="text-gray-400 hover:text-white transition">Mobile App Development</a></li>
        <li><a href="#courses" class="text-gray-400 hover:text-white transition">Graphic Designing</a></li>
        <!-- <li><a href="#courses" class="text-gray-400 hover:text-white transition">SEO</a></li> -->
    </ul>
</div>

                
                <div>
                    <h4 class="text-lg font-bold mb-4">Newsletter</h4>
                    <p class="text-gray-400 mb-4">Subscribe to get updates on new courses and offers.</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email" class="flex-1 px-4 py-2 rounded-l-lg bg-gray-800 border border-gray-700 focus:border-purple-600 outline-none">
                        <button class="gradient-bg px-6 py-2 rounded-r-lg font-semibold hover:shadow-lg transition">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
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
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
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

</body>
</html>