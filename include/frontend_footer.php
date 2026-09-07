<?php
$footer_email = "azamwaseem44@gmail.com";
?>
<style>
    .bk-footer {
        background: #0B1F3A;
        color: #fff;
        border-top: 4px solid #C8102E;
        padding: 4rem 0 0;
        font-family: 'Inter', sans-serif;
    }
    .bk-footer-inner {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }
    .bk-footer-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.5rem;
    }
    @media (min-width: 640px)  { .bk-footer-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .bk-footer-grid { grid-template-columns: 2fr 1.2fr 1.2fr; } }

    /* Brand column */
    .bk-footer-logo {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        text-decoration: none;
        margin-bottom: 1rem;
    }
    .bk-footer-logo svg { width: 38px; height: 38px; flex-shrink: 0; }
    .bk-footer-logo-text {
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -.02em;
        color: #fff;
    }
    .bk-footer-logo-text span { color: #C8102E; }
    .bk-footer-tagline {
        font-size: 0.85rem;
        color: rgba(255,255,255,.6);
        line-height: 1.7;
        margin-bottom: 1.25rem;
        max-width: 360px;
    }
    .bk-footer-socials {
        display: flex;
        gap: 0.5rem;
    }
    .bk-footer-social {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,.7);
        text-decoration: none;
        font-size: 0.8rem;
        transition: background .2s, color .2s;
    }
    .bk-footer-social:hover { background: #C8102E; color: #fff; }

    /* Link columns */
    .bk-footer-col-title {
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: rgba(255,255,255,.45);
        margin-bottom: 1.1rem;
    }
    .bk-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }
    .bk-footer-links a {
        font-size: 0.85rem;
        color: rgba(255,255,255,.65);
        text-decoration: none;
        transition: color .18s;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .bk-footer-links a:hover { color: #fff; }
    .bk-footer-links a i { font-size: 0.65rem; color: #C8102E; }

    /* Contact CTA bar */
    .bk-footer-contact-bar {
        margin-top: 3.5rem;
        background: rgba(200,16,46,.08);
        border: 1px solid rgba(200,16,46,.22);
        border-radius: 12px;
        padding: 1.1rem 1.5rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1rem;
        justify-content: space-between;
    }
    .bk-footer-contact-bar p {
        font-size: 0.85rem;
        color: rgba(255,255,255,.75);
    }
    .bk-footer-contact-bar strong { color: #fff; }
    .bk-footer-contact-mail {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
        background: #C8102E;
        padding: 0.5rem 1.1rem;
        border-radius: 8px;
        transition: background .2s;
        white-space: nowrap;
    }
    .bk-footer-contact-mail:hover { background: #E8433D; }

    /* Bottom bar */
    .bk-footer-bottom {
        margin-top: 3rem;
        border-top: 1px solid rgba(255,255,255,.08);
        padding: 1.25rem 0;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .bk-footer-copy {
        font-size: 0.78rem;
        color: rgba(255,255,255,.4);
    }
    .bk-footer-legal {
        display: flex;
        gap: 1.25rem;
    }
    .bk-footer-legal a {
        font-size: 0.75rem;
        color: rgba(255,255,255,.4);
        text-decoration: none;
        transition: color .18s;
    }
    .bk-footer-legal a:hover { color: rgba(255,255,255,.8); }
</style>

<!-- Schema.org Organization -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Breezekings",
    "url": "https://breezekings.com",
    "logo": "https://breezekings.com/images/breezekings-icon-red.svg",
    "contactPoint": {
        "@type": "ContactPoint",
        "email": "<?= $footer_email ?>",
        "contactType": "customer support"
    }
}
</script>

<footer class="bk-footer" itemscope itemtype="https://schema.org/WPFooter">
    <div class="bk-footer-inner">
        <div class="bk-footer-grid">

            <!-- ── Brand Column ────────────────────────────────── -->
            <div class="bk-footer-brand">
                <a href="/" class="bk-footer-logo" aria-label="Breezekings Home">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" aria-hidden="true">
                        <rect x="0" y="0" width="100" height="100" rx="22" fill="#FFFFFF"/>
                        <rect x="30" y="20" width="13" height="60" rx="3" fill="#0B1F3A"/>
                        <path d="M43,20 H60 L66,26 Q69,29 69,35 Q69,49 54,49 H43 Z" fill="#0B1F3A"/>
                        <path d="M46,55 L75,27" stroke="#E8433D" stroke-width="11" stroke-linecap="square" fill="none"/>
                        <path d="M46,59 L75,89" stroke="#E8433D" stroke-width="11" stroke-linecap="square" fill="none"/>
                    </svg>
                    <span class="bk-footer-logo-text">Breeze<span>kings</span></span>
                </a>
                <p class="bk-footer-tagline">
                    Fresh perspectives on technology, culture &amp; beyond.
                    Breezekings delivers content that matters — rigorously written, beautifully presented.
                </p>
                <div class="bk-footer-socials">
                    <a href="mailto:<?= $footer_email ?>" class="bk-footer-social" aria-label="Email us" title="<?= $footer_email ?>"><i class="fa-solid fa-envelope"></i></a>
                    <a href="/rss.xml" class="bk-footer-social" aria-label="RSS Feed" title="RSS Feed"><i class="fa-solid fa-rss"></i></a>
                    <a href="/contact" class="bk-footer-social" aria-label="Contact Us" title="Contact Us"><i class="fa-solid fa-headset"></i></a>
                </div>
            </div>

            <!-- ── Categories / Navigation Column ──────────────── -->
            <div>
                <h4 class="bk-footer-col-title">Categories</h4>
                <ul class="bk-footer-links">
                    <li><a href="/"><i class="fa-solid fa-chevron-right"></i> Home</a></li>
                    <?php
                    $footer_cats = $database->get_all_categories();
                    $fc = 0;
                    if ($footer_cats) {
                        while ($cat = mysqli_fetch_assoc($footer_cats)) {
                            if ($fc >= 6) break;
                            $f_cat_url = bk_category_url($cat['id'], $cat['category']);
                            echo "<li><a href='{$f_cat_url}'><i class='fa-solid fa-chevron-right'></i> " . htmlspecialchars($cat['category']) . "</a></li>";
                            $fc++;
                        }
                    }
                    ?>
                </ul>
            </div>

            <!-- ── Company & Legal Column ──────────────────────── -->
            <div>
                <h4 class="bk-footer-col-title">Company &amp; Legal</h4>
                <ul class="bk-footer-links">
                    <li><a href="/about"><i class="fa-solid fa-chevron-right"></i> About Us</a></li>
                    <li><a href="/contact"><i class="fa-solid fa-chevron-right"></i> Contact Us</a></li>
                    <li><a href="/privacy-policy"><i class="fa-solid fa-chevron-right"></i> Privacy Policy</a></li>
                    <li><a href="/terms-and-conditions"><i class="fa-solid fa-chevron-right"></i> Terms &amp; Conditions</a></li>
                    <li><a href="/cookie-policy"><i class="fa-solid fa-chevron-right"></i> Cookie Policy / Ads Disclosure</a></li>
                    <li><a href="/sitemap.xml" target="_blank"><i class="fa-solid fa-chevron-right"></i> XML Sitemap</a></li>
                    <li><a href="/rss.xml" target="_blank"><i class="fa-solid fa-chevron-right"></i> RSS Feed</a></li>
                </ul>
            </div>

        </div><!-- /.bk-footer-grid -->

        <!-- ── Contact CTA Bar ─────────────────────────────────── -->
        <div class="bk-footer-contact-bar">
            <p>Have a story tip, editorial inquiry or feedback? <strong>We'd love to hear from you.</strong></p>
            <a href="mailto:<?= $footer_email ?>" class="bk-footer-contact-mail" aria-label="Contact Us via Email">
                <i class="fa-solid fa-envelope"></i> Contact Us
            </a>
        </div>

        <!-- ── Bottom Bar ─────────────────────────────────────── -->
        <div class="bk-footer-bottom">
            <p class="bk-footer-copy">
                &copy; <?php echo date('Y'); ?> Breezekings. All rights reserved.
            </p>
            <nav class="bk-footer-legal" aria-label="Legal links">
                <a href="/about">About</a>
                <a href="/contact">Contact</a>
                <a href="/privacy-policy">Privacy Policy</a>
                <a href="/terms-and-conditions">Terms &amp; Conditions</a>
                <a href="/cookie-policy">Cookie Policy</a>
            </nav>
        </div>

    </div><!-- /.bk-footer-inner -->
</footer>
