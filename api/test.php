<?php
require_once(__DIR__ . '/config.php');

function __autoload($class_name) {
    require_once(__DIR__ . '/classes/' . $class_name . '.php');
}

$db = Database::getConnection();
$session = Session::getInstance($db);


$result = $session->getResults(33);
$log_columns = array_keys($result[1][0]);
$participant = $result[0]['identifier'];
$low_rows = count($result[1]);

var_dump($session->send($participant, $log_columns,$result[1], "Experiment results for participant identified as {$participant}", "mitchell.murphy96@gmail.com", "Test results", "noreply@sosaproject.com"));
//
//    $errorMessage = error_get_last()['message'];
//    echo $errorMessage;


?>
