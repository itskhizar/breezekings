<?php
include("include/classes/session.php");

if (!$session->logged_in) {
    header("Location: login.php");
    exit();
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $database->query("DELETE FROM comments WHERE id = $id");
    header("Location: admin-comments.php?msg=deleted");
    exit();
}

// Handle approval
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $database->query("UPDATE comments SET status = 'Approved' WHERE id = $id");
    header("Location: admin-comments.php?msg=approved");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments Management | BlogAdmin</title>
    
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
                
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-8">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <a href="dashboard.php" class="hover:text-brand-blue">DASHBOARD</a> 
                            <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i> 
                            <span class="text-slate-800">COMMENTS</span>
                        </div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">Manage Comments</h2>
                        <p class="text-slate-500">Review and moderate user discussions across your platform.</p>
                    </div>
                </div>
                
                <!-- Alert Messages -->
                <?php if (isset($_GET['msg'])): ?>
                    <div id="statusAlert" class="relative">
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r flex items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                <p class="text-sm text-emerald-700 font-medium">
                                    <?php 
                                    if($_GET['msg'] == 'deleted') echo "Comment deleted successfully.";
                                    if($_GET['msg'] == 'approved') echo "Comment approved and published.";
                                    ?>
                                </p>
                            </div>
                            <button onclick="document.getElementById('statusAlert').style.display='none'" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <?php
                    $total_c = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM comments"))[0];
                    $pending_c = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM comments WHERE status = 'Pending'"))[0];
                    $published_c = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM comments WHERE status = 'Approved'"))[0];
                    ?>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-blue-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Comments</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $total_c; ?></h3>
                        </div>
                        <div class="text-blue-500"><i class="fa-solid fa-comments text-xl"></i></div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-amber-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pending Approval</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $pending_c; ?></h3>
                        </div>
                        <div class="text-amber-500"><i class="fa-solid fa-clock-rotate-left text-xl"></i></div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-emerald-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Approved</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $published_c; ?></h3>
                        </div>
                        <div class="text-emerald-500"><i class="fa-solid fa-circle-check text-xl"></i></div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-purple-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Moderation Rate</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo ($total_c > 0) ? round(($published_c / $total_c) * 100) : 0; ?>%</h3>
                        </div>
                        <div class="text-purple-500"><i class="fa-solid fa-chart-pie text-xl"></i></div>
                    </div>
                </div>

                <!-- Main Table Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                    
                    <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white">
                        <div class="flex gap-3">
                            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium py-2 px-4 rounded flex items-center gap-2 transition-colors text-sm">
                                <i class="fa-solid fa-filter text-slate-400"></i> All Comments <i class="fa-solid fa-chevron-down text-[10px] ml-2 text-slate-400"></i>
                            </button>
                        </div>
                        <div class="text-sm text-slate-500">
                            Showing <span class="font-bold text-slate-800"><?php echo $total_c; ?></span> total comments
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[1000px]">
                            <thead>
                                <tr class="bg-white border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    <th class="px-6 py-4">User Details</th>
                                    <th class="px-6 py-4">Comment Content</th>
                                    <th class="px-6 py-4">On Post</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100 bg-white">
                                <?php
                                $q = "SELECT c.*, p.title as post_title FROM comments c LEFT JOIN posts p ON c.post_id = p.id ORDER BY c.created_at DESC";
                                $res = $database->query($q);
                                if ($res && mysqli_num_rows($res) > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $status_class = $row['status'] == 'Approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600';
                                        $status_dot = $row['status'] == 'Approved' ? 'bg-emerald-500' : 'bg-amber-500';
                                ?>
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-400 border border-slate-200">
                                                <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 leading-none mb-1"><?php echo htmlspecialchars($row['name']); ?></p>
                                                <p class="text-[10px] text-slate-400 font-mono"><?php echo htmlspecialchars($row['email']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-md">
                                        <p class="text-slate-600 leading-relaxed italic line-clamp-2">"<?php echo htmlspecialchars($row['comment']); ?>"</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="single.php?id=<?php echo $row['post_id']; ?>" class="text-xs font-bold text-brand-blue hover:underline truncate block max-w-[150px]">
                                            <?php echo htmlspecialchars($row['post_title'] ?? 'Deleted Post'); ?>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="<?php echo $status_class; ?> text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1.5 w-fit">
                                            <span class="w-1.5 h-1.5 rounded-full <?php echo $status_dot; ?>"></span>
                                            <?php echo strtoupper($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-slate-700 font-medium text-xs"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></p>
                                        <p class="text-[10px] text-slate-400"><?php echo date('h:i A', strtotime($row['created_at'])); ?></p>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <?php if($row['status'] == 'Pending'): ?>
                                            <a href="admin-comments.php?approve=<?php echo $row['id']; ?>" class="w-8 h-8 rounded bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Approve">
                                                <i class="fa-solid fa-check text-xs"></i>
                                            </a>
                                            <?php endif; ?>
                                            <a href="admin-comments.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this comment permanentely?')" class="w-8 h-8 rounded bg-red-50 text-red-500 border border-red-100 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm" title="Delete">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                        <i class="fa-regular fa-comment-slash text-4xl mb-3 block opacity-20"></i>
                                        No comments found to moderate.
                                    </td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
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
