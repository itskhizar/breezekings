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
    <title>Manage Admins | BlogAdmin</title>
    
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
        
        /* Toggle Switch */
        .toggle-checkbox:checked {
            right: 0;
            border-color: #0d6efd;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #0d6efd;
        }
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
                            <span class="text-slate-800">USERS</span>
                        </div>
                        <h2 class="text-3xl font-bold text-slate-900 mb-1">Administrators</h2>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button onclick="document.getElementById('addAdminModal').classList.remove('hidden')" class="bg-brand-blue hover:bg-brand-hover text-white font-medium py-2.5 px-5 rounded shadow-sm transition-colors text-sm flex items-center gap-2">
                            <i class="fa-solid fa-user-plus"></i> Add Admin
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
                    <?php elseif ($_GET['msg'] == 'self_delete'): ?>
                        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 rounded-r flex items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                                <p class="text-sm text-amber-700 font-medium">You cannot delete your own account while logged in.</p>
                            </div>
                            <button onclick="document.getElementById('statusAlert').style.display='none'" class="text-amber-400 hover:text-amber-600 transition-colors">
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
                    $total_u = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM users"))[0];
                    $super_u = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM users WHERE userlevel >= 4"))[0];
                    $admin_u = mysqli_fetch_row($database->query("SELECT COUNT(*) FROM users WHERE userlevel < 4 AND userlevel > 0"))[0];
                    ?>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-blue-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Staff</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $total_u; ?></h3>
                        </div>
                        <div class="text-blue-500"><i class="fa-solid fa-users-gear text-xl"></i></div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-purple-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Super Admins</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $super_u; ?></h3>
                        </div>
                        <div class="text-purple-500"><i class="fa-solid fa-user-shield text-xl"></i></div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-emerald-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Active Admins</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo $admin_u; ?></h3>
                        </div>
                        <div class="text-emerald-500"><i class="fa-solid fa-user-check text-xl"></i></div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 border-l-4 border-l-amber-500 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Available Seats</p>
                            <h3 class="text-2xl font-bold text-slate-800"><?php echo (20 - $total_u); ?></h3>
                        </div>
                        <div class="text-amber-500"><i class="fa-solid fa-id-badge text-xl"></i></div>
                    </div>
                </div>

                <!-- Main Table Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                    
                    <!-- Table Toolbar -->
                    <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white">
                        <div class="flex gap-3">
                            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium py-1.5 px-4 rounded text-sm flex items-center gap-2 transition-colors">
                                <i class="fa-solid fa-filter text-slate-400"></i> Filter
                            </button>
                            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium py-1.5 px-4 rounded text-sm flex items-center gap-2 transition-colors">
                                <i class="fa-solid fa-download text-slate-400"></i> Export
                            </button>
                        </div>
                        <div class="text-sm text-slate-500 font-medium">
                            Showing <span class="text-slate-800 font-bold">6</span> administrators
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[800px]">
                            <thead>
                                <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-white">
                                    <th class="px-6 py-4">Admin User</th>
                                    <th class="px-6 py-4">Role</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Last Login</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100 bg-white">
                                
                                <?php
                                $result = $database->get_all_users();
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $initials = strtoupper(substr($row['display_name'] ?? $row['username'], 0, 2));
                                        $role = ($row['userlevel'] == 4) ? 'Super Admin' : 'Admin';
                                        $role_class = ($row['userlevel'] == 4) ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600';
                                        $is_you = ($row['username'] == $session->username) ? '<span class="bg-blue-100 text-blue-700 text-[9px] px-1.5 py-0.5 rounded font-bold uppercase">YOU</span>' : '';
                                ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 text-sm">
                                                <?php echo $initials; ?>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 flex items-center gap-2"><?php echo htmlspecialchars($row['display_name'] ?? $row['username']); ?> <?php echo $is_you; ?></p>
                                                <p class="text-xs text-slate-500"><?php echo htmlspecialchars($row['email']); ?></p>
                                                <p class="text-[9px] text-brand-blue font-bold font-mono mt-0.5"><?php echo htmlspecialchars($row['registration_no']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="<?php echo $role_class; ?> text-[11px] font-bold px-3 py-1 rounded-full"><?php echo $role; ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-emerald-50 text-emerald-600 text-[11px] font-bold px-3 py-1 rounded-full">Active</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <?php 
                                            $last_login = $row['last_login'] ?? null;
                                            if ($last_login) {
                                                echo '<p class="text-slate-800 font-medium">' . date('M d, Y', strtotime($last_login)) . '</p>';
                                                echo '<p class="text-[10px] text-slate-400 font-mono mt-0.5">' . date('h:i A', strtotime($last_login)) . '</p>';
                                            } else {
                                                echo '<p class="text-slate-400 italic">Never</p>';
                                            }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <button onclick='openEditModal(<?php echo json_encode($row); ?>)' class="text-slate-400 hover:text-brand-blue transition-colors"><i class="fa-solid fa-pen"></i></button>
                                            <?php if ($row['username'] != $session->username) { ?>
                                            <a href="process.php?del_user=<?php echo urlencode($row['username']); ?>" onclick="return confirm('Are you sure you want to delete this administrator?')" class="text-slate-400 hover:text-red-500 transition-colors">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                            <?php } else { ?>
                                            <span class="text-slate-200 cursor-not-allowed" title="You cannot delete yourself"><i class="fa-solid fa-trash"></i></span>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="px-6 py-10 text-center text-slate-400">No administrators found.</td></tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4 border-t border-slate-100 flex items-center justify-between bg-white">
                        <span class="text-sm text-slate-500">Page <span class="font-bold text-slate-800">1</span> of <span class="font-bold text-slate-800">1</span></span>
                        <div class="flex gap-2">
                            <button class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors bg-white"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
                            <button class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors bg-white"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    
                    <!-- Access Roles Explain -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500">
                                <i class="fa-solid fa-shield text-lg"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">Access Roles</h3>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="relative pl-6">
                                <span class="absolute left-0 top-1.5 w-2 h-2 rounded-full bg-purple-500"></span>
                                <h4 class="font-bold text-slate-800 mb-1">Super Admin</h4>
                                <p class="text-sm text-slate-500 leading-relaxed">Full system access, manage admins, billing, and logs.</p>
                            </div>
                            <div class="relative pl-6">
                                <span class="absolute left-0 top-1.5 w-2 h-2 rounded-full bg-blue-500"></span>
                                <h4 class="font-bold text-slate-800 mb-1">Admin</h4>
                                <p class="text-sm text-slate-500 leading-relaxed">Manage posts, comments, and general users. Restricted system settings.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Invite Form -->
                    <div class="bg-brand-sidebar rounded-xl shadow-sm p-8 text-white relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-[0.02] rounded-full"></div>
                        
                        <h3 class="text-xl font-bold mb-3 relative z-10">Invite New Administrator</h3>
                        <p class="text-sm text-slate-400 leading-relaxed mb-6 relative z-10 pr-4">
                            Scale your team by sending direct invitation links. You can define their granular permissions before they even accept.
                        </p>
                        
                        <form action="#" class="flex flex-col sm:flex-row gap-3 relative z-10">
                            <input type="email" placeholder="email@address.com" class="flex-1 bg-white/5 border border-white/10 rounded-lg py-2.5 px-4 text-sm text-white focus:outline-none focus:border-brand-blue transition-colors placeholder-slate-500">
                            <button type="submit" class="bg-brand-blue hover:bg-brand-hover text-white font-medium py-2.5 px-6 rounded-lg shadow-sm transition-colors text-sm whitespace-nowrap">
                                Send Invite
                            </button>
                        </form>
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

    <!-- Add Admin Modal -->
    <div id="addAdminModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-900">Add New Administrator</h3>
                        <button onclick="document.getElementById('addAdminModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <form action="process.php" method="POST">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors" placeholder="e.g. John Doe">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Mobile Number</label>
                                <input type="text" name="mobile_no" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors" placeholder="03xxxxxxxxx">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                                <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors" placeholder="admin@domain.com">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Role Level</label>
                                <select name="userlevel" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors">
                                    <option value="1">Admin</option>
                                    <option value="4">Super Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-8 flex gap-3 pb-4">
                            <button type="button" onclick="document.getElementById('addAdminModal').classList.add('hidden')" class="flex-1 bg-white border border-slate-200 text-slate-700 font-semibold py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-sm">Cancel</button>
                            <button type="submit" name="add_admin" class="flex-1 bg-brand-blue hover:bg-brand-hover text-white font-semibold py-2.5 rounded-lg shadow-sm transition-colors text-sm">Add Administrator</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div id="editAdminModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-900">Edit Administrator</h3>
                        <button onclick="document.getElementById('editAdminModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <form action="process.php" method="POST">
                        <input type="hidden" name="username" id="edit_username">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                                <input type="text" name="name" id="edit_name" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Mobile Number</label>
                                <input type="text" name="mobile_no" id="edit_phone" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                                <input type="email" name="email" id="edit_email" required class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Role Level</label>
                                <select name="userlevel" id="edit_level" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors">
                                    <option value="1">Admin</option>
                                    <option value="4">Super Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-8 flex gap-3 pb-4">
                            <button type="button" onclick="document.getElementById('editAdminModal').classList.add('hidden')" class="flex-1 bg-white border border-slate-200 text-slate-700 font-semibold py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-sm">Cancel</button>
                            <button type="submit" name="edit_admin" class="flex-1 bg-brand-blue hover:bg-brand-hover text-white font-semibold py-2.5 rounded-lg shadow-sm transition-colors text-sm">Update Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(user) {
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_name').value = user.display_name || user.username;
            document.getElementById('edit_phone').value = user.phone || '';
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_level').value = user.userlevel;
            document.getElementById('editAdminModal').classList.remove('hidden');
        }
    </script>

</body>
</html>
