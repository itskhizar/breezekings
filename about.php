<?php include 'include/classes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $site_url_a  = bk_base_url();
    $cur_url_a   = $site_url_a . '/about';
    ?>
    <title>About Us | Breezekings</title>
    <meta name="title" content="About Us | Breezekings">
    <meta name="description" content="Learn about Breezekings — our mission, values and the team behind fresh perspectives on tech, culture and business.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo $cur_url_a; ?>">
    <link rel="icon" type="image/svg+xml" href="/images/breezekings-icon-red.svg">
    <meta name="theme-color" content="#0B1F3A">
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Breezekings">
    <meta property="og:title" content="About Us | Breezekings">
    <meta property="og:description" content="Learn about Breezekings — our mission, values and the team behind fresh perspectives on tech, culture and business.">
    <meta property="og:url" content="<?php echo $cur_url_a; ?>">
    <meta property="og:image" content="<?php echo $site_url_a; ?>/images/breezekings-icon-red.svg">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="About Us | Breezekings">
    <meta name="twitter:description" content="Learn about Breezekings — our mission, values and the team behind fresh perspectives on tech, culture and business.">

    <!-- Structured Data: AboutPage -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "AboutPage",
        "name": "About Us | Breezekings",
        "url": "<?php echo $cur_url_a; ?>",
        "description": "Learn about Breezekings — our mission, values and the team behind fresh perspectives on tech, culture and business.",
        "publisher": {
            "@type": "Organization",
            "name": "Breezekings",
            "url": "<?php echo $site_url_a; ?>/"
        }
    }
    </script>

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
                        navy: { 800: '#1e2336', 900: '#0B1F3A', 950: '#071526' },
                        crimson: { 500: '#e12b38', 600: '#C8102E', 700: '#a31a23' }
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
<body class="text-slate-800 antialiased bg-slate-50">

    <?php include 'include/frontend_header.php'; ?>

    <!-- Hero Section -->
    <div class="bg-navy-900 py-20 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-64 h-64 bg-crimson-600 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-600 rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <span class="inline-block px-3 py-1 bg-crimson-600 text-white text-xs font-bold uppercase tracking-widest rounded-full mb-4">About Breezekings</span>
            <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6">Our Mission for Clarity</h1>
            <p class="text-lg md:text-xl text-slate-300 leading-relaxed max-w-2xl mx-auto">
                Empowering readers with independent journalism, deep editorial insights, and a commitment to truth in the digital age.
            </p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-crimson-600 font-bold uppercase tracking-widest text-xs mb-4 block">Who We Are</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-navy-900 mb-6">Redefining Digital Storytelling</h2>
                <p class="text-slate-600 text-base md:text-lg leading-relaxed mb-6">
                    Founded with a passion for knowledge, <strong class="text-navy-900 font-semibold">Breezekings</strong> started with a simple belief: that quality journalism should be accessible, transparent, and aesthetically pleasing. We don't just report the news; we provide the context that makes it meaningful.
                </p>
                <p class="text-slate-600 text-base md:text-lg leading-relaxed mb-8">
                    Our team brings together tech enthusiasts, cultural commentators, and creative designers working to bring you fresh, unbiased perspectives on technology, business, and modern life.
                </p>
                
                <div class="grid grid-cols-2 gap-8 pt-4 border-t border-slate-200">
                    <div>
                        <h4 class="text-3xl font-bold text-navy-900 mb-1">10k+</h4>
                        <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Active Readers</p>
                    </div>
                    <div>
                        <h4 class="text-3xl font-bold text-navy-900 mb-1">500+</h4>
                        <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Curated Articles</p>
                    </div>
                </div>
            </div>
            
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=800&q=80" alt="Breezekings Editorial Room" class="rounded-2xl shadow-2xl relative z-10 w-full object-cover" onerror="this.src='/images/blog-default.jpg'">
                <div class="absolute -bottom-4 -right-4 w-full h-full bg-crimson-600/15 rounded-2xl -z-0"></div>
            </div>
        </div>

        <div class="mt-28">
            <h2 class="text-3xl font-serif font-bold text-navy-900 text-center mb-16">Our Core Values</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm text-center group hover:border-crimson-500/40 hover:shadow-md transition-all duration-300">
                    <div class="w-16 h-16 bg-crimson-50 rounded-2xl flex items-center justify-center text-crimson-600 text-2xl mx-auto mb-6 group-hover:bg-crimson-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="text-xl font-bold text-navy-900 mb-3">Uncompromising Integrity</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">We maintain strict editorial independence and fact-check every piece of content we publish.</p>
                </div>
                
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm text-center group hover:border-crimson-500/40 hover:shadow-md transition-all duration-300">
                    <div class="w-16 h-16 bg-crimson-50 rounded-2xl flex items-center justify-center text-crimson-600 text-2xl mx-auto mb-6 group-hover:bg-crimson-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-navy-900 mb-3">Modern Perspective</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">We leverage modern technology and data to tell stories in more engaging and interactive ways.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm text-center group hover:border-crimson-500/40 hover:shadow-md transition-all duration-300">
                    <div class="w-16 h-16 bg-crimson-50 rounded-2xl flex items-center justify-center text-crimson-600 text-2xl mx-auto mb-6 group-hover:bg-crimson-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold text-navy-900 mb-3">Community Driven</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Our readers are our partners. We listen to your feedback and shape our coverage around your interests.</p>
                </div>
            </div>
        </div>

        <!-- Contact CTA banner -->
        <div class="mt-24 bg-gradient-to-r from-navy-900 to-navy-800 rounded-3xl p-8 md:p-14 text-white text-center shadow-xl">
            <h3 class="text-2xl md:text-3xl font-serif font-bold mb-4">Want to collaborate or share a story?</h3>
            <p class="text-slate-300 max-w-xl mx-auto mb-8 text-sm md:text-base">
                Our editorial team is always open to tips, guest writing proposals, and inquiries.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="mailto:azamwaseem44@gmail.com" class="bg-crimson-600 hover:bg-crimson-700 text-white font-bold px-7 py-3 rounded-full text-sm transition-all shadow-lg hover:shadow-crimson-600/30 flex items-center gap-2">
                    <i class="fa-solid fa-envelope"></i> azamwaseem44@gmail.com
                </a>
                <a href="/contact" class="bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-7 py-3 rounded-full text-sm transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Contact Form
                </a>
            </div>
        </div>
    </div>
    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>