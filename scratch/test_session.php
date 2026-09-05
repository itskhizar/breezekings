<?php
// Test if the Session class has the change_accountdetails method
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['PHP_SELF'] = '/blogging/scratch/test_session.php';

include(__DIR__ . '/../include/classes/session.php');

echo "Session class loaded.\n";
echo "Methods in Session:\n";
$methods = get_class_methods('Session');
foreach ($methods as $m) {
    echo "  - $m\n";
}

echo "\nchange_accountdetails exists: " . (method_exists($session, 'change_accountdetails') ? 'YES' : 'NO') . "\n";
echo "changepassword exists: " . (method_exists($session, 'changepassword') ? 'YES' : 'NO') . "\n";
?>
