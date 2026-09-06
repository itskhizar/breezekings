<?php 
include 'include/classes/session.php'; 
http_response_code(404);
$site_url = bk_base_url();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 &mdash; Page Not Found | Breezekings</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fafafa; }
    </style>
</head>
<body class="text-slate-800 antialiased bg-slate-50 flex flex-col min-h-screen">

    <?php include 'include/frontend_header.php'; ?>

    <main class="flex-1 flex items-center justify-center py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full text-center">
            
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-crimson-50 text-crimson-600 mb-8 border border-crimson-100 shadow-sm">
                <span class="text-3xl font-black font-serif tracking-tight">404</span>
            </div>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold text-navy-900 mb-4 tracking-tight">
                Page Not Found
            </h1>
            <p class="text-slate-600 text-sm sm:text-base max-w-md mx-auto mb-8 leading-relaxed">
                The article or page you are looking for may have been moved, renamed, or is temporarily unavailable.
            </p>

            <!-- Search Form -->
            <form action="/" method="GET" class="max-w-md mx-auto mb-10 relative">
                <div class="relative flex items-center">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Search Breezekings articles…" 
                        class="w-full bg-white border border-slate-300 rounded-full py-3.5 pl-5 pr-14 text-sm text-slate-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-navy-900/20 focus:border-navy-900 transition-all"
                    >
                    <button type="submit" class="absolute right-2 bg-navy-900 hover:bg-navy-950 text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Action Links -->
            <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
                <a href="/" class="bg-crimson-600 hover:bg-crimson-700 text-white font-bold py-3 px-6 rounded-lg text-xs uppercase tracking-wider transition-all shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-house"></i>
                    <span>Return to Homepage</span>
                </a>
                <a href="/contact" class="bg-white hover:bg-slate-50 border border-slate-300 text-navy-900 font-bold py-3 px-6 rounded-lg text-xs uppercase tracking-wider transition-all shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Contact Support</span>
                </a>
            </div>

            <!-- Browse Categories -->
            <div class="mt-14 pt-10 border-t border-slate-200">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Or Browse by Category</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <?php
                    $browse_cats = $database->get_all_categories();
                    if ($browse_cats) {
                        while ($bc = mysqli_fetch_assoc($browse_cats)) {
                            echo '<a href="' . bk_category_url($bc) . '" class="bg-white px-3.5 py-1.5 rounded-full text-xs font-semibold text-slate-600 border border-slate-200 hover:border-crimson-600 hover:text-crimson-600 transition-colors">' . htmlspecialchars($bc['category']) . '</a>';
                        }
                    }
                    ?>
                </div>
            </div>

        </div>
    </main>

    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>
