<?php
// include/analytics_queries.php

// ─── Core KPI Totals with Month-over-Month Growth ────────────────────────────
function get_analytics_totals($database) {
    $stats = [
        'posts' => 0, 'users' => 0, 'views' => 0,
        'comments' => 0, 'categories' => 0, 'drafts' => 0,
        'published' => 0, 'featured' => 0,
        // Growth
        'posts_this_month' => 0, 'posts_last_month' => 0,
        'views_this_month' => 0, 'views_last_month' => 0,
        'comments_this_month' => 0, 'comments_last_month' => 0,
        'users_this_month' => 0, 'users_last_month' => 0,
    ];

    $res = $database->query("SELECT COUNT(*) as count FROM posts WHERE is_deleted = 0");
    if ($res) $stats['posts'] = (int)mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM posts WHERE is_deleted = 0 AND status = 'Published'");
    if ($res) $stats['published'] = (int)mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM posts WHERE is_deleted = 0 AND status = 'Draft'");
    if ($res) $stats['drafts'] = (int)mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM posts WHERE is_deleted = 0 AND is_featured = 1");
    if ($res) $stats['featured'] = (int)mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM users");
    if ($res) $stats['users'] = (int)mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COALESCE(SUM(views),0) as count FROM posts WHERE is_deleted = 0");
    if ($res) $stats['views'] = (int)mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM comments");
    if ($res) $stats['comments'] = (int)mysqli_fetch_assoc($res)['count'];

    $res = $database->query("SELECT COUNT(*) as count FROM categories");
    if ($res) $stats['categories'] = (int)mysqli_fetch_assoc($res)['count'];

    // Month-over-month
    $this_m_start = date('Y-m-01 00:00:00');
    $this_m_end   = date('Y-m-t 23:59:59');
    $last_m_start = date('Y-m-01 00:00:00', strtotime('-1 month'));
    $last_m_end   = date('Y-m-t 23:59:59', strtotime('-1 month'));

    $this_m_start_date = date('Y-m-01');
    $this_m_end_date   = date('Y-m-t');
    $last_m_start_date = date('Y-m-01', strtotime('-1 month'));
    $last_m_end_date   = date('Y-m-t', strtotime('-1 month'));

    $res = $database->query("SELECT COUNT(*) as c FROM posts WHERE COALESCE(published_at, created_at) BETWEEN '$this_m_start' AND '$this_m_end' AND is_deleted = 0 AND status = 'Published'");
    if ($res) $stats['posts_this_month'] = (int)mysqli_fetch_assoc($res)['c'];

    $res = $database->query("SELECT COUNT(*) as c FROM posts WHERE COALESCE(published_at, created_at) BETWEEN '$last_m_start' AND '$last_m_end' AND is_deleted = 0 AND status = 'Published'");
    if ($res) $stats['posts_last_month'] = (int)mysqli_fetch_assoc($res)['c'];

    // Track views from post_views_log
    $log_check = $database->query("SHOW TABLES LIKE 'post_views_log'");
    $has_log_table = ($log_check && mysqli_num_rows($log_check) > 0);

    if ($has_log_table) {
        $res = $database->query("SELECT COALESCE(SUM(views_count),0) as c FROM post_views_log WHERE view_date BETWEEN '$this_m_start_date' AND '$this_m_end_date'");
        if ($res) $stats['views_this_month'] = (int)mysqli_fetch_assoc($res)['c'];

        $res = $database->query("SELECT COALESCE(SUM(views_count),0) as c FROM post_views_log WHERE view_date BETWEEN '$last_m_start_date' AND '$last_m_end_date'");
        if ($res) $stats['views_last_month'] = (int)mysqli_fetch_assoc($res)['c'];
    }

    if ($stats['views_this_month'] == 0 && $stats['views'] > 0) {
        $stats['views_this_month'] = $stats['views'];
    }

    $res = $database->query("SELECT COUNT(*) as c FROM comments WHERE created_at BETWEEN '$this_m_start' AND '$this_m_end'");
    if ($res) $stats['comments_this_month'] = (int)mysqli_fetch_assoc($res)['c'];

    $res = $database->query("SELECT COUNT(*) as c FROM comments WHERE created_at BETWEEN '$last_m_start' AND '$last_m_end'");
    if ($res) $stats['comments_last_month'] = (int)mysqli_fetch_assoc($res)['c'];

    $res = $database->query("SELECT COUNT(*) as c FROM users WHERE created_at BETWEEN '$this_m_start' AND '$this_m_end'");
    if ($res) $stats['users_this_month'] = (int)mysqli_fetch_assoc($res)['c'];

    $res = $database->query("SELECT COUNT(*) as c FROM users WHERE created_at BETWEEN '$last_m_start' AND '$last_m_end'");
    if ($res) $stats['users_last_month'] = (int)mysqli_fetch_assoc($res)['c'];

    return $stats;
}

