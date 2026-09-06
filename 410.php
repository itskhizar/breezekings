<?php 
include 'include/classes/session.php'; 
http_response_code(410);
$site_url = bk_base_url();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>410 &mdash; Content Removed | Breezekings</title>
    <meta name="robots" content="noindex, follow">
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
                        navy: { 800: '#1e2336', 900: '#0B1F3A', 950: '#071526' },
                        crimson: { 500: '#e12b38', 600: '#C8102E', 700: '#a31a23' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-navy-900 text-white min-h-screen flex flex-col items-center justify-center px-4 antialiased">

    <!-- Logo -->
    <a href="/" class="flex items-center gap-3 mb-12 opacity-80 hover:opacity-100 transition-opacity">
        <svg width="40" height="40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" aria-hidden="true">
            <rect x="0" y="0" width="100" height="100" rx="22" fill="#0B1F3A"/>
            <rect x="30" y="20" width="13" height="60" rx="3" fill="#FFFFFF"/>
            <path d="M43,20 H60 L66,26 Q69,29 69,35 Q69,49 54,49 H43 Z" fill="#FFFFFF"/>
            <path d="M46,55 L75,27" stroke="#C8102E" stroke-width="11" stroke-linecap="square" fill="none"/>
            <path d="M46,59 L75,89" stroke="#C8102E" stroke-width="11" stroke-linecap="square" fill="none"/>
        </svg>
        <span style="font-family:'Inter',sans-serif;font-weight:800;font-size:1.35rem;letter-spacing:-.02em;color:#fff;">Breeze<span style="color:#C8102E;">kings</span></span>
    </a>

    <!-- Error card -->
    <div class="bg-navy-800/60 border border-white/10 backdrop-blur-sm rounded-2xl p-10 max-w-lg w-full text-center shadow-2xl">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-500/10 border border-amber-500/20 mb-6">
            <i class="fa-solid fa-trash-can text-amber-400 text-2xl" aria-hidden="true"></i>
        </div>
        <p class="text-amber-400 font-mono font-bold tracking-widest text-xs uppercase mb-3">HTTP 410 Gone</p>
        <h1 class="font-serif text-3xl md:text-4xl font-bold text-white mb-4">Content Permanently Removed</h1>
        <p class="text-slate-300 text-sm leading-relaxed mb-8">
            This page or section has been permanently removed from Breezekings and will not return. 
            If you followed an old link, please explore our current content below.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="/" class="inline-flex items-center justify-center gap-2 bg-crimson-600 hover:bg-crimson-700 text-white font-bold text-sm py-2.5 px-6 rounded-lg transition-colors">
                <i class="fa-solid fa-house text-xs" aria-hidden="true"></i> Back to Homepage
            </a>
            <a href="/contact" class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold text-sm py-2.5 px-6 rounded-lg border border-white/20 transition-colors">
                <i class="fa-solid fa-envelope text-xs" aria-hidden="true"></i> Contact Us
            </a>
        </div>
    </div>

    <!-- Browse categories -->
    <div class="mt-10 text-center">
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-4">Browse Active Categories</p>
        <div class="flex flex-wrap justify-center gap-2">
            <?php
            $browse_cats = $database->get_all_categories();
            while ($bc = mysqli_fetch_assoc($browse_cats)) {
                $bc_url = bk_category_url($bc['id'], $bc['category']);
                echo '<a href="' . $bc_url . '" class="px-3 py-1.5 bg-white/10 hover:bg-crimson-600 text-white text-xs font-bold rounded-full border border-white/10 transition-colors uppercase tracking-wide">' . htmlspecialchars($bc['category']) . '</a>';
            }
            ?>
        </div>
    </div>

</body>
</html>
