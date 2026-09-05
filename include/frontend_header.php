<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<?php
// Build base URL once for canonical/og use across header
$_site_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
?>

<!-- ============================================================
     BREEZEKINGS — Frontend Navigation
     Architecture: sticky top nav, SVG logo, dynamic categories,
     contact mailto, mobile full-screen menu
============================================================ -->

<!-- Favicon & Feed -->
<link rel="icon" type="image/svg+xml" href="<?php echo $_site_url; ?>/images/breezekings-icon-red.svg">
<link rel="shortcut icon" href="<?php echo $_site_url; ?>/images/breezekings-icon-red.svg">
<link rel="alternate" type="application/rss+xml" title="Breezekings RSS Feed" href="<?php echo $_site_url; ?>/rss.xml">
<meta name="theme-color" content="#0B1F3A">

<!-- Resource hints for fastest rendering -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

<style>
    /* ── Breezekings navbar tokens ─────────────────────────────── */
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
        box-shadow: 0 4px 24px rgba(0,0,0,.35);
    }

    /* ── Inner row ─────────────────────────────────────────────── */
    .bk-nav-inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: flex;
        align-items: center;
        height: 68px;
        gap: 2rem;
    }

    /* ── Logo ─────────────────────────────────────────────────── */
    .bk-logo {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        text-decoration: none;
        flex-shrink: 0;
        transition: opacity .2s;
    }
    .bk-logo:hover { opacity: .85; }
    .bk-logo-icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        filter: drop-shadow(0 2px 6px rgba(200,16,46,.35));
    }
    .bk-logo-wordmark {
        font-family: 'Inter', 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 1.35rem;
        letter-spacing: -.02em;
        line-height: 1;
        color: #fff;
    }
    .bk-logo-wordmark span { color: var(--bk-red); }

    /* ── Desktop nav links ─────────────────────────────────────── */
    .bk-nav-links {
        display: none;
        align-items: center;
        gap: 0.1rem;
        flex: 1;
    }
    @media (min-width: 1024px) { .bk-nav-links { display: flex; } }

    .bk-nav-link {
        font-family: 'Inter', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: rgba(255,255,255,.72);
        text-decoration: none;
        padding: 0.35rem 0.65rem;
        border-radius: 4px;
        transition: color .18s, background .18s;
        white-space: nowrap;
    }
    .bk-nav-link:hover,
    .bk-nav-link.active {
        color: #fff;
        background: rgba(200,16,46,.18);
    }
    .bk-nav-link.active {
        border-bottom: 2px solid var(--bk-red);
        border-radius: 4px 4px 0 0;
    }

    /* ── Categories mega dropdown ─────────────────────────────── */
    .bk-cat-dropdown {
        position: relative;
    }
    .bk-cat-btn {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-family: 'Inter', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: rgba(255,255,255,.72);
        background: none;
        border: none;
        padding: 0.35rem 0.65rem;
        border-radius: 4px;
        cursor: pointer;
        transition: color .18s, background .18s;
    }
    .bk-cat-btn:hover { color: #fff; background: rgba(200,16,46,.18); }
    .bk-cat-btn svg { width: 10px; height: 10px; transition: transform .2s; }
    .bk-cat-dropdown:hover .bk-cat-btn svg { transform: rotate(180deg); }

    .bk-cat-menu {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 12px 40px rgba(0,0,0,.18);
        min-width: 180px;
        padding: 0.5rem;
        z-index: 9999;
        border-top: 3px solid var(--bk-red);
    }
    .bk-cat-dropdown:hover .bk-cat-menu { display: block; }
    .bk-cat-menu a {
        display: block;
        padding: 0.55rem 1rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--bk-navy);
        text-decoration: none;
        border-radius: 6px;
        transition: background .15s, color .15s;
    }
    .bk-cat-menu a:hover { background: #fef2f4; color: var(--bk-red); }

    /* ── Right side actions ───────────────────────────────────── */
    .bk-actions {
        display: none;
        align-items: center;
        gap: 0.75rem;
        margin-left: auto;
    }
    @media (min-width: 768px) { .bk-actions { display: flex; } }

    /* Search */
    .bk-search-form {
        position: relative;
    }
    .bk-search-input {
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.15);
        border-radius: 20px;
        color: #fff;
        font-size: 0.75rem;
        padding: 0.42rem 2.2rem 0.42rem 0.9rem;
        width: 130px;
        transition: width .3s, border-color .2s, background .2s;
        outline: none;
    }
    .bk-search-input::placeholder { color: rgba(255,255,255,.4); }
    .bk-search-input:focus {
        width: 200px;
        border-color: var(--bk-red);
        background: rgba(255,255,255,.13);
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

    /* Contact Us link */
    .bk-contact-link {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: rgba(255,255,255,.82);
        text-decoration: none;
        padding: 0.4rem 0.8rem;
        border: 1px solid rgba(200,16,46,.45);
        border-radius: 20px;
        transition: all .2s;
        white-space: nowrap;
    }
    .bk-contact-link:hover {
        background: var(--bk-red);
        border-color: var(--bk-red);
        color: #fff;
    }
    .bk-contact-link i { font-size: 0.7rem; }

    /* Login / Dashboard */
    .bk-login-btn {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: #fff;
        background: var(--bk-red);
        text-decoration: none;
        padding: 0.45rem 1rem;
        border-radius: 20px;
        transition: background .2s, transform .15s;
        white-space: nowrap;
    }
    .bk-login-btn:hover { background: var(--bk-red-lt); transform: translateY(-1px); }

    .bk-user-chip {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        border-left: 1px solid rgba(255,255,255,.12);
        padding-left: 0.75rem;
    }
    .bk-user-name {
        font-size: 0.78rem;
        font-weight: 600;
        color: #fff;
        line-height: 1.2;
    }
    .bk-user-reg {
        font-size: 0.65rem;
        color: var(--bk-red-lt);
        font-family: 'DM Mono', monospace;
    }
    .bk-user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(200,16,46,.5);
        transition: border-color .2s;
    }
    .bk-user-avatar:hover { border-color: var(--bk-red); }

    /* ── Hamburger ────────────────────────────────────────────── */
    .bk-hamburger {
        display: flex;
        margin-left: auto;
        background: none;
        border: none;
        cursor: pointer;
        color: rgba(255,255,255,.8);
        font-size: 1.3rem;
        padding: 0.4rem;
        border-radius: 6px;
        transition: background .2s;
    }
    .bk-hamburger:hover { background: rgba(255,255,255,.08); }
    @media (min-width: 1024px) { .bk-hamburger { display: none; } }

    /* ── Mobile menu ─────────────────────────────────────────── */
    .bk-mobile-menu {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: var(--bk-navy);
        padding: 1.5rem;
        overflow-y: auto;
        flex-direction: column;
        gap: 0;
    }
    .bk-mobile-menu.open { display: flex; }

    .bk-mobile-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid rgba(255,255,255,.1);
    }
    .bk-mobile-close {
        background: rgba(255,255,255,.07);
        border: none;
        color: #fff;
        font-size: 1.3rem;
        border-radius: 8px;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .bk-mobile-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: rgba(255,255,255,.82);
        text-decoration: none;
        border-bottom: 1px solid rgba(255,255,255,.07);
        transition: color .18s;
    }
    .bk-mobile-link:hover { color: #fff; }
    .bk-mobile-link i { width: 20px; color: var(--bk-red); }

    .bk-mobile-section-label {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: rgba(255,255,255,.35);
        padding: 1.25rem 0 0.5rem;
    }

    .bk-mobile-contact {
        margin-top: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: var(--bk-red);
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        padding: 0.9rem 1.5rem;
        border-radius: 10px;
        transition: background .2s;
    }
    .bk-mobile-contact:hover { background: var(--bk-red-lt); }

    /* ── Ticker bar ───────────────────────────────────────────── */
    .bk-ticker {
        background: var(--bk-red);
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        overflow: hidden;
        height: 30px;
        display: flex;
        align-items: center;
    }
    .bk-ticker-label {
        flex-shrink: 0;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .2em;
        text-transform: uppercase;
        border-right: 1px solid rgba(255,255,255,.35);
        padding: 0 0.85rem 0 1rem;
        margin-right: 0.75rem;
        line-height: 30px;
        white-space: nowrap;
    }
    .bk-ticker-track-wrap { overflow: hidden; flex: 1; }
    .bk-ticker-track {
        display: inline-block;
        white-space: nowrap;
        padding-left: 100%;
        animation: bk-ticker-scroll 40s linear infinite;
    }
    .bk-ticker-track:hover { animation-play-state: paused; }
    @keyframes bk-ticker-scroll {
        from { transform: translateX(0); }
        to   { transform: translateX(-100%); }
    }
    .bk-ticker-track a {
        color: rgba(255,255,255,.92);
        text-decoration: none;
        margin-right: 2.5rem;
        transition: color .2s;
    }
    .bk-ticker-track a:hover { color: #fff; text-decoration: underline; }
</style>

<?php
// ── Pre-load nav categories (reusable by mobile menu) ──────────
$_nav_cats_result = $database->get_all_categories();
$_nav_cats = [];
while ($r = mysqli_fetch_assoc($_nav_cats_result)) {
    $_nav_cats[] = $r;
}
?>

<nav class="bk-nav" role="navigation" aria-label="Main navigation">
    <div class="bk-nav-inner">

        <!-- ── Logo ───────────────────────────────────────────── -->
        <a href="/" class="bk-logo" aria-label="Breezekings – Home">
            <!-- Inline BK icon SVG — navy bg, white B, crimson K -->
            <svg class="bk-logo-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" aria-hidden="true">
                <rect x="0" y="0" width="100" height="100" rx="22" fill="#0B1F3A"/>
                <rect x="30" y="20" width="13" height="60" rx="3" fill="#FFFFFF"/>
                <path d="M43,20 H60 L66,26 Q69,29 69,35 Q69,49 54,49 H43 Z" fill="#FFFFFF"/>
                <path d="M46,55 L75,27" stroke="#C8102E" stroke-width="11" stroke-linecap="square" fill="none"/>
                <path d="M46,59 L75,89" stroke="#C8102E" stroke-width="11" stroke-linecap="square" fill="none"/>
            </svg>
            <span class="bk-logo-wordmark">Breeze<span>kings</span></span>
        </a>

        <!-- ── Desktop Nav Links ──────────────────────────────── -->
        <div class="bk-nav-links">

            <a href="/" class="bk-nav-link <?= in_array($current_page, ['index.php', '']) ? 'active' : '' ?>">Home</a>

            <!-- Dynamic Categories Dropdown -->
            <?php if (!empty($_nav_cats)): ?>
            <div class="bk-cat-dropdown">
                <button class="bk-cat-btn" aria-haspopup="true" aria-expanded="false">
                    Categories
                    <svg viewBox="0 0 10 6" fill="currentColor"><path d="M0 0l5 6 5-6z"/></svg>
                </button>
                <div class="bk-cat-menu" role="menu">
                    <?php foreach ($_nav_cats as $cat): 
                        $cat_url = bk_category_url($cat['id'], $cat['category']);
                    ?>
                    <a href="<?= $cat_url ?>"
                       role="menuitem"
                       <?= (isset($cat_id) && $cat_id == $cat['id']) ? 'style="color:var(--bk-red);background:#fef2f4;"' : '' ?>>
                        <?= htmlspecialchars($cat['category']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <a href="/about"   class="bk-nav-link <?= $current_page === 'about.php' ? 'active' : '' ?>">About</a>
            <a href="/contact" class="bk-nav-link <?= $current_page === 'contact.php' ? 'active' : '' ?>">Contact</a>

        </div>

        <!-- ── Right Actions ──────────────────────────────────── -->
        <div class="bk-actions">

            <!-- Search -->
            <form action="/" method="GET" class="bk-search-form" role="search" aria-label="Search articles">
                <input type="text" name="q" id="bk-search"
                       value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
                       placeholder="Search…"
                       class="bk-search-input"
                       aria-label="Search articles">
                <button type="submit" class="bk-search-btn" aria-label="Submit search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <!-- Contact Us mailto -->
            <a href="mailto:info@breezekings.com" class="bk-contact-link" aria-label="Contact Breezekings by email">
                <i class="fa-solid fa-envelope"></i> Contact Us
            </a>

            <!-- Auth state -->
            <?php if ($session->logged_in && $session->username != GUEST_NAME): ?>
                <div class="bk-user-chip">
                    <div>
                        <div class="bk-user-name"><?= htmlspecialchars($session->userinfo['display_name'] ?? $session->username) ?></div>
                        <div class="bk-user-reg"><?= htmlspecialchars($session->userinfo['registration_no'] ?? '') ?></div>
                    </div>
                    <a href="/dashboard" aria-label="Go to dashboard">
                        <img src="<?php echo (!empty($session->userinfo['profile_image']) && file_exists('images/profiles/' . $session->userinfo['profile_image'])) ? 'images/profiles/' . $session->userinfo['profile_image'] : 'images/avatar.png'; ?>"
                             alt="Profile" class="bk-user-avatar">
                    </a>
                </div>
            <?php else: ?>
                <a href="/login" class="bk-login-btn">
                    <i class="fa-solid fa-right-to-bracket"></i> Login
                </a>
            <?php endif; ?>

        </div>

        <!-- ── Mobile Hamburger ───────────────────────────────── -->
        <button class="bk-hamburger" id="bk-menu-open" aria-label="Open menu" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>

    </div><!-- /.bk-nav-inner -->
</nav><!-- /.bk-nav -->

<!-- ── Mobile Full-screen Menu ─────────────────────────────────── -->
<div id="bk-mobile-menu" class="bk-mobile-menu" role="dialog" aria-modal="true" aria-label="Mobile navigation">

    <div class="bk-mobile-top">
        <!-- Mini logo in mobile menu -->
        <a href="/" class="bk-logo" style="gap:.5rem;">
            <svg style="width:34px;height:34px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" aria-hidden="true">
                <rect x="0" y="0" width="100" height="100" rx="22" fill="#0B1F3A"/>
                <rect x="30" y="20" width="13" height="60" rx="3" fill="#FFFFFF"/>
                <path d="M43,20 H60 L66,26 Q69,29 69,35 Q69,49 54,49 H43 Z" fill="#FFFFFF"/>
                <path d="M46,55 L75,27" stroke="#C8102E" stroke-width="11" stroke-linecap="square" fill="none"/>
                <path d="M46,59 L75,89" stroke="#C8102E" stroke-width="11" stroke-linecap="square" fill="none"/>
            </svg>
            <span class="bk-logo-wordmark" style="font-size:1.1rem;">Breeze<span>kings</span></span>
        </a>
        <button class="bk-mobile-close" id="bk-menu-close" aria-label="Close menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Home -->
    <a href="/" class="bk-mobile-link"><i class="fa-solid fa-house"></i> Home</a>

    <!-- Dynamic Categories -->
    <?php if (!empty($_nav_cats)): ?>
    <p class="bk-mobile-section-label">Categories</p>
    <?php foreach ($_nav_cats as $cat): ?>
    <a href="<?= bk_category_url($cat['id'], $cat['category']) ?>" class="bk-mobile-link" style="font-size:.95rem;padding:.75rem 0;">
        <i class="fa-solid fa-tag"></i> <?= htmlspecialchars($cat['category']) ?>
    </a>
    <?php endforeach; ?>
    <?php endif; ?>

    <!-- Pages -->
    <p class="bk-mobile-section-label">Pages</p>
    <a href="/about"   class="bk-mobile-link"><i class="fa-solid fa-circle-info"></i> About Us</a>
    <a href="/contact" class="bk-mobile-link"><i class="fa-solid fa-envelope"></i> Contact</a>
    <a href="/privacy-policy"   class="bk-mobile-link" style="font-size:.85rem;"><i class="fa-solid fa-shield-halved"></i> Privacy Policy</a>
    <a href="/termsofservices"  class="bk-mobile-link" style="font-size:.85rem;"><i class="fa-solid fa-file-lines"></i> Terms of Service</a>

    <!-- Auth -->
    <?php if ($session->logged_in && $session->username != GUEST_NAME): ?>
    <p class="bk-mobile-section-label">Account</p>
    <a href="/dashboard" class="bk-mobile-link"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <a href="/process?logout=1" class="bk-mobile-link" style="color:#f87171;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    <?php else: ?>
    <a href="/login" class="bk-mobile-link" style="margin-top:1rem;"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
    <?php endif; ?>

    <!-- Contact CTA -->
    <a href="mailto:info@breezekings.com" class="bk-mobile-contact">
        <i class="fa-solid fa-envelope"></i> Email Us — info@breezekings.com
    </a>

</div><!-- /#bk-mobile-menu -->

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