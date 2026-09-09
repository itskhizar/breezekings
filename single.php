<?php
include 'include/classes/session.php';

// Resolve post ID from numeric id or encoded_id or query string
$post_id = 0;
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
    $post_id = (int)$_GET['id'];
} elseif (!empty($_GET['encoded_id'])) {
    $post_id = bk_decode_id($_GET['encoded_id']);
} elseif (!empty($_GET['id'])) {
    $post_id = bk_decode_id($_GET['id']);
}

$post = null;
if ($post_id > 0) {
    $post_query = $database->query("SELECT p.*, c.category, COALESCE(NULLIF(u.display_name, ''), u.username, p.author) as author_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN users u ON (p.author = u.registration_no OR p.author = u.username) WHERE p.id = $post_id");
    if ($post_query && mysqli_num_rows($post_query) > 0) {
        $post = mysqli_fetch_assoc($post_query);
    }
}

// Fallback: Resolve by slug (WordPress style permalink, e.g. /post/my-article-title)
if (!$post) {
    $slug_candidate = !empty($_GET['slug']) ? trim($_GET['slug']) : (!empty($_GET['id']) ? trim($_GET['id']) : '');
    if (!empty($slug_candidate)) {
        $slug_clean = mysqli_real_escape_string($database->connection, $slug_candidate);
        $post_query = $database->query("SELECT p.*, c.category, COALESCE(NULLIF(u.display_name, ''), u.username, p.author) as author_name FROM posts p LEFT JOIN categories c ON p.category_id = c.id LEFT JOIN users u ON (p.author = u.registration_no OR p.author = u.username) WHERE p.slug = '$slug_clean' LIMIT 1");
        if ($post_query && mysqli_num_rows($post_query) > 0) {
            $post = mysqli_fetch_assoc($post_query);
            $post_id = (int)$post['id'];
        }
    }
}

if (!$post || $post['status'] !== 'Published') {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: /");
    exit();
}

// 301 Redirect old /single.php?id=... to clean SEO permalink
$clean_post_rel = bk_post_url($post['id'], $post['title'], $post['slug']);
$req_uri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($req_uri, 'single.php') !== false) {
    $extra_query = '';
    if (!empty($_GET['msg'])) {
        $extra_query = '?msg=' . urlencode($_GET['msg']);
    }
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: " . bk_base_url() . $clean_post_rel . $extra_query . (isset($_GET['msg']) ? '#comments' : ''));
    exit();
}

$search_q = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';

// Increment views
$database->increment_views($post_id);

