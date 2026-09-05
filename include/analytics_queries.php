<?php
// include/analytics_queries.php

function get_analytics_totals($database) {
    $stats = [
        'posts' => 0,
        'users' => 0,
        'views' => 0,
        'comments' => 0,
        'categories' => 0,
        'drafts' => 0
    ];

    $res = $database->query("SELECT COUNT(*) as count FROM posts");
    if($res) $stats['posts'] = mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM users");
    if($res) $stats['users'] = mysqli_fetch_assoc($res)['count'];

    // Use 'views' column from the posts table as shown in the screenshot
    $res = $database->query("SELECT SUM(views) as count FROM posts");
    if($res) {
        $row = mysqli_fetch_assoc($res);
        $stats['views'] = $row['count'] ? $row['count'] : 0;
    }

    $res = $database->query("SELECT COUNT(*) as count FROM comments");
    if($res) $stats['comments'] = mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM categories");
    if($res) $stats['categories'] = mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM posts WHERE status='Draft'");
    if($res) $stats['drafts'] = mysqli_fetch_assoc($res)['count'];

    return $stats;
}

function get_top_viewed_posts($database, $limit = 5) {
    $posts = [];
    $res = $database->query("SELECT id, title, views FROM posts ORDER BY views DESC LIMIT $limit");
    if ($res) {
        while($row = mysqli_fetch_assoc($res)) {
            $posts[] = $row;
        }
    }
    return $posts;
}

function get_top_commented_posts($database, $limit = 5) {
    $posts = [];
    $res = $database->query("SELECT p.id, p.title, COUNT(c.id) as comment_count 
                             FROM posts p 
                             LEFT JOIN comments c ON p.id = c.post_id 
                             GROUP BY p.id 
                             ORDER BY comment_count DESC 
                             LIMIT $limit");
    if ($res) {
        while($row = mysqli_fetch_assoc($res)) {
            $posts[] = $row;
        }
    }
    return $posts;
}

function get_seo_health($database) {
    $health = [
        'missing_meta' => 0,
        'missing_image' => 0,
        'low_views' => 0
    ];
    
    $res = $database->query("SELECT COUNT(*) as count FROM posts WHERE meta_description IS NULL OR meta_description = ''");
    if($res) $health['missing_meta'] = mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM posts WHERE featured_image IS NULL OR featured_image = ''");
    if($res) $health['missing_image'] = mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM posts WHERE views < 10");
    if($res) $health['low_views'] = mysqli_fetch_assoc($res)['count'];

    return $health;
}
?>