// ─── Helper: compute growth % ────────────────────────────────────────────────
function calc_growth($current, $previous) {
    if ($previous == 0) return $current > 0 ? 100 : 0;
    return round((($current - $previous) / $previous) * 100, 1);
}

// ─── Top Viewed Posts ─────────────────────────────────────────────────────────
function get_top_viewed_posts($database, $limit = 10) {
    $posts = [];
    $res = $database->query("
        SELECT p.id, p.title, p.views, p.status, p.slug, c.category
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_deleted = 0
        ORDER BY p.views DESC
        LIMIT $limit
    ");
    if ($res) while($row = mysqli_fetch_assoc($res)) $posts[] = $row;
    return $posts;
}

// ─── Top Commented Posts ──────────────────────────────────────────────────────
function get_top_commented_posts($database, $limit = 5) {
    $posts = [];
    $res = $database->query("
        SELECT p.id, p.title, COUNT(c.id) as comment_count
        FROM posts p
        LEFT JOIN comments c ON p.id = c.post_id
        WHERE p.is_deleted = 0
        GROUP BY p.id
        ORDER BY comment_count DESC
        LIMIT $limit
    ");
    if ($res) while($row = mysqli_fetch_assoc($res)) $posts[] = $row;
    return $posts;
}

// ─── SEO Health ───────────────────────────────────────────────────────────────
function get_seo_health($database) {
    $health = [
        'missing_meta' => 0, 'missing_image' => 0, 'low_views' => 0,
        'missing_title' => 0, 'total_published' => 0, 'score' => 0,
    ];

    $res = $database->query("SELECT COUNT(*) as c FROM posts WHERE is_deleted=0 AND status='Published'");
    if ($res) $health['total_published'] = (int)mysqli_fetch_assoc($res)['c'];

    $res = $database->query("SELECT COUNT(*) as c FROM posts WHERE is_deleted=0 AND (meta_description IS NULL OR meta_description='')");
    if ($res) $health['missing_meta'] = (int)mysqli_fetch_assoc($res)['c'];

    $res = $database->query("SELECT COUNT(*) as c FROM posts WHERE is_deleted=0 AND (featured_image IS NULL OR featured_image='')");
    if ($res) $health['missing_image'] = (int)mysqli_fetch_assoc($res)['c'];

    $res = $database->query("SELECT COUNT(*) as c FROM posts WHERE is_deleted=0 AND views < 10");
    if ($res) $health['low_views'] = (int)mysqli_fetch_assoc($res)['c'];

    $res = $database->query("SELECT COUNT(*) as c FROM posts WHERE is_deleted=0 AND (meta_title IS NULL OR meta_title='')");
    if ($res) $health['missing_title'] = (int)mysqli_fetch_assoc($res)['c'];

    // Simple SEO score 0-100
    $total = max(1, $health['total_published']);
    $issues = $health['missing_meta'] + $health['missing_image'] + $health['missing_title'];
    $health['score'] = max(0, round(100 - (($issues / ($total * 3)) * 100)));

    return $health;
}

// ─── Category Post Counts ─────────────────────────────────────────────────────
function get_category_post_counts($database) {
    $data = [];
    $res = $database->query("
        SELECT c.category, COUNT(p.id) as post_count,
               COALESCE(SUM(p.views),0) as total_views
        FROM categories c
        LEFT JOIN posts p ON p.category_id = c.id AND p.is_deleted = 0
        GROUP BY c.id
        ORDER BY post_count DESC
        LIMIT 10
    ");
    if ($res) while($row = mysqli_fetch_assoc($res)) $data[] = $row;
    return $data;
}

// ─── Recent Posts ─────────────────────────────────────────────────────────────
function get_recent_posts($database, $limit = 6) {
    $posts = [];
    $res = $database->query("
        SELECT p.id, p.title, p.status, p.views, p.slug, COALESCE(p.published_at, p.created_at) as created_at, c.category
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_deleted = 0
        ORDER BY COALESCE(p.published_at, p.created_at) DESC
        LIMIT $limit
    ");
    if ($res) while($row = mysqli_fetch_assoc($res)) $posts[] = $row;
    return $posts;
}

// ─── Recent Comments ──────────────────────────────────────────────────────────
function get_recent_comments($database, $limit = 6) {
    $comments = [];
    $res = $database->query("
        SELECT c.id, c.name, c.comment, c.status, c.created_at, p.title as post_title, p.id as post_id
        FROM comments c
        LEFT JOIN posts p ON c.post_id = p.id
        ORDER BY c.created_at DESC
        LIMIT $limit
    ");
    if ($res) while($row = mysqli_fetch_assoc($res)) $comments[] = $row;
    return $comments;
}

// ─── User Breakdown by Level ──────────────────────────────────────────────────
function get_user_breakdown($database) {
    $breakdown = [];
    $level_names = [0 => 'Guest', 1 => 'Author', 2 => 'Editor', 3 => 'Manager', 4 => 'Admin'];
    $res = $database->query("SELECT userlevel, COUNT(*) as count FROM users GROUP BY userlevel ORDER BY userlevel DESC");
    if ($res) {
        while($row = mysqli_fetch_assoc($res)) {
            $breakdown[] = [
                'level' => $row['userlevel'],
                'name' => $level_names[$row['userlevel']] ?? "Level {$row['userlevel']}",
                'count' => (int)$row['count']
            ];
        }
    }
    return $breakdown;
}

// ─── Comment Status Breakdown ─────────────────────────────────────────────────
function get_comment_status_breakdown($database) {
    $data = ['approved' => 0, 'pending' => 0, 'spam' => 0];
    $res = $database->query("SELECT status, COUNT(*) as count FROM comments GROUP BY status");
    if ($res) {
        while($row = mysqli_fetch_assoc($res)) {
            $key = strtolower($row['status']);
            if (isset($data[$key])) $data[$key] = (int)$row['count'];
        }
    }
    return $data;
}

// ─── Posts Published Last 12 Months ──────────────────────────────────────────
function get_posts_per_month($database, $months = 12) {
    $data = [];
    for ($i = $months - 1; $i >= 0; $i--) {
        $start = date('Y-m-01 00:00:00', strtotime("-$i months"));
        $end   = date('Y-m-t 23:59:59', strtotime("-$i months"));
        $label = date('M Y', strtotime("-$i months"));
        $res = $database->query("SELECT COUNT(*) as c FROM posts WHERE COALESCE(published_at, created_at) BETWEEN '$start' AND '$end' AND is_deleted = 0 AND status = 'Published'");
        $data[] = ['label' => $label, 'count' => $res ? (int)mysqli_fetch_assoc($res)['c'] : 0];
    }
    return $data;
}
?>
