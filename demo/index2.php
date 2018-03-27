
<?php
require_once __DIR__ . '/../classes/config.php';

function __autoload($class_name)
{
	$file = sprintf('../classes/%s.php', $class_name);
	if (is_file($file))
	{
		include $file;
		return;
	}
}

$dbc = Database::getConnection();
$session = new Session($dbc);

//$stimsets = $session->pullStimulusSets();
//$stimulus = $session->loadStimulusSet($stimsets[0]['stimset_id']);
//var_dump($stimulus);
$session->deleteStimulus(3);