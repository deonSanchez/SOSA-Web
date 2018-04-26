<?php
require_once(__DIR__ . '/config.php');

function __autoload($class_name) {
    require_once(__DIR__ . '/classes/' . $class_name . '.php');
}

$db = Database::getConnection();
$session = Session::getInstance($db);
//
//// output headers so that the file is downloaded rather than displayed
//header('Content-Type: text/csv; charset=utf-8');
//header('Content-Disposition: attachment; filename=data.csv');
//
//// create a file pointer connected to the output stream
//$output = fopen('php://output', 'w');
//
//$logs = $session->getResults(33);
//$log_columns = array_keys($logs[1][0]);
//$rows = $logs[1];
//// output the column headings
//fputcsv($output, $log_columns);
//
//// fetch the data
//// loop over the rows, outputting them
//$n = count($rows);
//for($i = 0; $i < $n; $i++) {
//	fputcsv($output, $rows[$i]);
//}

var_dump($session->loadStimSets());
?>
