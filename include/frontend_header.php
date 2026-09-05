<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<!-- Frontend Navigation -->
<nav class="bg-navy-900 text-white w-full z-50 sticky top-0 shadow-xl border-b border-navy-800">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <!-- Logo -->
            <a href="index.php" class="flex items-center gap-3 group">
                <div
                    class="w-10 h-10 bg-crimson-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-feather-pointed text-white text-xl"></i>
                </div>
                <div>
                    <span class="text-2xl font-serif font-bold text-white tracking-tight">The Blog<span
                            class="text-crimson-500 text-3xl leading-none">.</span></span>
                </div>
            </a>

            <!-- Navigation -->
            <div class="hidden lg:flex items-center space-x-10">
                <?php if ($current_page != 'index.php'): ?>
                    <a href="index.php"
                        class="text-[13px] font-bold text-white/80 hover:text-white uppercase tracking-widest">Home</a>
                <?php endif; ?>
                <?php
                $nav_cats = $database->get_all_categories();
                $count = 0;
                while ($cat = mysqli_fetch_assoc($nav_cats)) {
                    if ($count >= 5)
                        break;
                    $is_active = (isset($cat_id) && $cat_id == $cat['id']);
                    $active_classes = $is_active ? 'text-white border-b-2 border-crimson-600 pb-1' : 'text-white/80 hover:text-white';
                    echo "<a href='category.php?id={$cat['id']}' class='text-[13px] font-bold $active_classes uppercase tracking-widest'>{$cat['category']}</a>";
                    $count++;
                }
                ?>
            </div>

            <!-- Right Actions -->
            <div class="hidden md:flex items-center space-x-6">
                <form action="index.php" method="GET" class="relative group">
                    <input type="text" name="q"
                        value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>"
                        placeholder="Search archive..."
                        class="bg-navy-800 text-white text-xs py-2 px-4 pr-10 rounded-full border border-navy-700 focus:outline-none focus:border-crimson-500 w-32 group-hover:w-48 focus:w-48 cursor-pointer transition-all">
                    <button type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </form>

                <?php if ($session->logged_in && $session->username != GUEST_NAME): ?>
                    <div class="flex items-center gap-4 border-l border-navy-800 pl-6">
                        <div class="text-right">
                            <p class="text-xs font-bold text-white leading-none mb-1">
                                <?php echo htmlspecialchars($session->userinfo['display_name'] ?? $session->username); ?>
                            </p>
                            <p class="text-[10px] text-crimson-500 font-mono font-bold">
                                <?php echo htmlspecialchars($session->userinfo['registration_no'] ?? 'AUTH-1024'); ?></p>
                        </div>
                        <a href="dashboard.php" class="relative group">
                            <img src="<?php echo (!empty($session->userinfo['profile_image']) && file_exists('images/profiles/' . $session->userinfo['profile_image'])) ? 'images/profiles/' . $session->userinfo['profile_image'] : 'images/avatar.png'; ?>"
                                alt="Avatar"
                                class="w-10 h-10 rounded-full border-2 border-navy-700 group-hover:border-crimson-500 transition-colors object-cover shadow-lg">
                            <div
                                class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-navy-900 rounded-full">
                            </div>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="login.php"
                        class="px-8 py-2.5 bg-crimson-600 hover:bg-crimson-700 text-white rounded-full text-sm font-bold shadow-lg shadow-crimson-900/20 transition-all duration-300 transform hover:-translate-y-0.5">
                        Login
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-slate-300 hover:text-white focus:outline-none p-2">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Menu (Hidden by default) -->
<div id="mobile-menu" class="hidden fixed inset-0 z-[100] bg-navy-950 p-6 lg:hidden">
    <div class="flex justify-between items-center mb-10">
        <span class="text-xl font-serif font-bold text-white">Menu</span>
        <button id="close-menu-btn" class="text-white text-2xl"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="flex flex-col gap-6">
        <?php if ($current_page == 'category.php'): ?>
            <a href="index.php" class="text-lg font-bold text-white border-b border-white/10 pb-4">Home</a>
        <?php endif; ?>
        <?php
        mysqli_data_seek($nav_cats, 0);
        while ($cat = mysqli_fetch_assoc($nav_cats)) {
            $is_active = (isset($cat_id) && $cat_id == $cat['id']);
            $active_classes = $is_active ? 'text-white border-crimson-600' : 'text-slate-400 border-white/10';
            echo "<a href='category.php?id={$cat['id']}' class='text-lg font-bold $active_classes border-b pb-4'>{$cat['category']}</a>";
        }
        ?>
        <?php if ($session->logged_in): ?>
            <a href="dashboard.php"
                class="mt-4 px-8 py-4 bg-navy-800 text-white text-center rounded-lg font-bold flex items-center justify-center gap-3">
                <i class="fa-solid fa-gauge"></i> Go to Dashboard
            </a>
            <a href="process.php?logout=1"
                class="px-8 py-4 bg-red-600/20 text-red-400 text-center rounded-lg font-bold">Logout</a>
        <?php else: ?>
            <a href="login.php" class="mt-4 px-8 py-4 bg-crimson-600 text-white text-center rounded-lg font-bold">Admin
                Login</a>
        <?php endif; ?>
    </div>
</div>

<script>
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const closeMenuBtn = document.getElementById('close-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && closeMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => mobileMenu.classList.remove('hidden'));
        closeMenuBtn.addEventListener('click', () => mobileMenu.classList.add('hidden'));
    }
</script>