<?php
require_once(__DIR__ . '/config.php');

function __autoload($class_name) {
    require_once(__DIR__ . '/classes/' . $class_name . '.php');
}

$db = Database::getConnection();
$session = Session::getInstance($db);
echo var_dump(json_encode($session->loadExperiment("5yT9EmOQF8E1woc")));
?>
