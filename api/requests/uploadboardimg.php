<?php
require_once("base.php");
//all post variables are saved as $key = $val;
//IE $_POST['username'] accessible here as $username
//echo $label . " , " . $peg_r . " , " . $peg_g . " , " . $peg_b . " , " . $label_r . " , " . $label_g . " , " . $label_b . " , " . $set_title;

if ( 0 < $_FILES['file']['error'] ) {
	echo 'Error: ' . $_FILES['file']['error'] . '<br>';
}
else {
	$new_path = dirname(__FILE__) . "/../../board_images/" . $_FILES['file']['name'];
	move_uploaded_file($_FILES['file']['tmp_name'], $new_path);
	echo "Board image uploaded successfully!";
}