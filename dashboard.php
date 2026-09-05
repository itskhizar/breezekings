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
    <title>Dashboard | BlogAdmin</title>
    
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
                
                <!-- Page Header -->
                <div class="mb-8">
                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-2 flex items-center gap-2">
                        <span>HOME</span> <span class="text-slate-300">/</span> <span class="text-slate-600">DASHBOARD</span>
                    </div>
                    <h2 class="text-2xl font-semibold text-slate-800 mb-1">Welcome back, <?php echo htmlspecialchars($session->userinfo['display_name'] ?? $session->username); ?> <span class="text-xl">👋</span></h2>
                    <p class="text-sm text-slate-500">System state is currently optimal. No critical alerts found.</p>
                </div>

                <?php
                // Fetch stats
                $total_posts_res = $database->query("SELECT COUNT(*) as count FROM posts");
                $total_posts = mysqli_fetch_assoc($total_posts_res)['count'];

                $total_users_res = $database->query("SELECT COUNT(*) as count FROM users");
                $total_users = mysqli_fetch_assoc($total_users_res)['count'];

                $total_cats_res = $database->query("SELECT COUNT(*) as count FROM categories");
                $total_categories = mysqli_fetch_assoc($total_cats_res)['count'];

                $total_drafts_res = $database->query("SELECT COUNT(*) as count FROM posts WHERE status = 'Draft'");
                $total_drafts = mysqli_fetch_assoc($total_drafts_res)['count'];
                ?>

                <!-- Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    
                    <!-- Card 1 -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-blue-500 relative overflow-hidden">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Posts</p>
                                <h3 class="text-3xl font-bold text-slate-800"><?php echo number_format($total_posts); ?></h3>
                            </div>
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                                <i class="fa-solid fa-file-lines text-lg"></i>
                            </div>
                        </div>
                        <div class="text-xs font-medium flex items-center gap-1 text-slate-400">
                            <span class="text-emerald-500 flex items-center gap-1"><i class="fa-solid fa-check"></i> System</span> Online
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-purple-500 relative overflow-hidden">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Registered Admins</p>
                                <h3 class="text-3xl font-bold text-slate-800"><?php echo number_format($total_users); ?></h3>
                            </div>
                            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center text-purple-500">
                                <i class="fa-solid fa-shield-halved text-lg"></i>
                            </div>
                        </div>
                        <div class="text-xs font-medium flex items-center gap-1 text-slate-400">
                            <span class="text-slate-600 font-semibold">Active</span> Access Control
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-emerald-500 relative overflow-hidden">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Categories</p>
                                <h3 class="text-3xl font-bold text-slate-800"><?php echo number_format($total_categories); ?></h3>
                            </div>
                            <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500">
                                <i class="fa-solid fa-layer-group text-lg"></i>
                            </div>
                        </div>
                        <div class="text-xs font-medium flex items-center gap-1 text-slate-400">
                            <span class="text-emerald-500 flex items-center gap-1"><i class="fa-solid fa-circle-plus text-[10px]"></i> Structuring</span> content
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-amber-500 relative overflow-hidden">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Draft Posts</p>
                                <h3 class="text-3xl font-bold text-slate-800"><?php echo number_format($total_drafts); ?></h3>
                            </div>
                            <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center text-amber-500">
                                <i class="fa-solid fa-pen-nib text-lg"></i>
                            </div>
                        </div>
                        <div class="text-xs font-medium flex items-center gap-1 text-slate-400">
                            <span class="text-amber-600 flex items-center gap-1"><i class="fa-solid fa-clock"></i> Pending</span> Review
                        </div>
                    </div>

                </div>

                <!-- Action Shortcuts Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                    <a href="create-post.php" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-brand-blue hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">New Post</span>
                    </a>
                    <a href="posts.php" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-brand-blue hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Manage Posts</span>
                    </a>
                    <a href="admin-categories.php" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-brand-blue hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Categories</span>
                    </a>
                    <a href="admin-users.php" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-brand-blue hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center group-hover:bg-purple-500 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-user-group"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Admins</span>
                    </a>
                    <a href="settings.php" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-brand-blue hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-gears"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Settings</span>
                    </a>
                    <a href="index.php" target="_blank" class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-2 hover:border-brand-blue hover:shadow-md transition-all group">
                        <div class="w-10 h-10 rounded-full bg-slate-50 text-slate-500 flex items-center justify-center group-hover:bg-slate-800 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Live Site</span>
                    </a>
                </div>

                <!-- Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    
                    <!-- Left: Recent Posts Table -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-semibold text-slate-800">Recent Activity</h3>
                            <a href="posts.php" class="text-xs font-semibold text-brand-blue hover:text-brand-hover">View All Posts</a>
                        </div>
                        
                        <div class="overflow-x-auto flex-1">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        <th class="px-6 py-3">Post Details</th>
                                        <th class="px-6 py-3">Category</th>
                                        <th class="px-6 py-3">Status</th>
                                        <th class="px-6 py-3">Date</th>
                                        <th class="px-6 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm divide-y divide-slate-100">
                                    
                                    <?php
                                    $recent_posts = $database->query("SELECT p.*, c.category FROM posts p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 6");
                                    if ($recent_posts && mysqli_num_rows($recent_posts) > 0) {
                                        while ($post = mysqli_fetch_assoc($recent_posts)) {
                                            $status_class = ($post['status'] == 'Published') ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600';
                                            $status_dot = ($post['status'] == 'Published') ? 'bg-emerald-500' : 'bg-amber-500';
                                    ?>
                                    <tr class="hover:bg-slate-50 transition-colors cursor-pointer group" onclick="window.location='single.php?id=<?php echo $post['id']; ?>'">
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-slate-800 mb-0.5 group-hover:text-brand-blue transition-colors"><?php echo htmlspecialchars($post['title']); ?></p>
                                            <p class="text-[10px] text-slate-500">by <?php echo htmlspecialchars($post['author']); ?></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider"><?php echo htmlspecialchars($post['category'] ?? 'Uncategorized'); ?></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1.5 <?php echo $status_class; ?> text-[11px] font-bold px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 rounded-full <?php echo $status_dot; ?> block"></span> <?php echo $post['status']; ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-500">
                                            <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="single.php?id=<?php echo $post['id']; ?>" class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="View Post">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                </a>
                                                <a href="create-post.php?edit=<?php echo $post['id']; ?>" class="w-8 h-8 rounded bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-all shadow-sm" title="Edit Post">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">No recent posts found.</td></tr>';
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-center mt-auto">
                            <a href="posts.php" class="text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-700 transition-colors">VIEW ALL CONTENT</a>
                        </div>
                    </div>

                    <!-- Right: Distribution -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col h-full">
                        <div class="mb-6">
                            <h3 class="font-semibold text-slate-800 mb-1">Content Distribution</h3>
                            <p class="text-xs text-slate-500">Posts per category</p>
                        </div>

                        <div class="space-y-5 flex-1">
                            
                            <?php
                            $cat_dist = $database->query("SELECT c.category, COUNT(p.id) as count FROM categories c LEFT JOIN posts p ON c.id = p.category_id GROUP BY c.id ORDER BY count DESC LIMIT 8");
                            $colors = ['bg-blue-500', 'bg-purple-500', 'bg-emerald-500', 'bg-amber-500', 'bg-pink-500', 'bg-indigo-500'];
                            $i = 0;
                            if ($cat_dist && mysqli_num_rows($cat_dist) > 0) {
                                while ($cat = mysqli_fetch_assoc($cat_dist)) {
                                    $percent = ($total_posts > 0) ? ($cat['count'] / $total_posts) * 100 : 0;
                                    $color = $colors[$i % count($colors)];
                            ?>
                            <div>
                                <div class="flex justify-between text-xs font-bold uppercase tracking-widest mb-2">
                                    <span class="text-slate-600"><?php echo htmlspecialchars($cat['category']); ?></span>
                                    <span class="text-slate-800"><?php echo $cat['count']; ?></span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="<?php echo $color; ?> h-1.5 rounded-full" style="width: <?php echo $percent; ?>%"></div>
                                </div>
                            </div>
                            <?php
                                    $i++;
                                }
                            } else {
                                echo '<p class="text-xs text-slate-400">No categories found.</p>';
                            }
                            ?>

                        </div>

                        <!-- Tip Box -->
                        <div class="mt-8 bg-blue-50/50 border border-blue-100 rounded-lg p-4 flex gap-3 items-start">
                            <div class="w-5 h-5 rounded-full bg-brand-blue text-white flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-info text-[10px]"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-widest mb-1">QUICK TIP</h4>
                                <p class="text-xs text-blue-800 leading-relaxed">
                                    You have <?php echo $total_drafts; ?> drafts pending. Review them to keep your content pipeline active and healthy.
                                </p>
                            </div>
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

</body>
</html>