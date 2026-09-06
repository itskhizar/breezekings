<?php 
include 'include/classes/session.php'; 
$site_url_pp = bk_base_url();
$cur_url_pp  = $site_url_pp . '/privacy-policy';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy | Breezekings</title>
    <meta name="title" content="Privacy Policy | Breezekings">
    <meta name="description" content="Read the Breezekings Privacy Policy to understand how we collect, use, and protect your personal data, including our Google AdSense and cookie disclosures.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo $cur_url_pp; ?>">
    <link rel="icon" type="image/svg+xml" href="/images/breezekings-icon-red.svg">
    <meta name="theme-color" content="#0B1F3A">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Breezekings">
    <meta property="og:title" content="Privacy Policy | Breezekings">
    <meta property="og:description" content="Read the Breezekings Privacy Policy to understand how we collect, use, and protect your data.">
    <meta property="og:url" content="<?php echo $cur_url_pp; ?>">
    <meta property="og:image" content="<?php echo $site_url_pp; ?>/images/breezekings-icon-red.svg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Privacy Policy | Breezekings">
    <meta name="twitter:description" content="Read the Breezekings Privacy Policy to understand how we collect, use, and protect your data.">

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
            <span class="inline-block px-3 py-1 bg-crimson-600 text-white text-xs font-bold uppercase tracking-widest rounded-full mb-3">Legal Documentation</span>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">Privacy Policy</h1>
            <p class="text-slate-400 text-sm">Last updated: <?php echo date('F d, Y'); ?></p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-8 md:p-14 space-y-8 text-slate-700 leading-relaxed">
            
            <p class="text-base md:text-lg">
                <strong>Breezekings</strong> ("we," "us," "our") operates <a href="https://breezekings.com" class="text-crimson-600 font-bold hover:underline">breezekings.com</a> (the "Site"). This Privacy Policy explains what information we collect, how we use it, and the choices and legal rights you have regarding your personal data.
            </p>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">1. Information We Collect</h2>
                <ul class="list-disc pl-6 space-y-2 text-sm text-slate-600">
                    <li><strong>Usage Data:</strong> Pages visited, time spent on site, referring URLs, browser/device type, operating system, and approximate geographical location, collected automatically via analytics tools.</li>
                    <li><strong>Voluntarily Provided Data:</strong> Your name, email address, and message content if you contact us via our contact form, submit a comment on an article, or subscribe to our updates.</li>
                    <li><strong>Cookies &amp; Identifiers:</strong> Small data files stored on your device to remember preferences, enhance user experience, and measure site engagement (see Section 4).</li>
                </ul>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">2. How We Use Information</h2>
                <p class="text-sm text-slate-600">We utilize collected data strictly for legitimate operational purposes:</p>
                <ul class="list-disc pl-6 space-y-2 text-sm text-slate-600">
                    <li>To operate, maintain, and enhance the performance and security of the Site.</li>
                    <li>To understand reader preferences and optimize our editorial coverage across core categories.</li>
                    <li>To respond promptly to editorial inquiries, partnership proposals, and corrections submitted via our Contact page.</li>
                    <li>To moderate comments and prevent fraudulent or automated bot activity.</li>
                    <li>To serve relevant advertising in accordance with applicable privacy frameworks.</li>
                </ul>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">3. Advertising &amp; Third-Party Vendors</h2>
                <div class="bg-amber-50 border-l-4 border-amber-500 p-5 rounded-r-lg space-y-3 text-sm text-amber-950">
                    <p class="font-bold text-base">Google AdSense Disclosure</p>
                    <p>
                        Breezekings uses Google AdSense to display advertising. Google and its third-party advertising partners use cookies (including the DoubleClick cookie) to serve ads based on a visitor's prior visits to this and other websites across the Internet.
                    </p>
                    <p>
                        Google's use of advertising cookies enables it and its certified partners to serve targeted ads based on your visit to our Site and other destinations across the web.
                    </p>
                    <div class="pt-2 flex flex-wrap gap-4">
                        <a href="https://adssettings.google.com" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 bg-navy-900 text-white font-bold px-4 py-2 rounded-lg text-xs hover:bg-navy-950 transition-colors">
                            <i class="fa-solid fa-gear text-xs"></i> Google Ads Settings (Opt Out)
                        </a>
                        <a href="https://www.aboutads.info/choices/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 bg-slate-200 text-slate-800 font-bold px-4 py-2 rounded-lg text-xs hover:bg-slate-300 transition-colors">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i> AboutAds.info Choices
                        </a>
                    </div>
                </div>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">4. Cookies &amp; Tracking Technologies</h2>
                <p class="text-sm text-slate-600">
                    For complete, granular information on the types of cookies we utilize, their expiration timelines, and step-by-step guidance on controlling or deleting cookies from your web browser, please review our dedicated <a href="/cookie-policy" class="text-crimson-600 font-bold hover:underline">Cookie Policy &amp; Ads Disclosure</a>.
                </p>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">5. Children's Privacy</h2>
                <p class="text-sm text-slate-600">
                    Breezekings does not knowingly collect or solicit personal information from children under the age of 13. If you believe that a child under 13 has provided personal details to us without parental consent, please contact us immediately at <a href="mailto:azamwaseem44@gmail.com" class="text-crimson-600 font-bold hover:underline">azamwaseem44@gmail.com</a>, and we will promptly remove such records from our servers.
                </p>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">6. Your Rights (GDPR &amp; CCPA)</h2>
                <p class="text-sm text-slate-600">
                    If you are a resident of the European Economic Area (EEA), United Kingdom, or California, you possess specific data protection rights under the General Data Protection Regulation (GDPR) and the California Consumer Privacy Act (CCPA), including:
                </p>
                <ul class="list-disc pl-6 space-y-2 text-sm text-slate-600">
                    <li>The right to access, update, or delete the information we hold on you.</li>
                    <li>The right of rectification if your data is inaccurate or incomplete.</li>
                    <li>The right to object to or restrict our processing of your personal data.</li>
                    <li>The right to data portability.</li>
                    <li>The right to withdraw consent at any time where processing was based on consent.</li>
                </ul>
                <p class="text-sm text-slate-600">
                    To exercise any of these rights, please email our Data Protection Desk at <a href="mailto:azamwaseem44@gmail.com" class="text-crimson-600 font-bold hover:underline">azamwaseem44@gmail.com</a>.
                </p>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">7. Data Security</h2>
                <p class="text-sm text-slate-600">
                    We maintain reasonable administrative, technical, and physical safeguards designed to protect personal information against unauthorized access, loss, misuse, or alteration. All web communication is encrypted via Transport Layer Security (TLS/SSL).
                </p>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">8. Changes to This Policy</h2>
                <p class="text-sm text-slate-600">
                    We may update this Privacy Policy from time to time to reflect operational, legal, or regulatory modifications. Any changes will be posted on this page with an updated "Last updated" date.
                </p>
            </section>

            <section class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-2xl font-bold text-navy-900 font-serif">9. Contact Us</h2>
                <p class="text-sm text-slate-600">
                    If you have questions, comments, or concerns about this Privacy Policy, please reach out to:
                </p>
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 text-sm space-y-1">
                    <p class="font-bold text-navy-900">Breezekings Editorial &amp; Legal Desk</p>
                    <p>Email: <a href="mailto:azamwaseem44@gmail.com" class="text-crimson-600 font-bold hover:underline">azamwaseem44@gmail.com</a></p>
                    <p>Website: <a href="https://breezekings.com" class="text-navy-900 font-semibold hover:underline">https://breezekings.com</a></p>
                </div>
            </section>

        </div>
    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>
