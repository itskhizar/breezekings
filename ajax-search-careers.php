<?php
include("include/classes/session.php");
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed");
}

$search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';
$position = isset($_POST['position']) ? mysqli_real_escape_string($conn, $_POST['position']) : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';

// Build query
$query = "SELECT * FROM careers WHERE 1=1";

// Search filter
if (!empty($search)) {
    $query .= " AND (fullname LIKE '%$search%' OR phone LIKE '%$search%' OR email LIKE '%$search%')";
}

// Position filter
if (!empty($position)) {
    $query .= " AND position = '$position'";
}

// Date filter
if (!empty($date)) {
    switch ($date) {
        case 'today':
            $query .= " AND DATE(created_at) = CURDATE()";
            break;
        case 'yesterday':
            $query .= " AND DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            break;
        case 'week':
            $query .= " AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
            break;
        case 'month':
            $query .= " AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
            break;
    }
}

$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $counter = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        // Position badge
        $position_class = 'badge-other';
        if ($row['position'] == 'Assistant Supervisor') $position_class = 'badge-supervisor';
        elseif ($row['position'] == 'Admission Advisor') $position_class = 'badge-advisor';
        elseif ($row['position'] == 'Coach') $position_class = 'badge-coach';
        
        echo "<tr>";
        echo "<td><strong>{$counter}</strong></td>";
        echo "<td><strong>" . htmlspecialchars($row['fullname']) . "</strong></td>";
        echo "<td><span class='badge-position {$position_class}'>" . htmlspecialchars($row['position']) . "</span></td>";
        echo "<td><a href='tel:" . htmlspecialchars($row['phone']) . "' class='phone-link'><i class='fas fa-phone me-1'></i>" . htmlspecialchars($row['phone']) . "</a></td>";
        echo "<td><a href='mailto:" . htmlspecialchars($row['email']) . "' class='email-link'>" . htmlspecialchars($row['email']) . "</a></td>";
        echo "<td>" . htmlspecialchars($row['education']) . "</td>";
        echo "<td>" . htmlspecialchars($row['experience_years']) . "</td>";
        echo "<td>" . date('d M Y', strtotime($row['created_at'])) . "</td>";
        echo "<td>";
        echo "<a href='career-details.php?id=" . $row['id'] . "' class='btn-action btn-view' title='View Details'><i class='fas fa-eye'></i></a>";
        echo "<a href='javascript:void(0)' onclick='deleteApplication(" . $row['id'] . ")' class='btn-action btn-delete' title='Delete'><i class='fas fa-trash'></i></a>";
        echo "</td>";
        echo "</tr>";
        
        $counter++;
    }
} else {
    echo "<tr><td colspan='9' class='text-center py-4'>";
    echo "<i class='fas fa-inbox fa-3x text-muted mb-3'></i>";
    echo "<p class='text-muted'>No applications found</p>";
    echo "</td></tr>";
}

mysqli_close($conn);
?>