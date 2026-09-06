<?php
include("include/classes/session.php");

if (!$session->logged_in) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Breezekings Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/images/breezekings-icon-red.svg">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            50: '#eef0f7',
                            800: '#1e2336',
                            900: '#0B1F3A',
                            950: '#071324',
                        },
                        crimson: {
                            50: '#fff0f1',
                            500: '#e12b38',
                            600: '#C5202B',
                            700: '#a31a23',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
        .stat-card { transition: all 0.25s cubic-bezier(.25, .46, .45, .94); }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(11, 31, 58, 0.08); }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-slate-800 antialiased">

    <?php include 'include/admin_sidebar.php'; ?>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        <?php include 'include/admin_header.php'; ?>

        <!-- Main Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8 flex flex-col">
            <div class="max-w-[1400px] mx-auto w-full">
                
                <!-- Page Header & Live Link -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1.5 flex items-center gap-2">
                            <span>BREEZEKINGS</span> <span class="text-slate-300">/</span> <span class="text-crimson-600 font-semibold">MANAGEMENT SUITE</span>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-extrabold text-[#0B1F3A] tracking-tight">
                            Welcome back, <?php echo htmlspecialchars(!empty($session->userinfo['display_name']) ? $session->userinfo['display_name'] : $session->username); ?> <span class="text-xl">👋</span>
                        </h2>
                        <p class="text-xs lg:text-sm text-slate-500 mt-0.5">Platform overview, article performance and content pipeline status.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="analytics_dashboard.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-all text-xs flex items-center gap-2">
                            <i class="fa-solid fa-chart-line"></i> Analytics Dashboard
                        </a>
                        <a href="/" target="_blank" class="bg-white border border-slate-200 text-[#0B1F3A] hover:bg-slate-50 font-semibold py-2 px-4 rounded-lg shadow-sm transition-all text-xs flex items-center gap-2">
                            <i class="fa-solid fa-globe text-crimson-600"></i> View Live Site
                        </a>
                        <a href="create-post.php" class="bg-[#C5202B] hover:bg-[#a31a23] text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-all text-xs flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Write Article
                        </a>
                    </div>
                </div>

                <?php
                // Fetch stats with published / draft status breakdown
                $total_posts_res = $database->query("SELECT COUNT(*) as count FROM posts WHERE is_deleted = 0");
                $total_posts = (int)(mysqli_fetch_assoc($total_posts_res)['count'] ?? 0);

                $pub_posts_res = $database->query("SELECT COUNT(*) as count FROM posts WHERE status = 'Published' AND is_deleted = 0");
                $published_posts = (int)(mysqli_fetch_assoc($pub_posts_res)['count'] ?? 0);

                $draft_posts_res = $database->query("SELECT COUNT(*) as count FROM posts WHERE status = 'Draft' AND is_deleted = 0");
                $draft_posts = (int)(mysqli_fetch_assoc($draft_posts_res)['count'] ?? 0);

                $views_res = $database->query("SELECT SUM(views) as count FROM posts WHERE is_deleted = 0");
                $total_views = (int)(mysqli_fetch_assoc($views_res)['count'] ?? 0);

                $total_cats_res = $database->query("SELECT COUNT(*) as count FROM categories");
                $total_categories = (int)(mysqli_fetch_assoc($total_cats_res)['count'] ?? 0);

                $total_users_res = $database->query("SELECT COUNT(*) as count FROM users");
                $total_users = (int)(mysqli_fetch_assoc($total_users_res)['count'] ?? 0);
                ?>

                <!-- ── Enhanced Stats Row ──────────────────────────────────── -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                    
                    <!-- Card 1: Published Articles -->
                    <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-emerald-500 relative overflow-hidden">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Published Articles</p>
                                <h3 class="text-3xl font-extrabold text-[#0B1F3A]"><?php echo number_format($published_posts); ?></h3>
                            </div>
                            <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                        </div>
                        <div class="text-[11px] font-medium flex items-center justify-between text-slate-500 pt-2 border-t border-slate-50">
                            <span>Live & Indexed in Google</span>
                            <span class="text-emerald-600 font-bold"><?php echo $total_posts > 0 ? round(($published_posts / $total_posts) * 100) : 0; ?>%</span>
                        </div>
                    </div>

                    <!-- Card 2: Draft Posts -->
                    <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-amber-500 relative overflow-hidden">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pending Drafts</p>
                                <h3 class="text-3xl font-extrabold text-[#0B1F3A]"><?php echo number_format($draft_posts); ?></h3>
                            </div>
                            <div class="w-11 h-11 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-lg">
                                <i class="fa-solid fa-pen-ruler"></i>
                            </div>
                        </div>
                        <div class="text-[11px] font-medium flex items-center justify-between text-slate-500 pt-2 border-t border-slate-50">
                            <span>Needs review or image</span>
                            <a href="posts.php?status=Draft" class="text-amber-600 font-bold hover:underline">View Drafts &rarr;</a>
                        </div>
                    </div>

                    <!-- Card 3: Total Readership Views -->
                    <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-[#C5202B] relative overflow-hidden">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Page Views</p>
                                <h3 class="text-3xl font-extrabold text-[#0B1F3A]"><?php echo number_format($total_views); ?></h3>
                            </div>
                            <div class="w-11 h-11 bg-crimson-50 text-crimson-600 rounded-xl flex items-center justify-center text-lg">
                                <i class="fa-solid fa-fire"></i>
                            </div>
                        </div>
                        <div class="text-[11px] font-medium flex items-center justify-between text-slate-500 pt-2 border-t border-slate-50">
                            <span>Article engagement</span>
                            <a href="analytics_dashboard.php" class="text-crimson-600 font-bold hover:underline flex items-center gap-1">
                                Full Analytics <i class="fa-solid fa-arrow-right text-[9px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Card 4: Categories & Topics -->
                    <div class="stat-card bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-blue-600 relative overflow-hidden">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Topic Categories</p>
                                <h3 class="text-3xl font-extrabold text-[#0B1F3A]"><?php echo number_format($total_categories); ?></h3>
                            </div>
                            <div class="w-11 h-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg">
                                <i class="fa-solid fa-folder-tree"></i>
                            </div>
                        </div>
                        <div class="text-[11px] font-medium flex items-center justify-between text-slate-500 pt-2 border-t border-slate-50">
                            <span><?php echo number_format($total_posts); ?> Total Posts</span>
                            <a href="admin-categories.php" class="text-blue-600 font-bold hover:underline">Manage &rarr;</a>
                        </div>
                    </div>

                </div>

                <!-- ── Action Shortcuts Grid ───────────────────────────────── -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-8">
                    <a href="analytics_dashboard.php" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-indigo-600 hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-chart-line text-sm"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Analytics</span>
                    </a>
                    <a href="create-post.php" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-[#C5202B] hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-crimson-50 text-crimson-600 flex items-center justify-center group-hover:bg-[#C5202B] group-hover:text-white transition-colors">
                            <i class="fa-solid fa-pen-nib text-sm"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Write Post</span>
                    </a>
                    <a href="posts.php" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-[#0B1F3A] hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-slate-100 text-[#0B1F3A] flex items-center justify-center group-hover:bg-[#0B1F3A] group-hover:text-white transition-colors">
                            <i class="fa-solid fa-list text-sm"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">All Posts</span>
                    </a>
                    <a href="admin-categories.php" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-blue-600 hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-tags text-sm"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Categories</span>
                    </a>
                    <a href="/sitemap.xml" target="_blank" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-emerald-600 hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-sitemap text-sm"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Sitemap XML</span>
                    </a>
                    <a href="/rss.xml" target="_blank" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-amber-600 hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-rss text-sm"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">RSS Feed</span>
                    </a>
                    <a href="settings.php" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-slate-800 hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-gear text-sm"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700">Settings</span>
                    </a>
                </div>

                <!-- ── Analytics Quick Insight Banner ─────────────────────── -->
                <div class="mb-8 bg-gradient-to-r from-[#0B1F3A] via-[#15233e] to-[#1e1e3b] rounded-2xl p-6 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-6 border border-white/10 relative overflow-hidden">
                    <div class="relative z-10 max-w-xl">
                        <div class="inline-flex items-center gap-2 bg-indigo-500/20 border border-indigo-400/30 rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-indigo-300 mb-2">
                            <i class="fa-solid fa-chart-pie text-xs"></i> Analytics &amp; Traffic Intelligence
                        </div>
                        <h3 class="text-lg md:text-xl font-bold font-serif text-white">Full Analytics Dashboard</h3>
                        <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                            Monitor 30-day traffic velocity, readership breakdown by category, draft-to-published ratios, comments activity, and overall SEO health score.
                        </p>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 shrink-0">
                        <a href="analytics_dashboard.php" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-md transition-all flex items-center gap-2">
                            <i class="fa-solid fa-chart-line"></i> Launch Analytics &rarr;
                        </a>
                    </div>
                    <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
                </div>

                <!-- ── Two-Column Main Content ─────────────────────────────── -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    
                    <!-- Left: Recent Articles Table -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <div>
                                <h3 class="font-bold text-[#0B1F3A] text-sm">Recent Blog Articles</h3>
                                <p class="text-[11px] text-slate-400">Latest additions with permalinks & status</p>
                            </div>
                            <a href="posts.php" class="text-xs font-bold text-[#C5202B] hover:text-[#a31a23] transition-colors flex items-center gap-1">
                                View All Posts <i class="fa-solid fa-chevron-right text-[9px]"></i>
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto flex-1">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        <th class="px-5 py-3">Article</th>
                                        <th class="px-5 py-3">Category</th>
                                        <th class="px-5 py-3 text-center">Status</th>
                                        <th class="px-5 py-3 text-center">Views</th>
                                        <th class="px-5 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-slate-100">
                                    <?php
                                    $recent_posts = $database->query("SELECT p.*, c.category FROM posts p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_deleted = 0 ORDER BY p.created_at DESC LIMIT 6");
                                    if ($recent_posts && mysqli_num_rows($recent_posts) > 0) {
                                        while ($post = mysqli_fetch_assoc($recent_posts)) {
                                            $status_class = ($post['status'] == 'Published') ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600';
                                            $status_dot = ($post['status'] == 'Published') ? 'bg-emerald-500' : 'bg-amber-500';
                                            $post_url = bk_post_url($post);
                                    ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-5 py-3.5">
                                            <div class="font-semibold text-slate-800 hover:text-crimson-600 transition-colors line-clamp-1">
                                                <?php echo htmlspecialchars($post['title']); ?>
                                            </div>
                                            <div class="text-[10px] text-slate-400 font-mono flex items-center gap-1 mt-0.5">
                                                <span><?php echo $post_url; ?></span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                            <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded">
                                                <?php echo htmlspecialchars($post['category'] ?? 'General'); ?>
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 <?php echo $status_class; ?> text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full <?php echo $status_dot; ?>"></span> 
                                                <?php echo $post['status']; ?>
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-center whitespace-nowrap font-medium text-xs text-slate-600">
                                            <?php echo number_format($post['views']); ?>
                                        </td>
                                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <?php if ($post['status'] === 'Published'): ?>
                                                <a href="<?php echo $post_url; ?>" target="_blank" class="w-7 h-7 rounded bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all" title="View Live Post">
                                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                                </a>
                                                <?php endif; ?>
                                                <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="w-7 h-7 rounded bg-slate-100 text-slate-600 hover:bg-[#0B1F3A] hover:text-white flex items-center justify-center transition-all" title="Edit Post">
                                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">No blog articles created yet.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Right: Content Distribution & SEO Health -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col">
                        <div class="mb-5">
                            <h3 class="font-bold text-[#0B1F3A] text-sm mb-0.5">Category Distribution</h3>
                            <p class="text-[11px] text-slate-400">Published articles per category</p>
                        </div>

                        <div class="space-y-4 flex-1">
                            <?php
                            $cat_dist = $database->query("SELECT c.category, COUNT(p.id) as count FROM categories c LEFT JOIN posts p ON c.id = p.category_id AND p.is_deleted = 0 GROUP BY c.id ORDER BY count DESC LIMIT 6");
                            $palette = ['bg-crimson-600', 'bg-blue-600', 'bg-emerald-600', 'bg-purple-600', 'bg-amber-500', 'bg-indigo-600'];
                            $idx = 0;
                            if ($cat_dist && mysqli_num_rows($cat_dist) > 0) {
                                while ($cat = mysqli_fetch_assoc($cat_dist)) {
                                    $pct = ($total_posts > 0) ? ($cat['count'] / $total_posts) * 100 : 0;
                                    $c_bg = $palette[$idx % count($palette)];
                            ?>
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-slate-700"><?php echo htmlspecialchars($cat['category']); ?></span>
                                    <span class="text-slate-500 font-mono"><?php echo $cat['count']; ?></span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="<?php echo $c_bg; ?> h-1.5 rounded-full" style="width: <?php echo max(4, $pct); ?>%"></div>
                                </div>
                            </div>
                            <?php
                                    $idx++;
                                }
                            } else {
                                echo '<p class="text-xs text-slate-400">No categories found.</p>';
                            }
                            ?>
                        </div>

                        <!-- SEO Best Practices Banner -->
                        <div class="mt-6 bg-[#0B1F3A] text-white rounded-xl p-4 shadow-sm">
                            <div class="flex items-center gap-2 mb-1.5 text-crimson-400 text-xs font-bold uppercase tracking-wider">
                                <i class="fa-solid fa-bolt"></i> SEO Architecture Active
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                Sitemaps & clean encoded permalinks are auto-generated. When publishing a post, assign a high-res featured image and 300+ words to rank in Google Images & Discover.
                            </p>
                        </div>

                    </div>
                </div>

            </div>
            
            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 py-4 px-6 md:px-8 text-xs font-medium flex justify-between items-center mt-auto">
                <p class="text-slate-500">&copy; <?php echo date('Y'); ?> Breezekings Publishing Suite. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="/privacy-policy" target="_blank" class="text-slate-400 hover:text-crimson-600 transition-colors">Privacy Policy</a>
                    <a href="/termsofservices" target="_blank" class="text-slate-400 hover:text-crimson-600 transition-colors">Terms of Service</a>
                </div>
            </footer>
        </main>
    </div>

</body>
</html>