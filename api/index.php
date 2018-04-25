<?php

/**
 * @package Post handler
 * @author Mitchell M.
 * @version 1.5.0
 */
/**
 * Loading all the required classes/configuration files first
 */
require_once(__DIR__ . '/config.php');

function autoloader($class_name) {
    require_once(__DIR__ . '/classes/' . $class_name . '.php');
}

spl_autoload_register('autoloader');

/**
 * Creating the database connection and passing it to the primary session object
 * Need to define DB details in config/global.php
 */
$db = Database::getConnection();
$session = Session::getInstance($db);

//Copies all the POST data values into variable variable names
foreach ($_POST as $key => $val) {
    $$key = trim($val);
}

//List of valid requests that are handled
$VALID_REQUESTS = array('login','checklogin','logout', 'loadstimsetbyid',
 'register','createstimulus','loadstimsets',
 'loadstimsetbyname','createset','deleteset',
 'loadstimulus','deletestimulus','updatestimulus','saveboard'
 ,'loadboard', 'uploadboardimg', 'loadboards', 'deleteboard','loadexperiment','createresult');

//Validating the existance of server variable "HTTP_X_REQUESTED_WITH", if it exists it can verify that the call is ajax
$httpXrequested = isset($_SERVER['HTTP_X_REQUESTED_WITH']);

//Ternary operator to determine if the call is a true ajax call
$isAjaxCall = $httpXrequested ? strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' : null;

//Validates all the required conditions to make an API call
if ($httpXrequested && ($isAjaxCall) && isset($request)) {

    //Sets the access variable that is checked for in the included files and forms a path to the request targed
    $access = true;
    $file = './requests/' . $request . '.php';

    //If the request is in the subfolder, and its listed in the valid request array..
    //open it
    $file_exists = file_exists($file);
    $in_array = in_array($request, $VALID_REQUESTS);
    if ($file_exists && $in_array) {
        //LOAD THE CONTENT OF THE REQUEST
        require_once($file);
    } else {
    	$a = $file_exists ? "T" : "F";
    	$b = $in_array ? "T" : "F";
        die("Request not found in host file-system OR not whitelisted. {$request} <br />
        	File exists: {$a} <br />
        	In Array: {$b}");
    }
} else {
    //Build error message
    $req_out = isset($request) ? $request : "N/A";
    $a = $httpXrequested ? "T" : "F";
    $b = $isAjaxCall ? "T" : "F";
    $c = isset($request) ? "T" : "F";

    //Print error message
    die("
    Attempting to direct access OR malformed request sent to API! (API Level) <br /> <br />
	Error definitions: <br />
	    A=HTTP_X_REQUESTED_WITH server var set to \"XMLHttpRequest\" <br />
	    B=Verified as valid ajax call <br />
	    C=Request API variable set <br/>
	    D=Value of request variable <br /><br />
    Errors: A[<b>" . $a . "</b>] // B[<b>" . $b . "</b>] // C[<b>" . $c . "</b>] // D[<b>" . $req_out . "</b>]");
}
?>
