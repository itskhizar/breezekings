<?php
include 'include/classes/session.php';

$cat_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$cat_query = $database->query("SELECT * FROM categories WHERE id = $cat_id");
$cat_data = mysqli_fetch_assoc($cat_query);

if (!$cat_data) {
    header("Location: index.php");
    exit();
}

$category_name = $cat_data['category'];
$posts_result = $database->get_posts_by_category($cat_id);
$num_articles = mysqli_num_rows($posts_result);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $category_name; ?> | BlogName</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Read the latest editorial pieces in <?php echo $category_name; ?>.">
    
    <!-- Tailwind CSS -->
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
                        navy: {
                            800: '#1e2336',
                            900: '#151928',
                            950: '#0d101a',
                        },
                        crimson: {
                            500: '#e12b38',
                            600: '#c5202b',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fafafa; }
        .font-serif { font-family: 'Playfair Display', serif; }
        
        .nav-link { position: relative; padding-bottom: 4px; }
        .nav-link.active::after {
            content: ''; position: absolute; left: 0; bottom: 0; width: 100%; height: 2px; background-color: #e12b38;
        }
        .nav-link:hover::after {
            content: ''; position: absolute; left: 0; bottom: 0; width: 100%; height: 2px; background-color: #e12b38;
            opacity: 0.5;
        }

        .section-title {
            position: relative;
            padding-left: 1rem;
        }
        .section-title::before {
            content: ''; position: absolute; left: 0; top: 10%; height: 80%; width: 3px; background-color: #e12b38;
        }

        .card-zoom overflow-hidden img {
            transition: transform 0.5s ease;
        }
        .card-zoom:hover img {
            transform: scale(1.05);
        }

        /* Numbered List for Trending Now */
        .popular-num {
            font-size: 2.5rem;
            font-weight: 300;
            color: #d1d5db; /* Light gray */
            line-height: 1;
            font-family: 'Playfair Display', serif;
        }

        /* Mobile Hover/Active state fixes */
        @media (hover: none) {
            .group:active img { transform: scale(1.05); }
            .nav-link:active { color: #e12b38; }
        }
    </style>
</head>
<body class="text-slate-800 antialiased selection:bg-crimson-500 selection:text-white">

    <?php include 'include/frontend_header.php'; ?>

    <!-- Page Header (Category Title) -->
    <div class="bg-navy-900 pt-10 pb-12">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-4">
                <a href="index.php" class="hover:text-white transition-colors">Home</a> 
                <span class="mx-2">&gt;</span> 
                <span class="text-white"><?php echo htmlspecialchars($category_name); ?></span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-4"><?php echo htmlspecialchars($category_name); ?></h1>
            <p class="text-crimson-500 text-xs font-bold tracking-widest uppercase"><?php echo $num_articles; ?> Articles</p>
        </div>
    </div>

    <div class="bg-slate-100 pb-16 pt-12">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row gap-10">
                
                <!-- Left Column (70%) -->
                <main class="w-full lg:w-[70%]">
                    
                    <div class="flex justify-between items-end mb-8 border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-serif font-bold text-navy-900 section-title">Latest Journalism</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <?php
                        if ($num_articles > 0) {
                            while ($post = mysqli_fetch_assoc($posts_result)) {
                                $thumbnail = $post['featured_image'] ? "images/posts/" . $post['featured_image'] : "images/blog-default.jpg";
                        ?>
                        <article class="bg-white rounded-sm shadow-sm overflow-hidden group flex flex-col">
                            <a href="single.php?id=<?php echo $post['id']; ?>" class="block h-56 overflow-hidden relative">
                                <img src="<?php echo $thumbnail; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            </a>
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center gap-4 mb-3">
                                    <span class="bg-orange-50 text-orange-600 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-sm"><?php echo strtoupper(htmlspecialchars($category_name)); ?></span>
                                    <span class="text-[11px] text-slate-500 font-medium"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                </div>
                                <h3 class="text-xl font-serif font-bold text-navy-900 mb-3 leading-snug group-hover:text-crimson-600 transition-colors">
                                    <a href="single.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                                </h3>
                                <p class="text-slate-500 mb-5 text-sm leading-relaxed flex-1 line-clamp-3">
                                    <?php echo !empty($post['excerpt']) ? htmlspecialchars($post['excerpt']) : substr(strip_tags($post['content']), 0, 150) . '...'; ?>
                                </p>
                                <a href="single.php?id=<?php echo $post['id']; ?>" class="text-crimson-600 font-semibold text-sm hover:text-crimson-800 transition-colors flex items-center gap-1 group-hover:gap-2">
                                    Read Article <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </article>
                        <?php } } else { echo '<div class="md:col-span-2 text-center py-20 text-slate-400">No articles in this category yet.</div>'; } ?>

                    </div>

                    <!-- Pagination -->
                    <div class="mt-12 flex justify-center">
                        <nav class="flex items-center gap-2">
                            <a href="#" class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-500 hover:border-slate-300 transition-colors bg-white"><i class="fa-solid fa-chevron-left text-xs"></i></a>
                            <a href="#" class="w-9 h-9 flex items-center justify-center rounded bg-crimson-600 text-white font-medium text-sm">1</a>
                            <a href="#" class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium bg-white">2</a>
                            <a href="#" class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium bg-white">3</a>
                            <span class="px-1 text-slate-400">...</span>
                            <a href="#" class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium bg-white">12</a>
                            <a href="#" class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-500 hover:border-slate-300 transition-colors bg-white"><i class="fa-solid fa-chevron-right text-xs"></i></a>
                        </nav>
                    </div>

                </main>

                <!-- Right Sidebar Area (30%) -->
                <aside class="lg:w-[30%]">
                    <div class="sticky top-24 space-y-10">
                        
                        <!-- Top Read Today (Featured Sticky) -->
                        <?php
                        $top_read = $database->get_popular_posts(1);
                        if($tr = mysqli_fetch_assoc($top_read)) {
                            $tr_thumb = $tr['featured_image'] ? 'images/posts/'.$tr['featured_image'] : 'images/blog-default.jpg';
                        ?>
                        <div class="bg-navy-950 rounded-xl overflow-hidden shadow-xl border border-white/5 group">
                            <div class="p-4 bg-crimson-600 flex justify-between items-center">
                                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Top Read Today</h3>
                                <i class="fa-solid fa-fire-flame-curved text-white/50 text-xs"></i>
                            </div>
                            <a href="single.php?id=<?php echo $tr['id']; ?>" class="block relative h-48">
                                <img src="<?php echo $tr_thumb; ?>" class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity">
                                <div class="absolute inset-0 bg-gradient-to-t from-navy-950 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h4 class="text-lg font-serif font-bold text-white leading-tight group-hover:text-crimson-400 transition-colors"><?php echo htmlspecialchars($tr['title']); ?></h4>
                                </div>
                            </a>
                            <div class="p-5 bg-navy-900/50 backdrop-blur-md">
                                <p class="text-slate-400 text-xs mb-4 line-clamp-2 italic">"<?php echo htmlspecialchars($tr['excerpt']); ?>"</p>
                                <a href="single.php?id=<?php echo $tr['id']; ?>" class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center gap-2 hover:gap-3 transition-all">
                                    READ FULL ARTICLE <i class="fa-solid fa-arrow-right text-crimson-500"></i>
                                </a>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Sidebar Main Tinted Box -->
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-8 space-y-10">
                            
                            <!-- Search Archive -->
                            <div>
                                <h3 class="text-[11px] font-bold text-slate-900 mb-5 uppercase tracking-widest border-b border-slate-200 pb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-magnifying-glass text-crimson-500"></i> Search Archive
                                </h3>
                                <form action="index.php" method="GET" class="relative">
                                    <input type="text" name="q" placeholder="Keywords..." class="w-full bg-white border border-slate-200 text-slate-800 rounded-lg py-3 pl-4 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-crimson-500/10 focus:border-crimson-500 transition-all shadow-sm">
                                    <button type="submit" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-crimson-600 transition-colors">
                                        <i class="fa-solid fa-paper-plane text-sm"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Trending Now -->
                            <div>
                                <h3 class="text-[11px] font-bold text-slate-900 mb-5 uppercase tracking-widest border-b border-slate-200 pb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-bolt text-amber-500"></i> Trending Now
                                </h3>
                                <div class="space-y-6">
                                    <?php
                                    $trending = $database->get_popular_posts(4);
                                    mysqli_data_seek($trending, 1);
                                    while($t = mysqli_fetch_assoc($trending)) {
                                        $t_thumb = $t['featured_image'] ? 'images/posts/'.$t['featured_image'] : 'images/blog-default.jpg';
                                    ?>
                                    <a href="single.php?id=<?php echo $t['id']; ?>" class="flex gap-4 group">
                                        <div class="w-20 h-16 shrink-0 rounded-lg overflow-hidden border border-slate-200">
                                            <img src="<?php echo $t_thumb; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-serif font-bold text-navy-900 leading-snug group-hover:text-crimson-600 transition-colors line-clamp-2"><?php echo htmlspecialchars($t['title']); ?></h4>
                                            <div class="flex items-center gap-2 mt-2">
                                                <span class="text-[9px] text-slate-400 font-bold uppercase"><?php echo date('M d, Y', strtotime($t['created_at'])); ?></span>
                                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                <span class="text-[9px] text-crimson-500 font-bold"><?php echo $t['views']; ?> VIEWS</span>
                                            </div>
                                        </div>
                                    </a>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Categories -->
                            <div>
                                <h3 class="text-[11px] font-bold text-slate-900 mb-5 uppercase tracking-widest border-b border-slate-200 pb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-tags text-blue-500"></i> Categories
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    <?php
                                    $cats = $database->get_all_categories();
                                    while ($cat = mysqli_fetch_assoc($cats)) {
                                    ?>
                                    <a href="category.php?id=<?php echo $cat['id']; ?>" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 text-[10px] font-bold rounded hover:bg-navy-900 hover:text-white hover:border-navy-900 transition-all uppercase tracking-wider">
                                        <?php echo htmlspecialchars($cat['category']); ?> 
                                        <span class="ml-1 text-slate-400 group-hover:text-white/50">(<?php echo $cat['post_count']; ?>)</span>
                                    </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                    <!-- Ad Placeholder Square -->
                    <!-- Ad Placeholder (Hidden) -->
                    <!-- <div class="bg-slate-50 border border-dashed border-slate-300 flex flex-col items-center justify-center text-center h-[250px] w-full max-w-[300px] mx-auto hidden md:flex">
                        <p class="text-slate-400 text-[10px] uppercase tracking-widest mb-1">Advertisement</p>
                        <i class="fa-regular fa-image text-slate-300 text-3xl mt-2"></i>
                    </div> -->

                </aside>
            </div>
            
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-navy-900 text-white pt-16 pb-8 border-t-4 border-crimson-600">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                
                <!-- Brand -->
                <div class="col-span-1">
                    <a href="index.php" class="font-serif font-bold text-3xl tracking-tight mb-6 block">BlogName</a>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Pioneering digital journalism with depth, authority, and mathematical precision.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fa-solid fa-rss"></i></a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fa-solid fa-globe"></i></a>
                    </div>
                </div>

                <!-- Editorial -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase text-[11px] tracking-widest">Editorial</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="category.php" class="text-slate-400 hover:text-white transition-colors">Politics</a></li>
                        <li><a href="category.php" class="text-slate-400 hover:text-white transition-colors">Technology</a></li>
                        <li><a href="category.php" class="text-slate-400 hover:text-white transition-colors">Culture</a></li>
                        <li><a href="category.php" class="text-slate-400 hover:text-white transition-colors">Business</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase text-[11px] tracking-widest">Company</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="about.php" class="text-slate-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Contact</a></li>
                        <li><a href="privacy-policy.php" class="text-slate-400 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Editorial Guidelines</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase text-[11px] tracking-widest">Support</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="termsofservices.php" class="text-slate-400 hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Advertise</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Press Kit</a></li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-navy-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-xs">
                    &copy; 2024 BlogName Editorial Group. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
