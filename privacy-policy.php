<?php include 'include/classes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $site_url_pp = bk_base_url();
    $cur_url_pp  = $site_url_pp . '/privacy-policy';
    ?>
    <title>Privacy Policy | Breezekings</title>
    <meta name="title" content="Privacy Policy | Breezekings">
    <meta name="description" content="Read the Breezekings Privacy Policy to understand how we collect, use and protect your personal data.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo $cur_url_pp; ?>">
    <link rel="icon" type="image/svg+xml" href="/images/breezekings-icon-red.svg">
    <meta name="theme-color" content="#0B1F3A">
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
    </style>
</head>
<body class="text-slate-800 antialiased">

    <?php include 'include/frontend_header.php'; ?>

    <div class="bg-navy-900 py-16 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">Privacy Policy</h1>
            <p class="text-slate-400">Last Updated: April 25, 2026</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 prose prose-slate max-w-none">
            <h2 class="text-2xl font-bold text-navy-900 mb-6">1. Information We Collect</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                We collect information you provide directly to us, such as when you create an account, subscribe to our newsletter, or contact us for support. This may include your name, email address, and any other information you choose to provide.
            </p>

            <h2 class="text-2xl font-bold text-navy-900 mb-6">2. How We Use Your Information</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                We use the information we collect to:
            </p>
            <ul class="list-disc pl-6 text-slate-600 mb-8 space-y-2">
                <li>Provide, maintain, and improve our services;</li>
                <li>Send you technical notices, updates, and security alerts;</li>
                <li>Respond to your comments and questions;</li>
                <li>Communicate with you about products, services, and events.</li>
            </ul>

            <h2 class="text-2xl font-bold text-navy-900 mb-6">3. Cookies and Tracking</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                We use cookies and similar tracking technologies to track the activity on our service and hold certain information. Cookies are files with a small amount of data which may include an anonymous unique identifier.
            </p>

            <h2 class="text-2xl font-bold text-navy-900 mb-6">4. Data Security</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                The security of your data is important to us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal data, we cannot guarantee its absolute security.
            </p>

            <h2 class="text-2xl font-bold text-navy-900 mb-6">5. Changes to This Policy</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date.
            </p>

            <div class="mt-12 pt-8 border-t border-slate-100">
                <p class="text-sm text-slate-500">If you have any questions about this Privacy Policy, please contact us at <a href="mailto:azamwaseem44@gmail.com" class="text-crimson-600 font-bold hover:underline">azamwaseem44@gmail.com</a></p>
            </div>
        </div>
    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>
