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
	//saves response, 0 index is true/false 1 index is the path
	$imgup = $session->uploadIMG($FILES);
	if($imgup[0]) {
		$resp = $session->saveBoardImage($board_name,$imgup[1]);
		echo $resp[1];
	} else {
		echo "Could not upload board image!";
	}
}