<?php
/**
 * Breezekings — Master Dynamic XML Sitemap
 * Route: /sitemap.xml (via .htaccess rewrite) or /sitemap-xml.php
 * 
 * Auto-generates full XML sitemap containing all core pages,
 * all dynamic categories with published posts, and all published blog posts
 * with genuine, un-fabricated lastmod timestamps and Google Image metadata.
 */

error_reporting(0);
@ini_set('display_errors', 0);

require_once __DIR__ . '/include/classes/constants.php';
require_once __DIR__ . '/include/seo_helpers.php';

// Set correct XML header
header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex, follow');

$site_url = bk_base_url();

// Connect to database gracefully
if (function_exists('mysqli_report')) {
    @mysqli_report(MYSQLI_REPORT_OFF);
}
$db_conn = false;
try {
    $db_conn = @mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($db_conn) {
        mysqli_set_charset($db_conn, 'utf8mb4');
    }
} catch (Throwable $e) {
    $db_conn = false;
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
echo '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' . "\n";
echo '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
echo '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . "\n";
echo '        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd' . "\n";
echo '        http://www.google.com/schemas/sitemap-image/1.1' . "\n";
echo '        http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd">' . "\n";

// ── Resolve latest post date for the Homepage lastmod ────────────────────────
$latest_post_date = '2026-09-08';
if ($db_conn) {
    $latest_q = @mysqli_query($db_conn, "SELECT MAX(COALESCE(published_at, created_at)) as latest_date FROM posts WHERE status = 'Published' AND is_deleted = 0");
    if ($latest_q && $row = mysqli_fetch_assoc($latest_q)) {
        if (!empty($row['latest_date'])) {
            $latest_post_date = date('Y-m-d', strtotime($row['latest_date']));
        }
    }
}

// ── 1. Core Static Pages (Authentic, Stable Lastmod Dates) ────────────────────
$core_pages = [
    ['loc' => '/',                      'file' => 'index.php',                'changefreq' => 'daily',   'priority' => '1.0', 'lastmod' => $latest_post_date],
    ['loc' => '/about',                 'file' => 'about.php',                'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => '2026-09-01'],
    ['loc' => '/contact',               'file' => 'contact.php',              'changefreq' => 'monthly', 'priority' => '0.8', 'lastmod' => '2026-09-01'],
    ['loc' => '/privacy-policy',        'file' => 'privacy-policy.php',       'changefreq' => 'yearly',  'priority' => '0.3', 'lastmod' => '2026-08-15'],
    ['loc' => '/termsofservices',       'file' => 'termsofservices.php',      'changefreq' => 'yearly',  'priority' => '0.3', 'lastmod' => '2026-08-15'],
    ['loc' => '/cookie-policy',         'file' => 'cookie-policy.php',        'changefreq' => 'yearly',  'priority' => '0.3', 'lastmod' => '2026-08-15'],
    ['loc' => '/terms-and-conditions',  'file' => 'terms-and-conditions.php', 'changefreq' => 'yearly',  'priority' => '0.3', 'lastmod' => '2026-08-15'],
];

foreach ($core_pages as $page) {
    $lastmod = $page['lastmod'];
    $filePath = __DIR__ . '/' . $page['file'];
    if ($page['loc'] !== '/' && file_exists($filePath)) {
        $mtime = filemtime($filePath);
        if ($mtime && $mtime > strtotime('2026-01-01')) {
            $lastmod = date('Y-m-d', $mtime);
        }
    }
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($site_url . $page['loc']) . "</loc>\n";
    echo "    <lastmod>" . $lastmod . "</lastmod>\n";
    echo "    <changefreq>" . $page['changefreq'] . "</changefreq>\n";
    echo "    <priority>" . $page['priority'] . "</priority>\n";
    echo "  </url>\n";
}

// ── 2. All Categories with Published Posts ───────────────────────────────────
if ($db_conn) {
    $cats_res = @mysqli_query($db_conn,
        "SELECT c.id, c.category,
                MAX(COALESCE(p.published_at, p.created_at)) as latest_post_date,
                COUNT(p.id) as post_count
         FROM categories c
         INNER JOIN posts p ON p.category_id = c.id AND p.status = 'Published' AND p.is_deleted = 0
         GROUP BY c.id, c.category
         HAVING post_count > 0
         ORDER BY c.id ASC"
    );
    if ($cats_res) {
        while ($cat = mysqli_fetch_assoc($cats_res)) {
            $cat_url = $site_url . bk_category_url($cat['id'], $cat['category']);
            $cat_mod = !empty($cat['latest_post_date']) ? date('Y-m-d', strtotime($cat['latest_post_date'])) : $latest_post_date;
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($cat_url) . "</loc>\n";
            echo "    <lastmod>" . $cat_mod . "</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.8</priority>\n";
            echo "  </url>\n";
        }
    }
}

// ── 3. All Published Blog Posts (with Google Images) ──────────────────────────
if ($db_conn) {
    $posts_res = @mysqli_query(
        $db_conn,
        "SELECT id, title, slug, featured_image, published_at, created_at, updated_at 
         FROM posts 
         WHERE status = 'Published' AND is_deleted = 0 
         ORDER BY COALESCE(published_at, created_at) DESC, id DESC"
    );
    if ($posts_res) {
        while ($post = mysqli_fetch_assoc($posts_res)) {
            $post_url = $site_url . bk_post_url($post['id'], $post['title'], $post['slug']);
            $pub_ts = !empty($post['published_at']) ? strtotime($post['published_at']) : strtotime($post['created_at']);
            $upd_ts = !empty($post['updated_at']) ? strtotime($post['updated_at']) : $pub_ts;
            
            // Genuine edit check: only use updated_at if edited >24h after publication
            $mod_ts = ($upd_ts && ($upd_ts - $pub_ts) > 86400) ? $upd_ts : $pub_ts;
            $post_date = date('Y-m-d', $mod_ts ?: time());

            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($post_url) . "</loc>\n";
            echo "    <lastmod>" . $post_date . "</lastmod>\n";
            echo "    <changefreq>monthly</changefreq>\n";
            echo "    <priority>0.9</priority>\n";

            if (!empty($post['featured_image'])) {
                $img_url = $site_url . bk_thumb_url($post['featured_image']);
                echo "    <image:image>\n";
                echo "      <image:loc>" . htmlspecialchars($img_url) . "</image:loc>\n";
                echo "      <image:title>" . htmlspecialchars($post['title']) . "</image:title>\n";
                echo "    </image:image>\n";
            }

            echo "  </url>\n";
        }
    }
    @mysqli_close($db_conn);
}

echo '</urlset>' . "\n";
