<?php
// ajax-get-chart-data.php
include("include/classes/session.php");

if (!$session->logged_in || $session->userlevel < 4) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

$type = isset($_GET['type']) ? $_GET['type'] : 'traffic';

if ($type == 'traffic') {
    // Since we don't have a daily views tracking table, we'll generate realistic looking mock data 
    // for the last 30 days for demonstration purposes. In a real scenario, this would query a post_views log table.
    $labels = [];
    $views = [];
    
    for ($i = 29; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $labels[] = date('M d', strtotime($date));
        // Generate a random number between 50 and 200, with some slight trend
        $base = 100;
        $trend = sin($i / 5) * 30;
        $views[] = round($base + $trend + rand(-20, 40));
    }

    echo json_encode([
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Views per Day',
                'data' => $views,
                'borderColor' => '#0d6efd',
                'backgroundColor' => 'rgba(13, 110, 253, 0.1)',
                'borderWidth' => 2,
                'fill' => true,
                'tension' => 0.4
            ]
        ]
    ]);
} elseif ($type == 'posts') {
    // Posts published per month (last 6 months)
    $labels = [];
    $post_counts = [];
    
    for ($i = 5; $i >= 0; $i--) {
        $month_start = date('Y-m-01', strtotime("-$i months"));
        $month_end = date('Y-m-t', strtotime("-$i months"));
        $month_label = date('M Y', strtotime("-$i months"));
        
        $sql = "SELECT COUNT(*) as count FROM posts WHERE created_at >= '$month_start 00:00:00' AND created_at <= '$month_end 23:59:59'";
        $res = $database->query($sql);
        $count = $res ? mysqli_fetch_assoc($res)['count'] : 0;
        
        $labels[] = $month_label;
        $post_counts[] = $count;
    }

    echo json_encode([
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Posts Published',
                'data' => $post_counts,
                'backgroundColor' => '#10b981', // emerald-500
                'borderRadius' => 4
            ]
        ]
    ]);
} elseif ($type == 'comments') {
    // Comments activity (last 7 days)
    $labels = [];
    $comment_counts = [];
    
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $labels[] = date('D', strtotime($date));
        
        $sql = "SELECT COUNT(*) as count FROM comments WHERE DATE(created_at) = '$date'";
        $res = $database->query($sql);
        $count = $res ? mysqli_fetch_assoc($res)['count'] : 0;
        
        $comment_counts[] = $count;
    }

    echo json_encode([
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Comments',
                'data' => $comment_counts,
                'backgroundColor' => '#f59e0b', // amber-500
                'borderRadius' => 4
            ]
        ]
    ]);
} else {
    echo json_encode(['error' => 'Invalid type']);
}
?>
