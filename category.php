<?php
include 'include/classes/session.php';

$cat_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cat_data = null;

if ($cat_id <= 0 && !empty($_GET['slug'])) {
    $raw_slug = strtolower(trim($_GET['slug']));
    $clean_cat_search = mysqli_real_escape_string($database->connection, str_replace('-', ' ', $raw_slug));
    $cat_query = $database->query("SELECT * FROM categories WHERE LOWER(category) = '$clean_cat_search' LIMIT 1");
    $cat_data = mysqli_fetch_assoc($cat_query);
    if ($cat_data) {
        $cat_id = (int)$cat_data['id'];
    }
}

if (!$cat_data && $cat_id > 0) {
    $cat_query = $database->query("SELECT * FROM categories WHERE id = $cat_id");
    $cat_data = mysqli_fetch_assoc($cat_query);
}

if (!$cat_data) {
    header("Location: /");
    exit();
}

// 301 Redirect old duplicate category id=9 (Sports) to canonical category id=4 (Sports)
if ($cat_id == 9) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . bk_base_url() . "/category/4/sports");
    exit();
}

$category_name = $cat_data['category'];
$clean_cat_rel = bk_category_url($cat_id, $category_name);

// 301 Redirect old /category.php?id=... to clean SEO permalink
$req_uri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($req_uri, 'category.php') !== false) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . bk_base_url() . $clean_cat_rel);
    exit();
}

$posts_result = $database->get_posts_by_category($cat_id);
$num_articles = mysqli_num_rows($posts_result);

