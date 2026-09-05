<?php
/**
 * Breezekings — Master RSS 2.0 Feed
 * URL: https://breezekings.com/rss.xml (via .htaccess rewrite) or /rss.php
 * 
 * Enables instant content indexing across search engines and feed readers.
 */

error_reporting(0);
@ini_set('display_errors', 0);

require_once __DIR__ . '/include/classes/constants.php';
require_once __DIR__ . '/include/seo_helpers.php';

header('Content-Type: application/rss+xml; charset=utf-8');

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
?>
<rss version="2.0" 
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:media="http://search.yahoo.com/mrss/">
  <channel>
    <title>Breezekings — Insightful Perspectives on Technology &amp; Culture</title>
    <link><?= htmlspecialchars($site_url) ?></link>
    <description>Fresh perspectives on technology, culture, software engineering and beyond. Rigorously written, beautifully presented.</description>
    <language>en-US</language>
    <lastBuildDate><?= date(DATE_RSS) ?></lastBuildDate>
    <atom:link href="<?= htmlspecialchars($site_url) ?>/rss.xml" rel="self" type="application/rss+xml" />
    <image>
      <url><?= htmlspecialchars($site_url) ?>/images/breezekings-icon-red.svg</url>
      <title>Breezekings</title>
      <link><?= htmlspecialchars($site_url) ?></link>
    </image>
<?php
if ($db_conn) {
    $feed_query = @mysqli_query(
        $db_conn,
        "SELECT p.*, c.category 
         FROM posts p 
         LEFT JOIN categories c ON p.category_id = c.id 
         WHERE p.status = 'Published' AND p.is_deleted = 0 
         ORDER BY p.created_at DESC 
         LIMIT 30"
    );

    if ($feed_query) {
        while ($post = mysqli_fetch_assoc($feed_query)) {
            $post_url = $site_url . bk_post_url($post['id'], $post['title'], $post['slug']);
            $author = !empty($post['author']) ? $post['author'] : 'Breezekings Editorial';
            $category = !empty($post['category']) ? $post['category'] : 'General';
            $pub_date = date(DATE_RSS, strtotime($post['created_at']));
            $desc = !empty($post['meta_description']) ? $post['meta_description'] : substr(strip_tags($post['content']), 0, 280) . '…';
            ?>
    <item>
      <title><![CDATA[<?= $post['title'] ?>]]></title>
      <link><?= htmlspecialchars($post_url) ?></link>
      <guid isPermaLink="true"><?= htmlspecialchars($post_url) ?></guid>
      <dc:creator><![CDATA[<?= $author ?>]]></dc:creator>
      <category><![CDATA[<?= $category ?>]]></category>
      <pubDate><?= $pub_date ?></pubDate>
      <description><![CDATA[<?= $desc ?>]]></description>
      <?php if (!empty($post['featured_image'])): 
          $img_url = $site_url . '/images/posts/' . $post['featured_image'];
      ?>
      <media:content url="<?= htmlspecialchars($img_url) ?>" medium="image" />
      <enclosure url="<?= htmlspecialchars($img_url) ?>" type="image/jpeg" length="0" />
      <?php endif; ?>
    </item>
<?php
        }
    }
    @mysqli_close($db_conn);
}
?>
  </channel>
</rss>
