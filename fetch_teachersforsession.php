<?php
include 'include/classes/session.php';

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Database connection failed");
}

$session_id = mysqli_real_escape_string($conn, $_POST['session_id']);
$attendance_date = isset($_POST['attendance_date']) ? mysqli_real_escape_string($conn, $_POST['attendance_date']) : date('Y-m-d');

/* ---------------------------
   FETCH TEACHER ID FROM SESSION
---------------------------- */
$sessionQuery = "SELECT teacher_id FROM sessions WHERE id='$session_id' ";
$sessionResult = mysqli_query($conn, $sessionQuery);

if ($sessionResult && mysqli_num_rows($sessionResult) > 0) {
    $sessionRow = mysqli_fetch_assoc($sessionResult);
    $teacher_id = $sessionRow['teacher_id'];

    /* ---------------------------
       FETCH TEACHER DETAILS
    ---------------------------- */
    $teacherQuery = "SELECT * FROM teachers WHERE id='$teacher_id' LIMIT 1";
    $teacherResult = mysqli_query($conn, $teacherQuery);

    if ($teacherResult && mysqli_num_rows($teacherResult) > 0) {
        $teacher = mysqli_fetch_assoc($teacherResult);

        /* -------------------------------
           CHECK IF ATTENDANCE EXISTS FOR SELECTED DATE
        -------------------------------- */
        $attendanceQuery = "
            SELECT * FROM teacher_attendance
            WHERE teacher_id='$teacher_id'
            AND session_id='$session_id'
            AND DATE(created_at)='$attendance_date'
        ";
        $attendanceResult = mysqli_query($conn, $attendanceQuery);
        $existing = mysqli_fetch_assoc($attendanceResult);

        // Pre-filled values
        $attendance_val = $existing ? $existing['attendance'] : '';
        
        $checked = function($val) use ($attendance_val) {
            return ($attendance_val == $val) ? 'checked' : '';
        };

        $entry_time = $existing['entry_time'] ?? '';
        $exit_time  = $existing['exit_time'] ?? '';
        $note       = $existing['note'] ?? '';
?>

<tr>
    <td>1</td>
    <td><?php echo htmlspecialchars($teacher['registration_no']); ?></td>
    <td><?php echo htmlspecialchars($teacher['name']); ?></td>

    <td>
        <input type="hidden" name="teacher_ids[]" value="<?php echo $teacher_id; ?>">

        <div class="attendance-toggle-group">
            <input type="radio" class="attendance-toggle attendance-radio" 
                   id="present_<?php echo $teacher_id; ?>" 
                   name="attendance_<?php echo $teacher_id; ?>" 
                   value="Present" <?php echo $checked('Present'); ?>>
            <label for="present_<?php echo $teacher_id; ?>" class="label-present" title="Present">P</label>
            
            <input type="radio" class="attendance-toggle attendance-radio" 
                   id="leave_<?php echo $teacher_id; ?>" 
                   name="attendance_<?php echo $teacher_id; ?>" 
                   value="Leave" <?php echo $checked('Leave'); ?>>
            <label for="leave_<?php echo $teacher_id; ?>" class="label-late" title="Leave">L</label>

            <input type="radio" class="attendance-toggle attendance-radio" 
                   id="absent_<?php echo $teacher_id; ?>" 
                   name="attendance_<?php echo $teacher_id; ?>" 
                   value="Absent" <?php echo $checked('Absent'); ?>>
            <label for="absent_<?php echo $teacher_id; ?>" class="label-absent" title="Absent">A</label>

            <input type="radio" class="attendance-toggle attendance-radio" 
                   id="holiday_<?php echo $teacher_id; ?>" 
                   name="attendance_<?php echo $teacher_id; ?>" 
                   value="Holiday" <?php echo $checked('Holiday'); ?>>
            <label for="holiday_<?php echo $teacher_id; ?>" class="label-holiday" title="Holiday">H</label>

            <input type="radio" class="attendance-toggle attendance-radio" 
                   id="halfday_<?php echo $teacher_id; ?>" 
                   name="attendance_<?php echo $teacher_id; ?>" 
                   value="Half Day" <?php echo $checked('Half Day'); ?>>
            <label for="halfday_<?php echo $teacher_id; ?>" class="label-halfday" title="Half Day">HD</label>
        </div>
    </td>

    <td><input type="time" name="entry_time_<?php echo $teacher_id; ?>" class="time-input" value="<?php echo $entry_time; ?>"></td>
    <td><input type="time" name="exit_time_<?php echo $teacher_id; ?>" class="time-input" value="<?php echo $exit_time; ?>"></td>
    <td><input type="text" name="note_<?php echo $teacher_id; ?>" class="note-input" value="<?php echo htmlspecialchars($note); ?>" placeholder="Add note..."></td>
</tr>

<?php
    } else {
        echo '<tr><td colspan="7" class="text-center">Teacher not found</td></tr>';
    }
} else {
    echo '<tr><td colspan="7" class="text-center">Session not found</td></tr>';
}

mysqli_close($conn);
?>
