<?php
require_once(__DIR__ . '/config.php');

function __autoload($class_name) {
    require_once(__DIR__ . '/classes/' . $class_name . '.php');
}

$db = Database::getConnection();
$session = Session::getInstance($db);
$session->createExperiment(43,26,"exper1",1,1,"null");

echo var_dump(json_encode($session->loadExperiment("Y711bHeDKWWW7xQ")));
?>