$author_info = $database->getUserInfo($post['author']);
// Prioritize post author's actual name, then user account display name, then fallback to editorial team
$raw_author = $post['author_name'] ?? $author_info['display_name'] ?? $author_info['username'] ?? $post['author'] ?? '';
if (!empty($raw_author) && strcasecmp($raw_author, 'admin') !== 0) {
    $author_name = $raw_author;
} else {
    $author_name = 'BreezeKings Editorial';
}
$author_img  = bk_avatar_url($author_info['profile_image'] ?? $post['author_avatar'] ?? null, $author_name);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    $site_url_s    = bk_base_url();
    $canonical_url = $site_url_s . $clean_post_rel;
    $og_img_s      = !empty($post['featured_image']) ? $site_url_s . '/images/posts/' . $post['featured_image'] : $site_url_s . '/images/breezekings-icon-red.svg';
    // Build 155-char max meta description (Google truncates at ~155 chars)
    $raw_desc      = !empty($post['meta_description']) ? $post['meta_description'] : strip_tags(bk_render_content($post['content']));
    $raw_desc      = trim(preg_replace('/\s+/', ' ', $raw_desc));
    if (mb_strlen($raw_desc) > 155) {
        $raw_desc = mb_substr($raw_desc, 0, 152) . '...';
    }
    $post_desc     = htmlspecialchars($raw_desc);
    $post_title    = htmlspecialchars($post['title']);
    $post_author   = htmlspecialchars($author_name);
    $category_name = htmlspecialchars($post['category'] ?? 'General');
    $category_url  = $site_url_s . bk_category_url($post['category_id'], $post['category']);

    // Genuine dates: prioritize published_at, show updated only if modified >24h later
    $pub_raw       = !empty($post['published_at']) ? $post['published_at'] : $post['created_at'];
    $pub_ts        = strtotime($pub_raw);
    $upd_ts        = !empty($post['updated_at']) ? strtotime($post['updated_at']) : $pub_ts;
    $has_updated   = ($upd_ts && ($upd_ts - $pub_ts) > 86400);

    $pub_date_iso  = date('c', $pub_ts);
    $mod_date_iso  = $has_updated ? date('c', $upd_ts) : $pub_date_iso;
    $pub_date_fmt  = date('F d, Y', $pub_ts);
    $mod_date_fmt  = $has_updated ? date('F d, Y', $upd_ts) : '';

    $word_count    = str_word_count(strip_tags($post['content']));
    $reading_time  = max(1, ceil($word_count / 200));
    ?>

    <!-- Primary SEO Meta Tags -->
    <title><?php echo $post_title; ?> | Breezekings</title>
    <meta name="title" content="<?php echo $post_title; ?> | Breezekings">
    <meta name="description" content="<?php echo $post_desc; ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($post['tags'] ?? 'tech, blog, breezekings'); ?>">
    <meta name="author" content="<?php echo $post_author; ?>">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    <link rel="alternate" hreflang="en" href="<?php echo $canonical_url; ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo $canonical_url; ?>">
    <?php if (!empty($post['featured_image'])): ?>
    <link rel="preload" as="image" href="<?php echo $og_img_s; ?>" fetchpriority="high">
    <?php endif; ?>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="Breezekings">
    <meta property="og:title" content="<?php echo $post_title; ?>">
    <meta property="og:description" content="<?php echo $post_desc; ?>">
    <meta property="og:url" content="<?php echo $canonical_url; ?>">
    <meta property="og:image" content="<?php echo $og_img_s; ?>">
    <meta property="og:image:alt" content="<?php echo $post_title; ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="en_US">
    <meta property="article:published_time" content="<?php echo $pub_date_iso; ?>">
    <meta property="article:modified_time" content="<?php echo $mod_date_iso; ?>">
    <meta property="article:section" content="<?php echo $category_name; ?>">
    <meta property="article:author" content="<?php echo $post_author; ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@breezekings">
    <meta name="twitter:creator" content="@breezekings">
    <meta name="twitter:title" content="<?php echo $post_title; ?>">
    <meta name="twitter:description" content="<?php echo $post_desc; ?>">
    <meta name="twitter:image" content="<?php echo $og_img_s; ?>">

    <!-- Structured Data: BlogPosting, BreadcrumbList, Speakable -->
    <?php
    // Extract FAQ schema if article contains question headings
    $faq_items = [];
    if (preg_match_all('/<h[2-4][^>]*>(.*?\?.*?)<\/h[2-4]>\s*<p[^>]*>(.*?)<\/p>/is', $post['content'], $faq_matches, PREG_SET_ORDER)) {
        foreach (array_slice($faq_matches, 0, 5) as $fm) {
            $fq_q = trim(strip_tags($fm[1]));
            $fq_a = trim(strip_tags($fm[2]));
            if (mb_strlen($fq_q) >= 8 && mb_strlen($fq_a) >= 20) {
                $faq_items[] = [
                    "@type" => "Question",
                    "name" => $fq_q,
                    "acceptedAnswer" => [
                        "@type" => "Answer",
                        "text" => $fq_a
                    ]
                ];
            }
        }
    }
    // Build tags array for schema keywords
    $schema_tags = !empty($post['tags']) ? array_map('trim', explode(',', $post['tags'])) : [];
    $schema_tags_json = !empty($schema_tags) ? json_encode($schema_tags) : '[]';
    // Build image object
    $schema_img = [
        '@type'  => 'ImageObject',
        'url'    => $og_img_s,
        'width'  => 1200,
        'height' => 630
    ];
    ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "BlogPosting",
                "@id": "<?php echo $canonical_url; ?>#article",
                "isPartOf": {
                    "@type": "WebPage",
                    "@id": "<?php echo $canonical_url; ?>",
                    "url": "<?php echo $canonical_url; ?>",
                    "name": "<?php echo addslashes($post_title); ?>"
                },
                "headline": "<?php echo addslashes($post_title); ?>",
                "description": "<?php echo addslashes($post_desc); ?>",
                "image": <?php echo json_encode($schema_img); ?>,
                "datePublished": "<?php echo $pub_date_iso; ?>",
                "dateModified": "<?php echo $mod_date_iso; ?>",
                "dateCreated": "<?php echo $pub_date_iso; ?>",
                "mainEntityOfPage": "<?php echo $canonical_url; ?>",
                "wordCount": <?php echo (int)$word_count; ?>,
                "timeRequired": "PT<?php echo $reading_time; ?>M",
                "articleSection": "<?php echo addslashes($category_name); ?>",
                "inLanguage": "en-US",
                "keywords": <?php echo $schema_tags_json; ?>,
                "author": {
                    "@type": "<?php echo ($author_name === 'BreezeKings Editorial' ? 'Organization' : 'Person'); ?>",
                    "name": "<?php echo addslashes($post_author); ?>",
                    <?php if ($author_name !== 'BreezeKings Editorial'): ?>
                    "jobTitle": "<?php echo addslashes(!empty($author_info['job_title']) ? $author_info['job_title'] : 'Technology Contributor'); ?>",
                    <?php endif; ?>
                    "worksFor": {
                        "@type": "Organization",
                        "name": "Breezekings",
                        "url": "<?php echo $site_url_s; ?>/"
                    },
                    "url": "<?php echo $site_url_s; ?>/about"
                },
                "publisher": {
                    "@type": "Organization",
                    "name": "Breezekings",
                    "url": "<?php echo $site_url_s; ?>",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "<?php echo $site_url_s; ?>/images/breezekings-icon-red.svg",
                        "width": 60,
                        "height": 60
                    },
                    "sameAs": [
                        "https://twitter.com/breezekings",
                        "https://www.facebook.com/breezekings"
                    ]
                },
                "interactionStatistic": {
                    "@type": "InteractionCounter",
                    "interactionType": "https://schema.org/ReadAction",
                    "userInteractionCount": <?php echo (int)($post['views'] ?? 0); ?>
                },
                "speakable": {
                    "@type": "SpeakableSpecification",
                    "cssSelector": [".article-content h2", ".article-content h3", ".article-content p"]
                }
            },
            {
                "@type": "BreadcrumbList",
                "@id": "<?php echo $canonical_url; ?>#breadcrumb",
                "itemListElement": [
                    {
                        "@type": "ListItem",
                        "position": 1,
                        "name": "Home",
                        "item": "<?php echo $site_url_s; ?>/"
                    },
                    {
                        "@type": "ListItem",
                        "position": 2,
                        "name": "<?php echo addslashes($category_name); ?>",
                        "item": "<?php echo $category_url; ?>"
                    },
                    {
                        "@type": "ListItem",
                        "position": 3,
                        "name": "<?php echo addslashes($post_title); ?>",
                        "item": "<?php echo $canonical_url; ?>"
                    }
                ]
            },
            {
                "@type": "WebSite",
                "@id": "<?php echo $site_url_s; ?>/#website",
                "url": "<?php echo $site_url_s; ?>/",
                "name": "Breezekings",
                "description": "In-depth reporting, technology insights, and cultural analysis.",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": {
                        "@type": "EntryPoint",
                        "urlTemplate": "<?php echo $site_url_s; ?>/?q={search_term_string}"
                    },
                    "query-input": "required name=search_term_string"
                }
            }<?php if (!empty($faq_items)): ?>,
            {
                "@type": "FAQPage",
                "@id": "<?php echo $canonical_url; ?>#faq",
                "mainEntity": <?php echo json_encode($faq_items, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
            }
            <?php endif; ?>
        ]
    }
    </script>

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

        .article-content p {
            margin-bottom: 1.5rem;
            font-size: 1.05rem;
            line-height: 1.85;
            color: #334155;
        }

        .article-content a {
            color: #C5202B;
            font-weight: 600;
            text-decoration: underline;
            text-decoration-thickness: 1.5px;
            text-underline-offset: 3px;
            transition: color 0.15s ease, text-decoration-color 0.15s ease;
        }
        .article-content a:hover {
            color: #0B1F3A;
            text-decoration-color: #0B1F3A;
        }
        
        .article-content h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.875rem;
            color: #0f172a;
            margin-top: 3rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-left: 1rem;
        }
        .article-content h2::before {
            content: ''; position: absolute; left: 0; top: 15%; height: 70%; width: 4px; background-color: #e12b38;
        }

        .article-content h3 {
            font-weight: 700;
            font-size: 1.35rem;
            color: #0f172a;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }

        .article-content h4 {
            font-weight: 700;
            font-size: 1.15rem;
            color: #1e293b;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
        }

        .article-content ul {
            list-style-type: disc;
            padding-left: 1.75rem;
            margin-bottom: 1.75rem;
            color: #334155;
        }
        .article-content ol {
            list-style-type: decimal;
            padding-left: 1.75rem;
            margin-bottom: 1.75rem;
            color: #334155;
        }
        .article-content li {
            margin-bottom: 0.5rem;
            line-height: 1.75;
            font-size: 1.05rem;
        }

        .article-content blockquote, .article-quote {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.25rem;
            color: #334155;
            background-color: #f8fafc;
            padding: 1.5rem 2rem;
            border-left: 4px solid #e12b38;
            margin: 2rem 0;
            line-height: 1.7;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .article-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
            font-size: 0.95rem;
            background: #fff;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .article-content th, .article-content td {
            border: 1px solid #e2e8f0;
            padding: 0.85rem 1.1rem;
            text-align: left;
        }
        .article-content th {
            background-color: #f1f5f9;
            font-weight: 700;
            color: #0f172a;
        }
        .article-content tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .article-content hr {
            border: 0;
            border-top: 1px solid #e2e8f0;
            margin: 2.5rem 0;
        }

        .article-content code {
            background-color: #f1f5f9;
            color: #c5202b;
            padding: 0.15rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.9em;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
        .article-content pre {
            background-color: #0f172a;
            color: #f8fafc;
            padding: 1.25rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin: 2rem 0;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .article-content pre code {
            background-color: transparent;
            color: inherit;
            padding: 0;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 1.75rem auto;
            display: block;
        }

        /* Diagonal lines background for hero */
        .bg-stripes {
            background-image: repeating-linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.03) 0,
                rgba(255, 255, 255, 0.03) 2px,
                transparent 2px,
                transparent 8px
            );
        }

        .popular-num {
            font-size: 2.5rem;
            font-weight: 300;
            color: #d1d5db; /* Light gray */
            line-height: 1;
            font-family: 'Playfair Display', serif;
        }

        /* Reading progress bar */
        #reading-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #e12b38, #c5202b);
            z-index: 9999;
            transition: width 0.1s ease;
        }

        /* Share bar */
        .share-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .share-btn:hover { transform: translateY(-1px); }
        .share-btn-twitter  { background: #1da1f2; color: #fff; }
        .share-btn-facebook { background: #1877f2; color: #fff; }
        .share-btn-linkedin { background: #0077b5; color: #fff; }
        .share-btn-copy     { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }
        .share-btn-copy:hover { background: #e2e8f0; }
    </style>
</head>
<body class="text-slate-800 antialiased selection:bg-crimson-500 selection:text-white">

    <!-- Reading Progress Bar -->
    <div id="reading-progress" role="progressbar" aria-label="Reading progress"></div>

    <?php include 'include/frontend_header.php'; ?>

    <!-- Hero Article Header -->
    <?php $hero_img = !empty($post['featured_image']) ? bk_thumb_url($post['featured_image']) : ""; ?>
    <header class="<?php echo $hero_img ? 'relative' : 'bg-navy-900 bg-stripes relative'; ?> py-20 lg:py-32 overflow-hidden">
        <?php if ($hero_img): ?>
            <img src="<?php echo $hero_img; ?>" class="absolute inset-0 w-full h-full object-cover z-0 opacity-40" onerror="this.style.display='none'">
        <?php endif; ?>
        <div class="absolute inset-0 bg-gradient-to-r from-navy-950/80 via-navy-900/60 to-transparent z-0"></div>
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex">
            <div class="max-w-4xl">
                <!-- Breadcrumbs -->
                <nav aria-label="Breadcrumb" class="mb-5">
                    <ol class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-300">
                        <li>
                            <a href="/" class="hover:text-white transition-colors flex items-center gap-1.5">
                                <i class="fa-solid fa-house text-[10px]"></i> Home
                            </a>
                        </li>
                        <li class="text-slate-500">/</li>
                        <li>
                            <a href="<?php echo $category_url; ?>" class="hover:text-white text-crimson-400 transition-colors">
                                <?php echo $category_name; ?>
                            </a>
                        </li>
                        <li class="text-slate-500">/</li>
                        <li class="text-slate-400 truncate max-w-xs sm:max-w-md" aria-current="page">
                            <?php echo $post_title; ?>
                        </li>
                    </ol>
                </nav>
                <a href="<?php echo $category_url; ?>" class="bg-crimson-600 hover:bg-crimson-700 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-sm mb-6 inline-block transition-colors">
                    <?php echo strtoupper($category_name); ?>
                </a>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white leading-tight mb-8">
                    <?php echo $post_title; ?>
                </h1>
                
                <div class="flex items-center gap-4 text-xs font-medium uppercase tracking-wider text-slate-300">
                    <div class="flex items-center gap-3">
                        <img src="<?php echo $author_img; ?>" alt="<?php echo htmlspecialchars($author_name); ?>" class="w-8 h-8 rounded-full border-2 border-slate-400 object-cover" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($author_name); ?>&background=0B1F3A&color=ffffff&bold=true&size=64';">
                        <span class="text-white normal-case font-semibold"><?php echo htmlspecialchars($author_name); ?></span>
                    </div>
                    <span>&bull;</span>
                    <span>Published <?php echo $pub_date_fmt; ?><?php if ($has_updated): ?> <span class="text-slate-400 font-normal normal-case">(Updated <?php echo $mod_date_fmt; ?>)</span><?php endif; ?></span>
                    <span>&bull;</span>
                    <span><?php echo $database->getReadingTime($post['content']); ?> MIN READ</span>
                    <span>&bull;</span>
                    <span><?php echo number_format($post['views']); ?> VIEWS</span>
                </div>
            </div>
        </div>
    </header>

    <div class="bg-white pb-16 pt-12">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row gap-16">
                
                <!-- Right Content Area (70%) -->
                <main class="lg:w-[70%] flex-1">
                    
                    <div class="article-content max-w-4xl mx-auto" id="article-body">
                        <?php echo bk_render_content($post['content']); ?>
                    </div>

                    <!-- Social Share Bar -->
                    <div class="mt-10 pt-8 border-t border-slate-200">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Share this article</p>
                        <div class="flex flex-wrap gap-2">
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($canonical_url); ?>&text=<?php echo urlencode($post_title); ?>&via=breezekings" target="_blank" rel="noopener noreferrer" class="share-btn share-btn-twitter" aria-label="Share on X/Twitter">
                                <i class="fa-brands fa-x-twitter"></i> X / Twitter
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($canonical_url); ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-btn-facebook" aria-label="Share on Facebook">
                                <i class="fa-brands fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($canonical_url); ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-btn-linkedin" aria-label="Share on LinkedIn">
                                <i class="fa-brands fa-linkedin-in"></i> LinkedIn
                            </a>
                            <button onclick="copyArticleUrl()" class="share-btn share-btn-copy" id="copyUrlBtn" aria-label="Copy article link">
                                <i class="fa-regular fa-copy"></i> Copy Link
                            </button>
                        </div>
                    </div>
                        

                    <!-- Author Box -->
                    <div class="mt-16 bg-white border border-slate-200 rounded-lg p-8 flex flex-col sm:flex-row gap-8 items-start shadow-sm">
                        <img src="<?php echo $author_img; ?>" alt="<?php echo htmlspecialchars($author_name); ?>" class="w-20 h-20 rounded-full object-cover shrink-0 border-4 border-slate-50" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($author_name); ?>&background=0B1F3A&color=ffffff&bold=true&size=128';">
                        <div class="flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-crimson-600 mb-1">Article Author</p>
                            <h4 class="text-lg font-serif font-bold text-navy-900 mb-1.5"><?php echo htmlspecialchars($author_name); ?></h4>
                            <?php if (!empty($author_info['bio'])): ?>
                                <p class="text-xs text-slate-600 leading-relaxed mb-3.5"><?php echo nl2br(htmlspecialchars($author_info['bio'])); ?></p>
                            <?php else: ?>
                                <p class="text-xs text-slate-500 leading-relaxed mb-3.5">Lead technology editor and research analyst at Breezekings, specializing in artificial intelligence, software tools, digital security, and consumer technology trends.</p>
                            <?php endif; ?>
                            <div class="flex gap-4">
                                <a href="mailto:<?php echo htmlspecialchars(!empty($author_info['email']) ? $author_info['email'] : 'contact@breezekings.com'); ?>" class="text-[10px] font-bold text-crimson-600 hover:text-crimson-800 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                                    <i class="fa-solid fa-envelope text-xs"></i> Contact Author
                                </a>
                                <a href="/about" class="text-[10px] font-bold text-navy-900 hover:text-crimson-600 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                                    <i class="fa-solid fa-users text-xs"></i> Editorial Team
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Related Articles in Category -->
                    <?php
                    $cat_id_num = (int)$post['category_id'];
                    $related_query = $database->get_related_posts($cat_id_num, $cur_post_id, 3);
                    if ($related_query && mysqli_num_rows($related_query) > 0) {
                    ?>
                    <section class="mt-14 pt-12 border-t border-slate-200">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl md:text-2xl font-serif font-bold text-navy-900 flex items-center gap-3">
                                <span class="w-2.5 h-6 bg-crimson-600 rounded-sm inline-block"></span>
                                Related in <?= htmlspecialchars($post['category'] ?? 'Category') ?>
                            </h3>
                            <a href="<?= bk_category_url($post['category_id'], $post['category'] ?? '') ?>" class="text-xs font-bold text-crimson-600 hover:text-crimson-800 uppercase tracking-widest flex items-center gap-1.5 transition-colors">
                                View Category <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            <?php while ($rel = mysqli_fetch_assoc($related_query)): 
                                $rel_url = bk_post_url($rel);
                                $rel_img = bk_thumb_url($rel['featured_image']);
                            ?>
                            <article class="bg-slate-50 border border-slate-200 rounded-xl overflow-hidden hover:shadow-md transition-all group flex flex-col justify-between">
                                <div>
                                    <a href="<?= $rel_url ?>" class="block relative aspect-video overflow-hidden">
                                        <img src="<?= $rel_img ?>" alt="<?= htmlspecialchars($rel['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.src='/images/blog-default.jpg'">
                                    </a>
                                    <div class="p-4">
                                        <span class="text-[10px] font-bold text-crimson-600 uppercase tracking-widest block mb-1.5"><?= htmlspecialchars($rel['category'] ?? '') ?></span>
                                        <h4 class="font-bold text-sm text-navy-900 group-hover:text-crimson-600 transition-colors line-clamp-2 leading-snug">
                                            <a href="<?= $rel_url ?>"><?= htmlspecialchars($rel['title']) ?></a>
                                        </h4>
                                    </div>
                                </div>
                                <div class="px-4 pb-4 pt-1 text-[11px] text-slate-400">
                                    <?= date('M d, Y', strtotime($rel['created_at'])) ?>
                                </div>
                            </article>
                            <?php endwhile; ?>
                        </div>
                    </section>
                    <?php } ?>

                    <!-- Comments Section -->
                    <section id="comments" class="mt-16 border-t border-slate-100 pt-16">
                        <h3 class="text-2xl font-serif font-bold text-navy-900 mb-10 flex items-center gap-3">
                            <i class="fa-regular fa-comments text-crimson-600"></i>
                            Discussion
                        </h3>

                        <!-- Comment List -->
                        <div class="space-y-10 mb-16">
                            <?php
                            $comments = $database->get_comments($post_id);
                            if ($comments && mysqli_num_rows($comments) > 0) {
                                while ($c = mysqli_fetch_assoc($comments)) {
                            ?>
                            <div class="flex gap-5">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center shrink-0 font-bold text-slate-400">
                                    <?php echo strtoupper(substr($c['name'], 0, 1)); ?>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h5 class="font-bold text-slate-900"><?php echo htmlspecialchars($c['name']); ?></h5>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo date('M d, Y', strtotime($c['created_at'])); ?></span>
                                    </div>
                                    <p class="text-slate-600 text-sm leading-relaxed">
                                        <?php echo nl2br(htmlspecialchars($c['comment'])); ?>
                                    </p>
                                </div>
                            </div>
                            <?php
                                }
                            } else {
                                echo '<p class="text-slate-400 text-sm italic">No comments yet. Be the first to share your thoughts!</p>';
                            }
                            ?>
                        </div>

                        <!-- Comment Form -->
                        <div class="bg-slate-50 rounded-xl p-8 border border-slate-100">
                            <h4 class="text-lg font-bold text-navy-900 mb-6">Leave a Comment</h4>
                            
                            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'c_success'): ?>
                                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg text-sm mb-6 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                    <span>Your comment has been submitted successfully! It will appear once approved.</span>
                                </div>
                            <?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'c_error'): ?>
                                <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg text-sm mb-6 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                                    <span>Unable to post comment. Please fill out all fields properly and try again.</span>
                                </div>
                            <?php endif; ?>

                            <form action="/process.php" method="POST" class="space-y-6">
                                <input type="hidden" name="add_comment" value="1">
                                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Full Name</label>
                                        <input type="text" name="name" required class="w-full bg-white border border-slate-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-crimson-500 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Email Address</label>
                                        <input type="email" name="email" required class="w-full bg-white border border-slate-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-crimson-500 transition-colors">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Your Comment</label>
                                    <textarea name="comment" rows="5" required class="w-full bg-white border border-slate-200 rounded px-4 py-2.5 text-sm focus:outline-none focus:border-crimson-500 transition-colors resize-none"></textarea>
                                </div>

                                <!-- Bot Protection -->
                                <div class="hidden" aria-hidden="true">
                                    <input type="text" name="hp_field" tabindex="-1" autocomplete="off">
                                    <input type="hidden" name="form_time" value="<?php echo time(); ?>">
                                </div>
                                <button type="submit" class="bg-navy-900 hover:bg-navy-950 text-white font-bold py-3 px-8 rounded text-xs uppercase tracking-widest transition-colors">
                                    Post Comment
                                </button>
                            </form>
                        </div>
                    </section>
                </main>
                
                <!-- Left Sidebar Area (30%) -->
                <aside class="lg:w-[30%] shrink-0">
                    <div class="sticky top-24 space-y-10">
                        
                        <!-- Top Read Today (Featured Sticky) -->
                        <?php
                        $top_read = $database->get_popular_posts(1);
                        if($tr = mysqli_fetch_assoc($top_read)) {
                            $tr_thumb = bk_thumb_url($tr['featured_image']);
                        ?>
                        <div class="bg-navy-950 rounded-xl overflow-hidden shadow-xl border border-white/5 group">
                            <div class="p-4 bg-crimson-600 flex justify-between items-center">
                                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Top Read Today</h3>
                                <i class="fa-solid fa-fire-flame-curved text-white/50 text-xs"></i>
                            </div>
                            <a href="<?php echo bk_post_url($tr); ?>" class="block relative h-48 lg:h-64">
                                <img src="<?php echo $tr_thumb; ?>" class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity" alt="<?php echo htmlspecialchars($tr['title']); ?>" onerror="this.src='/images/blog-default.jpg'">
                                <div class="absolute inset-0 bg-gradient-to-t from-navy-950 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h4 class="text-lg font-serif font-bold text-white leading-tight group-hover:text-crimson-400 transition-colors line-clamp-2"><?php echo htmlspecialchars($tr['title']); ?></h4>
                                </div>
                            </a>
                            <div class="p-5 bg-navy-900/50 backdrop-blur-md">
                                <p class="text-slate-400 text-xs mb-4 line-clamp-2 italic">"<?php echo htmlspecialchars($tr['excerpt']); ?>"</p>
                                <a href="<?php echo bk_post_url($tr); ?>" class="text-[10px] font-bold text-white uppercase tracking-widest flex items-center gap-2 hover:gap-3 transition-all">
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

                            <!-- Latest News Section -->
                            <section aria-label="Latest News">
                                <div class="flex items-center justify-between mb-8">
                                    <h2 class="text-xl lg:text-2xl font-serif font-bold text-navy-900 section-title">
                                        Latest News
                                    </h2>
                                    <a href="/" class="text-[11px] font-bold text-crimson-600 hover:text-crimson-700 uppercase tracking-widest flex items-center gap-1 transition-colors">
                                        View All <i class="fa-solid fa-arrow-right text-[10px]" aria-hidden="true"></i>
                                    </a>
                                </div>

                                <div class="space-y-6" role="list">
                                    <?php
                                    $latest_posts = $database->get_all_posts('Published');
                                    $has_posts = $latest_posts && mysqli_num_rows($latest_posts) > 0;
                                    if ($has_posts):
                                        $count = 0;
                                        while ($lp = mysqli_fetch_assoc($latest_posts)):
                                            if ($lp['id'] == $post_id) continue; // Skip current post
                                            if ($count >= 5) break; // Limit to 5 in sidebar
                                            $count++;
                                            
                                            $lp_thumb = bk_thumb_url($lp['featured_image']);
                                            $lp_date = date('M d, Y', strtotime($lp['created_at']));
                                            $lp_cat = htmlspecialchars($lp['category'] ?? 'General');
                                            $lp_title = htmlspecialchars($lp['title']);
                                            $lp_url = bk_post_url($lp);
                                            $lp_cat_url = bk_category_url($lp['category_id'], $lp['category'] ?? 'General');
                                    ?>
                                            <article class="flex gap-4 group items-center" role="listitem">
                                                <a href="<?php echo $lp_url; ?>" class="w-24 h-20 flex-shrink-0 rounded-lg overflow-hidden border border-slate-100 shadow-sm">
                                                    <img src="<?php echo $lp_thumb; ?>" alt="<?php echo $lp_title; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.src='/images/blog-default.jpg'">
                                                </a>
                                                <div class="flex-1 min-w-0">
                                                    <a href="<?php echo $lp_cat_url; ?>" class="text-[9px] font-bold text-crimson-600 uppercase tracking-wider mb-1 block">
                                                        <?php echo $lp_cat; ?>
                                                    </a>
                                                    <h4 class="text-sm font-serif font-bold text-navy-900 leading-snug group-hover:text-crimson-600 transition-colors line-clamp-2">
                                                        <a href="<?php echo $lp_url; ?>">
                                                            <?php echo $lp_title; ?>
                                                        </a>
                                                    </h4>
                                                    <time class="text-[10px] text-slate-400 mt-1 block"><?php echo $lp_date; ?></time>
                                                </div>
                                            </article>
                                    <?php endwhile; endif; ?>
                                </div>
                            </section>
                            <!-- End Latest News Section -->
                        </div>
                    </div>
                </aside>
                
            </div>
        </div>
    </div>

    <!-- You May Also Like Section -->
    <div class="bg-slate-50 py-16 border-t border-slate-200">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-serif font-bold text-navy-900 mb-10 section-title">You May Also Like</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $related = $database->get_related_posts($post['category_id'], $post_id, 3);
                if ($related && mysqli_num_rows($related) > 0) {
                    while ($r = mysqli_fetch_assoc($related)) {
                        $r_thumb = bk_thumb_url($r['featured_image']);
                        $r_url = bk_post_url($r);
                ?>
                <article class="group">
                    <a href="<?php echo $r_url; ?>" class="block h-48 overflow-hidden rounded-sm mb-4 relative">
                        <img src="<?php echo $r_thumb; ?>" alt="<?php echo htmlspecialchars($r['title']); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" onerror="this.src='/images/blog-default.jpg'">
                    </a>
                    <span class="text-crimson-600 text-[10px] font-bold uppercase tracking-wider block mb-2"><?php echo strtoupper(htmlspecialchars($r['category'])); ?></span>
                    <h3 class="text-lg font-serif font-bold text-navy-900 leading-snug group-hover:text-crimson-600 transition-colors">
                        <a href="<?php echo $r_url; ?>"><?php echo htmlspecialchars($r['title']); ?></a>
                    </h3>
                </article>
                <?php
                    }
                } else {
                    echo '<p class="text-slate-400 italic text-sm col-span-3">No related posts found.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Breezekings Global Footer -->
    <?php include 'include/frontend_footer.php'; ?>

    <script>
    // ── Reading Progress Bar ─────────────────────────────────────────────
    (function() {
        const bar     = document.getElementById('reading-progress');
        const article = document.getElementById('article-body');
        if (!bar || !article) return;

        function updateProgress() {
            const articleTop    = article.getBoundingClientRect().top + window.scrollY;
            const articleBottom = articleTop + article.offsetHeight;
            const scrolled      = window.scrollY + window.innerHeight;
            const total         = articleBottom - articleTop;
            const progress      = Math.min(100, Math.max(0, ((scrolled - articleTop) / total) * 100));
            bar.style.width = progress + '%';
        }

        window.addEventListener('scroll', updateProgress, { passive: true });
        updateProgress();
    })();

    // ── Copy Article URL ─────────────────────────────────────────────────
    function copyArticleUrl() {
        const url = window.location.href;
        const btn = document.getElementById('copyUrlBtn');
        if (!btn) return;

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function() {
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
                btn.style.background = '#d1fae5';
                btn.style.color = '#065f46';
                setTimeout(function() {
                    btn.innerHTML = original;
                    btn.style.background = '';
                    btn.style.color = '';
                }, 2000);
            }).catch(function() {
                fallbackCopy(url, btn);
            });
        } else {
            fallbackCopy(url, btn);
        }
    }

    function fallbackCopy(url, btn) {
        const ta = document.createElement('textarea');
        ta.value = url;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        try {
            document.execCommand('copy');
            if (btn) {
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
                setTimeout(function() { btn.innerHTML = original; }, 2000);
            }
        } catch(e) {}
        document.body.removeChild(ta);
    }
    </script>

</body>
</html>
