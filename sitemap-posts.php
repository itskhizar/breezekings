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

// Latest post date for fallback
$latest_post_date = '2026-09-08';
if ($db_conn) {
    $latest_q = @mysqli_query($db_conn, "SELECT MAX(COALESCE(published_at, created_at)) as latest_date FROM posts WHERE status = 'Published' AND is_deleted = 0");
    if ($latest_q && $row = mysqli_fetch_assoc($latest_q)) {
        if (!empty($row['latest_date'])) {
            $latest_post_date = date('Y-m-d', strtotime($row['latest_date']));
        }
    }
}

// Categories with published posts
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

// Blog posts with images
if ($db_conn) {
    $posts_query = @mysqli_query(
        $db_conn,
        "SELECT id, title, slug, featured_image, published_at, created_at, updated_at
         FROM posts
         WHERE status = 'Published' AND is_deleted = 0
         ORDER BY COALESCE(published_at, created_at) DESC, id DESC"
    );
    if ($posts_query) {
        while ($post = mysqli_fetch_assoc($posts_query)) {
            $post_url = $site_url . bk_post_url($post['id'], $post['title'], $post['slug']);
            $pub_ts = !empty($post['published_at']) ? strtotime($post['published_at']) : strtotime($post['created_at']);
            $upd_ts = !empty($post['updated_at']) ? strtotime($post['updated_at']) : $pub_ts;
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
