<?php
include 'include/classes/session.php';
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Database Error");
}

if (isset($_POST['session_id'])) {

    $session_id = intval($_POST['session_id']);

    // Fetch session info
    $session_q = "SELECT s.*, c.course_title 
                  FROM sessions s 
                  LEFT JOIN courses c ON s.course_id = c.id
                  WHERE s.id = '$session_id' LIMIT 1";
    $session_res = mysqli_query($conn, $session_q);
    $session = mysqli_fetch_assoc($session_res);

    if (!$session) {
        echo "<div class='alert alert-danger'>Session not found.</div>";
        exit;
    }

    $start = date("h:i A", strtotime($session['start_time']));
    $end   = date("h:i A", strtotime($session['end_time']));

    // Fetch students of this session
    $student_q = "SELECT * FROM students WHERE session_id = '$session_id'";
    $student_res = mysqli_query($conn, $student_q);

    ?>
    
    <!-- Card Layout -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-white">Class Timetable</h5>
        </div>

        <div class="card-body">

            <!-- Session Details -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="font-weight-bold">Course:</label>
                    <p class="form-control"><?php echo $session['course_title']; ?></p>
                </div>

                <div class="col-md-4">
                    <label class="font-weight-bold">Session Time:</label>
                    <p class="form-control"><?php echo $start . " - " . $end; ?></p>
                </div>

               <div class="col-md-4">
    <label class="font-weight-bold">Days:</label>
    <ul class="form-control" style="list-style:none; padding-left:0; margin-bottom:0;">
        <li>Monday</li>
        <li>Tuesday</li>
        <li>Wednesday</li>
        <li>Thursday</li>
        <li>Friday</li>
        <li>Saturday</li>
    </ul>
</div>

            </div>

            <!-- Student Table -->
            <h5 class="text-secondary mb-3">Registered Students</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Registration No</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Slot No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 1;
                        while ($stu = mysqli_fetch_assoc($student_res)) { ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo htmlspecialchars($stu['registration_no']); ?></td>
                                <td><?php echo htmlspecialchars($stu['name']); ?></td>
                                <td><?php echo htmlspecialchars($stu['mobile_no']); ?></td>
                                <td><?php echo htmlspecialchars($stu['email']); ?></td>
                                <td><?php echo htmlspecialchars($stu['slot']); ?></td>
                            </tr>
                        <?php } ?>

                        <?php if ($count == 1) { ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No students found</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

<?php } ?>
