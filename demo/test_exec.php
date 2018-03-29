<?php
require_once __DIR__ . '/../api/config.php';

function __autoload($class_name)
{
	$file = sprintf('../api/classes/%s.php', $class_name);
	if (is_file($file))
	{
		include $file;
		return;
	}
}

$dbc = Database::getConnection();
$session = new Session($dbc);

//Creates stimulus abc with attempted stim id -1 (fails because no set -1)
$session->createStimulus("stimabc", "red", "red", 5);
?>
