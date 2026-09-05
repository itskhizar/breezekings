<?php
include 'include/classes/session.php';

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database connection failed");
}

$session_id = mysqli_real_escape_string($conn, $_POST['session_id']);
$attendance_date = isset($_POST['attendance_date']) ? mysqli_real_escape_string($conn, $_POST['attendance_date']) : date('Y-m-d');

// Fetch students for the selected session (both Active and Inactive)
$query = "SELECT * FROM students WHERE session_id='$session_id' AND status IN ('Active') ORDER BY id ASC";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $counter = 1;
    
    while ($student = mysqli_fetch_assoc($result)) {
        $student_id = $student['id'];
        
        // Check if attendance already exists for the selected date
        $attendance_query = "SELECT * FROM student_attendance 
                            WHERE student_id='$student_id' 
                            AND session_id='$session_id'
                            AND DATE(created_at)='$attendance_date'";
        
        $attendance_result = mysqli_query($conn, $attendance_query);
        $existing_attendance = mysqli_fetch_assoc($attendance_result);
        
        // Pre-fill values if attendance exists
        $attendance_val = $existing_attendance ? $existing_attendance['attendance'] : '';
        
        $checked = function($val) use ($attendance_val) {
            return ($attendance_val == $val) ? 'checked' : '';
        };
        
        $entry_time = $existing_attendance ? $existing_attendance['entry_time'] : '';
        $exit_time = $existing_attendance ? $existing_attendance['exit_time'] : '';
        $note = $existing_attendance ? $existing_attendance['note'] : '';
        
        // Check if Late
        $is_late = false;
        if (strpos($note, '[Late]') === 0) { // Check if note starts with [Late]
            $is_late = true;
            $note = trim(substr($note, 6)); // Remove [Late] tag for display in note input
        }
?>
<tr>
    <td><?php echo $counter; ?></td>
    <td><?php echo htmlspecialchars($student['registration_no']); ?></td>
    <td><?php echo htmlspecialchars($student['name']); ?></td>
    <td>
        <input type="hidden" name="student_ids[]" value="<?php echo $student_id; ?>">
        
        <div class="attendance-toggle-group">
            <input type="radio" class="attendance-toggle attendance-radio" 
                   id="present_<?php echo $student_id; ?>" 
                   name="attendance_<?php echo $student_id; ?>" 
                   value="Present" <?php echo $checked('Present'); ?>>
            <label for="present_<?php echo $student_id; ?>" class="label-present" title="Present">P</label>
            
            <!-- Late Checkbox (Only visible if Present) -->
            <div class="late-check-wrapper" style="margin-right: 5px; display: <?php echo ($attendance_val == 'Present') ? 'inline-block' : 'none'; ?>;">
                <input type="checkbox" class="late-checkbox" 
                       id="late_<?php echo $student_id; ?>" 
                       name="late_<?php echo $student_id; ?>" 
                       <?php echo $is_late ? 'checked' : ''; ?>>
                <label for="late_<?php echo $student_id; ?>" class="label-late-check" title="Mark as Late" style="cursor: pointer; font-size: 0.8rem; color: #d35400; font-weight: bold;">
                    <i class="fas fa-clock"></i> Late
                </label>
            </div>
            
            <input type="radio" class="attendance-toggle attendance-radio" 
                   id="leave_<?php echo $student_id; ?>" 
                   name="attendance_<?php echo $student_id; ?>" 
                   value="Leave" <?php echo $checked('Leave'); ?>>
            <label for="leave_<?php echo $student_id; ?>" class="label-late" title="Leave">L</label>

            <input type="radio" class="attendance-toggle attendance-radio" 
                   id="absent_<?php echo $student_id; ?>" 
                   name="attendance_<?php echo $student_id; ?>" 
                   value="Absent" <?php echo $checked('Absent'); ?>>
            <label for="absent_<?php echo $student_id; ?>" class="label-absent" title="Absent">A</label>

            <input type="radio" class="attendance-toggle attendance-radio" 
                   id="holiday_<?php echo $student_id; ?>" 
                   name="attendance_<?php echo $student_id; ?>" 
                   value="Holiday" <?php echo $checked('Holiday'); ?>>
            <label for="holiday_<?php echo $student_id; ?>" class="label-holiday" title="Holiday">H</label>

            <input type="radio" class="attendance-toggle attendance-radio" 
                   id="halfday_<?php echo $student_id; ?>" 
                   name="attendance_<?php echo $student_id; ?>" 
                   value="Half Day" <?php echo $checked('Half Day'); ?>>
            <label for="halfday_<?php echo $student_id; ?>" class="label-halfday" title="Half Day">HD</label>
        </div>
    </td>
    
    <td><input type="time" class="time-input" 
               name="entry_time_<?php echo $student_id; ?>" 
               value="<?php echo $entry_time; ?>"></td>
    
    <td><input type="time" class="time-input" 
               name="exit_time_<?php echo $student_id; ?>" 
               value="<?php echo $exit_time; ?>"></td>
    
    <td><input type="text" class="note-input" 
               name="note_<?php echo $student_id; ?>" 
               placeholder="Add note..." 
               value="<?php echo htmlspecialchars($note); ?>"></td>
</tr>
<?php 
        $counter++;
    }
} else {
    echo '<tr><td colspan="7" class="text-center py-5"><div class="empty-state"><i class="fas fa-users mb-2"></i><h4>No Students Found</h4><p>No active students are assigned to this session.</p></div></td></tr>';
}

mysqli_close($conn);
?>