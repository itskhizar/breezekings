<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}
$teacher_id = intval($_GET['teacher_id']);
$session_id = intval($_GET['session_id']);

if(!$teacher_id || !$session_id){
    die("Error: teacher_id or session_id missing.");
}

// Attendance statistics
$stats_query = "
    SELECT 
        SUM(attendance='Present') AS total_present,
        SUM(attendance='Leave') AS total_leave,
        SUM(attendance='Absent') AS total_absent,
        SUM(attendance='Half Day') AS total_halfday,
        SUM(attendance='Holiday') AS total_holiday,
        COUNT(*) AS total_days
    FROM teacher_attendance
    WHERE teacher_id=$teacher_id
    AND session_id=$session_id
";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

$total_present = $stats['total_present'] ?? 0;
$total_leave = $stats['total_leave'] ?? 0;
$total_absent = $stats['total_absent'] ?? 0;
$total_halfday = $stats['total_halfday'] ?? 0;
$total_holiday = $stats['total_holiday'] ?? 0;
$total_days = $stats['total_days'] ?? 0;

$denominator = $total_days - $total_leave - $total_holiday;
$attendance_percentage = $denominator > 0 ? round(($total_present) / $denominator * 100, 2) : 0;

// Recent attendance (last 10 days)
$recent_query = "
    SELECT DATE(created_at) AS attendance_date, attendance, entry_time, exit_time, note
    FROM teacher_attendance
    WHERE teacher_id=$teacher_id AND session_id=$session_id
    ORDER BY created_at DESC
    LIMIT 10
";
$recent_result = mysqli_query($conn, $recent_query);
?>

<div class="profile-card">
    <div class="attendance-stats d-flex gap-3">
        <div class="stat-box present p-2 bg-success text-white rounded">Present: <?php echo $total_present; ?></div>
        <div class="stat-box late p-2 bg-warning text-dark rounded">Leave: <?php echo $total_leave; ?></div>
        <div class="stat-box absent p-2 bg-danger text-white rounded">Absent: <?php echo $total_absent; ?></div>
        <div class="stat-box halfday p-2 bg-info text-white rounded">Half Day: <?php echo $total_halfday; ?></div>
        <div class="stat-box holiday p-2 bg-secondary text-white rounded">Holiday: <?php echo $total_holiday; ?></div>
    </div>

    <div class="mt-3">
        <h6>Attendance Percentage: <strong><?php echo $attendance_percentage; ?>%</strong></h6>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $attendance_percentage; ?>%"></div>
        </div>
    </div>

    <div class="recent-attendance mt-4">
        <h5>Recent Attendance (Last 10 Days)</h5>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Entry</th>
                    <th>Exit</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(mysqli_num_rows($recent_result) > 0){
                    while($row = mysqli_fetch_assoc($recent_result)){
                        echo "<tr>
                                <td>".date('d M Y', strtotime($row['attendance_date']))."</td>
                                <td>{$row['attendance']}</td>
                                <td>".($row['entry_time'] ? date('h:i A', strtotime($row['entry_time'])) : '-')."</td>
                                <td>".($row['exit_time'] ? date('h:i A', strtotime($row['exit_time'])) : '-')."</td>
                                <td>".htmlspecialchars($row['note'] ?: '-')."</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No attendance found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
