<?php 
include 'include/classes/session.php'; 
$site_url_a  = bk_base_url();
$cur_url_a   = $site_url_a . '/about';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Breezekings</title>
    <meta name="title" content="About Us | Breezekings">
    <meta name="description" content="Learn about Breezekings — an independent digital publication covering News, Business, Technology, Health, Entertainment, and Lifestyle.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo $cur_url_a; ?>">
    <link rel="icon" type="image/svg+xml" href="/images/breezekings-icon-red.svg">
    <meta name="theme-color" content="#0B1F3A">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Breezekings">
    <meta property="og:title" content="About Us | Breezekings">
    <meta property="og:description" content="Learn about Breezekings — an independent digital publication covering six core categories with clarity and depth.">
    <meta property="og:url" content="<?php echo $cur_url_a; ?>">
    <meta property="og:image" content="<?php echo $site_url_a; ?>/images/breezekings-icon-red.svg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="About Us | Breezekings">
    <meta name="twitter:description" content="Learn about Breezekings — an independent digital publication covering six core categories with clarity and depth.">

    <!-- Structured Data: AboutPage -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "AboutPage",
        "name": "About Us | Breezekings",
        "url": "<?php echo $cur_url_a; ?>",
        "description": "Learn about Breezekings — an independent digital publication covering News, Business, Technology, Health, Entertainment, and Lifestyle.",
        "publisher": {
            "@type": "Organization",
            "name": "Breezekings",
            "url": "<?php echo $site_url_a; ?>/",
            "logo": "<?php echo $site_url_a; ?>/images/breezekings-icon-red.svg"
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
        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <span class="inline-block px-3 py-1 bg-crimson-600 text-white text-xs font-bold uppercase tracking-widest rounded-full mb-4">Independent Digital Publication</span>
            <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6">About Breezekings</h1>
            <p class="text-lg md:text-xl text-slate-300 leading-relaxed max-w-2xl mx-auto">
                Delivering clear, well-researched reporting and expert perspectives across the stories, trends, and ideas shaping everyday life.
            </p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-crimson-600 font-bold uppercase tracking-widest text-xs mb-4 block">Our Editorial Standard</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-navy-900 mb-6">In-Depth Reporting Without the Noise</h2>
                <p class="text-slate-600 text-base md:text-lg leading-relaxed mb-6">
                    <strong class="text-navy-900 font-semibold">Breezekings</strong> is an independent digital publication covering News, Business, Technology, Health, Entertainment, and Lifestyle &mdash; clear, practical articles for readers who want the useful version of the story, not just the headline.
                </p>
                <p class="text-slate-600 text-base md:text-lg leading-relaxed mb-8">
                    We started Breezekings with a simple goal: give readers clear, well-researched articles they can actually use, without the noise. Every category on this site is run with the same standard &mdash; accurate information, credited sources, and a focus on what matters to the reader, not just what trends.
                </p>
                
                <div class="grid grid-cols-2 gap-8 pt-4 border-t border-slate-200">
                    <div>
                        <h4 class="text-3xl font-bold text-navy-900 mb-1">6</h4>
                        <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Active Editorial Desks</p>
                    </div>
                    <div>
                        <h4 class="text-3xl font-bold text-navy-900 mb-1">100%</h4>
                        <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Original Research &amp; Fact-Checking</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-8 md:p-10 rounded-2xl shadow-sm border border-slate-200/80 space-y-6">
                <h3 class="text-xl font-bold text-navy-900 font-serif">What You'll Find on Breezekings:</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-crimson-50 text-crimson-600 flex items-center justify-center shrink-0 mt-1 font-bold">
                            <i class="fa-solid fa-check text-xs"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Timely Coverage Across 6 Active Categories</h4>
                             <p class="text-xs text-slate-500 mt-1">News, Business, Technology, Health, Entertainment, and Lifestyle.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-1 font-bold">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Explainers That Go Beyond the Headline</h4>
                            <p class="text-xs text-slate-500 mt-1">We deconstruct complex technological, financial, and cultural shifts so you understand their true implications.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-1 font-bold">
                            <i class="fa-solid fa-book-open text-xs"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">Practical, Actionable Guides</h4>
                            <p class="text-xs text-slate-500 mt-1">How-to guides in health, productivity, lifestyle, personal finance, and modern developer tooling.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 mt-1 font-bold">
                            <i class="fa-solid fa-newspaper text-xs"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">A Growing, Regular Archive</h4>
                            <p class="text-xs text-slate-500 mt-1">Updated on a consistent publishing schedule with original reporting and expert contributors.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Team & Contact Info -->
        <div class="mt-20 bg-white rounded-3xl p-8 md:p-12 border border-slate-200/80 shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div>
                    <span class="text-xs font-bold text-crimson-600 uppercase tracking-widest block mb-2">Editorial Leadership</span>
                    <h3 class="text-2xl md:text-3xl font-serif font-bold text-navy-900 mb-4">Who Runs Breezekings</h3>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed mb-4">
                        Breezekings is operated by an independent editorial team led by Founder &amp; Editor-in-Chief <strong>Waseem Azam</strong> alongside senior tech contributor <strong>Khizar Ahmad</strong>, based in Lahore, Pakistan and serving a global audience.
                    </p>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                        We're always working to improve the accuracy, depth, and usefulness of what we publish &mdash; if you spot something that needs fixing, or want to pitch a story, tell us using the Contact page.
                    </p>
                </div>
                <div class="bg-slate-50 p-6 md:p-8 rounded-2xl border border-slate-200 text-center lg:text-left space-y-4">
                    <h4 class="font-bold text-navy-900 text-lg">Have a story tip, correction, or partnership?</h4>
                    <p class="text-xs text-slate-500">Our editorial desk reviews every message and responds within 24–48 business hours.</p>
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="mailto:azamwaseem44@gmail.com" class="bg-crimson-600 hover:bg-crimson-700 text-white font-bold px-6 py-2.5 rounded-lg text-xs transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-envelope"></i> azamwaseem44@gmail.com
                        </a>
                        <a href="/contact" class="bg-navy-900 hover:bg-navy-950 text-white font-bold px-6 py-2.5 rounded-lg text-xs transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Contact Desk
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>