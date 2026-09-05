<?php
include("include/classes/session.php");
include("include/analytics_queries.php");

if (!$session->logged_in) {
    header("Location: login.php");
    exit();
}

// Fetch stats using our new helper functions
$stats = get_analytics_totals($database);
$top_viewed = get_top_viewed_posts($database);
$top_commented = get_top_commented_posts($database);
$seo_health = get_seo_health($database);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard | BlogAdmin</title>
    
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
                        brand: {
                            sidebar: '#151928',
                            blue: '#0d6efd',
                            hover: '#0b5ed7',
                            primary: '#173663', // Used for specific brand elements
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background-color: rgba(255, 255, 255, 0.05); }
        .sidebar-link.active { background-color: rgba(255, 255, 255, 0.08); border-left: 3px solid #0d6efd; }
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
                
                <!-- Page Header & Action Buttons -->
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-2 flex items-center gap-2">
                            <span>HOME</span> <span class="text-slate-300">/</span> <span class="text-brand-blue">ANALYTICS</span>
                        </div>
                        <h2 class="text-2xl font-semibold text-slate-800 mb-1">Advanced Analytics</h2>
                        <p class="text-sm text-slate-500">Monitor your blog's performance, traffic, and user engagement.</p>
                    </div>
                    
                    <!-- Quick Action Buttons -->
                    <div class="flex gap-2">
                        <a href="create-post.php" class="bg-brand-blue hover:bg-brand-hover text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Create Post
                        </a>
                        <a href="admin-categories.php" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-tags"></i> Add Category
                        </a>
                        <a href="admin-users.php" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-users"></i> Manage Users
                        </a>
                    </div>
                </div>

                <!-- 1. TOP STATS (FIRST ROW) -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                    <!-- Total Posts -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 relative overflow-hidden group hover:border-brand-blue transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Posts</p>
                            <i class="fa-solid fa-file-lines text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800"><?php echo number_format($stats['posts']); ?></h3>
                        <p class="text-[10px] text-emerald-500 mt-2 font-medium"><i class="fa-solid fa-arrow-trend-up"></i> +12% this month</p>
                    </div>
                    
                    <!-- Total Users -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 relative overflow-hidden group hover:border-brand-blue transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Users</p>
                            <i class="fa-solid fa-user-group text-slate-300 group-hover:text-purple-500 transition-colors"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800"><?php echo number_format($stats['users']); ?></h3>
                        <p class="text-[10px] text-emerald-500 mt-2 font-medium"><i class="fa-solid fa-arrow-trend-up"></i> +3% this week</p>
                    </div>
                    
                    <!-- Total Views -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 relative overflow-hidden group hover:border-brand-blue transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Views</p>
                            <i class="fa-regular fa-eye text-slate-300 group-hover:text-emerald-500 transition-colors"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800"><?php echo number_format($stats['views']); ?></h3>
                        <p class="text-[10px] text-emerald-500 mt-2 font-medium"><i class="fa-solid fa-arrow-trend-up"></i> +24% this month</p>
                    </div>
                    
                    <!-- Total Comments -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 relative overflow-hidden group hover:border-brand-blue transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Comments</p>
                            <i class="fa-regular fa-comments text-slate-300 group-hover:text-amber-500 transition-colors"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800"><?php echo number_format($stats['comments']); ?></h3>
                        <p class="text-[10px] text-rose-500 mt-2 font-medium"><i class="fa-solid fa-arrow-trend-down"></i> -2% today</p>
                    </div>

                    <!-- Categories -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 relative overflow-hidden group hover:border-brand-blue transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Categories</p>
                            <i class="fa-solid fa-layer-group text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800"><?php echo number_format($stats['categories']); ?></h3>
                        <p class="text-[10px] text-slate-400 mt-2 font-medium">Stable</p>
                    </div>

                    <!-- Drafts -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 relative overflow-hidden group hover:border-brand-blue transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Draft Posts</p>
                            <i class="fa-solid fa-pen-nib text-slate-300 group-hover:text-rose-500 transition-colors"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800"><?php echo number_format($stats['drafts']); ?></h3>
                        <p class="text-[10px] text-amber-500 mt-2 font-medium"><i class="fa-solid fa-clock"></i> Pending review</p>
                    </div>
                </div>

                <!-- 2. TRAFFIC & ENGAGEMENT CHARTS -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Main Chart -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-semibold text-slate-800">Traffic Overview</h3>
                                <p class="text-xs text-slate-500">Views per day (Last 30 Days)</p>
                            </div>
                            <div class="bg-slate-100 rounded-lg p-1 flex">
                                <button class="text-[10px] font-bold px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm">30D</button>
                                <button class="text-[10px] font-bold px-3 py-1.5 rounded-md text-slate-500 hover:text-slate-800">7D</button>
                            </div>
                        </div>
                        <div class="flex-1 min-h-[300px] relative w-full">
                            <canvas id="trafficChart"></canvas>
                        </div>
                    </div>

                    <!-- Secondary Charts & AI Insights -->
                    <div class="flex flex-col gap-6">
                        <!-- AI Insights Box -->
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl shadow-sm border border-indigo-100 p-5 relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 text-indigo-100 opacity-50">
                                <i class="fa-solid fa-robot text-8xl"></i>
                            </div>
                            <div class="relative z-10">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-6 h-6 rounded bg-indigo-500 text-white flex items-center justify-center shadow-sm">
                                        <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                                    </div>
                                    <h3 class="font-bold text-indigo-900 text-sm">AI Insights</h3>
                                </div>
                                <p class="text-xs text-indigo-800 leading-relaxed mb-3">
                                    "Posts in the <strong>Technology</strong> category are getting 40% more engagement this week. Consider publishing more content on this topic to sustain growth."
                                </p>
                                <button class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 hover:text-indigo-800 flex items-center gap-1 transition-colors">
                                    Generate Content <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Posts Published Chart -->
                        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex-1 flex flex-col">
                            <div class="mb-4">
                                <h3 class="font-semibold text-slate-800 text-sm">Publishing Velocity</h3>
                                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Posts per month</p>
                            </div>
                            <div class="flex-1 w-full relative min-h-[150px]">
                                <canvas id="postsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. TOP PERFORMANCE & ACTIVITY -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    
                    <!-- Top Posts -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <h3 class="font-semibold text-slate-800">Top Performing Posts</h3>
                            <div class="flex gap-2">
                                <button class="text-[10px] font-bold uppercase tracking-widest text-brand-blue bg-blue-50 px-2 py-1 rounded">By Views</button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        <th class="px-6 py-3">Post Title</th>
                                        <th class="px-6 py-3 text-right">Views</th>
                                        <th class="px-6 py-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-slate-100">
                                    <?php 
                                    if(empty($top_viewed)): 
                                    ?>
                                        <tr><td colspan="3" class="px-6 py-4 text-center text-slate-400 text-xs">No posts available.</td></tr>
                                    <?php 
                                    else:
                                        foreach($top_viewed as $post): 
                                    ?>
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-3">
                                            <p class="font-semibold text-slate-700 truncate max-w-[250px] md:max-w-[400px]"><?php echo htmlspecialchars($post['title']); ?></p>
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 text-xs font-bold px-2 py-1 rounded-md">
                                                <i class="fa-solid fa-eye text-[10px]"></i> <?php echo number_format($post['views']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            <a href="single.php?id=<?php echo $post['id']; ?>" target="_blank" class="text-slate-400 hover:text-brand-blue transition-colors">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SEO Health & Alerts -->
                    <div class="flex flex-col gap-6">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                            <div class="p-5 border-b border-slate-100">
                                <h3 class="font-semibold text-slate-800 text-sm">SEO & Blog Health</h3>
                            </div>
                            <div class="p-5 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full <?php echo $seo_health['missing_meta'] > 0 ? 'bg-rose-50 text-rose-500' : 'bg-emerald-50 text-emerald-500'; ?> flex items-center justify-center">
                                            <i class="fa-solid <?php echo $seo_health['missing_meta'] > 0 ? 'fa-triangle-exclamation' : 'fa-check'; ?> text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-700">Missing Meta Desc</p>
                                            <p class="text-[10px] text-slate-500"><?php echo $seo_health['missing_meta']; ?> posts need attention</p>
                                        </div>
                                    </div>
                                    <a href="posts.php" class="text-xs text-brand-blue hover:text-brand-hover">Fix</a>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full <?php echo $seo_health['missing_image'] > 0 ? 'bg-amber-50 text-amber-500' : 'bg-emerald-50 text-emerald-500'; ?> flex items-center justify-center">
                                            <i class="fa-regular <?php echo $seo_health['missing_image'] > 0 ? 'fa-image' : 'fa-check'; ?> text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-700">Missing Featured Image</p>
                                            <p class="text-[10px] text-slate-500"><?php echo $seo_health['missing_image']; ?> posts need attention</p>
                                        </div>
                                    </div>
                                    <a href="posts.php" class="text-xs text-brand-blue hover:text-brand-hover">Fix</a>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full <?php echo $seo_health['low_views'] > 0 ? 'bg-blue-50 text-blue-500' : 'bg-emerald-50 text-emerald-500'; ?> flex items-center justify-center">
                                            <i class="fa-solid <?php echo $seo_health['low_views'] > 0 ? 'fa-chart-line' : 'fa-check'; ?> text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-slate-700">Low View Posts (< 10)</p>
                                            <p class="text-[10px] text-slate-500"><?php echo $seo_health['low_views']; ?> posts underperforming</p>
                                        </div>
                                    </div>
                                    <a href="posts.php" class="text-xs text-brand-blue hover:text-brand-hover">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. ACTIVITY FEEDS -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Real-Time Activity Feed -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-semibold text-slate-800 text-sm">System Activity Feed</h3>
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                        </div>
                        <div class="p-5">
                            <div class="relative border-l border-slate-200 ml-3 space-y-6">
                                <!-- Mock Data for Admin Activity -->
                                <div class="relative pl-6">
                                    <span class="absolute -left-1.5 top-1 w-3 h-3 rounded-full border-2 border-white bg-blue-500"></span>
                                    <p class="text-xs font-semibold text-slate-700">Admin published a new post</p>
                                    <p class="text-[10px] text-slate-500 mt-1">"How AI Automation is Transforming Modern Businesses..."</p>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mt-2">2 minutes ago</p>
                                </div>
                                <div class="relative pl-6">
                                    <span class="absolute -left-1.5 top-1 w-3 h-3 rounded-full border-2 border-white bg-amber-500"></span>
                                    <p class="text-xs font-semibold text-slate-700">User commented on post</p>
                                    <p class="text-[10px] text-slate-500 mt-1">Shahbaz Khan commented on an AI article.</p>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mt-2">15 minutes ago</p>
                                </div>
                                <div class="relative pl-6">
                                    <span class="absolute -left-1.5 top-1 w-3 h-3 rounded-full border-2 border-white bg-emerald-500"></span>
                                    <p class="text-xs font-semibold text-slate-700">System Backup Completed</p>
                                    <p class="text-[10px] text-slate-500 mt-1">Database auto-backup finished successfully.</p>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mt-2">1 hour ago</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Commented Posts -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                        <div class="p-5 border-b border-slate-100">
                            <h3 class="font-semibold text-slate-800 text-sm">Most Discussed Posts</h3>
                        </div>
                        <div class="overflow-x-auto flex-1">
                            <table class="w-full text-left border-collapse">
                                <tbody class="text-sm divide-y divide-slate-100">
                                    <?php 
                                    if(empty($top_commented)): 
                                    ?>
                                        <tr><td colspan="2" class="px-6 py-4 text-center text-slate-400 text-xs">No comments available.</td></tr>
                                    <?php 
                                    else:
                                        foreach($top_commented as $post): 
                                            // Skip posts with 0 comments
                                            if ($post['comment_count'] == 0) continue;
                                    ?>
                                    <tr class="hover:bg-slate-50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-slate-700 text-xs truncate max-w-[200px]"><?php echo htmlspecialchars($post['title']); ?></p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-600 text-xs font-bold px-2 py-1 rounded-md">
                                                <i class="fa-regular fa-comment text-[10px]"></i> <?php echo number_format($post['comment_count']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Footer -->
            <footer class="bg-[#f4f7f6] border-t border-slate-200 py-4 px-6 md:px-8 text-xs font-medium flex justify-between mt-auto">
                <p class="text-slate-400">&copy; <?php echo date('Y'); ?> BlogAdmin Management Suite. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-slate-400 hover:text-brand-blue">Privacy Policy</a>
                    <a href="#" class="text-slate-400 hover:text-brand-blue">Terms of Service</a>
                </div>
            </footer>
        </main>
    </div>

    <!-- Chart Initializations -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Chart Defaults
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = "#94a3b8"; // slate-400
            
            // 1. Fetch Traffic Data
            fetch('ajax-get-chart-data.php?type=traffic')
                .then(res => res.json())
                .then(data => {
                    const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
                    new Chart(ctxTraffic, {
                        type: 'line',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: '#f1f5f9', borderDash: [4, 4] },
                                    border: { display: false }
                                },
                                x: {
                                    grid: { display: false },
                                    border: { display: false }
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                        }
                    });
                });

            // 2. Fetch Posts Data
            fetch('ajax-get-chart-data.php?type=posts')
                .then(res => res.json())
                .then(data => {
                    const ctxPosts = document.getElementById('postsChart').getContext('2d');
                    new Chart(ctxPosts, {
                        type: 'bar',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { display: false },
                                    border: { display: false },
                                    ticks: { display: false }
                                },
                                x: {
                                    grid: { display: false },
                                    border: { display: false }
                                }
                            }
                        }
                    });
                });
        });
    </script>
</body>
</html>
