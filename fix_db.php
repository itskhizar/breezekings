<?php
// Inline constants to avoid include path issues
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "academy");

$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

if (!$connection) {
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
}

function executeQuery($conn, $sql, $description) {
    echo "Executing: $description ... ";
    try {
        if (mysqli_query($conn, $sql)) {
            echo "[OK]<br>";
        } else {
            // Check if error is "Duplicate column name" which is fine
            if (strpos(mysqli_error($conn), "Duplicate column name") !== false) {
                 echo "[ALREADY EXISTS]<br>";
            } else {
                echo "[ERROR] " . mysqli_error($conn) . "<br>";
            }
        }
    } catch (Exception $e) {
        echo "[EXCEPTION] " . $e->getMessage() . "<br>";
    }
}

// 1. Add learning_hours to courses
executeQuery($connection, 
    "ALTER TABLE courses ADD COLUMN learning_hours INT DEFAULT 0", 
    "Add learning_hours to courses"
);

// 2. Change credit_hours to total_marks in tasks
// First check if 'credit_hours' exists
$check = mysqli_query($connection, "SHOW COLUMNS FROM tasks LIKE 'credit_hours'");
if(mysqli_num_rows($check) > 0) {
    executeQuery($connection, 
        "ALTER TABLE tasks CHANGE credit_hours total_marks INT DEFAULT 0", 
        "Rename credit_hours to total_marks in tasks"
    );
} else {
    echo "Column 'credit_hours' not found in tasks (maybe already renamed or deleted).<br>";
    // Check if total_marks exists
    $check2 = mysqli_query($connection, "SHOW COLUMNS FROM tasks LIKE 'total_marks'");
    if(mysqli_num_rows($check2) == 0) {
        executeQuery($connection, 
            "ALTER TABLE tasks ADD COLUMN total_marks INT DEFAULT 0", 
            "Add total_marks to tasks"
        );
    }
}


// 3. Add max_marks to excercises
executeQuery($connection, 
    "ALTER TABLE excercises ADD COLUMN max_marks INT DEFAULT 10", 
    "Add max_marks to excercises"
);

echo "Database update completed.<br>";

// Verify
echo "<h3>Verification:</h3>";
echo "<b>Courses Columns:</b><br>";
$res = mysqli_query($connection, "SHOW COLUMNS FROM courses");
$found = false;
while($row = mysqli_fetch_assoc($res)) {
    if($row['Field'] == 'learning_hours') {
        echo "- learning_hours found!<br>";
        $found = true;
    }
}
if(!$found) echo "- learning_hours NOT found!<br>";

echo "<b>Tasks Columns:</b><br>";
$res = mysqli_query($connection, "SHOW COLUMNS FROM tasks");
$found = false;
while($row = mysqli_fetch_assoc($res)) {
    if($row['Field'] == 'total_marks') {
        echo "- total_marks found!<br>";
        $found = true;
    }
}
if(!$found) echo "- total_marks NOT found!<br>";

echo "<b>Excercises Columns:</b><br>";
$res = mysqli_query($connection, "SHOW COLUMNS FROM excercises");
$found = false;
while($row = mysqli_fetch_assoc($res)) {
    if($row['Field'] == 'max_marks') {
        echo "- max_marks found!<br>";
        $found = true;
    }
}
if(!$found) echo "- max_marks NOT found!<br>";

?>
