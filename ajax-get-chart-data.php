<?php
// ajax-get-chart-data.php
include("include/classes/session.php");

if (!$session->logged_in) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

$type  = $_GET['type'] ?? 'traffic';
$range = $_GET['range'] ?? '30';

// ── Traffic (views by day — from post_views_log) ──────────────────────────────
if ($type === 'traffic') {
    $days       = (int)$range ?: 30;
    $labels     = [];
    $views      = [];
    $start_date = date('Y-m-d', strtotime("-" . ($days - 1) . " days"));
    $end_date   = date('Y-m-d');

    // Fetch daily view counts from post_views_log
    $daily_data = [];
    $log_check = $database->query("SHOW TABLES LIKE 'post_views_log'");
    $has_log_table = ($log_check && mysqli_num_rows($log_check) > 0);

    if ($has_log_table) {
        $res = $database->query("SELECT view_date, SUM(views_count) as v 
                                 FROM post_views_log 
                                 WHERE view_date BETWEEN '$start_date' AND '$end_date'
                                 GROUP BY view_date");
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $daily_data[$row['view_date']] = (int)$row['v'];
            }
        }
    }

    // If log table has no rows yet in range, fallback to checking posts.views for non-zero recent activity
    if (empty($daily_data)) {
        $today_res = $database->query("SELECT SUM(views) as total_v FROM posts WHERE is_deleted = 0 AND status = 'Published'");
        $today_row = $today_res ? mysqli_fetch_assoc($today_res) : null;
        $total_v = (int)($today_row['total_v'] ?? 0);
        if ($total_v > 0) {
            // Attribute existing baseline views to today in log table for future tracking
            $daily_data[$end_date] = $total_v;
        }
    }

    for ($i = $days - 1; $i >= 0; $i--) {
        $date     = date('Y-m-d', strtotime("-$i days"));
        $labels[] = $days <= 7 ? date('D, M d', strtotime($date)) : date('M d', strtotime($date));
        $views[]  = $daily_data[$date] ?? 0;
    }

    echo json_encode([
        'labels'   => $labels,
        'datasets' => [[
            'label'           => 'Daily Views',
            'data'            => $views,
            'borderColor'     => '#6366f1',
            'backgroundColor' => 'rgba(99,102,241,0.08)',
            'borderWidth'     => 2.5,
            'fill'            => true,
            'tension'         => 0.45,
            'pointRadius'     => ($days <= 14 ? 3 : 0),
            'pointHoverRadius'=> 5,
        ]]
    ]);
}

// ── Posts published per month (last 12 months) ───────────────────────────────
elseif ($type === 'posts') {
    $months      = (int)$range ?: 12;
    $labels      = [];
    $post_counts = [];

    for ($i = $months - 1; $i >= 0; $i--) {
        $start  = date('Y-m-01 00:00:00', strtotime("-$i months"));
        $end    = date('Y-m-t 23:59:59', strtotime("-$i months"));
        $labels[] = date('M Y', strtotime("-$i months"));

        $res = $database->query("SELECT COUNT(*) as c FROM posts WHERE COALESCE(published_at, created_at) BETWEEN '$start' AND '$end' AND is_deleted = 0 AND status = 'Published'");
        $post_counts[] = $res ? (int)mysqli_fetch_assoc($res)['c'] : 0;
    }

    echo json_encode([
        'labels'   => $labels,
        'datasets' => [[
            'label'           => 'Posts',
            'data'            => $post_counts,
            'backgroundColor' => '#10b981',
            'borderRadius'    => 6,
            'borderSkipped'   => false,
        ]]
    ]);
}

// ── Comments per day (last 7 / 30 days) ─────────────────────────────────────
elseif ($type === 'comments') {
    $days   = (int)$range ?: 7;
    $labels = [];
    $counts = [];

    for ($i = $days - 1; $i >= 0; $i--) {
        $date   = date('Y-m-d', strtotime("-$i days"));
        $labels[] = $days <= 7 ? date('D', strtotime($date)) : date('M d', strtotime($date));

        $res = $database->query("SELECT COUNT(*) as c FROM comments WHERE DATE(created_at) = '$date'");
        $counts[] = $res ? (int)mysqli_fetch_assoc($res)['c'] : 0;
    }

    echo json_encode([
        'labels'   => $labels,
        'datasets' => [[
            'label'           => 'Comments',
            'data'            => $counts,
            'backgroundColor' => '#f59e0b',
            'borderRadius'    => 5,
        ]]
    ]);
}

// ── Posts by Category ─────────────────────────────────────────────────────────
elseif ($type === 'categories') {
    $labels = [];
    $counts = [];
    $colors = ['#6366f1','#10b981','#f59e0b','#ef4444','#3b82f6','#8b5cf6','#ec4899','#14b8a6','#f97316','#64748b'];

    $res = $database->query("
        SELECT c.category, COUNT(p.id) as cnt
        FROM categories c
        LEFT JOIN posts p ON p.category_id = c.id AND p.is_deleted = 0
        GROUP BY c.id
        ORDER BY cnt DESC
        LIMIT 10
    ");
    if ($res) {
        $i = 0;
        while($row = mysqli_fetch_assoc($res)) {
            $labels[]    = $row['category'];
            $counts[]    = (int)$row['cnt'];
            $bgColors[]  = $colors[$i % count($colors)];
            $i++;
        }
    }

    echo json_encode([
        'labels'   => $labels,
        'datasets' => [[
            'label'           => 'Posts',
            'data'            => $counts,
            'backgroundColor' => $bgColors ?? [],
            'borderRadius'    => 6,
            'borderSkipped'   => false,
        ]]
    ]);
}

// ── Post Status Donut ─────────────────────────────────────────────────────────
elseif ($type === 'status') {
    $published = 0; $draft = 0;
    $res = $database->query("SELECT status, COUNT(*) as c FROM posts WHERE is_deleted=0 GROUP BY status");
    if ($res) {
        while($row = mysqli_fetch_assoc($res)) {
            if ($row['status'] === 'Published') $published = (int)$row['c'];
            else $draft += (int)$row['c'];
        }
    }
    echo json_encode([
        'labels'   => ['Published', 'Draft'],
        'datasets' => [[
            'data'            => [$published, $draft],
            'backgroundColor' => ['#10b981', '#f59e0b'],
            'borderWidth'     => 0,
            'hoverOffset'     => 6,
        ]]
    ]);
}

// ── User Level Donut ─────────────────────────────────────────────────────────
elseif ($type === 'users') {
    $labels = []; $counts = []; $bgColors = [];
    $level_map  = [0=>'Guest',1=>'Author',2=>'Editor',3=>'Manager',4=>'Admin'];
    $color_map  = [0=>'#94a3b8',1=>'#6366f1',2=>'#3b82f6',3=>'#f59e0b',4=>'#ef4444'];

    $res = $database->query("SELECT userlevel, COUNT(*) as c FROM users GROUP BY userlevel ORDER BY userlevel DESC");
    if ($res) {
        while($row = mysqli_fetch_assoc($res)) {
            $lv = (int)$row['userlevel'];
            $labels[]   = $level_map[$lv] ?? "Level $lv";
            $counts[]   = (int)$row['c'];
            $bgColors[] = $color_map[$lv] ?? '#94a3b8';
        }
    }
    echo json_encode([
        'labels'   => $labels,
        'datasets' => [[
            'data'            => $counts,
            'backgroundColor' => $bgColors,
            'borderWidth'     => 0,
            'hoverOffset'     => 6,
        ]]
    ]);
}

else {
    echo json_encode(['error' => 'Invalid type']);
}
?>
