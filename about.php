<?php include 'include/classes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | BlogName</title>
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
                        navy: { 800: '#1e2336', 900: '#151928', 950: '#0d101a' },
                        crimson: { 500: '#e12b38', 600: '#c5202b' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fafafa; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="text-slate-800 antialiased">

    <?php include 'include/frontend_header.php'; ?>

    <!-- Hero Section -->
    <div class="bg-navy-900 py-20 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-crimson-600 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-600 rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <h1 class="text-5xl md:text-6xl font-serif font-bold text-white mb-6">Our Mission for Clarity</h1>
            <p class="text-xl text-slate-400 leading-relaxed">
                Empowering readers with independent journalism, deep editorial insights, and a commitment to truth in the digital age.
            </p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-crimson-600 font-bold uppercase tracking-widest text-xs mb-4 block">Who We Are</span>
                <h2 class="text-4xl font-serif font-bold text-navy-900 mb-6">Redefining Digital Storytelling</h2>
                <p class="text-slate-600 text-lg leading-relaxed mb-6">
                    Founded in 2024, BlogName started with a simple belief: that quality journalism should be accessible, transparent, and aesthetically pleasing. We don't just report the news; we provide the context that makes it meaningful.
                </p>
                <p class="text-slate-600 text-lg leading-relaxed mb-8">
                    Our team consists of veteran journalists, tech enthusiasts, and creative designers working together to bring you a unique perspective on technology, culture, and business.
                </p>
                
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-3xl font-bold text-navy-900 mb-1">10k+</h4>
                        <p class="text-sm text-slate-500 font-medium uppercase tracking-wider">Active Readers</p>
                    </div>
                    <div>
                        <h4 class="text-3xl font-bold text-navy-900 mb-1">500+</h4>
                        <p class="text-sm text-slate-500 font-medium uppercase tracking-wider">Deep Articles</p>
                    </div>
                </div>
            </div>
            
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=800&q=80" alt="Office" class="rounded-2xl shadow-2xl relative z-10">
                <div class="absolute -bottom-6 -right-6 w-full h-full bg-crimson-600/10 rounded-2xl -z-0"></div>
            </div>
        </div>

        <div class="mt-32">
            <h2 class="text-3xl font-serif font-bold text-navy-900 text-center mb-16">Our Core Values</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center group">
                    <div class="w-16 h-16 bg-white shadow-lg rounded-2xl flex items-center justify-center text-crimson-600 text-2xl mx-auto mb-6 group-hover:bg-crimson-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-xl font-bold text-navy-900 mb-3">Uncompromising Integrity</h3>
                    <p class="text-slate-500 leading-relaxed">We maintain strict editorial independence and fact-check every piece of content we publish.</p>
                </div>
                
                <div class="text-center group">
                    <div class="w-16 h-16 bg-white shadow-lg rounded-2xl flex items-center justify-center text-crimson-600 text-2xl mx-auto mb-6 group-hover:bg-crimson-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-navy-900 mb-3">Modern Perspective</h3>
                    <p class="text-slate-500 leading-relaxed">We leverage modern technology and data to tell stories in more engaging and interactive ways.</p>
                </div>

                <div class="text-center group">
                    <div class="w-16 h-16 bg-white shadow-lg rounded-2xl flex items-center justify-center text-crimson-600 text-2xl mx-auto mb-6 group-hover:bg-crimson-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold text-navy-900 mb-3">Community Driven</h3>
                    <p class="text-slate-500 leading-relaxed">Our readers are our partners. We listen to your feedback and shape our coverage around your interests.</p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>