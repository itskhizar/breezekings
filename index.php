<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ── SEO: gather data before output ──────────────────────────────────────────
$site_name    = 'Breezekings';
$site_tagline = 'Technology, Business, Health, Entertainment, Lifestyle & News';
$site_url     = bk_base_url();
$current_url  = $site_url . '/';
$meta_description = 'Breezekings covers Technology, Business, Health, Entertainment, Lifestyle, and News — clear, practical articles for readers who want the useful version of the story, not just the headline.';
$og_image  = $site_url . '/images/breezekings-icon-red.svg';
$search_q  = isset($_GET['q']) ? trim(htmlspecialchars($_GET['q'])) : '';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ── Primary SEO ─────────────────────────────────────────── -->
    <title><?php echo $search_q ? 'Search: ' . $search_q . ' | ' . $site_name : $site_name . ' | ' . $site_tagline; ?></title>
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="keywords" content="breezekings, blog, technology, culture, business, lifestyle, articles">
    <meta name="author" content="Breezekings Editorial">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="<?php echo $current_url; ?>">
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/images/breezekings-icon-red.svg">
    <link rel="shortcut icon" href="/images/breezekings-icon-red.svg">
    <meta name="theme-color" content="#0B1F3A">

    <!-- ── Open Graph ──────────────────────────────────────────── -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo $site_name; ?>">
    <meta property="og:title" content="<?php echo $site_name . ' | ' . $site_tagline; ?>">
    <meta property="og:description" content="<?php echo $meta_description; ?>">
    <meta property="og:url" content="<?php echo $current_url; ?>">
    <meta property="og:image" content="<?php echo $og_image; ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- ── Twitter Card ────────────────────────────────────────── -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $site_name . ' | ' . $site_tagline; ?>">
    <meta name="twitter:description" content="<?php echo $meta_description; ?>">
    <meta name="twitter:image" content="<?php echo $og_image; ?>">

    <!-- ── Schema.org WebSite & Organization ──────────────────── -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "WebSite",
                "@id": "<?php echo $site_url; ?>/#website",
                "name": "Breezekings",
                "url": "<?php echo $site_url; ?>/",
                "description": "<?php echo addslashes($meta_description); ?>",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": {
                        "@type": "EntryPoint",
                        "urlTemplate": "<?php echo $site_url; ?>/?q={search_term_string}"
                    },
                    "query-input": "required name=search_term_string"
                }
            },
            {
                "@type": "Organization",
                "@id": "<?php echo $site_url; ?>/#organization",
                "name": "Breezekings",
                "url": "<?php echo $site_url; ?>/",
                "logo": {
                    "@type": "ImageObject",
                    "url": "<?php echo $site_url; ?>/images/breezekings-icon-red.svg"
                },
                "contactPoint": {
                    "@type": "ContactPoint",
                    "email": "azamwaseem44@gmail.com",
                    "contactType": "customer support"
                }
            }
        ]
    }
    </script>

    <!-- ── Tailwind CSS ────────────────────────────────────────── -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                        mono: ['DM Mono', 'monospace'],
                    },
                    colors: {
                        navy: {
                            50: '#eef0f7',
                            100: '#d5d9ec',
                            800: '#1e2336',
                            900: '#151928',
                            950: '#0d101a',
                        },
                        crimson: {
                            50: '#fff0f1',
                            400: '#f2616d',
                            500: '#e12b38',
                            600: '#c5202b',
                            700: '#a31a23',
                        },
                    },
                    maxWidth: { '8xl': '1400px' },
                    boxShadow: {
                        'card': '0 1px 3px 0 rgba(0,0,0,.06), 0 1px 2px -1px rgba(0,0,0,.06)',
                        'card-hover': '0 10px 25px -5px rgba(0,0,0,.08), 0 4px 10px -6px rgba(0,0,0,.08)',
                    }
                }
            }
        }
    </script>

    <!-- ── Google Fonts ────────────────────────────────────────── -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap"
        rel="stylesheet">

    <!-- ── Font Awesome ────────────────────────────────────────── -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ── Base ──────────────────────────────────────────────── */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .font-mono {
            font-family: 'DM Mono', monospace;
        }

        /* ── Section title accent ──────────────────────────────── */
        .section-title {
            position: relative;
            padding-left: 1rem;
        }

        .section-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 10%;
            height: 80%;
            width: 3px;
            background-color: #c5202b;
            border-radius: 2px;
        }

        /* ── Card image zoom ───────────────────────────────────── */
        .card-img-wrap {
            overflow: hidden;
        }

        .card-img-wrap img {
            transition: transform 0.55s cubic-bezier(.25, .46, .45, .94);
        }

        .article-card:hover .card-img-wrap img {
            transform: scale(1.06);
        }

        /* ── Sidebar widget header ─────────────────────────────── */
        .widget-header {
            font-size: .625rem;
            /* 10px */
            font-weight: 700;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: #1e2336;
            padding-bottom: .625rem;
            margin-bottom: 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        /* ── Marquee ticker ────────────────────────────────────── */
        .marquee-track {
            display: inline-block;
            white-space: nowrap;
            padding-left: 100%;
            animation: ticker 35s linear infinite;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        @keyframes ticker {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-100%);
            }
        }

        /* ── Hero gradient ─────────────────────────────────────── */
        .hero-overlay {
            background: linear-gradient(to top,
                    rgba(13, 16, 26, .97) 0%,
                    rgba(13, 16, 26, .70) 40%,
                    rgba(13, 16, 26, .15) 75%,
                    transparent 100%);
        }


        /* ── Popular / trending numbered item ──────────────────── */
        .pop-num {
            font-size: 1.625rem;
            font-weight: 700;
            line-height: 1;
            color: #e2e8f0;
            min-width: 2rem;
            font-family: 'Playfair Display', serif;
        }

        /* ── Mobile sidebar order fix ──────────────────────────── */
        @media (max-width: 1023px) {
            .sidebar-mobile-order {
                order: -1;
            }
        }

        /* ── Hover: none (touch) ───────────────────────────────── */
        @media (hover: none) {
            .article-card:active .card-img-wrap img {
                transform: scale(1.06);
            }
        }
    </style>
