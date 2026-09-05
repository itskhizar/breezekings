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
    <title>Add Admin | BlogAdmin</title>
    
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

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
            <div class="max-w-[800px] mx-auto">
                
                <?php if (isset($_GET['msg'])): ?>
                    <?php if ($_GET['msg'] == 'success'): ?>
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            <p class="text-sm text-emerald-700 font-medium">Administrator added successfully.</p>
                        </div>
                    <?php elseif ($_GET['msg'] == 'error' || $_GET['msg'] == 'db_error'): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r flex items-center gap-3">
                            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                            <p class="text-sm text-red-700 font-medium">There was an error adding the administrator. Please check your inputs.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Page Header -->
                <div class="mb-8">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                        <a href="dashboard.php" class="hover:text-brand-blue">DASHBOARD</a> 
                        <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
                        <a href="admin-users.php" class="hover:text-brand-blue">USERS</a> 
                        <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i> 
                        <span class="text-slate-800">ADD ADMIN</span>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Create Administrator</h2>
                    <p class="text-slate-500">Add a new admin to the system and grant them management access.</p>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="font-semibold text-slate-800">Admin Details</h3>
                    </div>
                    <div class="p-6">
                        <form action="process.php" method="post" enctype="multipart/form-data" class="space-y-6">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-colors" placeholder="e.g. John Doe" value="<?php if(isset($form)) echo $form->value("name"); ?>" required>
                                    <?php if(isset($form) && $form->error("name")) echo '<p class="text-red-500 text-xs mt-1">'.$form->error("name").'</p>'; ?>
                                </div>

                                <!-- Mobile -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Mobile Number <span class="text-red-500">*</span></label>
                                    <input type="text" name="mobile_no" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-colors" placeholder="03xxxxxxxxx" value="<?php if(isset($form)) echo $form->value("mobile_no"); ?>" required>
                                    <?php if(isset($form) && $form->error("mobile_no")) echo '<p class="text-red-500 text-xs mt-1">'.$form->error("mobile_no").'</p>'; ?>
                                </div>

                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-colors" placeholder="admin@domain.com" value="<?php if(isset($form)) echo $form->value("email"); ?>" required>
                                    <?php if(isset($form) && $form->error("email")) echo '<p class="text-red-500 text-xs mt-1">'.$form->error("email").'</p>'; ?>
                                </div>

                            </div>

                            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                                <a href="admin-users.php" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-medium py-2.5 px-6 rounded-lg transition-colors text-sm">Cancel</a>
                                <button type="submit" name="add_admin" class="bg-brand-blue hover:bg-brand-hover text-white font-medium py-2.5 px-6 rounded-lg shadow-sm transition-colors text-sm">
                                    Save Administrator
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>