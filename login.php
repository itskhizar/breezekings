<?php 
    include("include/classes/session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login &mdash; Breezekings</title>
    
    <!-- SEO Meta Tags -->
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 44 44'><rect width='44' height='44' rx='10' fill='%230b1f3a'/><path d='M12 9H22.5C25.5 9 27.5 10.5 27.5 13C27.5 14.8 26.2 16.2 24.5 16.8C26.8 17.4 28.5 19 28.5 21.5C28.5 24.5 26 26.5 22.5 26.5H12V9ZM16.5 13V16.2H21.5C22.8 16.2 23.5 15.5 23.5 14.6C23.5 13.7 22.8 13 21.5 13H16.5ZM16.5 19.3V22.5H22C23.5 22.5 24.3 21.7 24.3 20.9C24.3 20 23.5 19.3 22 19.3H16.5Z' fill='%23ffffff'/><path d='M22 19L29 11H34.5L26.5 20L35 31H29.5L23.5 23' fill='%23C5202B'/></svg>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            DEFAULT: '#0B1F3A',
                            900: '#071527',
                            800: '#0B1F3A',
                            700: '#142B4D',
                        },
                        crimson: {
                            DEFAULT: '#C5202B',
                            hover: '#A81923',
                            50: '#FDF2F3',
                            100: '#FCE7E8',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #071527 0%, #0B1F3A 50%, #102A4E 100%);
            min-height: 100vh;
        }
        .login-card {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between text-slate-800 antialiased relative overflow-x-hidden">

    <?php
    if ($session->logged_in){
        echo "<script>location.href='dashboard.php';</script>";
        exit;
    }
    ?>

    <!-- Top Navigation Bar / Return to site -->
    <header class="w-full px-6 py-4 flex items-center justify-between relative z-10">
        <a href="/" class="flex items-center gap-2 text-white/80 hover:text-white transition-colors text-xs font-semibold group py-1.5 px-3 rounded-lg hover:bg-white/10">
            <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
            <span>Return to Breezekings</span>
        </a>
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Admin Gateway Online</span>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 flex items-center justify-center px-4 py-8 relative z-10">
        <div class="w-full max-w-[440px]">
            
            <!-- Branding Header above card -->
            <div class="text-center mb-6">
                <a href="/" class="inline-flex items-center gap-3 group">
                    <svg class="w-11 h-11 flex-shrink-0 shadow-lg rounded-xl transition-transform group-hover:scale-105" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="44" height="44" rx="10" fill="#0d1f3b"/>
                        <path d="M12 9H22.5C25.5 9 27.5 10.5 27.5 13C27.5 14.8 26.2 16.2 24.5 16.8C26.8 17.4 28.5 19 28.5 21.5C28.5 24.5 26 26.5 22.5 26.5H12V9ZM16.5 13V16.2H21.5C22.8 16.2 23.5 15.5 23.5 14.6C23.5 13.7 22.8 13 21.5 13H16.5ZM16.5 19.3V22.5H22C23.5 22.5 24.3 21.7 24.3 20.9C24.3 20 23.5 19.3 22 19.3H16.5Z" fill="#ffffff"/>
                        <path d="M22 19L29 11H34.5L26.5 20L35 31H29.5L23.5 23" fill="#C5202B"/>
                    </svg>
                    <span class="text-2xl font-black tracking-tight text-white font-sans">
                        Breeze<span class="text-[#C5202B]">kings</span>
                    </span>
                </a>
                <p class="text-xs text-slate-300 font-medium mt-1">Editorial &amp; Content Management System</p>
            </div>

            <!-- Login Card -->
            <div class="login-card rounded-2xl p-8 sm:p-9 border border-white/20">
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Sign In to Dashboard</h1>
                    <p class="text-xs text-slate-500 mt-1">Enter your author credentials to access the editorial suite</p>
                </div>

                <?php if(isset($form) && ($form->error("user") || $form->error("pass"))): ?>
                    <!-- Error Alert -->
                    <div class="bg-red-50 border-l-4 border-crimson p-3.5 mb-5 rounded-r-lg flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-crimson mt-0.5 text-sm"></i>
                        <div>
                            <p class="text-xs text-red-900 font-semibold">Authentication Failed</p>
                            <p class="text-xs text-red-700 mt-0.5">The registration number or password you entered is incorrect.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form action="process.php" method="post" id="loginForm" class="space-y-4">
                    
                    <!-- Username / Reg No -->
                    <div>
                        <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Registration No. / Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-id-card text-sm"></i>
                            </div>
                            <input 
                                type="text" 
                                id="username" 
                                name="user" 
                                class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:bg-white focus:ring-2 focus:ring-navy/20 focus:border-navy transition-all text-sm text-slate-900 placeholder-slate-400 font-medium" 
                                placeholder="e.g. AUTH-1024 or admin"
                                value="<?php if(isset($form)) echo htmlspecialchars($form->value('user') ?? ''); ?>"
                                required
                                autocomplete="username"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Password
                            </label>
                            <a href="mailto:azamwaseem44@gmail.com?subject=Breezekings%20Admin%20Password%20Reset%20Request" class="text-xs font-semibold text-crimson hover:text-crimson-hover transition-colors">
                                Need Help?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="pass" 
                                class="block w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:bg-white focus:ring-2 focus:ring-navy/20 focus:border-navy transition-all text-sm text-slate-900 placeholder-slate-400 font-medium" 
                                placeholder="••••••••••••"
                                required
                                autocomplete="current-password"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center">
                                <button type="button" id="togglePassword" class="text-slate-400 hover:text-slate-600 transition-colors focus:outline-none" aria-label="Toggle password visibility">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Security Info Badge -->
                    <!-- <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                            End-to-End Encrypted Session
                        </span>
                        <span>v2.4 Editorial</span>
                    </div> -->

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            name="sublogin" 
                            class="w-full bg-[#0B1F3A] hover:bg-[#142B4D] text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all text-sm flex justify-center items-center gap-2 group"
                        >
                            <span>Sign In to Admin Portal</span>
                            <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        </button>
                    </div>
                </form>

                <!-- Help note -->
                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500">
                        Authorized editorial personnel only. Inquiries: 
                        <a href="mailto:azamwaseem44@gmail.com" class="text-crimson font-semibold hover:underline">azamwaseem44@gmail.com</a>
                    </p>
                </div>
            </div>

            <!-- Footer below card -->
            <div class="mt-6 text-center text-xs text-slate-400 space-y-2">
                <p>&copy; <?php echo date('Y'); ?> Breezekings. All rights reserved.</p>
                <div class="flex justify-center items-center gap-3 text-[11px]">
                    <a href="/terms-and-conditions" class="hover:text-white transition-colors">Terms &amp; Conditions</a>
                    <span>&bull;</span>
                    <a href="/privacy-policy" class="hover:text-white transition-colors">Privacy Policy</a>
                    <span>&bull;</span>
                    <a href="/contact-us" class="hover:text-white transition-colors">Contact Support</a>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer Space -->
    <div class="py-2"></div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                const icon = this.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-eye', !isPassword);
                    icon.classList.toggle('fa-eye-slash', isPassword);
                }
            });
        }
    </script>
</body>
</html>