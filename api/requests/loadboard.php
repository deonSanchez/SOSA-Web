<?php
require_once("base.php");
//all post variables are saved as $key = $val;
//IE $_POST['username'] accessible here as $username
//echo $label . " , " . $peg_r . " , " . $peg_g . " , " . $peg_b . " , " . $label_r . " , " . $label_g . " , " . $label_b . " , " . $set_title;
$array = $session->loadBoard($board);
echo json_encode($array, JSON_FORCE_OBJECT);