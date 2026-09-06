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
    <title>Profile Settings | Breezekings Admin</title>
    
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

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8 flex flex-col">
            <div class="max-w-[1400px] mx-auto w-full">
                
                <!-- Page Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Profile Settings</h2>
                    <p class="text-slate-500">Manage your account preferences and security credentials.</p>
                </div>

                <!-- Alert Messages -->
                <?php if (isset($_GET['msg'])): ?>
                    <div id="statusAlert" class="relative">
                    <?php if ($_GET['msg'] == 'success'): ?>
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r flex items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                <p class="text-sm text-emerald-700 font-medium">Settings updated successfully.</p>
                            </div>
                            <button onclick="document.getElementById('statusAlert').style.display='none'" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    <?php elseif ($_GET['msg'] == 'error' || $_GET['msg'] == 'db_error'): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r flex items-center justify-between gap-3 shadow-sm">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                                <p class="text-sm text-red-700 font-medium">There was an error updating your settings. Please try again.</p>
                            </div>
                            <button onclick="document.getElementById('statusAlert').style.display='none'" class="text-red-400 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    
                    <!-- Left: Personal Info -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                            <h3 class="font-semibold text-slate-800">Personal Information</h3>
                        </div>
                        <div class="p-6">
                            
                            <form action="process.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="username" value="<?php echo isset($session->username) ? $session->username : ''; ?>">
                                
                                <!-- Profile Photo -->
                                <div class="flex items-center gap-6 mb-8">
                                    <div class="relative">
                                        <?php if (!empty($session->userinfo['profile_image']) && file_exists('images/profiles/'.$session->userinfo['profile_image'])): ?>
                                            <img src="images/profiles/<?php echo $session->userinfo['profile_image']; ?>" id="profilePreview" class="w-24 h-24 rounded-xl object-cover border border-slate-200">
                                        <?php else: ?>
                                            <div id="profilePlaceholder" class="w-24 h-24 rounded-xl bg-slate-100 flex items-center justify-center text-3xl font-bold text-slate-400 border border-slate-200">
                                                <?php echo strtoupper(substr($session->userinfo['display_name'] ?? $session->username, 0, 1)); ?>
                                            </div>
                                            <img src="" id="profilePreview" class="w-24 h-24 rounded-xl object-cover border border-slate-200 hidden">
                                        <?php endif; ?>
                                        <button type="button" onclick="document.getElementById('profileInput').click()" class="absolute -bottom-2 -right-2 w-8 h-8 bg-brand-blue text-white rounded-lg flex items-center justify-center shadow-sm border-2 border-white hover:bg-brand-hover transition-colors">
                                            <i class="fa-solid fa-camera text-xs"></i>
                                        </button>
                                        <input type="file" name="profile_image" id="profileInput" class="hidden" accept="image/*" onchange="previewProfile(this)">
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800 mb-1">Profile Photo</p>
                                        <p class="text-xs text-slate-500 mb-3">JPG, GIF or PNG. Max size 2MB.</p>
                                        <div class="flex gap-3">
                                            <button type="button" onclick="document.getElementById('profileInput').click()" class="bg-brand-blue hover:bg-brand-hover text-white text-xs font-semibold py-2 px-4 rounded transition-colors shadow-sm">Upload New</button>
                                            <button type="button" class="bg-white border border-red-200 text-red-500 hover:bg-red-50 text-xs font-semibold py-2 px-4 rounded transition-colors">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    function previewProfile(input) {
                                        if (input.files && input.files[0]) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                const preview = document.getElementById('profilePreview');
                                                const placeholder = document.getElementById('profilePlaceholder');
                                                preview.src = e.target.result;
                                                preview.classList.remove('hidden');
                                                if (placeholder) placeholder.classList.add('hidden');
                                            }
                                            reader.readAsDataURL(input.files[0]);
                                        }
                                    }
                                </script>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-2">Display Name <span class="text-slate-400 font-normal normal-case">(shown on articles & author bylines)</span></label>
                                        <input type="text" name="displayname" class="w-full bg-white border border-slate-200 rounded px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors" value="<?php echo htmlspecialchars($session->userinfo['display_name'] ?? ''); ?>" placeholder="Your full name">
                                        <p class="text-[11px] text-slate-400 mt-1.5">This name appears publicly on all articles you publish.</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-2">Email Address</label>
                                        <input type="email" name="email" class="w-full bg-white border border-slate-200 rounded px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors" value="<?php echo htmlspecialchars($session->userinfo['email'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-xs font-semibold text-slate-500 mb-2">Phone</label>
                                    <input type="text" name="phone" class="w-full bg-white border border-slate-200 rounded px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors" value="<?php echo htmlspecialchars($session->userinfo['phone'] ?? ''); ?>">
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" name="changeaccountdetails" class="bg-brand-blue hover:bg-brand-hover text-white font-semibold py-2.5 px-6 rounded-lg shadow-sm transition-colors text-sm">
                                        Save Changes
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Right: Account Summary -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
                        <div class="p-6 border-b border-slate-100">
                            <h3 class="font-semibold text-slate-800">Account Summary</h3>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="space-y-5 flex-1">
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-500">Username</span>
                                    <span class="text-sm font-semibold text-slate-800"><?php echo htmlspecialchars($session->username); ?></span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-500">Reg No</span>
                                    <span class="text-sm font-mono text-slate-600"><?php echo htmlspecialchars($session->userinfo['registration_no'] ?? 'N/A'); ?></span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-500">Role</span>
                                    <span class="bg-purple-50 text-purple-600 text-[10px] font-bold px-2.5 py-1 rounded uppercase tracking-wider"><?php echo ($session->userlevel == 4) ? 'Super Admin' : 'Admin'; ?></span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-500">Joined</span>
                                    <span class="text-sm font-semibold text-slate-800"><?php echo date('M d, Y', strtotime($session->userinfo['created_at'] ?? 'now')); ?></span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-500">Status</span>
                                    <span class="inline-flex items-center gap-1.5 text-slate-800 text-sm font-medium"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 block"></span> Active</span>
                                </div>

                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                                <a href="index.php" class="text-sm font-semibold text-brand-blue hover:text-brand-hover flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-eye text-xs"></i> View Blog Home
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Security Settings -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                    <div class="p-6 border-b border-slate-100 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-lock text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800 mb-1">Security Settings</h3>
                            <p class="text-sm text-slate-500">Update your password to keep your account secure.</p>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <form action="process.php" method="POST">
                            <input type="hidden" name="username" value="<?php echo isset($session->username) ? $session->username : ''; ?>">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                                
                                <!-- Password Inputs -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-2">Current Password</label>
                                        <input type="password" name="curpass" class="w-full bg-white border border-slate-200 rounded px-4 py-2 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors" placeholder="••••••••" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-2">New Password</label>
                                        <input type="password" name="newpass" class="w-full bg-white border border-slate-200 rounded px-4 py-2 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors" placeholder="••••••••" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 mb-2">Confirm New Password</label>
                                        <input type="password" name="confpass" class="w-full bg-white border border-slate-200 rounded px-4 py-2 text-sm text-slate-800 focus:outline-none focus:border-brand-blue transition-colors" placeholder="••••••••" required>
                                    </div>
                                </div>

                                <!-- Requirements -->
                                <div class="bg-slate-50 rounded-lg p-5 border border-slate-100">
                                    <h4 class="text-sm font-semibold text-slate-800 mb-3">Password Requirements</h4>
                                    <ul class="space-y-2 text-xs mb-5">
                                        <li class="flex items-center gap-2 text-emerald-600"><i class="fa-solid fa-circle-check text-[10px]"></i> Minimum 12 characters</li>
                                        <li class="flex items-center gap-2 text-emerald-600"><i class="fa-solid fa-circle-check text-[10px]"></i> One uppercase letter</li>
                                        <li class="flex items-center gap-2 text-slate-400"><i class="fa-solid fa-circle text-[6px] ml-0.5 mr-1"></i> One special character</li>
                                        <li class="flex items-center gap-2 text-slate-400"><i class="fa-solid fa-circle text-[6px] ml-0.5 mr-1"></i> One number (0-9)</li>
                                    </ul>
                                    
                                    <div>
                                        <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest mb-2">
                                            <span class="text-slate-500">Strength</span>
                                            <span class="text-amber-500">Moderate</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5 flex gap-1">
                                            <div class="bg-emerald-500 h-1.5 rounded-full flex-1"></div>
                                            <div class="bg-amber-500 h-1.5 rounded-full flex-1"></div>
                                            <div class="bg-slate-300 h-1.5 rounded-full flex-1"></div>
                                            <div class="bg-slate-300 h-1.5 rounded-full flex-1"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <div class="flex items-center gap-2 text-[11px] text-slate-400 italic">
                                    <i class="fa-solid fa-circle-info"></i> Last changed: 3 months ago
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" class="bg-white text-slate-600 font-semibold py-2.5 px-5 rounded hover:bg-slate-50 transition-colors text-sm">Cancel</button>
                                    <button type="submit" name="changepassword" class="bg-brand-blue hover:bg-brand-hover text-white font-semibold py-2.5 px-6 rounded shadow-sm transition-colors text-sm">
                                        Update Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="bg-red-50/50 rounded-xl border border-red-100 p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h3 class="font-bold text-red-600 text-lg mb-1">Delete Account</h3>
                        <p class="text-sm text-slate-600 leading-relaxed max-w-xl">
                            Once you delete your account, there is no going back. Please be certain. All your data including posts and comments will be permanently erased.
                        </p>
                    </div>
                    <button class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 px-6 rounded shadow-sm transition-colors text-sm whitespace-nowrap shrink-0">
                        Delete Permanently
                    </button>
                </div>

            </div>
            
            <!-- Footer -->
            <footer class="bg-[#f4f7f6] border-t border-slate-200 py-4 px-6 md:px-8 text-xs font-medium flex justify-between mt-auto">
                <p class="text-slate-400">&copy; <?php echo date('Y'); ?> Breezekings Admin. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-slate-400 hover:text-brand-blue">Privacy Policy</a>
                    <a href="#" class="text-slate-400 hover:text-brand-blue">Terms of Service</a>
                </div>
            </footer>
        </main>
    </div>

</body>
</html>