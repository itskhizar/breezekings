<!-- Topbar -->
<header class="h-16 bg-white flex items-center justify-between px-6 shrink-0 shadow-sm z-10 relative">
    <div class="flex items-center gap-4">
        <!-- Sidebar Toggle (Mobile) -->
        <button id="sidebarToggle" class="lg:hidden text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>

        <!-- Search (Desktop) -->
        <form action="posts.php" method="GET" class="hidden md:block w-96 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-search text-slate-400 text-sm"></i>
            </div>
            <input type="text" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" placeholder="Search posts, users..." class="w-full bg-slate-50 border-none rounded-full py-2 pl-10 pr-4 text-sm text-slate-700 focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all placeholder-slate-400">
        </form>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-6">
        <button class="text-slate-400 hover:text-slate-600 transition-colors relative">
            <i class="fa-solid fa-bell"></i>
            <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
        </button>
        
        <div class="h-8 w-px bg-slate-200"></div>
        
        <!-- User Profile Dropdown -->
        <div class="relative">
                <!-- User Profile -->
                <div id="userMenuBtn" class="flex items-center gap-4 cursor-pointer">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-800 leading-none mb-1"><?php echo htmlspecialchars(!empty($session->userinfo['display_name']) ? $session->userinfo['display_name'] : $session->username); ?></p>
                        <span class="text-[10px] font-bold text-brand-blue uppercase tracking-widest"><?php echo ($session->userlevel == 4) ? 'Super Admin' : 'Admin'; ?></span>
                    </div>
                    <div class="relative group">
                        <img src="<?php echo (!empty($session->userinfo['profile_image']) && file_exists('images/profiles/'.$session->userinfo['profile_image'])) ? 'images/profiles/'.$session->userinfo['profile_image'] : 'images/avatar.png'; ?>" alt="Avatar" class="w-10 h-10 rounded-lg border border-slate-200 group-hover:border-brand-blue transition-colors object-cover shadow-sm">
                        <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full"></div>
                    </div>
                </div>

            <!-- Dropdown Menu -->
            <div id="userDropdown" class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-2 hidden z-50">
                <div class="px-4 py-2 border-b border-slate-50 mb-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Account</p>
                </div>
                <a href="settings.php" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition-colors">
                    <i class="fa-solid fa-user-gear w-4 text-center"></i> Profile Settings
                </a>
                <a href="settings.php" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-brand-blue transition-colors">
                    <i class="fa-solid fa-shield-halved w-4 text-center"></i> Security
                </a>
                <div class="h-px bg-slate-100 my-1"></div>
                <a href="process.php?logout=1" class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    // Sidebar Toggle Logic
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-navy-950/50 z-30 hidden lg:hidden';
    document.body.appendChild(overlay);

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    }

    // Toggle User Dropdown
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            userDropdown.classList.add('hidden');
        });

        userDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
</script>
