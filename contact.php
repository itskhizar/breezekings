<?php include 'include/classes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $site_url_c = bk_base_url();
    $cur_url_c  = $site_url_c . '/contact';
    ?>
    <title>Contact Us | Breezekings</title>
    <meta name="title" content="Contact Us | Breezekings">
    <meta name="description" content="Get in touch with Breezekings. Send us your story tips, feedback or advertising enquiries at azamwaseem44@gmail.com.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo $cur_url_c; ?>">
    <link rel="icon" type="image/svg+xml" href="/images/breezekings-icon-red.svg">
    <meta name="theme-color" content="#0B1F3A">
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Breezekings">
    <meta property="og:title" content="Contact Us | Breezekings">
    <meta property="og:description" content="Get in touch with Breezekings. Send us your story tips, feedback or advertising enquiries.">
    <meta property="og:url" content="<?php echo $cur_url_c; ?>">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Contact Us | Breezekings">
    <meta name="twitter:description" content="Get in touch with Breezekings.">

    <!-- Structured Data: ContactPage -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ContactPage",
        "name": "Contact Us | Breezekings",
        "url": "<?php echo $cur_url_c; ?>",
        "description": "Get in touch with Breezekings. Send us your story tips, feedback or advertising enquiries.",
        "publisher": {
            "@type": "Organization",
            "name": "Breezekings",
            "url": "<?php echo $site_url_c; ?>/"
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
    </style>
</head>
<body class="text-slate-800 antialiased bg-slate-50">

    <?php include 'include/frontend_header.php'; ?>

    <div class="bg-navy-900 py-16 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="inline-block px-3 py-1 bg-crimson-600 text-white text-xs font-bold uppercase tracking-widest rounded-full mb-3">Get in Touch</span>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">We'd Love to Hear From You</h1>
            <p class="text-slate-300 max-w-xl mx-auto text-sm md:text-base">Have a story idea, feedback, or advertising inquiry? Reach our team directly at <a href="mailto:azamwaseem44@gmail.com" class="text-white font-bold underline hover:text-crimson-400">azamwaseem44@gmail.com</a></p>
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Contact Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/80 flex items-start gap-5">
                    <div class="w-12 h-12 bg-crimson-50 text-crimson-600 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-envelope text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-900 mb-1">Direct Editorial Email</h3>
                        <p class="text-xs text-slate-500 mb-2">Pitches, feedback &amp; inquiries</p>
                        <a href="mailto:azamwaseem44@gmail.com" class="text-crimson-600 font-bold hover:underline text-sm break-all">azamwaseem44@gmail.com</a>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/80 flex items-start gap-5">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-bullhorn text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-900 mb-1">Advertising &amp; Partnerships</h3>
                        <p class="text-xs text-slate-500 mb-2">Sponsorships &amp; collaborations</p>
                        <a href="mailto:azamwaseem44@gmail.com" class="text-blue-600 font-bold hover:underline text-sm break-all">azamwaseem44@gmail.com</a>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200/80 flex items-start gap-5">
                    <div class="w-12 h-12 bg-slate-100 text-slate-700 rounded-xl flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-clock text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy-900 mb-1">Response Time</h3>
                        <p class="text-xs text-slate-500 mb-1">Editorial desk hours</p>
                        <p class="text-slate-800 text-sm font-semibold">Within 24–48 Business Hours</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2 bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 mb-8">Send us a Message</h2>
                <form action="#" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Full Name</label>
                            <input type="text" placeholder="John Doe" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-crimson-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" placeholder="john@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-crimson-500 transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Subject</label>
                        <input type="text" placeholder="How can we help?" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-crimson-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Your Message</label>
                        <textarea rows="6" placeholder="Tell us more about your query..." class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm focus:outline-none focus:border-crimson-500 transition-colors resize-none"></textarea>
                    </div>
                    <button type="submit" class="bg-crimson-600 hover:bg-crimson-500 text-white font-bold py-4 px-8 rounded-lg text-sm uppercase tracking-wider transition-all shadow-lg shadow-crimson-600/20">Send Message</button>
                </form>
            </div>

        </div>
    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>
