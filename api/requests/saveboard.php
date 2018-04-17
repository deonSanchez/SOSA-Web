<?php
require_once("base.php");
/**
 * Created by PhpStorm.
 * User: Dan Blocker
 * Date: 4/15/2018
 * Time: 4:52 PM
 */
header("Content-Type: application/json; charset=UTF-8");
$board = json_decode($_POST["x"], false);
$board_name = $board[0];
$lock_tilt = $board[1];
$lock_rotate = $board[2];
$lock_zoom = $board[3];
$board_color = $board[4];
$background_color = $board[5];
$cover_color = $board[6];
$image = $board[7];

echo $session->saveBoard($board_name, $lock_tilt, $lock_rotate, $lock_zoom, $board_color, $background_color, $cover_color, $image);