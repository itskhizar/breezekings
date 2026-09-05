<?php 
    include("include/classes/session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | ModernBlogger Admin</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Create an account for ModernBlogger Admin Portal.">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif-title { font-family: 'Playfair Display', serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex items-center justify-center relative overflow-hidden py-12">

    <?php
    if ($session->logged_in){
        echo "<script>location.href='dashboard.php';</script>";
    } else {
    ?>

    <!-- Abstract Background -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-brand-400 mix-blend-multiply filter blur-[100px] opacity-30"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-purple-400 mix-blend-multiply filter blur-[100px] opacity-30"></div>
    </div>

    <div class="w-full max-w-md px-4 relative z-10">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="index.php" class="inline-flex items-center gap-2 group">
                <div class="w-12 h-12 bg-gradient-to-br from-brand-500 to-purple-600 text-white rounded-xl flex items-center justify-center font-bold text-2xl shadow-lg group-hover:rotate-12 transition-transform duration-300">
                    MB
                </div>
                <span class="font-serif-title font-bold text-3xl tracking-tight text-slate-900">ModernBlogger</span>
            </a>
            <p class="text-slate-500 mt-3 font-medium">Create a new account to get started.</p>
        </div>

        <!-- Signup Card -->
        <div class="glass-panel rounded-3xl shadow-xl p-8">
            <form action="process.php" method="post" id="signupForm">
                
                <!-- Username -->
                <div class="mb-5">
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-user text-slate-400"></i>
                        </div>
                        <input 
                            type="text" 
                            id="username" 
                            name="user" 
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all" 
                            placeholder="Choose a username"
                            value="<?php if(isset($form)) echo $form->value('user'); ?>"
                            required
                        >
                    </div>
                    <?php if(isset($form) && $form->error("user")): ?>
                        <p class="mt-2 text-sm text-red-500 font-medium"><i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $form->error("user"); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-regular fa-envelope text-slate-400"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all" 
                            placeholder="Enter your email"
                            value="<?php if(isset($form)) echo $form->value('email'); ?>"
                            required
                        >
                    </div>
                    <?php if(isset($form) && $form->error("email")): ?>
                        <p class="mt-2 text-sm text-red-500 font-medium"><i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $form->error("email"); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="pass" 
                            class="block w-full pl-11 pr-11 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all" 
                            placeholder="Create a password"
                            required
                        >
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <button type="button" id="togglePassword" class="text-slate-400 hover:text-brand-600 transition-colors focus:outline-none">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <?php if(isset($form) && $form->error("pass")): ?>
                        <p class="mt-2 text-sm text-red-500 font-medium"><i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $form->error("pass"); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="subjoin" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex justify-center items-center gap-2">
                    Sign Up <i class="fa-solid fa-user-plus"></i>
                </button>
                
            </form>

            <div class="mt-8 text-center border-t border-slate-100 pt-6">
                <p class="text-sm text-slate-500">Already have an account? <a href="login.php" class="font-bold text-brand-600 hover:text-brand-800 transition-colors">Log In</a></p>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="index.php" class="text-sm text-slate-500 hover:text-slate-800 transition-colors"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Blog</a>
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
