<?php
require_once('base.php');
if ($session->isLoggedIn()) {
    die("1");
} else {
    die("0");
}
?>