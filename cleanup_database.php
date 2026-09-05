<?php
include("include/classes/session.php");

if (php_sapi_name() !== 'cli' && !$session->logged_in) {
    die("Unauthorized");
}

$tables_to_drop = [
    'admissions', 'careers', 'classes', 'clients', 'courses', 
    'expences', 'fees', 'inactive_users', 'inventory', 
    'investments', 'purchases', 'sales', 'sessions', 
    'session_classes', 'students', 'student_attendance', 
    'timetable_overrides', 'visitors'
];

echo "Starting database cleanup...\n";

$database->query("SET FOREIGN_KEY_CHECKS = 0");

foreach ($tables_to_drop as $table) {
    $q = "DROP TABLE IF EXISTS `$table`";
    if ($database->query($q)) {
        echo "Dropped table: $table\n";
    } else {
        echo "Failed to drop table: $table\n";
    }
}

$database->query("SET FOREIGN_KEY_CHECKS = 1");

echo "Database cleanup complete.\n";
?>
