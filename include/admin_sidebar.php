<!-- Sidebar -->
<aside id="sidebar" class="fixed lg:relative w-64 bg-[#0B1F3A] text-white flex flex-col h-full shrink-0 z-40 transition-transform duration-300 -translate-x-full lg:translate-x-0 border-r border-white/5">
    <style>
        .sidebar-link { transition: all 0.2s ease; border-left: 3px solid transparent; }
        .sidebar-link:hover { background-color: rgba(255, 255, 255, 0.06); color: #ffffff; }
        .sidebar-link.active { 
            background: linear-gradient(to right, rgba(197, 32, 43, 0.28), rgba(197, 32, 43, 0.04)) !important; 
            border-left: 4px solid #C5202B !important; 
            color: #ffffff !important; 
            font-weight: 600 !important; 
        }
        .sidebar-link.active i { color: #f87171 !important; }
    </style>
    
    <!-- Logo Area -->
    <div class="h-20 flex items-center px-6 border-b border-white/10 shrink-0">
        <a href="dashboard.php" class="flex items-center gap-3">
            <svg class="w-9 h-9 flex-shrink-0 shadow-md rounded-lg" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Breezekings Logo">
                <rect width="44" height="44" rx="10" fill="#0d1f3b"/>
                <path d="M12 9H22.5C25.5 9 27.5 10.5 27.5 13C27.5 14.8 26.2 16.2 24.5 16.8C26.8 17.4 28.5 19 28.5 21.5C28.5 24.5 26 26.5 22.5 26.5H12V9ZM16.5 13V16.2H21.5C22.8 16.2 23.5 15.5 23.5 14.6C23.5 13.7 22.8 13 21.5 13H16.5ZM16.5 19.3V22.5H22C23.5 22.5 24.3 21.7 24.3 20.9C24.3 20 23.5 19.3 22 19.3H16.5Z" fill="#ffffff"/>
                <path d="M22 19L29 11H34.5L26.5 20L35 31H29.5L23.5 23" fill="#C8102E"/>
            </svg>
            <div>
                <span class="font-black text-lg tracking-tight text-white font-sans">Breeze<span class="text-[#C8102E]">kings</span></span>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Admin Portal</p>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            <li>
                <a href="dashboard.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-border-all text-slate-400 w-5"></i> Overview
                </a>
            </li>
            <li>
                <a href="posts.php" class="sidebar-link <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['posts.php', 'edit-post.php'])) ? 'active' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-file-lines text-slate-400 w-5"></i> Blog Posts
                </a>
            </li>
            <li>
                <a href="create-post.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'create-post.php') ? 'active' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-pen-nib text-slate-400 w-5"></i> Write New Post
                </a>
            </li>
            <li>
                <a href="admin-categories.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-categories.php') ? 'active' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-tags text-slate-400 w-5"></i> Categories
                </a>
            </li>
            <?php if($session->userlevel >= 4): ?>
            <li>
                <a href="admin-users.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-users.php') ? 'active' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-users-gear text-slate-400 w-5"></i> Admin Users
                </a>
            </li>
            <?php endif; ?>
            <li>
                <a href="admin-comments.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-comments.php') ? 'active' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-regular fa-comments text-slate-400 w-5"></i> Comments
                </a>
            </li>

            <li class="pt-6 pb-2 px-6">
                <div class="uppercase text-[10px] font-bold text-slate-400 tracking-widest">Public Site</div>
            </li>
            <li>
                <a href="/" target="_blank" class="sidebar-link text-slate-300 flex items-center justify-between px-6 py-3 text-sm font-medium group">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-globe text-slate-400 w-5 group-hover:text-crimson-400"></i> View Live Site
                    </span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-500 group-hover:text-white"></i>
                </a>
            </li>
            <li>
                <a href="/sitemap.xml" target="_blank" class="sidebar-link text-slate-300 flex items-center justify-between px-6 py-3 text-sm font-medium group">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-sitemap text-slate-400 w-5 group-hover:text-crimson-400"></i> XML Sitemap
                    </span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-500 group-hover:text-white"></i>
                </a>
            </li>

            <li class="pt-4 pb-2 px-6">
                <div class="uppercase text-[10px] font-bold text-slate-400 tracking-widest">Configuration</div>
            </li>
            <li>
                <a href="settings.php" class="sidebar-link <?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : 'text-slate-300'; ?> flex items-center gap-3 px-6 py-3 text-sm font-medium">
                    <i class="fa-solid fa-gear text-slate-400 w-5"></i> Settings
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-white/10">
        <a href="process.php?logout=1" class="flex items-center gap-3 px-4 py-2 text-sm font-medium text-slate-400 hover:text-red-400 hover:bg-white/5 rounded-lg transition-colors">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>
