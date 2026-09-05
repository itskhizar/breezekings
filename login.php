<?php 
    include("include/classes/session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | BlogName</title>
    
    <!-- SEO Meta Tags -->
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            blue: '#2563eb',
                            hover: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc;
            /* Light Blue Dot Pattern */
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 relative text-slate-800 antialiased">

    <?php
    if ($session->logged_in){
        echo "<script>location.href='dashboard.php';</script>";
    } else {
    ?>

    <div class="w-full max-w-[400px] bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 sm:p-10 relative z-10 border border-slate-100">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex justify-center items-center w-12 h-12 bg-brand-blue rounded-lg shadow-sm text-white mb-5">
                <i class="fa-solid fa-file-lines text-xl"></i>
            </div>
            
            <h1 class="font-bold text-2xl text-slate-900 mb-1">Admin Portal</h1>
            <p class="text-sm text-slate-500 font-medium">Sign in to manage your blog</p>
        </div>

        <?php if(isset($form) && ($form->error("user") || $form->error("pass"))): ?>
            <!-- Error Alert -->
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                <p class="text-sm text-red-700 font-medium">Invalid credentials</p>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="process.php" method="post" id="loginForm" class="space-y-5">
            
            <!-- Email / Username -->
            <div>
                <label for="username" class="block text-xs font-semibold text-slate-700 mb-1.5">Registration Number / Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-id-card text-slate-400 text-sm"></i>
                    </div>
                    <input 
                        type="text" 
                        id="username" 
                        name="user" 
                        class="block w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all text-sm text-slate-900 placeholder-slate-400" 
                        placeholder="AUTH-1024 or username"
                        value="<?php if(isset($form)) echo $form->value('user'); ?>"
                        required
                    >
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
                    <a href="#" class="text-xs font-medium text-brand-blue hover:text-brand-hover transition-colors">Forgot Password?</a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-slate-400 text-sm"></i>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="pass" 
                        class="block w-full pl-10 pr-10 py-2.5 bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all text-sm text-slate-900 placeholder-slate-400" 
                        placeholder="••••••••"
                        required
                    >
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center">
                        <button type="button" id="togglePassword" class="text-slate-400 hover:text-slate-600 transition-colors focus:outline-none">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" name="sublogin" class="w-full bg-brand-blue hover:bg-brand-hover text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition-colors text-sm flex justify-center items-center gap-2">
                    Sign In <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                </button>
            </div>
            
        </form>

        <!-- Divider -->
        <div class="mt-8 relative flex items-center justify-center">
            <div class="absolute inset-x-0 border-t border-slate-200"></div>
            <span class="relative bg-white px-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Or Continue With</span>
        </div>

        <!-- Social Logins -->
        <div class="mt-6 grid grid-cols-2 gap-4">
            <button class="flex justify-center items-center gap-2 w-full py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-4 h-4"> Google
            </button>
            <button class="flex justify-center items-center gap-2 w-full py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="fa-brands fa-github text-base"></i> GitHub
            </button>
        </div>
        
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center relative z-10">
        <p class="text-xs text-slate-500 font-medium mb-3">&copy; <?php echo date('Y'); ?> BlogName &mdash; Admin Panel v1.0</p>
        <div class="flex justify-center items-center gap-3 text-[10px] font-medium text-slate-400">
            <a href="#" class="hover:text-slate-600 transition-colors">Privacy Policy</a>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <a href="#" class="hover:text-slate-600 transition-colors">Terms of Service</a>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <a href="#" class="hover:text-slate-600 transition-colors">Contact Support</a>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    </script>
    
    <?php } ?>
</body>
</html>