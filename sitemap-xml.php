<?php
/**
 * Breezekings — Master Dynamic XML Sitemap
 * Route: /sitemap.xml (via .htaccess rewrite) or /sitemap-xml.php
 * 
 * Auto-generates full XML sitemap containing all core pages,
 * all dynamic categories, and all published blog posts with Google Image metadata.
 */

// Enable error reporting to error log, don't output notices in XML
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

// ── 1. Core Static Pages ─────────────────────────────────────────────────────
$core_pages = [
    ['loc' => '/',                       'changefreq' => 'daily',   'priority' => '1.0'],
    ['loc' => '/about',                  'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => '/contact',               'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => '/privacy-policy',        'changefreq' => 'yearly',  'priority' => '0.3'],
    ['loc' => '/termsofservices',       'changefreq' => 'yearly',  'priority' => '0.3'],
    ['loc' => '/cookie-policy',         'changefreq' => 'yearly',  'priority' => '0.3'],
    ['loc' => '/terms-and-conditions',  'changefreq' => 'yearly',  'priority' => '0.3'],
];

$today = date('Y-m-d');

foreach ($core_pages as $page) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($site_url . $page['loc']) . "</loc>\n";
    echo "    <lastmod>" . $today . "</lastmod>\n";
    echo "    <changefreq>" . $page['changefreq'] . "</changefreq>\n";
    echo "    <priority>" . $page['priority'] . "</priority>\n";
    echo "  </url>\n";
}

// ── 2. Phase-1 Nav-Visible Categories (with ≥1 published post) ────────────────
// Uses get_nav_categories_for_sitemap() — only nav_visible=1 cats with posts
if ($db_conn) {
    $cats_res = @mysqli_query($db_conn,
        "SELECT c.id, c.category, c.created_at,
                (SELECT COUNT(*) FROM posts WHERE category_id = c.id AND status = 'Published' AND is_deleted = 0) as post_count
         FROM categories c
         WHERE c.nav_visible = 1
         HAVING post_count > 0
         ORDER BY c.id ASC"
    );
    if ($cats_res) {
        while ($cat = mysqli_fetch_assoc($cats_res)) {
            $cat_url = $site_url . bk_category_url($cat['id'], $cat['category']);
            $cat_date = !empty($cat['created_at']) ? date('Y-m-d', strtotime($cat['created_at'])) : $today;
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($cat_url) . "</loc>\n";
            echo "    <lastmod>" . $cat_date . "</lastmod>\n";
            echo "    <changefreq>daily</changefreq>\n";
            echo "    <priority>0.8</priority>\n";
            echo "  </url>\n";
        }
    }
}

// ── 3. All Blog Posts (with Google Images) ───────────────────────────────────
if ($db_conn) {
    $posts_res = @mysqli_query(
        $db_conn,
        "SELECT id, title, slug, featured_image, updated_at, created_at 
         FROM posts 
         WHERE status = 'Published' AND is_deleted = 0 
         ORDER BY id DESC"
    );
    if ($posts_res) {
        while ($post = mysqli_fetch_assoc($posts_res)) {
            $post_url = $site_url . bk_post_url($post['id'], $post['title'], $post['slug']);
            $mod_time = !empty($post['updated_at']) ? $post['updated_at'] : $post['created_at'];
            $post_date = !empty($mod_time) ? date('Y-m-d', strtotime($mod_time)) : $today;

            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($post_url) . "</loc>\n";
            echo "    <lastmod>" . $post_date . "</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.9</priority>\n";

            if (!empty($post['featured_image'])) {
                $img_url = $site_url . '/images/posts/' . $post['featured_image'];
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
