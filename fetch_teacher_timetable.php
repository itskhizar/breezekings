<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database Error");
}

if (isset($_POST['session_id'])) {
    $week_number = isset($_POST['week_number']) ? intval($_POST['week_number']) : 1;
    $session_id = intval($_POST['session_id']);
    $teacher_id = intval($_GET['id']);

    // Fetch session data
    $session_q = "SELECT s.* FROM sessions s WHERE s.id = '$session_id' LIMIT 1";
    $session_res = mysqli_query($conn, $session_q);
    $session_data = mysqli_fetch_assoc($session_res);

    if (!$session_data) {
        echo "<div class='alert alert-warning text-center py-4'>";
        echo "<i class='fas fa-exclamation-triangle fa-2x mb-3 text-warning'></i><br>";
        echo "<h5>No active session found.</h5>";
        echo "<p class='mb-0'>Please ensure you are assigned to a valid session.</p>";
        echo "</div>";
        exit;
    }

    // Fetch teacher info
    $teachers_q = "SELECT * FROM teachers WHERE id = '$teacher_id' LIMIT 1";
    $teachers_res = mysqli_query($conn, $teachers_q);
    $teacher = mysqli_fetch_assoc($teachers_res);

    // Fetch classes for this session and week from the new session_classes table with overrides
    $classes_q = "SELECT sc.*, o.new_start_time, o.new_end_time, o.new_duration, o.status as override_status, o.reason as override_reason
                  FROM session_classes sc 
                  LEFT JOIN timetable_overrides o ON sc.id = o.class_id
                  WHERE sc.session_id = '$session_id' AND sc.week_number = '$week_number' 
                  ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
    $classes_res = mysqli_query($conn, $classes_q);
    $week_classes = [];
    while ($row = mysqli_fetch_assoc($classes_res)) {
        // Apply overrides if present
        if (!empty($row['new_start_time'])) $row['start_time'] = $row['new_start_time'];
        if (!empty($row['new_end_time'])) $row['end_time'] = $row['new_end_time'];
        if (!empty($row['new_duration'])) $row['duration_hours'] = $row['new_duration'];
        if (!empty($row['override_status'])) $row['class_status'] = $row['override_status'];
        else $row['class_status'] = 'Active';

        $week_classes[$row['day_of_week']] = $row;
    }

    $all_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    
    // Fetch all courses for this session
    $course_ids = $session_data['course_id'];
    $course_titles = [];
    if (!empty($course_ids)) {
        $course_id_array = array_map('trim', explode(',', $course_ids));
        $course_id_list = implode(',', $course_id_array);
        $courses_q = "SELECT course_title FROM courses WHERE id IN ($course_id_list)";
        $courses_res = mysqli_query($conn, $courses_q);
        while ($course_row = mysqli_fetch_assoc($courses_res)) {
            $course_titles[] = $course_row['course_title'];
        }
    }
    $course_display = !empty($course_titles) ? implode(', ', $course_titles) : 'No Course';
    ?>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Timetable - Week <?php echo $week_number; ?></h4>
                <div class="text-muted small">
                    Course: <?php echo htmlspecialchars($session_data['title']); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <?php foreach ($all_days as $day): ?>
                                        <th class="text-center" style="min-width: 140px;"><?php echo $day; ?></th>
                                    <?php endforeach; ?>
                                    <th class="text-center bg-light" style="width: 60px;">
                                        <i class="fas fa-print"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach ($all_days as $day): 
                                        $class = isset($week_classes[$day]) ? $week_classes[$day] : null;
                                        $bg_style = ($class && $class['class_status'] == 'Cancelled') ? 'background-color: #fff5f5;' : '';
                                    ?>
                                        <td class="p-3" style="vertical-align: top; <?php echo $bg_style; ?>">
                                            <?php if ($class): 
                                                $time_slot = date("g:i A", strtotime($class['start_time'])) . " - " . date("g:i A", strtotime($class['end_time']));
                                                $is_overridden = !empty($class['new_start_time']) || !empty($class['new_end_time']) || !empty($class['new_duration']) || $class['class_status'] == 'Cancelled';
                                            ?>
                                                <div class="timetable-cell p-2 rounded <?php echo $class['class_status'] == 'Cancelled' ? 'bg-danger-subtle' : ($is_overridden ? 'bg-warning-subtle' : ''); ?>">
                                                    <div class="mb-2 d-flex justify-content-between align-items-start">
                                                        <span class="badge bg-primary text-white"><?php echo date("d M", strtotime($class['class_date'])); ?></span>
                                                        
                                                        <?php if ($class['class_status'] == 'Cancelled'): ?>
                                                            <span class="badge bg-danger text-white">Cancelled</span>
                                                        <?php elseif ($is_overridden): ?>
                                                            <span class="badge bg-warning text-dark" title="<?php echo htmlspecialchars($class['override_reason'] ?? ''); ?>">Updated</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="mb-2 <?php echo $class['class_status'] == 'Cancelled' ? 'text-decoration-line-through text-muted' : ''; ?>">
                                                        <strong class="text-dark d-block" style="font-size: 0.75rem;">Course: <?php echo htmlspecialchars($course_display); ?></strong>
                                                        <div class="mt-1 small"><i class="far fa-clock text-muted me-1"></i><?php echo $time_slot; ?></div>
                                                        <div class="text-info extra-small fw-semibold mt-1">(<?php echo (float)$class['duration_hours']; ?> hrs)</div>
                                                    </div>
                                                    
                                                    <?php if (!empty($class['override_reason'])): ?>
                                                        <div class="extra-small text-muted italic border-top pt-1 mt-1">
                                                            <i class="fas fa-info-circle me-1"></i> <?php echo htmlspecialchars($class['override_reason']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-center text-muted py-3">
                                                    <i class="fas fa-times-circle opacity-25"></i><br>
                                                    <small>No Class</small>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="text-center align-middle bg-light">
                                        <button class="btn btn-sm btn-link text-muted" onclick="window.print()" title="Print Timetable">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .timetable-cell {
        font-size: 0.8rem;
        transition: all 0.2s;
    }
    .timetable-cell:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .bg-warning-subtle { background-color: #fff9db !important; }
    .bg-danger-subtle { background-color: #fff5f5 !important; }

    .timetable-cell i {
        width: 15px;
    }
    .table thead th {
        font-weight: 600;
        padding: 12px;
        font-size: 0.95rem;
    }
    .table td {
        border: 1px solid #dee2e6;
    }
    @media print {
        .btn, button, .no-print {
            display: none !important;
        }
        table th:last-child,
        table td:last-child {
            display: none !important;
        }
    }
    </style>

<?php } // end if isset session_id ?>
