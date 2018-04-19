<?php
require_once("base.php");
/**
 * Created by PhpStorm.
 * User: Dan Blocker
 * Date: 4/15/2018
 * Time: 4:52 PM
 */
header("Content-Type: application/json; charset=UTF-8");

$board = (array) json_decode($_POST["board"]);

$board_name = $board["boardName"];

$lock_tilt = $board["lock_tilt"] ? 1 : 0;
$lock_rotate = $board["lock_rotate"] ? 1 : 0;
$lock_zoom = $board["lock_zoom"] ? 1 : 0;
$cover_board = $board["cover_board"] ? 1 : 0;

$board_color = $board["board_color"];
$background_color = $board["background_color"];
$cover_color = $board["cover_color"];

$camerax = $board["camerax"];
$cameray = $board["cameray"];
$cameraz = $board["cameraz"];

$image = $board["image"];

echo $session->saveBoard($board_name, $lock_tilt, $lock_rotate, $lock_zoom, $cover_board, $board_color, $background_color, $cover_color, $image,$camerax,$cameray,$cameraz) ? 1 : 0;