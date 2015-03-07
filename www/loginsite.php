<?php
session_start();

$the_title = 'Einloggen';

$the_content = '
	<form action="loginaction.php" method="post">
		<table>
			<tr>
				<td><label for="username">Benutzername</label></td>
				<td><input autofocus type="text" name="username" required /></td>
			</tr>

			<tr>
				<td><label for="password">Passwort</label></td>
				<td><input type="password" name="password" required /></td>
			</tr>
		</table>
		<input type="submit" value="Einloggen" name="Einloggen"/>
	</form>
	';

/* old login site
$the_content = '
	<form action="loginaction.php" method="post">
	<p>
	<label for="username">Benutzername</label>
		<input type="text" name="username" />
	</p>
	<p>
	<label for="password">Passwort</label>
		<input type="password" name="password" />
	</p>
	<p>
	<input type="submit" name="Einloggen"/>
	</p>
	</form>
	';
*/

?>

<?php include('single.php'); ?>
