<?php
require_once("base.php");
//all post variables are saved as $key = $val;
//IE $_POST['username'] accessible here as $username
//echo $label . " , " . $peg_r . " , " . $peg_g . " , " . $peg_b . " , " . $label_r . " , " . $label_g . " , " . $label_b . " , " . $set_title;

if ( 0 < $_FILES['file']['error'] ) {
	echo 'Error: ' . $_FILES['file']['error'] . '<br>';
}
else {
	$parts = explode(".", $_FILES['file']['name']);
	$path = dirname(__FILE__) . "/../../board_images/". $session->generateRandID(8) + $parts[1];
	move_uploaded_file($_FILES['file']['tmp_name'], $path);
	$session->saveBoardImage($board_name,$path);
	echo "Board image uploaded successfully!";
}