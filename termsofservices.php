<?php include 'include/classes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service | BlogName</title>
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
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-4">Terms of Service</h1>
            <p class="text-slate-400">Last Updated: April 25, 2026</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-12 prose prose-slate max-w-none">
            <h2 class="text-2xl font-bold text-navy-900 mb-6">1. Acceptance of Terms</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.
            </p>

            <h2 class="text-2xl font-bold text-navy-900 mb-6">2. Use License</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                Permission is granted to temporarily download one copy of the materials (information or software) on BlogName's website for personal, non-commercial transitory viewing only.
            </p>

            <h2 class="text-2xl font-bold text-navy-900 mb-6">3. Content Ownership</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                All content published on this blog, including text, images, and layout, is the intellectual property of BlogName unless otherwise stated. Unauthorized reproduction is strictly prohibited.
            </p>

            <h2 class="text-2xl font-bold text-navy-900 mb-6">4. User Comments</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                We reserve the right to moderate, delete, or edit user comments that are deemed offensive, spammy, or irrelevant. By posting a comment, you grant us the right to display it publicly on our platform.
            </p>

            <h2 class="text-2xl font-bold text-navy-900 mb-6">5. Governing Law</h2>
            <p class="text-slate-600 mb-8 leading-relaxed">
                These terms and conditions are governed by and construed in accordance with the laws of the jurisdiction in which the blog operates.
            </p>

            <div class="mt-12 pt-8 border-t border-slate-100">
                <p class="text-sm text-slate-400">For legal inquiries, please contact legal@blogname.com</p>
            </div>
        </div>
    </div>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>
