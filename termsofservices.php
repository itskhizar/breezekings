<?php 
include 'include/classes/session.php'; 
$site_url_tos = bk_base_url();
$cur_url_tos  = $site_url_tos . '/termsofservices';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms &amp; Conditions | Breezekings</title>
    <meta name="title" content="Terms &amp; Conditions | Breezekings">
    <meta name="description" content="Review the Terms &amp; Conditions governing your use of Breezekings, including content usage, advertising disclosures, and liability policies.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo $cur_url_tos; ?>">
    <link rel="icon" type="image/svg+xml" href="/images/breezekings-icon-red.svg">
    <meta name="theme-color" content="#0B1F3A">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Breezekings">
    <meta property="og:title" content="Terms &amp; Conditions | Breezekings">
    <meta property="og:description" content="Review the Terms &amp; Conditions governing your use of Breezekings.">
    <meta property="og:url" content="<?php echo $cur_url_tos; ?>">
    <meta property="og:image" content="<?php echo $site_url_tos; ?>/images/breezekings-icon-red.svg">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Terms &amp; Conditions | Breezekings">
    <meta name="twitter:description" content="Review the Terms &amp; Conditions governing your use of Breezekings.">

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
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">Terms &amp; Conditions</h1>
            <p class="text-slate-400 text-sm">Last updated: <?php echo date('F d, Y'); ?></p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-8 md:p-14 space-y-8 text-slate-700 leading-relaxed">
            
            <p class="text-base md:text-lg">
                By accessing and using <a href="https://breezekings.com" class="text-crimson-600 font-bold hover:underline">breezekings.com</a> (the "Site"), you agree to comply with and be legally bound by these Terms &amp; Conditions. If you do not agree to these terms, please do not use the Site.
            </p>

            <section class="space-y-3 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-bold text-navy-900 font-serif">1. Use of Content</h2>
                <p class="text-sm text-slate-600">
                    All articles, images, graphics, trademarks, logos, and branding on Breezekings are the intellectual property of Breezekings unless otherwise credited. You may share hyperlinks to our content across social networks, but you may not republish, scrape, copy, or redistribute full articles without explicit prior written authorization.
                </p>
            </section>

            <section class="space-y-3 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-bold text-navy-900 font-serif">2. User Conduct &amp; Comments</h2>
                <p class="text-sm text-slate-600">
                    When submitting comments, feedback, or contacting our team, you agree not to submit anything unlawful, defamatory, abusive, harassing, infringing on third-party intellectual property, or automated spam. We reserve the absolute right to moderate, edit, or remove any comments that violate these principles.
                </p>
            </section>

            <section class="space-y-3 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-bold text-navy-900 font-serif">3. External Links</h2>
                <p class="text-sm text-slate-600">
                    Breezekings articles may include hyperlinks to external third-party websites for journalistic reference and source attribution. We are not responsible for the content, privacy policies, or operational practices of external destinations.
                </p>
            </section>

            <section class="space-y-3 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-bold text-navy-900 font-serif">4. No Professional Advice</h2>
                <p class="text-sm text-slate-600">
                    Content published across all Breezekings categories &mdash; including Health, Business, Finance, and Technology &mdash; is created strictly for general informational and educational purposes. It does not constitute professional medical, legal, or financial advice. Always consult a qualified professional before making health, financial, or legal decisions.
                </p>
            </section>

            <section class="space-y-3 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-bold text-navy-900 font-serif">5. Advertising &amp; Third-Party Disclosures</h2>
                <p class="text-sm text-slate-600">
                    Breezekings displays third-party advertising, including advertisements served via Google AdSense and certified networks. We do not directly endorse or control the products or services advertised in third-party ad units.
                </p>
            </section>

            <section class="space-y-3 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-bold text-navy-900 font-serif">6. Disclaimer of Warranties</h2>
                <p class="text-sm text-slate-600">
                    The Site is provided on an "as is" and "as available" basis without warranties of any kind, whether express or implied. While we strive for absolute accuracy, we do not guarantee the completeness, timeliness, or error-free status of all published materials.
                </p>
            </section>

            <section class="space-y-3 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-bold text-navy-900 font-serif">7. Limitation of Liability</h2>
                <p class="text-sm text-slate-600">
                    Under no circumstances shall Breezekings, its editors, authors, or affiliates be liable for any direct, indirect, incidental, or consequential damages resulting from your use of, or inability to use, this Site or its content.
                </p>
            </section>

            <section class="space-y-3 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-bold text-navy-900 font-serif">8. Changes to These Terms</h2>
                <p class="text-sm text-slate-600">
                    We reserve the right to revise and update these Terms &amp; Conditions at our sole discretion. Your continued use of the Site following the posting of updated terms constitutes your acceptance of the amendments.
                </p>
            </section>

            <section class="space-y-3 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-bold text-navy-900 font-serif">9. Governing Law &amp; Jurisdiction</h2>
                <p class="text-sm text-slate-600">
                    These Terms are governed by and construed in accordance with the applicable laws of Pakistan and international digital media standards, without regard to conflict of law principles.
                </p>
            </section>

            <section class="space-y-3 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-bold text-navy-900 font-serif">10. Contact Us</h2>
                <p class="text-sm text-slate-600">
                    For legal questions, licensing inquiries, or permissions regarding these Terms, contact our editorial desk:
                </p>
                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 text-sm space-y-1">
                    <p class="font-bold text-navy-900">Breezekings Legal Desk</p>
                    <p>Email: <a href="mailto:azamwaseem44@gmail.com" class="text-crimson-600 font-bold hover:underline">azamwaseem44@gmail.com</a></p>
                    <p>Website: <a href="https://breezekings.com" class="text-navy-900 font-semibold hover:underline">https://breezekings.com</a></p>
                </div>
            </section>

        </div>
    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>