</head>

<body class="text-slate-800 antialiased selection:bg-crimson-500 selection:text-white">

    <!-- ════════════════════════════════════════════════════════════
         HEADER (included)
    ════════════════════════════════════════════════════════════ -->
    <?php include 'include/frontend_header.php'; ?>

    <!-- ════════════════════════════════════════════════════════════
         TRENDING TICKER
    ════════════════════════════════════════════════════════════ -->
    <div class="bg-crimson-600 text-white text-[11px] font-semibold py-2 overflow-hidden relative z-40 shadow-lg"
        aria-label="Trending articles">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-4 overflow-hidden">
            <span
                class="shrink-0 tracking-[.15em] font-bold font-mono text-[10px] border-r border-crimson-400 pr-4 mr-2">
                TRENDING NOW:
            </span>
            <div class="overflow-hidden flex-1" role="marquee" aria-live="off">
                <span class="marquee-track font-normal text-crimson-50 opacity-90">
                    <?php
                    $ticker_posts = $database->get_popular_posts(5);
                    $tick_items = [];
                    while ($tp = mysqli_fetch_assoc($ticker_posts)) {
                        $tp_url = bk_post_url($tp);
                        $tick_items[] = '<a href="' . $tp_url . '"
                            class="hover:underline mr-10 hover:text-white transition-colors">'
                            . htmlspecialchars($tp['title'])
                            . ' &nbsp;&#8226;</a>';
                    }
                    echo implode('', $tick_items);
                    ?>
                </span>
            </div>
        </div>
    </div>


    <!-- ════════════════════════════════════════════════════════════
         MAIN PAGE WRAPPER
    ════════════════════════════════════════════════════════════ -->
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-20">

        <!--
        ╔══════════════════════════════════════════════════════════╗
        ║  TWO-COLUMN FLEX GRID                                    ║
        ║  LEFT  70%  → main article content                       ║
        ║  RIGHT 30%  → sidebar widgets                            ║
        ╚══════════════════════════════════════════════════════════╝
        -->
        <div class="flex flex-col lg:flex-row gap-[5%] items-start">

            <!-- ════════════════════════════════════════════════════
                 LEFT COLUMN — 70%
            ════════════════════════════════════════════════════ -->
            <main class="w-full lg:w-[70%] min-w-0" id="main-content" aria-label="Main content">

                <!-- ── HERO FEATURED POST ──────────────────────── -->
                <section class="mb-12" aria-label="Featured post">
                    <?php
                    // Try featured flag first, fall back to latest
                    $featured_sql = "SELECT p.*, c.category, 
                                     CASE 
                                         WHEN u.display_name IS NOT NULL AND TRIM(u.display_name) != '' THEN TRIM(u.display_name)
                                         WHEN u.username IS NOT NULL AND TRIM(u.username) != '' THEN TRIM(u.username)
                                         WHEN p.author IS NOT NULL AND TRIM(p.author) != '' THEN TRIM(p.author)
                                         ELSE 'Admin'
                                     END as author_name,
                                     u.profile_image as author_avatar
                                     FROM posts p
                                     LEFT JOIN categories c ON p.category_id = c.id
                                     LEFT JOIN users u ON (p.author = u.registration_no OR p.author = u.username)
                                     WHERE p.status = 'Published'
                                       AND p.is_deleted = 0
                                       AND p.is_featured = 1
                                     ORDER BY COALESCE(p.published_at, p.created_at) DESC, p.id DESC
                                     LIMIT 1";
                    $featured_posts = $database->query($featured_sql);
                    if (mysqli_num_rows($featured_posts) === 0) {
                        $featured_posts = $database->query(
                            "SELECT p.*, c.category, 
                             CASE 
                                         WHEN u.display_name IS NOT NULL AND TRIM(u.display_name) != '' THEN TRIM(u.display_name)
                                         WHEN u.username IS NOT NULL AND TRIM(u.username) != '' THEN TRIM(u.username)
                                         WHEN p.author IS NOT NULL AND TRIM(p.author) != '' THEN TRIM(p.author)
                                         ELSE 'BreezeKings Editorial'
                                     END as author_name,
                             u.profile_image as author_avatar
                             FROM posts p
                             LEFT JOIN categories c ON p.category_id = c.id
                             LEFT JOIN users u ON (p.author = u.registration_no OR p.author = u.username)
                             WHERE p.status = 'Published' AND p.is_deleted = 0
                             ORDER BY COALESCE(p.published_at, p.created_at) DESC, p.id DESC LIMIT 1"
                        );
                    }

                    if ($hero = mysqli_fetch_assoc($featured_posts)):
                        $hero_thumb = bk_thumb_url($hero['featured_image']);
                        $hero_raw_date = !empty($hero['published_at']) ? $hero['published_at'] : $hero['created_at'];
                        $hero_date_fmt = date('M d, Y', strtotime($hero_raw_date));
                        $hero_iso = date('c', strtotime($hero_raw_date));
                        $hero_read = $database->getReadingTime($hero['content']);
                        $hero_author = htmlspecialchars((!empty($hero['author_name']) && strcasecmp($hero['author_name'], 'admin') !== 0) ? $hero['author_name'] : 'BreezeKings Editorial');
                        $hero_avatar = bk_avatar_url($hero['author_avatar'] ?? null, $hero_author);
                        $hero_cat = htmlspecialchars($hero['category'] ?? '');
                        $hero_title = htmlspecialchars($hero['title']);
                        $raw_hero_excerpt = !empty($hero['excerpt']) ? $hero['excerpt'] : substr(strip_tags($hero['content']), 0, 180) . '…';
                        $hero_excerpt = htmlspecialchars(bk_clean_text($raw_hero_excerpt));
                        $hero_url = bk_post_url($hero);
                        ?>
                        <!-- Schema: Article (hero) -->
                        <script type="application/ld+json">
                            {
                                "@context": "https://schema.org",
                                "@type": "NewsArticle",
                                "headline":       "<?php echo addslashes($hero_title); ?>",
                                "description":    "<?php echo addslashes($hero_excerpt); ?>",
                                "image":          "<?php echo str_starts_with($hero_thumb, 'http') ? $hero_thumb : $site_url . $hero_thumb; ?>",
                                "datePublished":  "<?php echo $hero_iso; ?>",
                                "author":         { "@type": "Person", "name": "<?php echo addslashes($hero_author); ?>" },
                                "publisher":      { "@type": "Organization", "name": "<?php echo $site_name; ?>" },
                                "url":            "<?php echo $site_url . $hero_url; ?>"
                            }
                            </script>

                        <article itemscope itemtype="https://schema.org/NewsArticle">
                            <a href="<?php echo $hero_url; ?>" itemprop="url"
                                class="relative flex h-[420px] lg:h-[530px] rounded-xl overflow-hidden group card-img-wrap block shadow-2xl"
                                aria-label="Read featured article: <?php echo $hero_title; ?>">

                                <!-- Image -->
                                <img src="<?php echo $hero_thumb; ?>" alt="<?php echo $hero_title; ?>" itemprop="image"
                                    class="absolute inset-0 w-full h-full object-cover" width="900" height="530"
                                    loading="eager" fetchpriority="high" onerror="this.src='/images/blog-default.jpg'">

                                <!-- Gradient overlay -->
                                <div class="absolute inset-0 hero-overlay"></div>

                                <!-- Text content -->
                                <div class="absolute bottom-0 left-0 p-8 lg:p-10 w-full md:w-5/6">
                                    <!-- Badges -->
                                    <div class="flex flex-wrap items-center gap-2 mb-4">
                                        <?php if ($hero_cat): ?>
                                            <span
                                                class="bg-crimson-600 text-white text-[10px] font-bold uppercase tracking-[.14em] px-3 py-1 rounded-sm shadow"
                                                itemprop="articleSection">
                                                <?php echo $hero_cat; ?>
                                            </span>
                                        <?php endif; ?>
                                        <span
                                            class="bg-amber-400 text-navy-950 text-[10px] font-bold uppercase tracking-[.14em] px-3 py-1 rounded-sm flex items-center gap-1 shadow">
                                            <i class="fa-solid fa-star text-[8px]" aria-hidden="true"></i> Featured
                                        </span>
                                    </div>

                                    <!-- Title -->
                                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-white leading-tight mb-4
                                           group-hover:text-slate-100 transition-colors duration-300"
                                        itemprop="headline">
                                        <?php echo $hero_title; ?>
                                    </h1>

                                    <!-- Excerpt -->
                                    <p class="text-slate-300 text-sm md:text-base mb-5 line-clamp-2 max-w-2xl opacity-90"
                                        itemprop="description">
                                        <?php echo $hero_excerpt; ?>
                                    </p>

                                    <!-- Meta -->
                                    <div
                                        class="flex flex-wrap items-center gap-3 text-slate-400 text-[11px] font-bold tracking-widest uppercase">
                                        <div class="flex items-center gap-2" itemprop="author" itemscope
                                            itemtype="https://schema.org/Person">
                                            <img src="<?php echo $hero_avatar; ?>" class="w-6 h-6 rounded-full border border-white/20 object-cover"
                                                alt="<?php echo $hero_author; ?>" width="24" height="24" onerror="this.src='/images/avatar.png'">
                                            <span itemprop="name">BY <?php echo htmlspecialchars($hero_author); ?></span>
                                        </div>
                                        <span class="opacity-30" aria-hidden="true">|</span>
                                        <time datetime="<?php echo $hero_iso; ?>"
                                            itemprop="datePublished"><?php echo $hero_date_fmt; ?></time>
                                        <span class="opacity-30" aria-hidden="true">|</span>
                                        <span><i class="fa-regular fa-clock mr-1"
                                                aria-hidden="true"></i><?php echo $hero_read; ?> MIN READ</span>
                                    </div>
                                </div>

                                <!-- Read more CTA -->
                                <div
                                    class="absolute top-5 right-5 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-1 group-hover:translate-y-0">
                                    <span
                                        class="bg-white/10 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full border border-white/20 flex items-center gap-2">
                                        Read Article <i class="fa-solid fa-arrow-right text-crimson-400"
                                            aria-hidden="true"></i>
                                    </span>
                                </div>
                            </a>
                        </article>
                    <?php endif; ?>
                </section>
                <!-- ── /HERO ────────────────────────────────────── -->

                <!-- ── LATEST ARTICLES ─────────────────────────── -->
                <!-- Latest Articles section moved to sidebar -->
                <!-- Section heading -->
                <?php if ($search_q): ?>
                    <h2 class="text-xl lg:text-2xl font-serif font-bold text-navy-900 mb-8 section-title">
                        Search Results for: &ldquo;<?php echo $search_q; ?>&rdquo;
                    </h2>
                <?php else: ?>
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl lg:text-2xl font-serif font-bold text-navy-900 section-title">
                            Latest Articles
                        </h2>
                        <a href="#main-content"
                            class="text-[11px] font-bold text-crimson-600 hover:text-crimson-700 uppercase tracking-widest flex items-center gap-1 transition-colors">
                            View All <i class="fa-solid fa-arrow-right text-[10px]" aria-hidden="true"></i>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Articles grid -->
                <?php
                if ($search_q) {
                    $latest_posts = $database->search_posts($search_q);
                } else {
                    $latest_posts = $database->get_all_posts('Published');
                }
                $has_posts = $latest_posts && mysqli_num_rows($latest_posts) > 0;
                ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-7" role="list">
                    <?php if ($has_posts):
                        while ($post = mysqli_fetch_assoc($latest_posts)):
                            $thumb = bk_thumb_url($post['featured_image']);
                            $post_raw_date = !empty($post['published_at']) ? $post['published_at'] : $post['created_at'];
                            $post_date = date('M d, Y', strtotime($post_raw_date));
                            $post_iso = date('c', strtotime($post_raw_date));
                            $read_time = $database->getReadingTime($post['content']);
                            $cat_name = htmlspecialchars($post['category'] ?? 'General');
                            $post_title = htmlspecialchars($post['title']);
                            $raw_post_excerpt = !empty($post['excerpt'])
                                ? $post['excerpt']
                                : substr(strip_tags($post['content']), 0, 150) . '…';
                            $excerpt = htmlspecialchars(bk_clean_text($raw_post_excerpt));
                            $author_name = htmlspecialchars((!empty($post['author_name']) && strcasecmp($post['author_name'], 'admin') !== 0) ? $post['author_name'] : 'BreezeKings Editorial');
                            $author_avatar = bk_avatar_url($post['author_avatar'] ?? null, $author_name);
                            $post_url = bk_post_url($post);
                            $cat_url = bk_category_url($post['category_id'], $post['category'] ?? 'General');
                            ?>
                            <article
                                class="article-card bg-white rounded-xl shadow-card border border-slate-100 overflow-hidden flex flex-col group hover:shadow-card-hover transition-shadow duration-300"
                                role="listitem" itemscope itemtype="https://schema.org/BlogPosting">

                                <!-- Thumbnail -->
                                <a href="<?php echo $post_url; ?>"
                                    class="block h-48 card-img-wrap flex-shrink-0" itemprop="url" tabindex="-1"
                                    aria-hidden="true">
                                    <img src="<?php echo $thumb; ?>" alt="<?php echo $post_title; ?>" itemprop="image"
                                        class="w-full h-full object-cover" width="500" height="300" loading="lazy" onerror="this.src='/images/blog-default.jpg'">
                                </a>

                                <!-- Card body -->
                                <div class="p-6 flex-1 flex flex-col">

                                    <!-- Category chip -->
                                    <a href="<?php echo $cat_url; ?>"
                                        class="self-start bg-amber-50 text-amber-700 text-[9px] font-bold uppercase tracking-[.12em] px-2.5 py-1 rounded-sm mb-3 hover:bg-amber-100 transition-colors"
                                        itemprop="articleSection">
                                        <?php echo $cat_name; ?>
                                    </a>

                                    <!-- Title -->
                                    <h3 class="text-lg lg:text-xl font-serif font-bold text-navy-900 mb-3 leading-snug group-hover:text-crimson-600 transition-colors"
                                        itemprop="headline">
                                        <a href="<?php echo $post_url; ?>" class="hover:no-underline">
                                            <?php echo $post_title; ?>
                                        </a>
                                    </h3>

                                    <!-- Excerpt -->
                                    <p class="text-slate-500 text-sm leading-relaxed flex-1 line-clamp-3 mb-5"
                                        itemprop="description">
                                        <?php echo $excerpt; ?>
                                    </p>

                                    <!-- Meta row -->
                                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                        <div class="flex items-center gap-2">
                                            <img src="<?php echo $author_avatar; ?>" 
                                                 alt="<?php echo $author_name; ?>" 
                                                 class="w-6 h-6 rounded-full border border-slate-200 object-cover flex-shrink-0" 
                                                 width="24" height="24" onerror="this.src='/images/avatar.png'">
                                            <span class="text-[11px] text-slate-600 font-medium normal-case tracking-normal"
                                                itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                <span itemprop="name"><?php echo $author_name; ?></span>
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3 text-[10px] text-slate-400 font-medium">
                                            <time datetime="<?php echo $post_iso; ?>" itemprop="datePublished">
                                                <?php echo $post_date; ?>
                                            </time>
                                            <span class="w-1 h-1 bg-slate-300 rounded-full" aria-hidden="true"></span>
                                            <span><i class="fa-regular fa-clock mr-0.5"
                                                    aria-hidden="true"></i><?php echo $read_time; ?> min</span>
                                            <span class="w-1 h-1 bg-slate-300 rounded-full" aria-hidden="true"></span>
                                            <span><i class="fa-regular fa-eye mr-0.5"
                                                    aria-hidden="true"></i><?php echo number_format((int) $post['views']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            <?php
                        endwhile;
                    else: ?>
                        <div class="sm:col-span-2 flex flex-col items-center justify-center py-20 text-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fa-solid fa-magnifying-glass text-slate-400 text-xl" aria-hidden="true"></i>
                            </div>
                            <p class="text-slate-400 text-base font-medium mb-1">
                                <?php echo $search_q ? 'No results for &ldquo;' . $search_q . '&rdquo;' : 'No articles yet'; ?>
                            </p>
                            <p class="text-slate-300 text-sm">
                                <?php echo $search_q ? 'Try a different keyword.' : 'Check back soon.'; ?>
                            </p>
                            <?php if ($search_q): ?>
                                <a href="index.php" class="mt-4 text-crimson-600 text-sm font-medium hover:underline">← Back to
                                    home</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                </section>
                <!-- ── /LATEST ARTICLES ────────────────────────── -->

            </main>
            <!-- ════════ / LEFT COLUMN ════════════════════════════ -->


            <!-- ════════════════════════════════════════════════════
                 RIGHT COLUMN — 30% SIDEBAR
                 NOTE: This <aside> is a SIBLING of <main>, not nested inside it.
            ════════════════════════════════════════════════════ -->
            <aside class="w-full lg:w-[30%] flex-shrink-0" aria-label="Sidebar">
                <div class="sticky top-6 space-y-8">


                    <!-- ── WIDGET 2: TOP READ TODAY ──────────────── -->
                    <?php
                    $top_read = $database->get_popular_posts(1);
                    if ($tr = mysqli_fetch_assoc($top_read)):
                        $tr_thumb = bk_thumb_url($tr['featured_image']);
                        $tr_title = htmlspecialchars($tr['title']);
                        $tr_excerpt = htmlspecialchars(bk_clean_text($tr['excerpt'] ?? substr(strip_tags($tr['content']), 0, 120)));
                        $tr_url = bk_post_url($tr);
                        ?>
                        <div class="bg-navy-950 rounded-xl overflow-hidden shadow-xl border border-white/5 group">
                            <!-- Widget header bar -->
                            <div class="px-5 py-3 bg-crimson-600 flex items-center justify-between">
                                <h3 class="text-[10px] font-black text-white uppercase tracking-[.2em]">
                                    <i class="fa-solid fa-fire-flame-curved mr-1.5" aria-hidden="true"></i>
                                    Top Read Today
                                </h3>
                                <span class="text-[9px] text-crimson-200 font-bold uppercase tracking-wide">
                                    <?php echo number_format((int) $tr['views']); ?> views
                                </span>
                            </div>
                            <!-- Image -->
                            <a href="<?php echo $tr_url; ?>" class="block relative h-44 card-img-wrap"
                                aria-label="<?php echo $tr_title; ?>">
                                <img src="<?php echo $tr_thumb; ?>" alt="<?php echo $tr_title; ?>"
                                    class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity duration-500"
                                    width="380" height="176" loading="lazy" onerror="this.src='/images/blog-default.jpg'">
                                <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-900/60 to-transparent">
                                </div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h4
                                        class="text-base font-serif font-bold text-white leading-snug group-hover:text-crimson-400 transition-colors line-clamp-2">
                                        <?php echo $tr_title; ?>
                                    </h4>
                                </div>
                            </a>
                            <!-- Body -->
                            <div class="p-5 bg-navy-900/50">
                                <p class="text-slate-400 text-xs mb-4 line-clamp-2 italic leading-relaxed">
                                    &ldquo;<?php echo $tr_excerpt; ?>&rdquo;
                                </p>
                                <a href="<?php echo $tr_url; ?>"
                                    class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center gap-2 hover:gap-3 transition-all duration-200 group/link">
                                    Read Full Article
                                    <i class="fa-solid fa-arrow-right text-crimson-500 group-hover/link:text-crimson-400"
                                        aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- ── WIDGET 3: SEARCH ARCHIVE ──────────────── -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-card">
                        <h3 class="widget-header">
                            <i class="fa-solid fa-magnifying-glass text-crimson-500" aria-hidden="true"></i>
                            Search Archive
                        </h3>
                        <form action="/" method="GET" role="search" aria-label="Search articles">
                            <div class="relative">
                                <label for="sidebar-search" class="sr-only">Search keywords</label>
                                <input type="search" id="sidebar-search" name="q" value="<?php echo $search_q; ?>"
                                    placeholder="Keywords…" autocomplete="off" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg py-3 pl-4 pr-12 text-sm
                                              focus:outline-none focus:ring-2 focus:ring-crimson-500/20 focus:border-crimson-500
                                              transition-all placeholder-slate-400">
                                <button type="submit"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center
                                               bg-crimson-600 hover:bg-crimson-700 text-white rounded-md transition-colors"
                                    aria-label="Search">
                                    <i class="fa-solid fa-magnifying-glass text-xs" aria-hidden="true"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- ── WIDGET 4: TRENDING NOW ─────────────────── -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-card">
                        <h3 class="widget-header">
                            <i class="fa-solid fa-bolt text-amber-500" aria-hidden="true"></i>
                            Trending Now
                        </h3>
                        <div class="space-y-5" role="list">
                            <?php
                            $trending = $database->get_popular_posts(5);
                            // Skip index 0 (shown in "Top Read Today")
                            if ($trending && mysqli_num_rows($trending) > 1) {
                                mysqli_data_seek($trending, 1);
                            }
                            $t_count = 1;
                            while ($t = mysqli_fetch_assoc($trending)):
                                $t_thumb = bk_thumb_url($t['featured_image']);
                                $t_title = htmlspecialchars($t['title']);
                                $t_url = bk_post_url($t);
                                ?>
                                <article class="flex items-start gap-3 group" role="listitem">
                                    <!-- Decorative number -->
                                    <span class="pop-num shrink-0 mt-0.5 leading-none select-none">
                                        0<?php echo $t_count; ?>
                                    </span>
                                    <!-- Thumbnail -->
                                    <a href="<?php echo $t_url; ?>"
                                        class="w-[68px] h-[52px] shrink-0 rounded-lg overflow-hidden border border-slate-100 card-img-wrap block"
                                        tabindex="-1" aria-hidden="true">
                                        <img src="<?php echo $t_thumb; ?>" alt="<?php echo $t_title; ?>"
                                            class="w-full h-full object-cover" width="68" height="52" loading="lazy" onerror="this.src='/images/blog-default.jpg'">
                                    </a>
                                    <!-- Text -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-[12px] font-serif font-bold text-navy-900 leading-snug
                                               group-hover:text-crimson-600 transition-colors line-clamp-2">
                                            <a
                                                href="<?php echo $t_url; ?>"><?php echo $t_title; ?></a>
                                        </h4>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <time class="text-[9px] text-slate-400 font-bold uppercase tracking-wide">
                                                <?php 
                                                $t_raw_date = !empty($t['published_at']) ? $t['published_at'] : $t['created_at'];
                                                echo date('M d, Y', strtotime($t_raw_date)); 
                                                ?>
                                            </time>
                                            <span class="w-1 h-1 bg-slate-300 rounded-full" aria-hidden="true"></span>
                                            <span class="text-[9px] text-crimson-500 font-bold">
                                                <?php echo number_format((int) $t['views']); ?> views
                                            </span>
                                        </div>
                                    </div>
                                </article>
                                <?php $t_count++; endwhile; ?>
                        </div>
                    </div>

                    <!-- ── WIDGET 5: CATEGORIES ───────────────────── -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-card">
                        <h3 class="widget-header">
                            <i class="fa-solid fa-tags text-navy-800" aria-hidden="true"></i>
                            Categories
                        </h3>
                        <div class="space-y-1" role="list">
                            <?php
                            $cats = $database->get_all_categories(true);
                            while ($cat = mysqli_fetch_assoc($cats)):
                                $cat_name = htmlspecialchars($cat['category']);
                                $cat_count = (int) $cat['post_count'];
                                $cat_url = bk_category_url($cat['id'], $cat['category']);
                                ?>
                                <a href="<?php echo $cat_url; ?>" class="flex items-center justify-between px-3 py-2.5 rounded-lg
                                      text-slate-600 hover:bg-navy-900 hover:text-white
                                      transition-all duration-200 group" role="listitem">
                                    <span class="text-[12px] font-medium">
                                        <?php echo $cat_name; ?>
                                    </span>
                                    <span class="text-[10px] font-bold bg-slate-100 text-slate-500
                                             group-hover:bg-crimson-600 group-hover:text-white
                                             px-2 py-0.5 rounded-full transition-colors">
                                        <?php echo $cat_count; ?>
                                    </span>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </div>


                </div><!-- /sticky -->
            </aside>
            <!-- ════════ / RIGHT COLUMN ═══════════════════════════ -->

        </div>
        <!-- ════════ / TWO-COLUMN GRID ═══════════════════════════ -->


    </div>
    <!-- ════════ / MAIN PAGE WRAPPER ══════════════════════════════ -->

    <!-- ════════════════════════════════════════════════════════════
         FOOTER (included)
    ════════════════════════════════════════════════════════════ -->
    <?php include 'include/frontend_footer.php'; ?>

</body>

</html>