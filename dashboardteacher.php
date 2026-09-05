dashboardteacher.php
 <!-- Dashboard Cards Section -->
                <div class="row">
                    <!-- Active Sessions Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-3" style="margin-top: 0px;">
                        <!-- <div class="card shadow-sm h-100">
                            <div class="card-header text-white">
                                <h6 class="mb-0"><i class="fas fa-chalkboard-teacher mr-2"></i>Active Sessions</h6>
                            </div>
                            <div class="card-body">
                                <h2 class="text-primary mb-3"><?php echo $database->numberOfSessionsByTeacher($teacher_id); ?></h2>
                                <div class="mb-2">
                                    <small class="text-muted">This Week:</small>
                                    <strong class="d-block"><?php echo $database->numberOfWeeklySessionsByTeacher($teacher_id); ?> Sessions</strong>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Next Session:</small>
                                    <strong class="d-block"><?php echo $next_session_time; ?></strong>
                                </div>
                                <a href="view_sessions.php" class="btn btn-sm btn-outline-primary btn-block mt-3">View All Sessions</a>
                            </div>
                        </div> -->
                    </div>

                    <!-- Students Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <!-- <div class="card shadow-sm h-100">
                            <div class="card-header text-white">
                                <h6 class="mb-0"><i class="fas fa-users mr-2"></i>Students</h6>
                            </div>
                            <div class="card-body">
                                <h2 class="text-success mb-3"><?php echo $database->numberOfStudentsByTeacher($teacher_id); ?></h2>
                                <div class="mb-2">
                                    <small class="text-muted">Average Attendance:</small>
                                    <strong class="d-block"><?php echo $avg_attendance; ?>%</strong>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: <?php echo $avg_attendance; ?>%" 
                                         aria-valuenow="<?php echo $avg_attendance; ?>" 
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="text-muted">Overall Performance</small>
                                <a href="view_students.php" class="btn btn-sm btn-outline-success btn-block mt-3">View Students</a>
                            </div>
                        </div> -->
                    </div>

                    <!-- Tasks & Assignments Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <!-- <div class="card shadow-sm h-100">
                            <div class="card-header text-white">
                                <h6 class="mb-0"><i class="fas fa-tasks mr-2"></i>Tasks & Assignments</h6>
                            </div>
                            <div class="card-body">
                                <h2 class="text-info mb-3"><?php echo $database->numberOfTasksByTeacher($teacher_id); ?></h2>
                                <div class="mb-2">
                                    <small class="text-muted">Pending Review:</small>
                                    <strong class="d-block"><?php echo $database->numberOfPendingTaskReviews($teacher_id); ?> Submissions</strong>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Due This Week:</small>
                                    <strong class="d-block text-warning"><?php echo $database->numberOfTasksDueThisWeek($teacher_id); ?> Tasks</strong>
                                </div>
                                <a href="manage_tasks.php" class="btn btn-sm btn-outline-info btn-block mt-3">Manage Tasks</a>
                            </div>
                        </div> -->
                    </div>

                    <!-- Attendance Card -->
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <!-- <div class="card shadow-sm h-100">
                            <div class="card-header text-dark">
                                <h6 class="mb-0"><i class="fas fa-calendar-check mr-2"></i>Attendance</h6>
                            </div>
                            <div class="card-body">
                                <h2 class="text-warning mb-3"><?php echo $database->getTodayAttendanceCount($teacher_id); ?></h2>
                                <div class="mb-2">
                                    <small class="text-muted">Today's Status:</small>
                                    <strong class="d-block">
                                        <?php echo $database->getTodayAttendanceCount($teacher_id); ?> / 
                                        <?php echo $database->getTotalStudentsToday($teacher_id); ?> Present
                                    </strong>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">This Month:</small>
                                    <strong class="d-block"><?php echo $monthly_attendance_avg; ?>% Average</strong>
                                </div>
                                <a href="mark_attendance.php" class="btn btn-sm btn-outline-warning btn-block mt-3">Mark Attendance</a>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- Sessions by Category Section -->
                <!-- <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-layer-group mr-2"></i>My Sessions by Category</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Category</th>
                                                <th>Course</th>
                                                <th>Session Title</th>
                                                <th>Schedule</th>
                                                <th>Students</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch sessions assigned to this teacher
                                            $query = "SELECT s.*, c.course_title, cat.category_name, 
                                                      COUNT(DISTINCT sa.student_id) as student_count
                                                      FROM sessions s
                                                      LEFT JOIN courses c ON s.course_id = c.course_id
                                                      LEFT JOIN categories cat ON c.category_id = cat.category_id
                                                      LEFT JOIN session_attendance sa ON s.session_id = sa.session_id
                                                      WHERE s.teacher_id = '$teacher_id'
                                                      GROUP BY s.session_id
                                                      ORDER BY s.session_date DESC, s.session_time DESC";
                                            $result = $database->query($query);
                                            
                                            if($result && $result->num_rows > 0) {
                                                while($session = $result->fetch_assoc()) {
                                                    $session_date = date("d M Y", strtotime($session['session_date']));
                                                    $session_time = date("h:i A", strtotime($session['session_time']));
                                                    
                                                    // Determine status
                                                    $current_datetime = date("Y-m-d H:i:s");
                                                    $session_datetime = $session['session_date'] . ' ' . $session['session_time'];
                                                    
                                                    if($current_datetime < $session_datetime) {
                                                        $status = '<span class="badge badge-info">Upcoming</span>';
                                                    } elseif($current_datetime > $session_datetime) {
                                                        $status = '<span class="badge badge-secondary">Completed</span>';
                                                    } else {
                                                        $status = '<span class="badge badge-success">Active</span>';
                                                    }
                                            ?>
                                            <tr>
                                                <td><span class="badge badge-primary"><?php echo $session['category_name']; ?></span></td>
                                                <td><?php echo $session['course_title']; ?></td>
                                                <td><strong><?php echo $session['session_title']; ?></strong></td>
                                                <td>
                                                    <small class="d-block"><?php echo $session_date; ?></small>
                                                    <small class="text-muted"><?php echo $session_time; ?></small>
                                                </td>
                                                <td><span class="badge badge-pill badge-success"><?php echo $session['student_count']; ?></span></td>
                                                <td><?php echo $status; ?></td>
                                                <td>
                                                    <a href="session_details.php?id=<?php echo $session['session_id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="mark_attendance.php?session_id=<?php echo $session['session_id']; ?>" 
                                                       class="btn btn-sm btn-outline-success" title="Attendance">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="7" class="text-center text-muted">No sessions assigned yet</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Course & Task Overview Section -->
                <div class="row mt-3">
                    <!-- <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-book mr-2"></i>Courses Overview</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                // Fetch courses with sessions assigned to teacher
                                $course_query = "SELECT c.*, cat.category_name, COUNT(DISTINCT s.session_id) as session_count
                                                FROM courses c
                                                LEFT JOIN categories cat ON c.category_id = cat.category_id
                                                LEFT JOIN sessions s ON c.course_id = s.course_id
                                                WHERE s.teacher_id = '$teacher_id'
                                                GROUP BY c.course_id
                                                ORDER BY c.course_title";
                                $course_result = $database->query($course_query);
                                
                                if($course_result && $course_result->num_rows > 0) {
                                    while($course = $course_result->fetch_assoc()) {
                                ?>
                                <div class="mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><?php echo $course['course_title']; ?></h6>
                                            <small class="text-muted">
                                                <span class="badge badge-info"><?php echo $course['category_name']; ?></span>
                                                <span class="ml-2"><?php echo $course['session_count']; ?> Sessions</span>
                                            </small>
                                        </div>
                                        <a href="course_details.php?id=<?php echo $course['course_id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">View</a>
                                    </div>
                                </div>
                                <?php
                                    }
                                } else {
                                    echo '<p class="text-center text-muted">No courses assigned yet</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i>Recent Tasks</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                // Fetch recent tasks created by teacher
                                $task_query = "SELECT t.*, c.course_title, 
                                              COUNT(ts.submission_id) as submission_count
                                              FROM tasks t
                                              LEFT JOIN courses c ON t.course_id = c.course_id
                                              LEFT JOIN task_submissions ts ON t.task_id = ts.task_id
                                              WHERE t.teacher_id = '$teacher_id'
                                              GROUP BY t.task_id
                                              ORDER BY t.created_date DESC
                                              LIMIT 5";
                                $task_result = $database->query($task_query);
                                
                                if($task_result && $task_result->num_rows > 0) {
                                    while($task = $task_result->fetch_assoc()) {
                                        $due_date = date("d M Y", strtotime($task['due_date']));
                                        $is_overdue = strtotime($task['due_date']) < time();
                                ?>
                                <div class="mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?php echo $task['task_title']; ?></h6>
                                            <small class="text-muted d-block"><?php echo $task['course_title']; ?></small>
                                            <small class="<?php echo $is_overdue ? 'text-danger' : 'text-muted'; ?>">
                                                <i class="far fa-clock mr-1"></i>Due: <?php echo $due_date; ?>
                                            </small>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-pill badge-info"><?php echo $task['submission_count']; ?> Submissions</span>
                                            <a href="task_details.php?id=<?php echo $task['task_id']; ?>" 
                                               class="btn btn-sm btn-outline-primary d-block mt-2">Review</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                } else {
                                    echo '<p class="text-center text-muted">No tasks created yet</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div> -->
                </div>

                <!-- Academic Statistics Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Teaching Statistics</h5>
                            </div>
                            <!-- <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                                        <h3 class="text-primary mb-1"><?php echo $database->numberOfSessionsByTeacher($teacher_id); ?></h3>
                                        <small class="text-muted">Total Sessions</small>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                                        <h3 class="text-success mb-1"><?php echo $database->numberOfStudentsByTeacher($teacher_id); ?></h3>
                                        <small class="text-muted">Total Students</small>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                                        <h3 class="text-info mb-1"><?php echo $database->numberOfTasksByTeacher($teacher_id); ?></h3>
                                        <small class="text-muted">Tasks Created</small>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-6 mb-3">
                                        <h3 class="text-warning mb-1"><?php echo $avg_attendance; ?>%</h3>
                                        <small class="text-muted">Avg Attendance</small>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>