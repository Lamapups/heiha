<?php
session_start();

$the_title = 'Benutzer suchen';
$the_content = '<p>Diese Seite ermöglicht die Suche nach einem Benutzer. <a href="searchpapersite.php">Stattdessen nach einer Arbeit suchen.</a></p>';

$the_content .= '
	<form action=searchuseraction.php method="get">
		<table>
			<tr>
				<td><label for="username">Benutzername</label></td>
				<td><input type="text" name="username" /></td>
			</tr>

			<tr>
				<td><label for="forename">Vorname</label></td>
				<td><input type="text" name="forename" /></td>
			</tr>

			<tr>
				<td><label for="surname">Nachname</label></td>
				<td><input type="text" name="surname" /></td>
			</tr>

			<tr>
				<td><label for="university">Hochschule</label></td>
				<td><input type="text" name="university" /></td>
			</tr>

			<tr>
				<td colspan="2" ><center><input type="submit" value="Suchen" name="searchuser"/></center></td>
			</tr>
		</table>
	</form>
	';

?>

<?php include('single.php'); ?>
