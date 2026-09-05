<!-- Sidebar -->
<aside id="sidebar" class="fixed lg:relative w-64 bg-brand-sidebar text-white flex flex-col h-full shrink-0 z-40 transition-transform duration-300 -translate-x-full lg:translate-x-0">
    <style>
        .sidebar-link.active { background: linear-gradient(to right, rgba(13, 110, 253, 0.15), transparent); border-left: 4px solid #0d6efd; }
        .sidebar-link.active i { color: #0d6efd !important; }
    </style>
    <!-- Logo Area -->
    <div class="h-20 flex items-center px-6 border-b border-white/10 shrink-0">
        <a href="dashboard.php" class="flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-layer-group text-sm"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight tracking-wide">BlogAdmin</h1>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Management Suite</p>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            <li>
                <a href="dashboard.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active text-white' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-border-all text-slate-400 w-5"></i> Overview
                </a>
            </li>
            <li>
                <a href="analytics_dashboard.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'analytics_dashboard.php') ? 'active text-white' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-chart-line text-slate-400 w-5"></i> Analytics
                </a>
            </li>
            <li>
                <a href="posts.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'posts.php' || basename($_SERVER['PHP_SELF']) == 'create-post.php') ? 'active text-white' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-file-lines text-slate-400 w-5"></i> Posts
                </a>
            </li>
            <?php if($session->userlevel >= 4): ?>
            <li>
                <a href="admin-categories.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-categories.php') ? 'active text-white' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-tags text-slate-400 w-5"></i> Categories
                </a>
            </li>
            <?php endif; ?>
            <li>
                <a href="admin-comments.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-comments.php') ? 'active text-white' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-regular fa-comments text-slate-400 w-5"></i> Comments
                </a>
            </li>
            <?php if($session->userlevel >= 4): ?>
            <li>
                <a href="admin-users.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-users.php') ? 'active text-white' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-users text-slate-400 w-5"></i> Users
                </a>
            </li>
            <?php endif; ?>
            <li class="pt-6 pb-2 px-6">
                <div class="uppercase text-[10px] font-bold text-slate-500 tracking-widest">Administration</div>
            </li>
            <li>
                <a href="settings.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active text-white' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-gear text-slate-400 w-5"></i> Settings
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-white/10">
        <a href="process.php?logout=1" class="flex items-center gap-3 px-4 py-2 text-sm font-medium text-slate-400 hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>
