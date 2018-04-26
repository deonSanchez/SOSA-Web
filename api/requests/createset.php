<?php
require_once("base.php");
//all post variables are saved as $key = $val;
//IE $_POST['username'] accessible here as $username
//echo $label . " , " . $peg_r . " , " . $peg_g . " , " . $peg_b . " , " . $label_r . " , " . $label_g . " , " . $label_b . " , " . $set_title;
if(!$session->isLoggedIn())
	die( "You must be logged in for this feature!");

$resp = $session->createStimulusSet($set_name, 1);
echo $resp ? 1 : $resp;