<?php
include("include/classes/session.php");

if (!$session->logged_in) {
    header("Location: login.php");
    exit();
}

// Toggle Featured Post handler
if (isset($_GET['toggle_featured'])) {
    $feat_id = (int)$_GET['toggle_featured'];
    if ($feat_id > 0) {
        $chk_post = mysqli_fetch_assoc($database->query("SELECT is_featured FROM posts WHERE id = $feat_id"));
        if ($chk_post) {
            $new_status = (!empty($chk_post['is_featured'])) ? 0 : 1;
            if ($new_status === 1) {
                $database->query("UPDATE posts SET is_featured = 0");
            }
            $database->query("UPDATE posts SET is_featured = $new_status WHERE id = $feat_id");
        }
        header("Location: posts.php?msg=featured_updated");
        exit();
    }
}

$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// Query posts
if ($q) {
    $result = $database->search_posts($q, $status_filter ?: NULL);
} else {
    $result = $database->get_all_posts($status_filter ?: NULL);
}

$total_results = $result ? mysqli_num_rows($result) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts | Breezekings Admin</title>
    
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
                        mono: ['DM Mono', 'monospace'],
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
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }
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
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 flex items-center gap-2">
                            <a href="dashboard.php" class="hover:text-crimson-600 transition-colors">DASHBOARD</a> 
                            <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i> 
                            <span class="text-[#0B1F3A]">ALL ARTICLES</span>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-extrabold text-[#0B1F3A] tracking-tight">Blog Posts</h2>
                        <p class="text-xs lg:text-sm text-slate-500 mt-0.5">Manage, review, edit, and view live permalinks for all your published and draft articles.</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex gap-2.5">
                        <a href="create-post.php" class="bg-[#C5202B] hover:bg-[#a31a23] text-white font-semibold py-2.5 px-5 rounded-lg shadow-sm transition-all text-xs flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Write New Article
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
                                <p class="text-xs font-semibold text-emerald-800">Operation completed successfully.</p>
                            </div>
                            <button onclick="document.getElementById('statusAlert').style.display='none'" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    <?php elseif ($_GET['msg'] == 'duplicate'): ?>
                        <?php $dup_id = isset($_GET['dup_id']) ? (int)$_GET['dup_id'] : 0; ?>
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 rounded-r flex items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                                <p class="text-xs font-semibold text-amber-800">
                                    Duplicate post prevented &mdash; a post with this title was already created in the last 60 seconds.
                                    <?php if ($dup_id): ?>
                                        <a href="edit-post.php?id=<?php echo $dup_id; ?>" class="underline ml-1">Click here to edit the existing post.</a>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <button onclick="document.getElementById('statusAlert').style.display='none'" class="text-amber-400 hover:text-amber-600 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    <?php elseif ($_GET['msg'] == 'error' || $_GET['msg'] == 'db_error'): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r flex items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                                <p class="text-xs font-semibold text-red-800">There was an error processing your request. Please check inputs and database permissions.</p>
                            </div>
                            <button onclick="document.getElementById('statusAlert').style.display='none'" class="text-red-400 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                    <?php
                    $total_p = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM posts WHERE is_deleted = 0"))[0];
                    $published_p = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM posts WHERE status = 'Published' AND is_deleted = 0"))[0];
                    $draft_p = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM posts WHERE status = 'Draft' AND is_deleted = 0"))[0];
                    $total_v = mysqli_fetch_row($database->query("SELECT SUM(views) FROM posts WHERE is_deleted = 0"))[0] ?? 0;
                    ?>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-[#0B1F3A] flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Posts</p>
                            <h3 class="text-2xl font-extrabold text-slate-800"><?php echo number_format($total_p); ?></h3>
                        </div>
                        <div class="w-10 h-10 bg-slate-100 text-[#0B1F3A] rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-newspaper text-lg"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-emerald-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Published</p>
                            <h3 class="text-2xl font-extrabold text-emerald-600"><?php echo number_format($published_p); ?></h3>
                        </div>
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-circle-check text-lg"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-amber-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Drafts</p>
                            <h3 class="text-2xl font-extrabold text-amber-600"><?php echo number_format($draft_p); ?></h3>
                        </div>
                        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-pen-ruler text-lg"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-[#C5202B] flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Views</p>
                            <h3 class="text-2xl font-extrabold text-crimson-600"><?php echo number_format($total_v); ?></h3>
                        </div>
                        <div class="w-10 h-10 bg-crimson-50 text-crimson-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-eye text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Main Table Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                    
                    <!-- Table Toolbar -->
                    <div class="p-4 border-b border-slate-100 flex flex-wrap gap-4 items-center bg-slate-50/50 justify-between">
                        <form action="posts.php" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                            <!-- Search -->
                            <div class="relative min-w-[280px]">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-search text-slate-400 text-xs"></i>
                                </div>
                                <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search title, slug, content..." class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 pl-9 text-xs text-slate-700 focus:outline-none focus:border-[#C5202B] transition-colors">
                            </div>

                            <!-- Status Dropdown Filter -->
                            <div class="relative">
                                <select name="status" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-200 rounded-lg px-3 py-2 pr-8 text-xs font-semibold text-slate-700 focus:outline-none focus:border-[#C5202B] cursor-pointer">
                                    <option value="" <?php echo empty($status_filter) ? 'selected' : ''; ?>>All Statuses (<?php echo $total_p; ?>)</option>
                                    <option value="Published" <?php echo ($status_filter == 'Published') ? 'selected' : ''; ?>>Published Only (<?php echo $published_p; ?>)</option>
                                    <option value="Draft" <?php echo ($status_filter == 'Draft') ? 'selected' : ''; ?>>Drafts Only (<?php echo $draft_p; ?>)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>

                            <?php if ($q || $status_filter): ?>
                            <a href="posts.php" class="text-xs text-slate-500 hover:text-crimson-600 font-semibold flex items-center gap-1">
                                <i class="fa-solid fa-xmark"></i> Clear Filters
                            </a>
                            <?php endif; ?>
                        </form>

                        <div class="text-xs text-slate-400 font-medium">
                            Showing <span class="font-bold text-slate-700"><?php echo $total_results; ?></span> articles
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[1000px]">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    <th class="px-5 py-3.5 w-12 text-center">ID</th>
                                    <th class="px-5 py-3.5">Article Details & Clean Permalink</th>
                                    <th class="px-5 py-3.5">Author</th>
                                    <th class="px-5 py-3.5 text-center">Category</th>
                                    <th class="px-5 py-3.5 text-center">Views</th>
                                    <th class="px-5 py-3.5 text-center">Status</th>
                                    <th class="px-5 py-3.5">Date</th>
                                    <th class="px-5 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100 bg-white">
                                
                                <?php
                                if ($result && $total_results > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $featured_image = $row['featured_image'] ? 'images/posts/'.$row['featured_image'] : 'images/blog-default.jpg';
                                        $status_class = $row['status'] == 'Published' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600';
                                        $status_dot = $row['status'] == 'Published' ? 'bg-emerald-500' : 'bg-amber-500';
                                        $post_url = bk_post_url($row);
                                ?>
                                <tr class="group hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-4 text-center text-slate-400 font-mono text-xs font-semibold">
                                        <?php echo $row['id']; ?>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3.5">
                                            <div class="relative flex-shrink-0">
                                                <img src="<?php echo $featured_image; ?>" alt="Thumbnail" class="w-14 h-11 rounded-lg object-cover border border-slate-100 shadow-sm" onerror="this.src='images/blog-default.jpg'">
                                                <?php 
                                                $is_f = !empty($row['is_featured']);
                                                $star_title = $is_f ? 'Featured post (Hero section) — click to unfeature' : 'Click to make this the Featured Hero post';
                                                $star_class = $is_f ? 'bg-amber-400 text-white shadow ring-2 ring-white' : 'bg-slate-200/80 text-slate-400 hover:bg-amber-400 hover:text-white';
                                                ?>
                                                <a href="posts.php?toggle_featured=<?php echo $row['id']; ?>" class="absolute -top-2 -right-2 w-5 h-5 <?php echo $star_class; ?> rounded-full flex items-center justify-center text-[8px] transition-all cursor-pointer z-10" title="<?php echo $star_title; ?>">
                                                    <i class="fa-solid fa-star"></i>
                                                </a>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <a href="edit-post.php?id=<?php echo $row['id']; ?>" class="font-bold text-[#0B1F3A] hover:text-[#C5202B] transition-colors line-clamp-1 text-sm block">
                                                    <?php echo htmlspecialchars($row['title']); ?>
                                                </a>
                                                <p class="text-[11px] text-slate-400 font-mono flex items-center gap-1 mt-0.5">
                                                    <i class="fa-solid fa-link text-[9px] text-slate-300"></i>
                                                    <span class="truncate"><?php echo $post_url; ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <?php 
                                        $portal_author = $row['author_name'] ?? $row['author'] ?? 'Admin';
                                        $portal_avatar = bk_avatar_url($row['author_avatar'] ?? null, $portal_author);
                                        ?>
                                        <div class="flex items-center gap-2">
                                            <img src="<?php echo $portal_avatar; ?>" alt="<?php echo htmlspecialchars($portal_author); ?>" class="w-6 h-6 rounded-full object-cover border border-slate-200" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($portal_author); ?>&background=0B1F3A&color=ffffff&bold=true&size=64';">
                                            <span class="font-medium text-xs text-slate-700"><?php echo htmlspecialchars($portal_author); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center whitespace-nowrap">
                                        <span class="bg-slate-100 text-slate-700 text-[11px] font-bold px-2.5 py-1 rounded-md">
                                            <?php echo htmlspecialchars($row['category'] ?? 'General'); ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1 text-slate-500 text-xs font-semibold">
                                            <i class="fa-regular fa-eye text-[10px] text-slate-400"></i>
                                            <span><?php echo number_format($row['views']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 <?php echo $status_class; ?> text-[11px] font-bold px-2.5 py-1 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full <?php echo $status_dot; ?>"></span> 
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-500 whitespace-nowrap">
                                        <p class="text-slate-700 font-semibold"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></p>
                                        <p class="text-[10px] text-slate-400"><?php echo date('h:i A', strtotime($row['created_at'])); ?></p>
                                    </td>
                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <?php if ($row['status'] === 'Published'): ?>
                                            <a href="<?php echo $post_url; ?>" target="_blank" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all shadow-sm" title="View Live Post">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                            </a>
                                            <?php endif; ?>
                                            
                                            <?php 
                                            $can_manage_post = ($session->userlevel >= 1 || $row['author'] == ($session->userinfo['registration_no'] ?? '') || $row['author'] == $session->username);
                                            if ($can_manage_post): 
                                            ?>
                                            <a href="edit-post.php?id=<?php echo $row['id']; ?>" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-[#0B1F3A] hover:text-white flex items-center justify-center transition-all shadow-sm" title="Edit Article">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                            <a href="process.php?del_post=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this article?')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Delete Article">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </a>
                                            <?php else: ?>
                                            <span class="text-[10px] text-slate-300 italic">No access</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="8" class="px-6 py-16 text-center text-slate-400">
                                        <i class="fa-regular fa-folder-open text-4xl mb-3 block opacity-25"></i>
                                        <p class="font-medium text-slate-600">No blog posts found matching your criteria.</p>
                                        <a href="create-post.php" class="inline-block mt-3 text-xs font-bold text-crimson-600 hover:underline">+ Create New Post</a>
                                    </td></tr>';
                                }
                                ?>

                            </tbody>
                        </table>
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
