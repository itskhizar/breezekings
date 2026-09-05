<?php
/**
 * Breezekings — Dynamic XML Sitemap for Blog Posts & Categories
 * URL: https://breezekings.com/sitemap-posts.php (or /sitemap-posts.xml)
 */

error_reporting(0);
@ini_set('display_errors', 0);

require_once __DIR__ . '/include/classes/constants.php';
require_once __DIR__ . '/include/seo_helpers.php';

header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex, follow');

$site_url = bk_base_url();

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
echo '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

$today = date('Y-m-d');

// Static pages (clean URLs)
$static_pages = [
    ['url' => '/',                'freq' => 'daily',   'priority' => '1.0'],
    ['url' => '/about',           'freq' => 'monthly', 'priority' => '0.8'],
    ['url' => '/contact',         'freq' => 'monthly', 'priority' => '0.8'],
    ['url' => '/privacy-policy',  'freq' => 'yearly',  'priority' => '0.3'],
    ['url' => '/termsofservices', 'freq' => 'yearly',  'priority' => '0.3'],
];

foreach ($static_pages as $page) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($site_url . $page['url']) . "</loc>\n";
    echo "    <lastmod>" . $today . "</lastmod>\n";
    echo "    <changefreq>" . $page['freq'] . "</changefreq>\n";
    echo "    <priority>" . $page['priority'] . "</priority>\n";
    echo "  </url>\n";
}

// Categories
if ($db_conn) {
    $cats_query = @mysqli_query($db_conn, "SELECT id, category, created_at FROM categories ORDER BY id ASC");
    if ($cats_query) {
        while ($cat = mysqli_fetch_assoc($cats_query)) {
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

// Blog posts with images
if ($db_conn) {
    $posts_query = @mysqli_query(
        $db_conn,
        "SELECT id, title, slug, featured_image, updated_at, created_at
         FROM posts
         WHERE status = 'Published' AND is_deleted = 0
         ORDER BY created_at DESC"
    );
    if ($posts_query) {
        while ($post = mysqli_fetch_assoc($posts_query)) {
            $post_url = $site_url . bk_post_url($post['id'], $post['title'], $post['slug']);
            $last_mod = !empty($post['updated_at']) ? $post['updated_at'] : $post['created_at'];
            $last_mod_fmt = !empty($last_mod) ? date('Y-m-d', strtotime($last_mod)) : $today;

            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($post_url) . "</loc>\n";
            echo "    <lastmod>" . $last_mod_fmt . "</lastmod>\n";
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
