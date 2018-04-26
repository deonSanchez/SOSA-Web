<?php
require_once("base.php");
//all post variables are saved as $key = $val;
//IE $_POST['username'] accessible here as $username
//echo $label . " , " . $peg_r . " , " . $peg_g . " , " . $peg_b . " , " . $label_r . " , " . $label_g . " , " . $label_b . " , " . $set_title;
if(!$session->isLoggedIn())
	die( "You must be logged in for this feature!");
if ( 0 < $_FILES['file']['error'] ) {
	echo 'Error: ' . $_FILES['file']['error'] . '<br>';
}
else {
	$imgup = $session->uploadIMG($FILES);
	if($imgup) {
		$resp = $session->saveBoardImage($board_name,$path);
		echo $resp[0] == true ? $resp[1] : $resp; 
	} else {
		echo "Could not upload board image!";
	}
}