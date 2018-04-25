<?php
require_once("base.php");
//all post variables are saved as $key = $val;
//IE $_POST['username'] accessible here as $username
//echo $label . " , " . $peg_r . " , " . $peg_g . " , " . $peg_b . " , " . $label_r . " , " . $label_g . " , " . $label_b . " , " . $set_title;
if(!$session->isLoggedIn())
	die( "You must be logged in for this feature!");
	
$access = $session->createExperiment($board, $stimid, $grid, $title,$cover, 1, null);
if($session->loadExperiment($access) != null ){
	echo $access;
} else {
	echo "Error: " + $access;
}