<?php
include("include/classes/session.php");
include("include/analytics_queries.php");

if (!$session->logged_in) {
    header("Location: login.php");
    exit();
}

// ── Fetch all analytics data ──────────────────────────────────────────────────
$stats    = get_analytics_totals($database);
$top_viewed    = get_top_viewed_posts($database, 10);
$top_commented = get_top_commented_posts($database, 5);
$seo_health    = get_seo_health($database);
$cat_counts    = get_category_post_counts($database);
$recent_posts  = get_recent_posts($database, 6);
$recent_cmts   = get_recent_comments($database, 6);
$user_breakdown= get_user_breakdown($database);
$cmt_status    = get_comment_status_breakdown($database);

// ── Growth helpers ────────────────────────────────────────────────────────────
function growth_badge($current, $previous) {
    $pct = calc_growth($current, $previous);
    if ($pct > 0)  return "<span class='inline-flex items-center gap-0.5 text-emerald-600 text-[10px] font-bold'><i class='fa-solid fa-arrow-trend-up'></i> +{$pct}%</span>";
    if ($pct < 0)  return "<span class='inline-flex items-center gap-0.5 text-rose-500 text-[10px] font-bold'><i class='fa-solid fa-arrow-trend-down'></i> {$pct}%</span>";
    return "<span class='text-slate-400 text-[10px] font-bold'>→ No change</span>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard | BreezeKings Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { sidebar: '#151928', blue: '#6366f1', hover: '#4f46e5' },
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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background-color: rgba(255,255,255,0.05); }
        .sidebar-link.active { background-color: rgba(255,255,255,0.08); border-left: 3px solid #6366f1; }

        /* Stat card accent bars */
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 12px 12px 0 0;
        }
        .stat-card.accent-indigo::before  { background: linear-gradient(90deg, #6366f1, #818cf8); }
        .stat-card.accent-emerald::before { background: linear-gradient(90deg, #10b981, #34d399); }
        .stat-card.accent-amber::before   { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .stat-card.accent-rose::before    { background: linear-gradient(90deg, #ef4444, #f87171); }
        .stat-card.accent-blue::before    { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
        .stat-card.accent-violet::before  { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }

        /* Smooth chart card */
        .chart-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; padding: 1.5rem; }

        /* Period tab */
        .period-tab { cursor: pointer; padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; transition: all .15s; color: #64748b; }
        .period-tab.active { background: #fff; color: #1e293b; box-shadow: 0 1px 3px rgba(0,0,0,.12); }

        /* Table row hover */
        .analytics-table tr:hover td { background: #f8fafc; }

        /* SEO score ring */
        .score-ring { position: relative; display: inline-flex; align-items: center; justify-content: center; }
        .score-ring svg { transform: rotate(-90deg); }

        /* Animate in */
        @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp .4s ease forwards; }
        .delay-1 { animation-delay: .05s; opacity: 0; }
        .delay-2 { animation-delay: .1s; opacity: 0; }
        .delay-3 { animation-delay: .15s; opacity: 0; }
        .delay-4 { animation-delay: .2s; opacity: 0; }
        .delay-5 { animation-delay: .25s; opacity: 0; }
        .delay-6 { animation-delay: .3s; opacity: 0; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-slate-800 antialiased">

    <?php include 'include/admin_sidebar.php'; ?>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col h-full overflow-hidden">

        <?php include 'include/admin_header.php'; ?>

        <!-- Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-5 lg:p-7">
            <div class="max-w-[1500px] mx-auto w-full space-y-6">

                <!-- ── Page Header ──────────────────────────────────────────── -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1 flex items-center gap-2">
                            <a href="dashboard.php" class="hover:text-brand-blue transition-colors">Dashboard</a>
                            <i class="fa-solid fa-chevron-right text-[8px]"></i>
                            <span class="text-brand-blue">Analytics</span>
                        </p>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Analytics Dashboard</h1>
                        <p class="text-sm text-slate-500 mt-0.5">Real-time performance tracking across content, traffic, users & SEO.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-semibold text-slate-500">Live Data</span>
                        <a href="create-post.php" class="ml-3 bg-brand-blue hover:bg-brand-hover text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> New Post
                        </a>
                    </div>
                </div>

                <!-- ── ROW 1: KPI Stat Cards ────────────────────────────────── -->
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">

                    <!-- Total Posts -->
                    <div class="stat-card accent-indigo bg-white rounded-xl border border-slate-100 shadow-sm p-4 relative overflow-hidden fade-up delay-1">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Total<br>Posts</p>
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                                <i class="fa-solid fa-file-lines text-indigo-500 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-extrabold text-slate-900 leading-none mb-2"><?php echo number_format($stats['posts']); ?></p>
                        <?php echo growth_badge($stats['posts_this_month'], $stats['posts_last_month']); ?>
                        <p class="text-[9px] text-slate-400 mt-1">vs last month</p>
                    </div>

                    <!-- Total Views -->
                    <div class="stat-card accent-emerald bg-white rounded-xl border border-slate-100 shadow-sm p-4 relative overflow-hidden fade-up delay-2">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Total<br>Views</p>
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                                <i class="fa-regular fa-eye text-emerald-500 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-extrabold text-slate-900 leading-none mb-2"><?php echo number_format($stats['views']); ?></p>
                        <?php echo growth_badge($stats['views_this_month'], $stats['views_last_month']); ?>
                        <p class="text-[9px] text-slate-400 mt-1">vs last month</p>
                    </div>

                    <!-- Total Comments -->
                    <div class="stat-card accent-amber bg-white rounded-xl border border-slate-100 shadow-sm p-4 relative overflow-hidden fade-up delay-3">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Total<br>Comments</p>
                            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                                <i class="fa-regular fa-comments text-amber-500 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-extrabold text-slate-900 leading-none mb-2"><?php echo number_format($stats['comments']); ?></p>
                        <?php echo growth_badge($stats['comments_this_month'], $stats['comments_last_month']); ?>
                        <p class="text-[9px] text-slate-400 mt-1">vs last month</p>
                    </div>

                    <!-- Total Users -->
                    <div class="stat-card accent-blue bg-white rounded-xl border border-slate-100 shadow-sm p-4 relative overflow-hidden fade-up delay-4">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Total<br>Users</p>
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                <i class="fa-solid fa-user-group text-blue-500 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-extrabold text-slate-900 leading-none mb-2"><?php echo number_format($stats['users']); ?></p>
                        <?php echo growth_badge($stats['users_this_month'], $stats['users_last_month']); ?>
                        <p class="text-[9px] text-slate-400 mt-1">vs last month</p>
                    </div>

                    <!-- Published -->
                    <div class="stat-card accent-violet bg-white rounded-xl border border-slate-100 shadow-sm p-4 relative overflow-hidden fade-up delay-5">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Published<br>Posts</p>
                            <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                                <i class="fa-solid fa-circle-check text-violet-500 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-extrabold text-slate-900 leading-none mb-2"><?php echo number_format($stats['published']); ?></p>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                            <div class="bg-violet-500 h-1.5 rounded-full" style="width:<?php echo $stats['posts'] > 0 ? round(($stats['published']/$stats['posts'])*100) : 0; ?>%"></div>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-1"><?php echo $stats['posts'] > 0 ? round(($stats['published']/$stats['posts'])*100) : 0; ?>% of total</p>
                    </div>

                    <!-- Drafts -->
                    <div class="stat-card accent-rose bg-white rounded-xl border border-slate-100 shadow-sm p-4 relative overflow-hidden fade-up delay-6">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight">Draft<br>Posts</p>
                            <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center">
                                <i class="fa-solid fa-pen-nib text-rose-500 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-extrabold text-slate-900 leading-none mb-2"><?php echo number_format($stats['drafts']); ?></p>
                        <span class="inline-flex items-center gap-1 text-amber-600 text-[10px] font-bold">
                            <i class="fa-solid fa-clock"></i> Pending review
                        </span>
                        <p class="text-[9px] text-slate-400 mt-1"><?php echo $stats['featured']; ?> posts featured</p>
                    </div>
                </div>

                <!-- ── ROW 2: Traffic Chart + Publishing Velocity ─────────────── -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Traffic Line Chart -->
                    <div class="chart-card lg:col-span-2 flex flex-col">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h2 class="font-bold text-slate-800">Traffic Overview</h2>
                                <p class="text-xs text-slate-500">Estimated daily views trend</p>
                            </div>
                            <div class="bg-slate-100 rounded-lg p-1 flex gap-1" id="trafficTabs">
                                <button class="period-tab active" data-days="7" onclick="switchTrafficRange(7, this)">7D</button>
                                <button class="period-tab" data-days="30" onclick="switchTrafficRange(30, this)">30D</button>
                                <button class="period-tab" data-days="90" onclick="switchTrafficRange(90, this)">90D</button>
                            </div>
                        </div>
                        <div class="flex-1 min-h-[280px]">
                            <canvas id="trafficChart"></canvas>
                        </div>
                    </div>

                    <!-- Publishing Velocity -->
                    <div class="chart-card flex flex-col">
                        <div class="mb-5">
                            <h2 class="font-bold text-slate-800">Publishing Velocity</h2>
                            <p class="text-xs text-slate-500">Posts published per month (12M)</p>
                        </div>
                        <div class="flex-1 min-h-[280px]">
                            <canvas id="postsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- ── ROW 3: Category Chart + Status Donut + User Donut ──────── -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                    <!-- Posts by Category -->
                    <div class="chart-card md:col-span-2 xl:col-span-1 flex flex-col">
                        <div class="mb-5">
                            <h2 class="font-bold text-slate-800">Posts by Category</h2>
                            <p class="text-xs text-slate-500">Content distribution across categories</p>
                        </div>
                        <div class="flex-1 min-h-[220px]">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>

                    <!-- Post Status Donut -->
                    <div class="chart-card flex flex-col">
                        <div class="mb-5">
                            <h2 class="font-bold text-slate-800">Post Status</h2>
                            <p class="text-xs text-slate-500">Published vs Draft ratio</p>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-center min-h-[200px]">
                            <canvas id="statusChart" class="max-w-[180px]"></canvas>
                        </div>
                        <div class="flex items-center justify-center gap-5 mt-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                                <span class="text-xs text-slate-600 font-medium">Published <strong><?php echo $stats['published']; ?></strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                                <span class="text-xs text-slate-600 font-medium">Draft <strong><?php echo $stats['drafts']; ?></strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- User Levels Donut -->
                    <div class="chart-card flex flex-col">
                        <div class="mb-5">
                            <h2 class="font-bold text-slate-800">User Levels</h2>
                            <p class="text-xs text-slate-500">Breakdown by access level</p>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-center min-h-[200px]">
                            <canvas id="usersChart" class="max-w-[180px]"></canvas>
                        </div>
                        <div class="space-y-1.5 mt-4">
                            <?php foreach ($user_breakdown as $ub): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-600"><?php echo htmlspecialchars($ub['name']); ?></span>
                                <span class="text-xs font-bold text-slate-800"><?php echo $ub['count']; ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- ── ROW 4: Top Posts Table + SEO Health ───────────────────── -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    <!-- Top Posts Table -->
                    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden xl:col-span-2">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <h2 class="font-bold text-slate-800">Top Performing Posts</h2>
                                <p class="text-xs text-slate-500">Ranked by total views</p>
                            </div>
                            <a href="posts.php" class="text-xs text-brand-blue hover:text-brand-hover font-semibold flex items-center gap-1 transition-colors">
                                View All <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left analytics-table">
                                <thead>
                                    <tr class="border-b border-slate-100">
                                        <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">#</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Post Title</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Category</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Views</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-slate-50">
                                    <?php if (empty($top_viewed)): ?>
                                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs">No posts found.</td></tr>
                                    <?php else: $rank = 1; foreach ($top_viewed as $post): ?>
                                    <tr>
                                        <td class="px-6 py-3">
                                            <span class="<?php echo $rank <= 3 ? 'w-6 h-6 rounded-full text-white text-[10px] font-black flex items-center justify-center '.['bg-amber-400','bg-slate-400','bg-amber-700'][$rank-1] : 'text-slate-400 font-bold text-xs pl-1'; ?>">
                                                <?php echo $rank; ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 max-w-[220px]">
                                            <p class="font-semibold text-slate-700 truncate text-xs"><?php echo htmlspecialchars($post['title']); ?></p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-medium"><?php echo htmlspecialchars($post['category'] ?? '—'); ?></span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 text-xs font-bold px-2 py-0.5 rounded-md">
                                                <i class="fa-solid fa-eye text-[10px]"></i> <?php echo number_format($post['views']); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?php echo $post['status'] === 'Published' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>">
                                                <?php echo $post['status']; ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="text-slate-400 hover:text-brand-blue transition-colors" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $rank++; endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SEO Health Panel -->
                    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h2 class="font-bold text-slate-800">SEO & Blog Health</h2>
                            <p class="text-xs text-slate-500">Fix issues to improve search ranking</p>
                        </div>

                        <!-- SEO Score Ring -->
                        <div class="flex flex-col items-center py-6 border-b border-slate-100">
                            <?php $score = $seo_health['score']; $circumference = 2 * M_PI * 40; $dash = ($score / 100) * $circumference; ?>
                            <div class="score-ring w-24 h-24">
                                <svg width="96" height="96" viewBox="0 0 96 96">
                                    <circle cx="48" cy="48" r="40" fill="none" stroke="#f1f5f9" stroke-width="8"/>
                                    <circle cx="48" cy="48" r="40" fill="none"
                                        stroke="<?php echo $score >= 80 ? '#10b981' : ($score >= 50 ? '#f59e0b' : '#ef4444'); ?>"
                                        stroke-width="8"
                                        stroke-dasharray="<?php echo round($dash,2); ?> <?php echo round($circumference,2); ?>"
                                        stroke-linecap="round"/>
                                </svg>
                                <div class="absolute text-center">
                                    <p class="text-2xl font-extrabold text-slate-900"><?php echo $score; ?></p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">Score</p>
                                </div>
                            </div>
                            <p class="text-xs font-semibold mt-2 <?php echo $score >= 80 ? 'text-emerald-600' : ($score >= 50 ? 'text-amber-600' : 'text-rose-600'); ?>">
                                <?php echo $score >= 80 ? '✓ Excellent' : ($score >= 50 ? '⚠ Needs Work' : '✗ Poor'); ?>
                            </p>
                        </div>

                        <!-- Health Items -->
                        <div class="p-5 space-y-4 flex-1">
                            <?php
                            $health_items = [
                                ['icon'=>'fa-tag','label'=>'Missing Meta Title','val'=>$seo_health['missing_title'],'color'=>'rose'],
                                ['icon'=>'fa-align-left','label'=>'Missing Meta Description','val'=>$seo_health['missing_meta'],'color'=>'amber'],
                                ['icon'=>'fa-image','label'=>'Missing Featured Image','val'=>$seo_health['missing_image'],'color'=>'orange'],
                                ['icon'=>'fa-chart-line','label'=>'Low View Posts (< 10)','val'=>$seo_health['low_views'],'color'=>'blue'],
                            ];
                            foreach ($health_items as $item):
                                $ok = $item['val'] == 0;
                                $c  = $ok ? 'emerald' : $item['color'];
                            ?>
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-<?php echo $c; ?>-50 text-<?php echo $c; ?>-500 flex items-center justify-center shrink-0">
                                        <i class="fa-solid <?php echo $ok ? 'fa-check' : $item['icon']; ?> text-xs"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-700 truncate"><?php echo $item['label']; ?></p>
                                        <p class="text-[10px] text-slate-400"><?php echo $ok ? 'All clear!' : $item['val'].' posts need attention'; ?></p>
                                    </div>
                                </div>
                                <?php if (!$ok): ?>
                                <a href="posts.php" class="shrink-0 text-[10px] font-bold text-brand-blue hover:text-brand-hover transition-colors">Fix</a>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Comment Status -->
                        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/30">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Comment Moderation</p>
                            <div class="flex items-center gap-4">
                                <div class="text-center">
                                    <p class="text-lg font-extrabold text-emerald-600"><?php echo $cmt_status['approved']; ?></p>
                                    <p class="text-[9px] text-slate-400 uppercase font-bold">Approved</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-extrabold text-amber-500"><?php echo $cmt_status['pending']; ?></p>
                                    <p class="text-[9px] text-slate-400 uppercase font-bold">Pending</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-extrabold text-rose-500"><?php echo $cmt_status['spam']; ?></p>
                                    <p class="text-[9px] text-slate-400 uppercase font-bold">Spam</p>
                                </div>
                                <?php if ($cmt_status['pending'] > 0): ?>
                                <a href="admin-comments.php" class="ml-auto text-[10px] font-bold text-brand-blue hover:text-brand-hover transition-colors flex items-center gap-1">
                                    Review <i class="fa-solid fa-arrow-right text-[8px]"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── ROW 5: Recent Posts + Recent Comments ──────────────────── -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Recent Posts -->
                    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <h2 class="font-bold text-slate-800">Recent Posts</h2>
                                <p class="text-xs text-slate-500">Latest content published</p>
                            </div>
                            <a href="create-post.php" class="text-xs font-bold text-brand-blue hover:text-brand-hover transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-plus text-[10px]"></i> New
                            </a>
                        </div>
                        <div class="divide-y divide-slate-50">
                            <?php if (empty($recent_posts)): ?>
                            <div class="px-5 py-8 text-center text-slate-400 text-xs">No posts yet. <a href="create-post.php" class="text-brand-blue font-semibold">Create one!</a></div>
                            <?php else: foreach ($recent_posts as $rp): ?>
                            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-slate-50 transition-colors group">
                                <div class="w-8 h-8 rounded-lg <?php echo $rp['status'] === 'Published' ? 'bg-emerald-50' : 'bg-amber-50'; ?> flex items-center justify-center shrink-0">
                                    <i class="fa-solid <?php echo $rp['status'] === 'Published' ? 'fa-circle-check text-emerald-500' : 'fa-pen text-amber-500'; ?> text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-700 truncate"><?php echo htmlspecialchars($rp['title']); ?></p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">
                                        <?php echo htmlspecialchars($rp['category'] ?? 'Uncategorized'); ?>
                                        · <?php echo date('M d, Y', strtotime($rp['created_at'])); ?>
                                    </p>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="text-[10px] text-slate-400 flex items-center gap-1">
                                        <i class="fa-regular fa-eye"></i> <?php echo number_format($rp['views']); ?>
                                    </span>
                                    <a href="edit-post.php?id=<?php echo $rp['id']; ?>" class="opacity-0 group-hover:opacity-100 transition-opacity text-slate-400 hover:text-brand-blue">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/30">
                            <a href="posts.php" class="text-xs font-semibold text-brand-blue hover:text-brand-hover transition-colors flex items-center gap-1">
                                View all posts <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Recent Comments -->
                    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <div>
                                <h2 class="font-bold text-slate-800">Recent Comments</h2>
                                <p class="text-xs text-slate-500">Latest reader engagement</p>
                            </div>
                            <?php if ($cmt_status['pending'] > 0): ?>
                            <span class="text-[10px] font-bold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">
                                <?php echo $cmt_status['pending']; ?> pending
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="divide-y divide-slate-50">
                            <?php if (empty($recent_cmts)): ?>
                            <div class="px-5 py-8 text-center text-slate-400 text-xs">No comments yet.</div>
                            <?php else: foreach ($recent_cmts as $rc): ?>
                            <div class="px-5 py-3.5 flex items-start gap-3 hover:bg-slate-50 transition-colors">
                                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500 text-xs font-bold">
                                    <?php echo strtoupper(substr($rc['name'], 0, 1)); ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <p class="text-xs font-semibold text-slate-700"><?php echo htmlspecialchars($rc['name']); ?></p>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full <?php echo $rc['status']==='Approved' ? 'bg-emerald-50 text-emerald-600' : ($rc['status']==='Spam' ? 'bg-rose-50 text-rose-500' : 'bg-amber-50 text-amber-600'); ?>">
                                            <?php echo $rc['status']; ?>
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 truncate"><?php echo htmlspecialchars(substr($rc['comment'], 0, 80)); ?><?php echo strlen($rc['comment']) > 80 ? '...' : ''; ?></p>
                                    <p class="text-[10px] text-slate-400 mt-1">
                                        on <span class="font-medium text-slate-600 truncate"><?php echo htmlspecialchars(substr($rc['post_title'] ?? 'Deleted post', 0, 40)); ?></span>
                                        · <?php echo date('M d', strtotime($rc['created_at'])); ?>
                                    </p>
                                </div>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/30">
                            <a href="admin-comments.php" class="text-xs font-semibold text-brand-blue hover:text-brand-hover transition-colors flex items-center gap-1">
                                Manage comments <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ── ROW 6: Comments Activity Chart + Category Stats Table ─── -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Weekly Comments Chart -->
                    <div class="chart-card flex flex-col">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h2 class="font-bold text-slate-800">Comment Activity</h2>
                                <p class="text-xs text-slate-500">Daily comments (last 7 days)</p>
                            </div>
                        </div>
                        <div class="flex-1 min-h-[200px]">
                            <canvas id="commentsChart"></canvas>
                        </div>
                    </div>

                    <!-- Category Performance Table -->
                    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h2 class="font-bold text-slate-800">Category Performance</h2>
                            <p class="text-xs text-slate-500">Posts & views per category</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left analytics-table">
                                <thead>
                                    <tr class="border-b border-slate-100">
                                        <th class="px-5 py-2.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Category</th>
                                        <th class="px-4 py-2.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Posts</th>
                                        <th class="px-4 py-2.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Views</th>
                                        <th class="px-4 py-2.5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Coverage</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 text-sm">
                                    <?php
                                    $counts = !empty($cat_counts) ? array_column($cat_counts, 'post_count') : [];
                                    $max_posts = !empty($counts) ? max(1, (int)max($counts)) : 1;
                                    foreach ($cat_counts as $cat):
                                        $pct = round(($cat['post_count'] / $max_posts) * 100);
                                    ?>
                                    <tr>
                                        <td class="px-5 py-3">
                                            <p class="text-xs font-semibold text-slate-700"><?php echo htmlspecialchars($cat['category']); ?></p>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="text-xs font-bold text-slate-800"><?php echo $cat['post_count']; ?></span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="text-xs text-slate-600"><?php echo number_format($cat['total_views']); ?></span>
                                        </td>
                                        <td class="px-4 py-3 w-32">
                                            <div class="w-full bg-slate-100 rounded-full h-1.5">
                                                <div class="bg-brand-blue h-1.5 rounded-full" style="width:<?php echo $pct; ?>%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($cat_counts)): ?>
                                    <tr><td colspan="4" class="px-5 py-6 text-center text-slate-400 text-xs">No categories found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ── Charts Initialization ─────────────────────────────────────────────── -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color       = '#94a3b8';

        let trafficChartInstance = null;

        // ─ Traffic Chart ──────────────────────────────────────────────────────
        function loadTrafficChart(days) {
            fetch(`ajax-get-chart-data.php?type=traffic&range=${days}`)
                .then(r => r.json())
                .then(data => {
                    const ctx = document.getElementById('trafficChart').getContext('2d');
                    if (trafficChartInstance) trafficChartInstance.destroy();
                    trafficChartInstance = new Chart(ctx, {
                        type: 'line',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#1e293b',
                                    titleColor: '#94a3b8',
                                    bodyColor: '#fff',
                                    padding: 12,
                                    cornerRadius: 8,
                                }
                            },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#f1f5f9', borderDash:[4,4] }, border:{display:false} },
                                x: { grid: { display: false }, border:{display:false} }
                            },
                            interaction: { intersect: false, mode: 'index' }
                        }
                    });
                });
        }

        window.switchTrafficRange = function(days, btn) {
            document.querySelectorAll('#trafficTabs .period-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            loadTrafficChart(days);
        };

        loadTrafficChart(7);

        // ─ Posts per Month ────────────────────────────────────────────────────
        fetch('ajax-get-chart-data.php?type=posts&range=12')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('postsChart').getContext('2d'), {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9', borderDash:[4,4] }, border:{display:false} },
                            x: { grid: { display: false }, border:{display:false} }
                        }
                    }
                });
            });

        // ─ Posts by Category ──────────────────────────────────────────────────
        fetch('ajax-get-chart-data.php?type=categories')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('categoryChart').getContext('2d'), {
                    type: 'bar',
                    data: data,
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { beginAtZero: true, grid: { color: '#f1f5f9', borderDash:[4,4] }, border:{display:false} },
                            y: { grid: { display: false }, border:{display:false} }
                        }
                    }
                });
            });

        // ─ Post Status Donut ──────────────────────────────────────────────────
        fetch('ajax-get-chart-data.php?type=status')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('statusChart').getContext('2d'), {
                    type: 'doughnut',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '70%',
                        plugins: { legend: { display: false } }
                    }
                });
            });

        // ─ User Levels Donut ─────────────────────────────────────────────────
        fetch('ajax-get-chart-data.php?type=users')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('usersChart').getContext('2d'), {
                    type: 'doughnut',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: true,
                                position: 'right',
                                labels: { boxWidth: 10, font: { size: 10 } }
                            }
                        }
                    }
                });
            });

        // ─ Comments Activity ──────────────────────────────────────────────────
        fetch('ajax-get-chart-data.php?type=comments&range=7')
            .then(r => r.json())
            .then(data => {
                new Chart(document.getElementById('commentsChart').getContext('2d'), {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9', borderDash:[4,4] }, border:{display:false} },
                            x: { grid: { display: false }, border:{display:false} }
                        }
                    }
                });
            });
    });
    </script>
</body>
</html>
