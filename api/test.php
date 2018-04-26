<?php
require_once(__DIR__ . '/config.php');

function __autoload($class_name) {
    require_once(__DIR__ . '/classes/' . $class_name . '.php');
}

$db = Database::getConnection();
$session = Session::getInstance($db);

echo "A";
$a =  "a/b/c/../../b.gif";
echo $a;
//
//    $errorMessage = error_get_last()['message'];
//    echo $errorMessage;


?>
