<?php
require_once(__DIR__ . '/config.php');

function __autoload($class_name) {
    require_once(__DIR__ . '/classes/' . $class_name . '.php');
}

$db = Database::getConnection();
$session = Session::getInstance($db);
$res = $session->loadBoard(18);
echo "<pre>";
var_dump($session->loadBoard(18));

echo $
echo "</pre>";