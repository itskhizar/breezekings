<?php
include("include/classes/session.php");

if (!$session->logged_in) {
    header("Location: login.php");
    exit();
}
if ($session->userlevel < 4) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Categories | BlogAdmin</title>
    
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
                            <span class="text-slate-800">CATEGORIES</span>
                        </div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">Blog Categories</h2>
                        <p class="text-slate-500">Organize and manage the taxonomy of your content ecosystem.</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')" class="bg-brand-blue hover:bg-brand-hover text-white font-medium py-2.5 px-5 rounded shadow-sm transition-colors text-sm flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Add Category
                        </button>
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
                    $total_c = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM categories"))[0];
                    $empty_c = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM categories c WHERE (SELECT COUNT(*) FROM posts WHERE category_id = c.id AND is_deleted = 0) = 0"))[0];
                    $most_active_res = $database->query("SELECT c.category, COUNT(p.id) as count FROM categories c LEFT JOIN posts p ON c.id = p.category_id WHERE p.is_deleted = 0 GROUP BY c.id ORDER BY count DESC LIMIT 1");
                    $most_active = mysqli_fetch_assoc($most_active_res);
                    $total_p = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM posts WHERE is_deleted = 0"))[0];
                    ?>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-blue-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Categories</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $total_c; ?></h3>
                        </div>
                        <div class="text-blue-500">
                            <i class="fa-solid fa-sitemap text-xl"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-emerald-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Content</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $total_p; ?></h3>
                        </div>
                        <div class="text-emerald-500">
                            <i class="fa-solid fa-newspaper text-xl"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-amber-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Empty Categories</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $empty_c; ?></h3>
                        </div>
                        <div class="text-amber-500">
                            <i class="fa-solid fa-folder-open text-xl"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-purple-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Primary Topic</p>
                            <h3 class="text-lg font-bold text-slate-800 leading-tight truncate max-w-[120px]"><?php echo $most_active['category'] ?? 'N/A'; ?></h3>
                        </div>
                        <div class="text-purple-500">
                            <i class="fa-solid fa-arrow-trend-up text-xl"></i>
                        </div>
                    </div>

                </div>

                <!-- Main Table Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                    
                    <!-- Table Toolbar -->
                    <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white">
                        <div class="flex gap-3">
                            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium py-2 px-4 rounded flex items-center gap-2 transition-colors text-sm">
                                <i class="fa-solid fa-filter text-slate-400"></i> All Categories <i class="fa-solid fa-chevron-down text-[10px] ml-2 text-slate-400"></i>
                            </button>
                            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium py-2 px-3.5 rounded transition-colors text-sm">
                                <i class="fa-solid fa-rotate-right text-slate-400"></i>
                            </button>
                        </div>
                        <div class="text-sm text-slate-500">
                            Showing <span class="font-bold text-slate-800">1 - 5</span> of 24
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead>
                                <tr class="bg-white border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    <th class="px-6 py-4 w-16">#</th>
                                    <th class="px-6 py-4 w-48">Name</th>
                                    <th class="px-6 py-4 w-64">Slug</th>
                                    <th class="px-6 py-4">Description</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100 bg-white">
                                
                                <?php
                                $result = $database->get_all_categories_admin();
                                if ($result && mysqli_num_rows($result) > 0) {
                                    $count = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $slug = strtolower(str_replace(' ', '-', $row['category']));
                                ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-slate-400 font-mono text-xs"><?php echo str_pad($count++, 2, '0', STR_PAD_LEFT); ?></td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800 flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> <?php echo htmlspecialchars($row['category']); ?>
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="bg-slate-100 text-slate-600 font-mono text-[11px] px-2 py-1 rounded inline-block">
                                            <?php echo $slug; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-xs">
                                        Managing posts under the <?php echo htmlspecialchars($row['category']); ?> classification.
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <button onclick='openEditModal(<?php echo json_encode($row); ?>)' class="w-8 h-8 rounded bg-white border border-slate-200 text-slate-400 hover:text-brand-blue hover:border-brand-blue flex items-center justify-center transition-all shadow-sm">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>
                                            <a href="process.php?del_cat=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure? This will not delete posts in this category.')" class="w-8 h-8 rounded bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-500 flex items-center justify-center transition-all shadow-sm">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="px-6 py-10 text-center text-slate-400">No categories found. Create your first category!</td></tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-t border-slate-100 flex items-center justify-between bg-white">
                        <div class="flex gap-2">
                            <button class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors bg-white"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                            <button class="w-9 h-9 flex items-center justify-center rounded bg-brand-blue text-white font-medium text-sm">1</button>
                            <button class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium bg-white">2</button>
                            <button class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium bg-white">3</button>
                            <span class="px-1 text-slate-400 self-center">...</span>
                            <button class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium bg-white">5</button>
                            <button class="w-9 h-9 flex items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors bg-white"><i class="fa-solid fa-chevron-right text-xs"></i></button>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-500">Rows per page:</span>
                            <button class="border border-slate-200 rounded px-3 py-1.5 text-sm font-medium text-slate-700 bg-white flex items-center gap-2">
                                25 <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tip Box -->
                <div class="bg-purple-50 border border-purple-100 rounded-lg p-5 flex gap-4 items-start mb-8">
                    <div class="w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-info text-xs"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-purple-900 mb-1">Category Management Tips</h4>
                        <p class="text-sm text-purple-800 leading-relaxed">
                            Use descriptive slugs for better SEO rankings. Categories with more than 100 posts might benefit from being split into sub-categories to improve navigation.
                        </p>
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

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-900">Add New Category</h3>
                        <button onclick="document.getElementById('addCategoryModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <form action="process.php" method="POST">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Category Name</label>
                                <input type="text" name="category" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors" placeholder="e.g. Technology">
                            </div>
                        </div>
                        <div class="mt-8 flex gap-3 pb-4">
                            <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')" class="flex-1 bg-white border border-slate-200 text-slate-700 font-semibold py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-sm">Cancel</button>
                            <button type="submit" name="addcategory" class="flex-1 bg-brand-blue hover:bg-brand-hover text-white font-semibold py-2.5 rounded-lg shadow-sm transition-colors text-sm">Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div id="editCategoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-900">Edit Category</h3>
                        <button onclick="document.getElementById('editCategoryModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <form action="process.php" method="POST">
                        <input type="hidden" name="id" id="edit_cat_id">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Category Name</label>
                                <input type="text" name="category" id="edit_cat_name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors">
                            </div>
                        </div>
                        <div class="mt-8 flex gap-3 pb-4">
                            <button type="button" onclick="document.getElementById('editCategoryModal').classList.add('hidden')" class="flex-1 bg-white border border-slate-200 text-slate-700 font-semibold py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-sm">Cancel</button>
                            <button type="submit" name="edit_category" class="flex-1 bg-brand-blue hover:bg-brand-hover text-white font-semibold py-2.5 rounded-lg shadow-sm transition-colors text-sm">Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(cat) {
            document.getElementById('edit_cat_id').value = cat.id;
            document.getElementById('edit_cat_name').value = cat.category;
            document.getElementById('editCategoryModal').classList.remove('hidden');
        }
    </script>

</body>
</html>
