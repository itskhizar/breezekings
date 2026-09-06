<?php 
include 'include/classes/session.php'; 
$site_url = bk_base_url();
$canonical_url = $site_url . '/cookie-policy';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Policy &amp; Ads Disclosure | Breezekings</title>
    <meta name="title" content="Cookie Policy &amp; Ads Disclosure | Breezekings">
    <meta name="description" content="Learn how Breezekings uses cookies and how Google AdSense serves relevant advertising. Manage and opt out of advertising cookies.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= $canonical_url ?>">
    <link rel="icon" type="image/svg+xml" href="/images/breezekings-icon-red.svg">
    <meta name="theme-color" content="#0B1F3A">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Breezekings">
    <meta property="og:title" content="Cookie Policy &amp; Ads Disclosure | Breezekings">
    <meta property="og:description" content="Learn how Breezekings uses cookies and how Google AdSense serves relevant advertising.">
    <meta property="og:url" content="<?= $canonical_url ?>">
    <meta property="og:image" content="<?= $site_url ?>/images/breezekings-icon-red.svg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Cookie Policy &amp; Ads Disclosure | Breezekings">
    <meta name="twitter:description" content="Learn how Breezekings uses cookies and how Google AdSense serves relevant advertising.">

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

    <!-- Hero -->
    <div class="bg-navy-900 py-16 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <span class="inline-block px-3 py-1 bg-crimson-600 text-white text-xs font-bold uppercase tracking-widest rounded-full mb-3">Transparency &amp; Privacy</span>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">Cookie Policy &amp; Ads Disclosure</h1>
            <p class="text-slate-400 text-sm">Last Updated: <?php echo date('F d, Y'); ?></p>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-8 md:p-14 space-y-8 text-slate-700 leading-relaxed">
            
            <section class="space-y-4">
                <p class="text-base md:text-lg">
                    <strong>Breezekings</strong> uses cookies and related technologies to improve your browsing experience, analyze site traffic, personalize content, and serve relevant advertising through trusted third-party partners including Google AdSense.
                </p>
                <p class="text-sm text-slate-600">
                    This Cookie Policy explains what cookies are, the specific categories of cookies we use on <a href="<?= $site_url ?>" class="text-crimson-600 font-semibold hover:underline">breezekings.com</a>, and how you can exercise full control over your preferences at any time.
                </p>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">1. What Are Cookies?</h2>
                <p class="text-sm text-slate-600">
                    Cookies are small text files placed on your computer, smartphone, or tablet when you visit a website. They are widely used by online publishers to ensure website functionality, improve efficiency, preserve user preferences, and provide analytical reporting to site owners.
                </p>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">2. Types of Cookies We Use</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                        <div class="w-10 h-10 bg-navy-900 text-white rounded-lg flex items-center justify-center mb-3">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <h3 class="font-bold text-navy-900 text-base mb-2">Essential Cookies</h3>
                        <p class="text-xs text-slate-600">Required for basic site navigation, secure sessions, authentication, and core functionality. Cannot be switched off.</p>
                    </div>

                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center mb-3">
                            <i class="fa-solid fa-chart-line text-sm"></i>
                        </div>
                        <h3 class="font-bold text-navy-900 text-base mb-2">Analytics Cookies</h3>
                        <p class="text-xs text-slate-600">Help us measure visitor counts, popular pages, bounce rates, and traffic sources to optimize editorial quality.</p>
                    </div>

                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                        <div class="w-10 h-10 bg-crimson-600 text-white rounded-lg flex items-center justify-center mb-3">
                            <i class="fa-solid fa-bullhorn text-sm"></i>
                        </div>
                        <h3 class="font-bold text-navy-900 text-base mb-2">Advertising Cookies</h3>
                        <p class="text-xs text-slate-600">Used by Google AdSense and certified ad networks to serve personalized, relevant advertisements based on your interests.</p>
                    </div>
                </div>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">3. Third-Party Advertising &amp; Google AdSense</h2>
                <p class="text-sm text-slate-600">
                    Breezekings partners with third-party vendors, including Google, which use cookies (including the DoubleClick cookie) to serve ads based on your prior visits to this and other websites across the Internet.
                </p>
                <p class="text-sm text-slate-600">
                    Google's use of advertising cookies enables it and its certified advertising partners to serve ads to our users based on their visit to our sites and/or other sites on the Internet.
                </p>
                <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-r-lg text-sm text-blue-900 space-y-2">
                    <p class="font-semibold">How Google uses data when you use our partner sites:</p>
                    <p>To learn more about Google's data privacy practices, visit: <a href="https://policies.google.com/technologies/partner-sites" target="_blank" rel="noopener noreferrer" class="underline font-bold hover:text-blue-800">https://policies.google.com/technologies/partner-sites</a></p>
                </div>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">4. How to Control or Opt Out of Cookies</h2>
                <p class="text-sm text-slate-600">
                    You have the right to decide whether to accept or reject cookies. You can exercise your choices through multiple methods:
                </p>
                <ul class="list-disc pl-6 space-y-2 text-sm text-slate-600">
                    <li>
                        <strong>Google Personalized Ads Opt-Out:</strong> You may opt out of personalized advertising by visiting Google's Ads Settings at: <a href="https://adssettings.google.com" target="_blank" rel="noopener noreferrer" class="text-crimson-600 font-bold hover:underline">https://adssettings.google.com</a>.
                    </li>
                    <li>
                        <strong>Industry Opt-Out Portals:</strong> You can opt out of interest-based advertising from participating third-party networks via the Digital Advertising Alliance (<a href="https://www.aboutads.info/choices/" target="_blank" rel="noopener noreferrer" class="text-crimson-600 font-bold hover:underline">www.aboutads.info/choices</a>) or the European Interactive Digital Advertising Alliance (<a href="https://www.youronlinechoices.com/" target="_blank" rel="noopener noreferrer" class="text-crimson-600 font-bold hover:underline">www.youronlinechoices.com</a>).
                    </li>
                    <li>
                        <strong>Browser Controls:</strong> Most web browsers allow you to manage or delete cookies directly through their settings (Chrome, Safari, Firefox, Edge). Please note that disabling essential cookies may impact certain functionality of our website.
                    </li>
                </ul>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">5. Contact Our Privacy Officer</h2>
                <p class="text-sm text-slate-600">
                    If you have questions about our use of cookies, ad disclosures, or data privacy practices, reach our editorial desk directly:
                </p>
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 text-sm space-y-1">
                    <p class="font-bold text-navy-900">Breezekings Privacy Desk</p>
                    <p>Email: <a href="mailto:azamwaseem44@gmail.com" class="text-crimson-600 font-bold hover:underline">azamwaseem44@gmail.com</a></p>
                    <p>Website: <a href="https://breezekings.com" class="text-navy-900 font-semibold hover:underline">https://breezekings.com</a></p>
                </div>
            </section>

        </div>
    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>
