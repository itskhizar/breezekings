<?php
include("include/classes/constants.php");
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$student_id = 1; // Example
$course_id = 1;   // Example

$query = "
    SELECT 
        t.id,
        t.title,
        t.description,
        t.max_time,
        t.total_marks,
        t.created_at,
        t.exercise_ids,
        c.course_title,
        cat.category as category_name,
        (SELECT COUNT(*) 
         FROM task_assign 
         WHERE task_id = t.id 
         AND student_id = '$student_id' 
         AND excercise_id IS NOT NULL 
         AND status IN ('Turned In', 'Returned', 'Graded')) as submitted_exercises,
        (SELECT COUNT(*) 
         FROM task_assign 
         WHERE task_id = t.id 
         AND student_id = '$student_id' 
         AND excercise_id IS NOT NULL 
         AND status = 'Graded') as graded_exercises,
        COALESCE(
            (SELECT status 
             FROM task_assign 
             WHERE task_id = t.id 
             AND student_id = '$student_id' 
             LIMIT 1), 
            'Pending'
        ) as status
    FROM tasks t
    INNER JOIN courses c ON t.course_id = c.id
    LEFT JOIN categories cat ON t.category_id = cat.id
    WHERE t.course_id = '$course_id'
    ORDER BY t.created_at ASC
";

echo "Running query...\n";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "SQL ERROR: " . mysqli_error($conn) . "\n";
} else {
    echo "Query SUCCESS. Rows: " . mysqli_num_rows($result) . "\n";
    if ($row = mysqli_fetch_assoc($result)) {
        print_r($row);
    }
}

mysqli_close($conn);
