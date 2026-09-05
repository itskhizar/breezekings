<?php
include 'include/classes/session.php';

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post_query = $database->query("SELECT p.*, c.category FROM posts p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = $post_id");
$post = mysqli_fetch_assoc($post_query);

if (!$post || $post['status'] !== 'Published') {
    header("Location: index.php");
    exit();
}

$search_q = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';

// Increment views
$database->increment_views($post_id);

$author_info = $database->getUserInfo($post['author']);
$author_name = $author_info['display_name'] ?? $post['author'];
$author_img = (!empty($author_info['profile_image']) && file_exists('images/profiles/' . $author_info['profile_image'])) ? 'images/profiles/' . $author_info['profile_image'] : 'images/avatar.png';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> | BlogName</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo htmlspecialchars($post['meta_description'] ?: substr(strip_tags($post['content']), 0, 160)); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($post['tags']); ?>">
    
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
            line-height: 1.8;
            color: #334155;
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
            font-size: 1.25rem;
            color: #0f172a;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }

        .article-quote {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.5rem;
            color: #475569;
            background-color: #fffbeb; /* Light yellow */
            padding: 2rem;
            border-left: 4px solid #e12b38;
            margin: 2.5rem 0;
            line-height: 1.6;
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
    </style>
</head>
<body class="text-slate-800 antialiased selection:bg-crimson-500 selection:text-white">

    <?php include 'include/frontend_header.php'; ?>

    <!-- Hero Article Header -->
    <?php $hero_img = $post['featured_image'] ? "images/posts/" . $post['featured_image'] : ""; ?>
    <header class="<?php echo $hero_img ? 'relative' : 'bg-navy-900 bg-stripes relative'; ?> py-20 lg:py-32 overflow-hidden">
        <?php if ($hero_img): ?>
            <img src="<?php echo $hero_img; ?>" class="absolute inset-0 w-full h-full object-cover z-0 opacity-40">
        <?php endif; ?>
        <div class="absolute inset-0 bg-gradient-to-r from-navy-950/80 via-navy-900/60 to-transparent z-0"></div>
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex">
            <div class="max-w-4xl">
                <span class="bg-crimson-600 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-sm mb-6 inline-block"><?php echo strtoupper(htmlspecialchars($post['category'])); ?></span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white leading-tight mb-8">
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>
                
                <div class="flex items-center gap-4 text-xs font-medium uppercase tracking-wider text-slate-300">
                    <div class="flex items-center gap-3">
                        <img src="<?php echo $author_img; ?>" alt="<?php echo htmlspecialchars($author_name); ?>" class="w-8 h-8 rounded-full border-2 border-slate-400 object-cover">
                        <span class="text-white"><?php echo htmlspecialchars($author_name); ?></span>
                    </div>
                    <span>&bull;</span>
                    <span><?php echo date('F d, Y', strtotime($post['created_at'])); ?></span>
                    <span>&bull;</span>
                    <span><?php echo $database->getReadingTime($post['content']); ?> MIN READ</span>
                    <span>&bull;</span>
                    <span><?php echo $post['views']; ?> VIEWS</span>
                </div>
            </div>
        </div>
    </header>

    <div class="bg-white pb-16 pt-12">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row gap-16">
                
                <!-- Right Content Area (70%) -->
                <main class="lg:w-[70%] flex-1">
                    
                    <div class="article-content max-w-4xl mx-auto">
                        <?php echo $post['content']; ?>
                    </div>
                        

                    <!-- Author Box -->
                    <div class="mt-16 bg-white border border-slate-200 rounded-lg p-8 flex flex-col sm:flex-row gap-8 items-start shadow-sm">
                        <img src="<?php echo $author_img; ?>" alt="<?php echo htmlspecialchars($author_name); ?>" class="w-24 h-24 rounded-full object-cover shrink-0 border-4 border-slate-50">
                        <div>
                            <h4 class="text-xl font-serif font-bold text-navy-900 mb-2">About <?php echo htmlspecialchars($author_name); ?></h4>
                            <p class="text-slate-500 text-sm leading-relaxed mb-4">
                                <?php echo htmlspecialchars($author_name); ?> is a contributor at The Blog. They have been sharing insightful articles since joining the platform.
                            </p>
                            <div class="flex gap-4">
                                <a href="#" class="text-[10px] font-bold text-crimson-600 hover:text-crimson-800 uppercase tracking-widest transition-colors">FOLLOW ON TWITTER</a>
                                <a href="#" class="text-[10px] font-bold text-crimson-600 hover:text-crimson-800 uppercase tracking-widest transition-colors">VIEW PORTFOLIO</a>
                            </div>
                        </div>
                    </div>

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
                                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg text-sm mb-6 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-check"></i>
                                    Your comment has been posted successfully! It will appear once approved.
                                </div>
                            <?php endif; ?>

                            <form action="process.php" method="POST" class="space-y-6">
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
                            $tr_thumb = $tr['featured_image'] ? 'images/posts/'.$tr['featured_image'] : 'images/blog-default.jpg';
                        ?>
                        <div class="bg-navy-950 rounded-xl overflow-hidden shadow-xl border border-white/5 group">
                            <div class="p-4 bg-crimson-600 flex justify-between items-center">
                                <h3 class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Top Read Today</h3>
                                <i class="fa-solid fa-fire-flame-curved text-white/50 text-xs"></i>
                            </div>
                            <a href="single.php?id=<?php echo $tr['id']; ?>" class="block relative h-48 lg:h-64">
                                <img src="<?php echo $tr_thumb; ?>" class="w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity">
                                <div class="absolute inset-0 bg-gradient-to-t from-navy-950 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h4 class="text-lg font-serif font-bold text-white leading-tight group-hover:text-crimson-400 transition-colors line-clamp-2"><?php echo htmlspecialchars($tr['title']); ?></h4>
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
<!-- Latest News Section -->
<section aria-label="Latest News">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-xl lg:text-2xl font-serif font-bold text-navy-900 section-title">
            Latest News
        </h2>
        <a href="index.php" class="text-[11px] font-bold text-crimson-600 hover:text-crimson-700 uppercase tracking-widest flex items-center gap-1 transition-colors">
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
                
                $lp_thumb = $lp['featured_image'] ? 'images/posts/' . $lp['featured_image'] : 'images/blog-default.jpg';
                $lp_date = date('M d, Y', strtotime($lp['created_at']));
                $lp_cat = htmlspecialchars($lp['category'] ?? 'General');
                $lp_title = htmlspecialchars($lp['title']);
        ?>
                <article class="flex gap-4 group items-center" role="listitem">
                    <a href="single.php?id=<?php echo (int) $lp['id']; ?>" class="w-24 h-20 flex-shrink-0 rounded-lg overflow-hidden border border-slate-100 shadow-sm">
                        <img src="<?php echo $lp_thumb; ?>" alt="<?php echo $lp_title; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </a>
                    <div class="flex-1 min-w-0">
                        <a href="category.php?id=<?php echo $lp['category_id']; ?>" class="text-[9px] font-bold text-crimson-600 uppercase tracking-wider mb-1 block">
                            <?php echo $lp_cat; ?>
                        </a>
                        <h4 class="text-sm font-serif font-bold text-navy-900 leading-snug group-hover:text-crimson-600 transition-colors line-clamp-2">
                            <a href="single.php?id=<?php echo (int) $lp['id']; ?>">
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
                        $r_thumb = $r['featured_image'] ? 'images/posts/'.$r['featured_image'] : 'images/blog-default.jpg';
                ?>
                <article class="group">
                    <a href="single.php?id=<?php echo $r['id']; ?>" class="block h-48 overflow-hidden rounded-sm mb-4 relative">
                        <img src="<?php echo $r_thumb; ?>" alt="<?php echo htmlspecialchars($r['title']); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </a>
                    <span class="text-crimson-600 text-[10px] font-bold uppercase tracking-wider block mb-2"><?php echo strtoupper(htmlspecialchars($r['category'])); ?></span>
                    <h3 class="text-lg font-serif font-bold text-navy-900 leading-snug group-hover:text-crimson-600 transition-colors">
                        <a href="single.php?id=<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['title']); ?></a>
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

    <!-- Footer -->
    <footer class="bg-navy-900 text-white pt-16 pb-8 border-t-4 border-crimson-600">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <!-- Brand -->
                <div class="col-span-1">
                    <a href="index.php" class="font-serif font-bold text-3xl tracking-tight mb-6 block">BlogName</a>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Leading the conversation at the intersection of technology, culture, and business since 2018.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fa-solid fa-rss"></i></a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fa-solid fa-share-nodes"></i></a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors"><i class="fa-solid fa-envelope"></i></a>
                    </div>
                </div>

                <!-- SECTIONS -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase text-[11px] tracking-widest">Sections</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Politics</a></li>
                        <li><a href="category.php" class="text-crimson-500 hover:text-crimson-400 transition-colors font-medium">Technology</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Culture</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Business</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Science</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase text-[11px] tracking-widest">Company</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="about.php" class="text-slate-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Contact</a></li>
                        <li><a href="privacy-policy.php" class="text-slate-400 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="termsofservices.php" class="text-slate-400 hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="font-bold text-white mb-6 uppercase text-[11px] tracking-widest">Legal</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Editorial Guidelines</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Advertise</a></li>
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
