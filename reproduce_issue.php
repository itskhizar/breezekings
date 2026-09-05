<?php
include("include/classes/database.php");

// Instantiate MySQLDB 
$database = new MySQLDB();
$db = $database->connection;

$inputs = ["10", "010", " 10 ", "10.0"];

foreach ($inputs as $max_marks_to_test) {
    $excercise_title = "Test Max Marks " . $max_marks_to_test . " " . time();
    
    echo "Testing insertion with max_marks = '$max_marks_to_test'\n";

    $inserted = $database->addexcercise(
        0, 0, "", "", 
        $excercise_title, "", "", "", 
        $max_marks_to_test
    );

    if ($inserted) {
        $insert_id = mysqli_insert_id($db);
        $q = "SELECT * FROM excercises WHERE id = $insert_id";
        $r = mysqli_query($db, $q);
        $row = mysqli_fetch_assoc($r);

        echo "Retrieved max_marks: " . $row['max_marks'] . "\n";
        
        // Cleanup
        mysqli_query($db, "DELETE FROM excercises WHERE id = $insert_id");
    } else {
        echo "Insertion failed.\n";
    }
    echo "-------------------\n";
}
?>
