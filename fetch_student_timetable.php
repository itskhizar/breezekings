<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database Error");
}

if (isset($_POST['session_id']) || isset($_POST['student_id'])) {
    $session_id = isset($_POST['session_id']) ? intval($_POST['session_id']) : 0;
    
    // If student_id is provided, prioritize fetching the session from the student record
    if (isset($_POST['student_id'])) {
        $student_id = intval($_POST['student_id']);
        $student_q = "SELECT session_id FROM students WHERE id = '$student_id' LIMIT 1";
        $student_res = mysqli_query($conn, $student_q);
        if ($student_res && mysqli_num_rows($student_res) > 0) {
            $student_row = mysqli_fetch_assoc($student_res);
            $session_id = $student_row['session_id'];
        }
    }

    // Fetch session info
    $session_q = "SELECT s.*, c.course_title, s.session_start_date
                  FROM sessions s 
                  LEFT JOIN courses c ON FIND_IN_SET(c.id, s.course_id)
                  WHERE s.id = '$session_id' LIMIT 1";
    $session_res = mysqli_query($conn, $session_q);
    $session_data = mysqli_fetch_assoc($session_res);

    if (!$session_data) {
        echo "<div class='alert alert-warning text-center py-4'>";
        echo "<i class='fas fa-exclamation-triangle fa-2x mb-3 text-warning'></i><br>";
        echo "<h5>No active session found.</h5>";
        echo "<p class='mb-0'>Please ensure this student is assigned to a valid session.</p>";
        echo "</div>";
        exit;
    }

    // Calculate current week based on session_start_date
    $current_week = 1;
    if (!empty($session_data['session_start_date'])) {
        $start_date = new DateTime($session_data['session_start_date']);
        $today = new DateTime();
        $interval = $start_date->diff($today);
        $days_diff = $interval->days;
        
        // If today is before start date, it's week 1
        if ($interval->invert == 1) {
            $current_week = 1;
        } else {
            $current_week = floor($days_diff / 7) + 1;
        }
    }
    
    // Default to current week if not specified, cap at 8 weeks
    $week_number = isset($_POST['week_number']) ? intval($_POST['week_number']) : $current_week;
    if ($week_number > 8) $week_number = 8;
    if ($week_number < 1) $week_number = 1;

    // Fetch teacher info
    $teacher_id = $session_data['teacher_id'];
    $teacher_q = "SELECT * FROM teachers WHERE id = '$teacher_id' LIMIT 1";
    $teacher_res = mysqli_query($conn, $teacher_q);
    $teacher = mysqli_fetch_assoc($teacher_res);

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
    ?>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Timetable - Week <?php echo $week_number; ?></h4>
                <div class="text-muted small">
                    Course Start: <?php echo date("d M Y", strtotime($session_data['session_start_date'])); ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 printable-timetable">
                            <thead>
                                <tr class="bg-light">
                                    <?php foreach ($all_days as $day): ?>
                                        <th class="text-center" data-day="<?php echo $day; ?>"><?php echo $day; ?></th>
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
                                                        <strong class="text-dark d-block" style="font-size: 0.75rem;">Course: <?php echo htmlspecialchars($session_data['course_title']); ?></strong>
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
                                        <button class="btn btn-sm btn-link text-muted" onclick="printTimetableRow(this)">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <br><br>
                    
                    
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
        .printable-timetable th:last-child,
        .printable-timetable td:last-child {
            display: none !important;
        }
    }

    </style>
<script>
function printTimetableRow(btn) {
    const row = btn.closest('tr');
    const table = btn.closest('table');
    const headers = table.querySelectorAll('thead th[data-day]');

    // Build printable table
    let printHtml = `
        <html>
        <head>
            <title>Timetable</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                h2 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 13px;
                }
                th, td {
                    border: 1px solid #333;
                    padding: 10px;
                    vertical-align: top;
                }
                th {
                    background: #f5f5f5;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <h2>Weekly Timetable</h2>
            <table>
                <thead>
                    <tr>`;

    // Add headers with dates
    headers.forEach(h => {
        const dayName = h.dataset.day;
        const date = getNextDate(dayName);
        printHtml += `<th>${dayName}<br><small>${date}</small></th>`;
    });

    printHtml += `</tr></thead><tbody><tr>`;

    // Add row cells (ignore last print column)
    row.querySelectorAll('td:not(:last-child)').forEach(td => {
        printHtml += `<td>${td.innerHTML}</td>`;
    });

    printHtml += `
                </tr>
            </tbody>
        </table>
        </body>
        </html>`;

    // Open print window
    const win = window.open('', '', 'width=900,height=650');
    win.document.write(printHtml);
    win.document.close();
    win.focus();
    win.print();
    win.close();
}

// Get upcoming date for a given day name
function getNextDate(dayName) {
    const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const today = new Date();
    const targetDay = days.indexOf(dayName);
    const diff = (targetDay + 7 - today.getDay()) % 7;
    const result = new Date(today);
    result.setDate(today.getDate() + diff);

    return result.toLocaleDateString(undefined, {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}
</script>

<?php } ?>