$site_url_cat   = bk_base_url();
$canonical_cat  = $site_url_cat . $clean_cat_rel;
$cat_intro      = bk_category_description($category_name);
$cat_meta_desc  = htmlspecialchars(mb_substr(strip_tags($cat_intro), 0, 155));
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary SEO Meta Tags -->
    <title><?php echo htmlspecialchars($category_name); ?> Articles | Breezekings</title>
    <meta name="title" content="<?php echo htmlspecialchars($category_name); ?> Articles | Breezekings">
    <meta name="description" content="<?php echo $cat_meta_desc; ?>">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
    <link rel="canonical" href="<?php echo $canonical_cat; ?>">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Breezekings">
    <meta property="og:title" content="<?php echo htmlspecialchars($category_name); ?> | Breezekings">
    <meta property="og:description" content="<?php echo $cat_meta_desc; ?>">
    <meta property="og:url" content="<?php echo $canonical_cat; ?>">
    <meta property="og:image" content="<?php echo $site_url_cat; ?>/images/breezekings-icon-red.svg">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@breezekings">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($category_name); ?> | Breezekings">
    <meta name="twitter:description" content="<?php echo $cat_meta_desc; ?>">
    <meta name="twitter:image" content="<?php echo $site_url_cat; ?>/images/breezekings-icon-red.svg">

    <!-- Structured Data: CollectionPage & BreadcrumbList -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "CollectionPage",
                "@id": "<?php echo $canonical_cat; ?>#webpage",
                "url": "<?php echo $canonical_cat; ?>",
                "name": "<?php echo addslashes($category_name); ?> Articles | Breezekings",
                "description": "<?php echo addslashes($cat_meta_desc); ?>",
                "isPartOf": {
                    "@type": "WebSite",
                    "name": "Breezekings",
                    "url": "<?php echo $site_url_cat; ?>/"
                }
            },
            {
                "@type": "BreadcrumbList",
                "@id": "<?php echo $canonical_cat; ?>#breadcrumb",
                "itemListElement": [
                    {
                        "@type": "ListItem",
                        "position": 1,
                        "name": "Home",
                        "item": "<?php echo $site_url_cat; ?>/"
                    },
                    {
                        "@type": "ListItem",
                        "position": 2,
                        "name": "<?php echo addslashes($category_name); ?>",
                        "item": "<?php echo $canonical_cat; ?>"
                    }
                ]
            }
        ]
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
            <nav aria-label="Breadcrumb" class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-4">
                <a href="/" class="hover:text-white transition-colors">Home</a> 
                <span class="mx-2">&gt;</span> 
                <span class="text-white" aria-current="page"><?php echo htmlspecialchars($category_name); ?></span>
            </nav>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-4"><?php echo htmlspecialchars($category_name); ?></h1>
            <p class="text-slate-300 text-sm md:text-base leading-relaxed max-w-3xl mb-5"><?php echo htmlspecialchars($cat_intro); ?></p>
            <p class="text-crimson-400 text-xs font-bold tracking-widest uppercase flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-xs"></i>
                <span><?php echo $num_articles; ?> Published <?php echo $num_articles == 1 ? 'Article' : 'Articles'; ?></span>
            </p>
        </div>
    </div>

    <div class="bg-slate-100 pb-16 pt-12">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row gap-10">
                
                <!-- Left Column (70%) -->
                <main class="w-full lg:w-[70%]">
                    
                    <div class="flex justify-between items-end mb-8 border-b border-slate-200 pb-4">
                        <h2 class="text-2xl font-serif font-bold text-navy-900 section-title">Latest Articles</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <?php
                        if ($num_articles > 0) {
                            while ($post = mysqli_fetch_assoc($posts_result)) {
                                $thumbnail = bk_thumb_url($post['featured_image']);
                                $post_url = bk_post_url($post);
                        ?>
                        <article class="bg-white rounded-sm shadow-sm overflow-hidden group flex flex-col" itemscope itemtype="https://schema.org/BlogPosting">
                            <a href="<?php echo $post_url; ?>" class="block h-56 overflow-hidden relative" itemprop="url">
                                <img src="<?php echo $thumbnail; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" itemprop="image" onerror="this.src='/images/blog-default.jpg'">
                            </a>
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center gap-4 mb-3">
                                    <span class="bg-orange-50 text-orange-600 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-sm"><?php echo strtoupper(htmlspecialchars($category_name)); ?></span>
                                    <span class="text-[11px] text-slate-500 font-medium"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                </div>
                                <h3 class="text-xl font-serif font-bold text-navy-900 mb-3 leading-snug group-hover:text-crimson-600 transition-colors" itemprop="headline">
                                    <a href="<?php echo $post_url; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                                </h3>
                                <p class="text-slate-500 mb-5 text-sm leading-relaxed flex-1 line-clamp-3" itemprop="description">
                                    <?php echo !empty($post['excerpt']) ? htmlspecialchars($post['excerpt']) : substr(strip_tags($post['content']), 0, 150) . '...'; ?>
                                </p>
                                <a href="<?php echo $post_url; ?>" class="text-crimson-600 font-semibold text-sm hover:text-crimson-800 transition-colors flex items-center gap-1 group-hover:gap-2">
                                    Read Article <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </article>
                        <?php } } else { echo '<div class="md:col-span-2 text-center py-20 text-slate-400">No articles in this category yet.</div>'; } ?>

                    </div>

                </main>

                <!-- Right Sidebar Area (30%) -->
                <aside class="lg:w-[30%]">
                    <div class="sticky top-24 space-y-10">
                        
                        <!-- Top Read Today (Featured Sticky) -->
                        <?php
                        $top_read = $database->get_popular_posts(1);
                        if($tr = mysqli_fetch_assoc($top_read)) {
                            $tr_thumb = bk_thumb_url($tr['featured_image']);
                            $tr_url = bk_post_url($tr);
                        ?>
                        <div class="bg-navy-950 rounded-xl overflow-hidden shadow-xl border border-white/5 group">
                            <div class="p-4 bg-crimson-600 flex justify-between items-center">
                                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Top Read Today</h3>
                                <i class="fa-solid fa-fire-flame-curved text-white/50 text-xs"></i>
                            </div>
                            <a href="<?php echo $tr_url; ?>" class="block relative h-48">
                                <img src="<?php echo $tr_thumb; ?>" alt="<?php echo htmlspecialchars($tr['title']); ?>" class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity" loading="lazy" onerror="this.src='/images/blog-default.jpg'">
                                <div class="absolute inset-0 bg-gradient-to-t from-navy-950 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h4 class="text-lg font-serif font-bold text-white leading-tight group-hover:text-crimson-400 transition-colors"><?php echo htmlspecialchars($tr['title']); ?></h4>
                                </div>
                            </a>
                            <div class="p-5 bg-navy-900/50 backdrop-blur-md">
                                <p class="text-slate-400 text-xs mb-4 line-clamp-2 italic">"<?php echo htmlspecialchars($tr['excerpt']); ?>"</p>
                                <a href="<?php echo $tr_url; ?>" class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center gap-2 hover:gap-3 transition-all">
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
                                <form action="/" method="GET" class="relative">
                                    <input type="text" name="q" placeholder="Keywords..." class="w-full bg-white border border-slate-200 text-slate-800 rounded-lg py-3 pl-4 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-crimson-500/10 focus:border-crimson-500 transition-all shadow-sm">
                                    <button type="submit" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-crimson-600 transition-colors" aria-label="Search">
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
                                        $t_thumb = bk_thumb_url($t['featured_image']);
                                        $t_url = bk_post_url($t);
                                    ?>
                                    <a href="<?php echo $t_url; ?>" class="flex gap-4 group">
                                        <div class="w-20 h-16 shrink-0 rounded-lg overflow-hidden border border-slate-200">
                                            <img src="<?php echo $t_thumb; ?>" alt="<?php echo htmlspecialchars($t['title']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.src='/images/blog-default.jpg'">
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
                                        $c_url = bk_category_url($cat['id'], $cat['category']);
                                    ?>
                                    <a href="<?php echo $c_url; ?>" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 text-[10px] font-bold rounded hover:bg-navy-900 hover:text-white hover:border-navy-900 transition-all uppercase tracking-wider">
                                        <?php echo htmlspecialchars($cat['category']); ?> 
                                        <span class="ml-1 text-slate-400 group-hover:text-white/50">(<?php echo $cat['post_count']; ?>)</span>
                                    </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
            
        </div>
    </div>

    <!-- Breezekings Global Footer -->
    <?php include 'include/frontend_footer.php'; ?>

</body>
</html>
