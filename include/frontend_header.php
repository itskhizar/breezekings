<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
$_site_url = bk_base_url();
$contact_email = "azamwaseem44@gmail.com";
?>

<!-- Favicon & Feed -->
<link rel="icon" type="image/svg+xml" href="<?php echo $_site_url; ?>/images/breezekings-icon-red.svg">
<link rel="shortcut icon" href="<?php echo $_site_url; ?>/images/breezekings-icon-red.svg">
<link rel="alternate" type="application/rss+xml" title="Breezekings RSS Feed" href="<?php echo $_site_url; ?>/rss.xml">
<meta name="theme-color" content="#0B1F3A">

<!-- Tailwind CDN fallback ensure available on all pages -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    serif: ['Playfair Display', 'serif'],
                    mono: ['DM Mono', 'monospace'],
                },
                colors: {
                    navy: {
                        800: '#1e2336',
                        900: '#151928',
                        950: '#0B1F3A',
                    },
                    crimson: {
                        500: '#e12b38',
                        600: '#C8102E',
                        700: '#a31a23',
                    }
                }
            }
        }
    }
</script>

<!-- Google Fonts & Font Awesome -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --bk-navy:   #0B1F3A;
        --bk-red:    #C8102E;
        --bk-red-lt: #E8433D;
        --bk-white:  #FFFFFF;
    }

    /* ── Sticky nav shell ──────────────────────────────────────── */
    .bk-nav {
        position: sticky;
        top: 0;
        z-index: 9999;
        background: var(--bk-navy);
        border-bottom: 3px solid var(--bk-red);
        box-shadow: 0 4px 20px rgba(0,0,0,.25);
    }

    .bk-nav-inner {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 68px;
        gap: 1.5rem;
    }

    /* ── Left: Logo ────────────────────────────────────────────── */
    .bk-nav-left {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }
    .bk-logo {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        text-decoration: none;
        transition: opacity .2s;
    }
    .bk-logo:hover { opacity: .9; }
    .bk-logo-icon {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
    }
    .bk-logo-wordmark {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 1.35rem;
        letter-spacing: -.02em;
        line-height: 1;
        color: #fff;
    }
    .bk-logo-wordmark span { color: var(--bk-red); }

    /* ── Center: 5 Categories + More aligned in Center ─────────── */
    .bk-nav-center {
        display: none;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        flex: 1;
        max-width: 780px;
    }
    @media (min-width: 1024px) { 
        .bk-nav-center { display: flex; } 
    }

    .bk-nav-link {
        font-family: 'Inter', sans-serif;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: rgba(255,255,255,.8);
        text-decoration: none;
        padding: 0.45rem 0.75rem;
        border-radius: 6px;
        transition: color .18s, background .18s;
        white-space: nowrap;
    }
    .bk-nav-link:hover,
    .bk-nav-link.active {
        color: #fff;
        background: rgba(200,16,46,.18);
    }
    .bk-nav-link.active {
        color: #fff;
        border-bottom: 2px solid var(--bk-red);
        border-radius: 6px 6px 0 0;
    }

    /* More Dropdown */
    .bk-more-dropdown {
        position: relative;
    }
    .bk-more-btn {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-family: 'Inter', sans-serif;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: rgba(255,255,255,.8);
        background: none;
        border: none;
        padding: 0.45rem 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        transition: color .18s, background .18s;
    }
    .bk-more-btn:hover { color: #fff; background: rgba(200,16,46,.18); }
    .bk-more-btn svg { width: 9px; height: 9px; transition: transform .2s; }
    .bk-more-dropdown:hover .bk-more-btn svg { transform: rotate(180deg); }

    .bk-more-menu {
        display: none;
        position: absolute;
        top: calc(100% + 4px);
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 15px 40px rgba(0,0,0,.22);
        min-width: 220px;
        padding: 0.5rem;
        z-index: 9999;
        border-top: 3px solid var(--bk-red);
    }
    .bk-more-dropdown:hover .bk-more-menu { display: block; }
    .bk-more-menu-header {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: #94a3b8;
        padding: 0.4rem 0.75rem 0.2rem;
    }
    .bk-more-menu a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--bk-navy);
        text-decoration: none;
        border-radius: 6px;
        transition: background .15s, color .15s;
    }
    .bk-more-menu a:hover { background: #fef2f4; color: var(--bk-red); }
    .bk-more-menu .divider {
        height: 1px;
        background: #f1f5f9;
        margin: 0.35rem 0;
    }

    /* ── Right: Search, Contact Us, Login ──────────────────────── */
    .bk-nav-right {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-shrink: 0;
    }

    .bk-search-form {
        position: relative;
        display: none;
    }
    @media (min-width: 640px) { .bk-search-form { display: block; } }

    .bk-search-input {
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 20px;
        color: #fff;
        font-size: 0.75rem;
        padding: 0.4rem 2rem 0.4rem 0.85rem;
        width: 120px;
        transition: width .25s, border-color .2s, background .2s;
        outline: none;
    }
    .bk-search-input::placeholder { color: rgba(255,255,255,.4); }
    .bk-search-input:focus {
        width: 170px;
        border-color: var(--bk-red);
        background: rgba(255,255,255,.14);
    }
    .bk-search-btn {
        position: absolute;
        right: 0.65rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: rgba(255,255,255,.5);
        cursor: pointer;
        font-size: 0.75rem;
        padding: 0;
        transition: color .2s;
    }
    .bk-search-btn:hover { color: #fff; }

    .bk-contact-link {
        display: none;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #fff;
        background: var(--bk-red);
        text-decoration: none;
        padding: 0.42rem 0.85rem;
        border-radius: 20px;
        transition: background .2s, transform .15s;
        white-space: nowrap;
    }
    @media (min-width: 480px) { .bk-contact-link { display: flex; } }
    .bk-contact-link:hover {
        background: var(--bk-red-lt);
        transform: translateY(-1px);
    }

    .bk-login-btn {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #fff;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.2);
        text-decoration: none;
        padding: 0.42rem 0.85rem;
        border-radius: 20px;
        transition: all .2s;
        white-space: nowrap;
    }
    .bk-login-btn:hover {
        background: #fff;
        color: var(--bk-navy);
        border-color: #fff;
    }

    .bk-user-chip {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-left: 0.4rem;
    }
    .bk-user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--bk-red);
    }

    /* ── Mobile Hamburger ─────────────────────────────────────── */
    .bk-hamburger {
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        cursor: pointer;
        color: rgba(255,255,255,.85);
        font-size: 1.25rem;
        padding: 0.4rem;
        border-radius: 6px;
        transition: background .2s;
    }
    .bk-hamburger:hover { background: rgba(255,255,255,.08); }
    @media (min-width: 1024px) { .bk-hamburger { display: none; } }

    /* ── Mobile Drawer ────────────────────────────────────────── */
    .bk-mobile-menu {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: var(--bk-navy);
        padding: 1.5rem;
        overflow-y: auto;
        flex-direction: column;
    }
    .bk-mobile-menu.open { display: flex; }
    .bk-mobile-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255,255,255,.1);
    }
    .bk-mobile-close {
        background: rgba(255,255,255,.08);
        border: none;
        color: #fff;
        font-size: 1.25rem;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .bk-mobile-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: rgba(255,255,255,.85);
        text-decoration: none;
        border-bottom: 1px solid rgba(255,255,255,.06);
        transition: color .18s;
    }
    .bk-mobile-link:hover { color: #fff; }
    .bk-mobile-link i { width: 18px; color: var(--bk-red); }
</style>

<?php
// Pre-load all categories from database
$_nav_cats_result = $database->get_all_categories();
$_all_cats = [];
if ($_nav_cats_result) {
    while ($r = mysqli_fetch_assoc($_nav_cats_result)) {
        $_all_cats[] = $r;
    }
}

// Prioritize Technology and 5 main categories directly in center nav
$priority_order = [
    'technology'    => 1,
    'business'      => 2,
    'entertainment' => 3,
    'health'        => 4,
    'lifestyle'     => 5,
    'news'          => 6
];
usort($_all_cats, function($a, $b) use ($priority_order) {
    $pa = $priority_order[strtolower(trim($a['category']))] ?? 99;
    $pb = $priority_order[strtolower(trim($b['category']))] ?? 99;
    if ($pa !== $pb) {
        return $pa - $pb;
    }
    return strcasecmp($a['category'], $b['category']);
});

// Top 5 main categories for direct display in center nav (Technology, Business, Entertainment, Health, Lifestyle)
$_primary_cats = array_slice($_all_cats, 0, 5);
// Remaining categories for "More" dropdown (e.g. News, and any others)
$_more_cats = array_slice($_all_cats, 5);
?>

<nav class="bk-nav" role="navigation" aria-label="Main navigation">
    <div class="bk-nav-inner">

        <!-- ── Left: Logo & Wordmark ──────────────────────────── -->
        <div class="bk-nav-left">
            <a href="/" class="bk-logo" aria-label="Breezekings Home">
                <svg class="bk-logo-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" aria-hidden="true">
                    <rect x="0" y="0" width="100" height="100" rx="22" fill="#0B1F3A"/>
                    <rect x="30" y="20" width="13" height="60" rx="3" fill="#FFFFFF"/>
                    <path d="M43,20 H60 L66,26 Q69,29 69,35 Q69,49 54,49 H43 Z" fill="#FFFFFF"/>
                    <path d="M46,55 L75,27" stroke="#C8102E" stroke-width="11" stroke-linecap="square" fill="none"/>
                    <path d="M46,59 L75,89" stroke="#C8102E" stroke-width="11" stroke-linecap="square" fill="none"/>
                </svg>
                <span class="bk-logo-wordmark">Breeze<span>kings</span></span>
            </a>
        </div>

        <!-- ── Center: Home + 5 Categories + More Dropdown ─────── -->
        <div class="bk-nav-center">
            
            <a href="/" class="bk-nav-link <?= in_array($current_page, ['index.php', '']) ? 'active' : '' ?>">Home</a>

            <!-- 5 Dynamic Categories directly visible -->
            <?php foreach ($_primary_cats as $p_cat): 
                $p_url = bk_category_url($p_cat['id'], $p_cat['category']);
                $is_p_active = (isset($cat_id) && $cat_id == $p_cat['id']);
            ?>
            <a href="<?= $p_url ?>" class="bk-nav-link <?= $is_p_active ? 'active' : '' ?>">
                <?= htmlspecialchars($p_cat['category']) ?>
            </a>
            <?php endforeach; ?>

            <!-- "More ▾" Dropdown with remaining categories & core pages -->
            <div class="bk-more-dropdown">
                <button class="bk-more-btn" aria-haspopup="true" aria-expanded="false">
                    More
                    <svg viewBox="0 0 10 6" fill="currentColor"><path d="M0 0l5 6 5-6z"/></svg>
                </button>
                <div class="bk-more-menu" role="menu">
                    
                    <?php if (!empty($_more_cats)): ?>
                        <div class="bk-more-menu-header">More Topics</div>
                        <?php foreach ($_more_cats as $m_cat): 
                            $m_url = bk_category_url($m_cat['id'], $m_cat['category']);
                        ?>
                        <a href="<?= $m_url ?>" role="menuitem">
                            <span><?= htmlspecialchars($m_cat['category']) ?></span>
                            <span class="text-[10px] text-slate-400 font-mono"><?= $m_cat['post_count'] ?? '' ?></span>
                        </a>
                        <?php endforeach; ?>
                        <div class="divider"></div>
                    <?php endif; ?>

                    <div class="bk-more-menu-header">Explore</div>
                    <a href="/about" role="menuitem">
                        <span>About Us</span>
                        <i class="fa-solid fa-circle-info text-slate-300 text-xs"></i>
                    </a>
                    <a href="/contact" role="menuitem">
                        <span>Contact</span>
                        <i class="fa-solid fa-envelope text-slate-300 text-xs"></i>
                    </a>
                    <a href="/privacy-policy" role="menuitem">
                        <span>Privacy Policy</span>
                        <i class="fa-solid fa-shield-halved text-slate-300 text-xs"></i>
                    </a>
                    <a href="/termsofservices" role="menuitem">
                        <span>Terms of Service</span>
                        <i class="fa-solid fa-file-lines text-slate-300 text-xs"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- ── Right: Search + Contact Us + Login / User ───────── -->
        <div class="bk-nav-right">
            
            <!-- Search -->
            <form action="/" method="GET" class="bk-search-form" role="search" aria-label="Search articles">
                <input type="text" name="q" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" placeholder="Search…" class="bk-search-input" aria-label="Search articles">
                <button type="submit" class="bk-search-btn" aria-label="Submit search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <!-- Contact Us Button -->
            <a href="mailto:<?= $contact_email ?>" class="bk-contact-link" aria-label="Contact Breezekings by email">
                <i class="fa-solid fa-envelope"></i> Contact Us
            </a>

            <!-- Auth / User -->
            <?php if ($session->logged_in && $session->username != GUEST_NAME): ?>
                <div class="bk-user-chip">
                    <?php $user_chip_name = !empty($session->userinfo['display_name']) ? $session->userinfo['display_name'] : $session->username; ?>
                    <a href="/dashboard" class="flex items-center gap-2" title="Go to Dashboard">
                        <img src="<?= bk_avatar_url($session->userinfo['profile_image'] ?? null, $user_chip_name) ?>" alt="Profile" class="bk-user-avatar" onerror="this.src='/images/avatar.png'">
                        <span class="text-xs text-white font-bold hidden xl:inline normal-case"><?= htmlspecialchars($user_chip_name) ?></span>
                    </a>
                </div>
            <?php else: ?>
                <a href="/login.php" class="bk-login-btn" aria-label="Sign In">
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                    <span>Login</span>
                </a>
            <?php endif; ?>

            <!-- Mobile Hamburger -->
            <button class="bk-hamburger" id="bk-menu-open" aria-label="Open menu" aria-expanded="false">
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>

    </div>
</nav>

<!-- ── Mobile Menu Drawer ──────────────────────────────────────── -->
<div id="bk-mobile-menu" class="bk-mobile-menu" role="dialog" aria-modal="true" aria-label="Mobile navigation">
    <div class="bk-mobile-top">
        <a href="/" class="bk-logo" style="gap:.5rem;">
            <svg style="width:32px;height:32px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" aria-hidden="true">
                <rect x="0" y="0" width="100" height="100" rx="22" fill="#0B1F3A"/>
                <rect x="30" y="20" width="13" height="60" rx="3" fill="#FFFFFF"/>
                <path d="M43,20 H60 L66,26 Q69,29 69,35 Q69,49 54,49 H43 Z" fill="#FFFFFF"/>
                <path d="M46,55 L75,27" stroke="#C8102E" stroke-width="11" stroke-linecap="square" fill="none"/>
                <path d="M46,59 L75,89" stroke="#C8102E" stroke-width="11" stroke-linecap="square" fill="none"/>
            </svg>
            <span class="bk-logo-wordmark" style="font-size:1.15rem;">Breeze<span>kings</span></span>
        </a>
        <button class="bk-mobile-close" id="bk-menu-close" aria-label="Close menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Search Mobile -->
    <form action="/" method="GET" class="relative mb-4">
        <input type="text" name="q" placeholder="Search articles…" class="w-full bg-white/10 border border-white/20 rounded-lg py-2.5 pl-3 pr-10 text-white placeholder-white/40 text-sm outline-none focus:border-crimson-600">
        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>

    <a href="/" class="bk-mobile-link"><i class="fa-solid fa-house"></i> Home</a>

    <!-- All Categories in Mobile Drawer -->
    <div class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mt-4 mb-1">Categories</div>
    <?php foreach ($_all_cats as $cat): ?>
    <a href="<?= bk_category_url($cat['id'], $cat['category']) ?>" class="bk-mobile-link" style="font-size:.9rem;padding:.65rem 0;">
        <i class="fa-solid fa-tag text-xs"></i> <?= htmlspecialchars($cat['category']) ?>
    </a>
    <?php endforeach; ?>

    <div class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mt-4 mb-1">Explore</div>
    <a href="/about" class="bk-mobile-link"><i class="fa-solid fa-circle-info"></i> About Us</a>
    <a href="/contact" class="bk-mobile-link"><i class="fa-solid fa-envelope"></i> Contact</a>
    <a href="/privacy-policy" class="bk-mobile-link"><i class="fa-solid fa-shield-halved"></i> Privacy Policy</a>
    <a href="/termsofservices" class="bk-mobile-link"><i class="fa-solid fa-file-lines"></i> Terms of Service</a>

    <?php if ($session->logged_in && $session->username != GUEST_NAME): ?>
    <a href="/dashboard" class="bk-mobile-link" style="color:#60a5fa;"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <a href="/process?logout=1" class="bk-mobile-link" style="color:#f87171;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    <?php else: ?>
    <a href="/login.php" class="bk-mobile-link" style="color:#38bdf8;"><i class="fa-solid fa-arrow-right-to-bracket"></i> Login / Sign In</a>
    <?php endif; ?>

    <!-- Mobile Contact CTA -->
    <a href="mailto:<?= $contact_email ?>" class="mt-6 flex items-center justify-center gap-2 bg-[#C8102E] hover:bg-[#a31a23] text-white font-bold text-sm py-3 px-4 rounded-xl shadow-md transition-colors" aria-label="Contact Us via Email">
        <i class="fa-solid fa-envelope"></i> Contact Us
    </a>
</div>

<script>
(function() {
    var openBtn  = document.getElementById('bk-menu-open');
    var closeBtn = document.getElementById('bk-menu-close');
    var menu     = document.getElementById('bk-mobile-menu');
    if (!openBtn || !closeBtn || !menu) return;
    function openMenu()  { menu.classList.add('open');  openBtn.setAttribute('aria-expanded','true');  document.body.style.overflow='hidden'; }
    function closeMenu() { menu.classList.remove('open'); openBtn.setAttribute('aria-expanded','false'); document.body.style.overflow=''; }
    openBtn.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeMenu(); });
})();
</script>