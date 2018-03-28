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
?>
<html>
<head>
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript">
	$(function() {
	    alert( "ready!" );
	});
	</script>
</head>
<!-- creating stimulus set -->
<table>
	<tr>
		<td><b>Create stimulus set :</b></td>
		<td><input style="width: 100%;" type="text" value="stim set name" /></td>
	</tr>
	<tr>
		<td colspan="2"><input style="width: 100%;" type="button" value="GO>" />
		</td>
	</tr>
</table>
<br />
<br />
<!-- creating individual stimulus -->
<table>
	<tr>
		<td><b>Create stimulus :</b></td>
		<td><select style="width: 100%;">
			<option value="Stim1">Stim 1</option>
			<option value="Stim2">Stim 2</option>
			<option value="Stim3">Stim 3</option>
		</select></td>
	</tr>
	<tr>
		<td>Label: <input type="text" value="" name="label" /></td>
	</tr>
	<tr>
		<td>Label color: <input type="color" value="#ff0000"></td>
		<td>Peg color: <input type="color" value="#ff0000"></td>
	</tr>
	<tr>
		<td colspan="2"><input style="width: 100%;" type="button" value="GO>" />
		</td>
	</tr>
</table>
<br />
<br />
<table>
	<tr>
		<td><b>Select stimulus sets :</b></td>
		<td><select>
			<option value="Stim1">Stim 1</option>
			<option value="Stim2">Stim 2</option>
			<option value="Stim3">Stim 3</option>
		</select></td>
	</tr>
</table>
<table>
	<tr>
		<th>Label</th>
		<th>Label color</th>
		<th>Peg color</th>
		<th>Delete</th>
	</tr>
	<tr>
		<td>Happy</td>
		<td>#ff0000</td>
		<td>#ff0000</td>
		<td><input type="button" value="Delete" /></td>
	</tr>
	<tr>
		<td>Sad</td>
		<td>#ff0000</td>
		<td>#ff0000</td>
		<td><input type="button" value="Delete" /></td>
	</tr>
	<tr>
		<td>Mad</td>
		<td>#ff0000</td>
		<td>#ff0000</td>
		<td><input type="button" value="Delete" /></td>
	</tr>
</table>
</html>
