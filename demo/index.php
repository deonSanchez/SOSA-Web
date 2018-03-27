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
$experiments = $session->loadExperiments();
$keys = array_keys($experiments[0]);

?>
<table>
	<tr>
		<th>Experiment name</th>

		<th>Experiment ID</th>
	</tr>
	<?php
	foreach($experiments as $experiment) {
		?>
	<tr>
		<td><?php echo $experiment['title']; ?></td>
		<td><?php echo $experiment['experiment_id']; ?></td>
	</tr>
	<?php
	}
	?>
</table>

<br />
<br />

<form action="index.php"
	method="post"></form>
<table>
	<tr>
		<td>Experiment Title:</td>
		<td><input type="text" name="title" /></td>
	</tr>
	<tr>
		<td>Board Tint RGB:</td>
		<td><input type="text" name="board_tintrgb" /></td>
	</tr>
	<tr>
		<td>Background Tint RGB:</td>
		<td><input type="text" name="background_tintrgb" /></td>
	</tr>
	<tr>
		<td>Grid Size:</td>
		<td><input type="text" name="grid_size" /></td>
	</tr>
	<tr>
		<td>Show background:</td>
		<td><input type="checkbox" name="show_background" /></td>
	</tr>
	<tr>
		<td>Show labels:</td>
		<td><input type="checkbox" name="show_labels" /></td>
	</tr>
	<tr>
		<td>Label pos:</td>
		<td><input type="text" name="label_pos" /></td>
	</tr>
	<tr>
		<td>Label shade:</td>
		<td><input type="text" name="label_shade" /></td>
	</tr>
	<tr>
		<td>Label size:</td>
		<td><input type="text" name="label_size" /></td>
	</tr>
	<tr>
		<td>Preview image:</td>
		<td><input type="text" name="preview_img" /></td>
	</tr>
	<tr>
		<td>SAVE</td>
		<td><input style="width: 100%;" type="submit" value="SUBMIT" /></td>
	</tr>
</table>
