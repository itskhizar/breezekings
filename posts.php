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
    <title>All Posts | BlogAdmin</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', "Liberation Mono", "Courier New", 'monospace'],
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

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8 flex flex-col">
            <div class="max-w-[1400px] mx-auto w-full">
                
                <!-- Page Header & Actions -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <a href="dashboard.php" class="hover:text-brand-blue">DASHBOARD</a> 
                            <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i> 
                            <span class="text-slate-800">POSTS</span>
                        </div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-1">Blog Posts</h2>
                        <p class="text-slate-500">Manage, edit and track the performance of your articles.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="create-post.php" class="bg-brand-blue hover:bg-brand-hover text-white font-medium py-2.5 px-5 rounded shadow-sm transition-colors text-sm flex items-center gap-2 inline-block">
                            <i class="fa-solid fa-plus"></i> Create New Post
                        </a>
                    </div>
                </div>

                <!-- Alert Messages -->
                <?php if (isset($_GET['msg'])): ?>
                    <div id="statusAlert" class="relative">
                    <?php if ($_GET['msg'] == 'success' || $_GET['msg'] == 'deleted'): ?>
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r flex items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                <p class="text-sm text-emerald-700 font-medium">Operation completed successfully.</p>
                            </div>
                            <button onclick="document.getElementById('statusAlert').style.display='none'" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    <?php elseif ($_GET['msg'] == 'error' || $_GET['msg'] == 'db_error'): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r flex items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                                <p class="text-sm text-red-700 font-medium">There was an error processing your request.</p>
                            </div>
                            <button onclick="document.getElementById('statusAlert').style.display='none'" class="text-red-400 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <?php
                    $total_p = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM posts WHERE is_deleted = 0"))[0];
                    $published_p = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM posts WHERE status = 'Published' AND is_deleted = 0"))[0];
                    $featured_p = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM posts WHERE is_featured = 1 AND is_deleted = 0"))[0];
                    $total_v = mysqli_fetch_row($database->query("SELECT SUM(views) FROM posts WHERE is_deleted = 0"))[0] ?? 0;
                    ?>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-blue-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Posts</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $total_p; ?></h3>
                        </div>
                        <div class="text-blue-500"><i class="fa-solid fa-newspaper text-xl"></i></div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-emerald-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Published</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $published_p; ?></h3>
                        </div>
                        <div class="text-emerald-500"><i class="fa-solid fa-circle-check text-xl"></i></div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-amber-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Featured</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $featured_p; ?></h3>
                        </div>
                        <div class="text-amber-500"><i class="fa-solid fa-star text-xl"></i></div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-purple-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Reach</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo number_format($total_v); ?></h3>
                        </div>
                        <div class="text-purple-500"><i class="fa-solid fa-eye text-xl"></i></div>
                    </div>
                </div>

                <!-- Main Table Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                    
                    <!-- Table Toolbar -->
                    <div class="p-4 border-b border-slate-100 flex flex-wrap gap-4 items-center bg-white justify-between">
                        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                            <!-- Search -->
                            <form action="posts.php" method="GET" class="relative min-w-[300px]">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-search text-slate-400 text-sm"></i>
                                </div>
                                <input type="text" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" placeholder="Filter by title, slug or author..." class="w-full bg-white border border-slate-200 rounded px-3 py-2 pl-9 text-sm text-slate-700 focus:outline-none focus:border-brand-blue transition-colors">
                            </form>

                            <!-- Status Dropdown -->
                            <div class="relative">
                                <select class="appearance-none bg-white border border-slate-200 rounded px-4 py-2 pr-10 text-sm text-slate-700 focus:outline-none focus:border-brand-blue cursor-pointer">
                                    <option>All Status</option>
                                    <option>Published</option>
                                    <option>Draft</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium py-2 px-4 rounded text-sm flex items-center gap-2 transition-colors">
                                <i class="fa-solid fa-file-export text-slate-400"></i> Export
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[1200px]">
                            <thead>
                                <tr class="bg-white border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    <th class="px-6 py-4 w-12 text-center">#</th>
                                    <th class="px-6 py-4">Post Details</th>
                                    <th class="px-6 py-4">Author</th>
                                    <th class="px-6 py-4 text-center">Category</th>
                                    <th class="px-6 py-4 text-center">Stats</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100 bg-white">
                                
                                <?php
                                $q = isset($_GET['q']) ? $_GET['q'] : '';
                                if ($q) {
                                    $result = $database->search_posts($q, NULL); // Search all statuses
                                } else {
                                    $result = $database->get_all_posts();
                                }
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $featured_image = $row['featured_image'] ? 'images/posts/'.$row['featured_image'] : 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=100&q=80';
                                        $status_class = $row['status'] == 'Published' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600';
                                        $status_dot = $row['status'] == 'Published' ? 'bg-emerald-500' : 'bg-amber-500';
                                ?>
                                <tr class="group hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs"><?php echo $row['id']; ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                <img src="<?php echo $featured_image; ?>" alt="Thumbnail" class="w-16 h-12 rounded object-cover border border-slate-100 shadow-sm">
                                                <?php if ($row['is_featured']): ?>
                                                    <div class="absolute -top-2 -right-2 w-5 h-5 bg-amber-400 text-white rounded-full flex items-center justify-center text-[8px] border-2 border-white shadow-sm" title="Featured Post">
                                                        <i class="fa-solid fa-star"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 mb-0.5"><?php echo htmlspecialchars($row['title']); ?></p>
                                                <p class="text-[10px] text-slate-400 font-mono">/<?php echo $row['slug'] ?: strtolower(str_replace(' ', '-', $row['title'])); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-200">
                                                <?php echo strtoupper(substr($row['author_name'] ?? $row['author'], 0, 1)); ?>
                                            </div>
                                            <p class="font-medium text-slate-700 leading-tight"><?php echo htmlspecialchars($row['author_name'] ?? $row['author']); ?></p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-blue-50 text-brand-blue text-[11px] font-bold px-2.5 py-1 rounded"><?php echo htmlspecialchars($row['category'] ?? 'Uncategorized'); ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-3 text-slate-400">
                                            <div class="flex items-center gap-1" title="Views">
                                                <i class="fa-regular fa-eye text-[10px]"></i>
                                                <span class="text-xs font-medium"><?php echo number_format($row['views']); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 <?php echo $status_class; ?> text-[11px] font-bold px-2.5 py-1 rounded-full"><span class="w-1.5 h-1.5 rounded-full <?php echo $status_dot; ?> block"></span> <?php echo $row['status']; ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500">
                                        <p class="text-slate-700 font-medium"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></p>
                                        <p class="text-[10px]"><?php echo date('h:i A', strtotime($row['created_at'])); ?></p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <?php if ($session->userlevel >= 4 || $row['author'] == $session->userinfo['registration_no']): ?>
                                            <a href="edit-post.php?id=<?php echo $row['id']; ?>" class="w-8 h-8 rounded bg-white border border-slate-200 text-slate-400 hover:text-brand-blue hover:border-brand-blue flex items-center justify-center transition-all shadow-sm" title="Edit Post">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                            <a href="process.php?del_post=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?')" class="w-8 h-8 rounded bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-500 flex items-center justify-center transition-all shadow-sm" title="Delete Post">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </a>
                                            <?php else: ?>
                                            <span class="text-[10px] text-slate-300 italic">No permission</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="8" class="px-6 py-16 text-center text-slate-400">
                                        <i class="fa-regular fa-folder-open text-4xl mb-3 block opacity-20"></i>
                                        <p>No posts found. Create your first post!</p>
                                    </td></tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <span class="text-xs text-slate-500">Showing <span class="font-bold text-slate-800"><?php echo mysqli_num_rows($result); ?></span> results</span>
                        <div class="flex gap-1.5">
                            <button disabled class="px-3 py-1.5 flex items-center justify-center rounded border border-slate-200 text-slate-300 bg-white cursor-not-allowed text-xs font-medium"><i class="fa-solid fa-chevron-left mr-1.5"></i> Previous</button>
                            <button class="px-3 py-1.5 flex items-center justify-center rounded border border-brand-blue bg-brand-blue text-white text-xs font-bold">1</button>
                            <button class="px-3 py-1.5 flex items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-slate-50 bg-white text-xs font-medium">Next <i class="fa-solid fa-chevron-right ml-1.5"></i></button>
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
