<?php
session_start();

$the_title = 'Registrieren';

$the_content = '
	<form action="registeraction.php" method="post">
		<fieldset>
		<legend>Allgemein</legend>
		<table>
			<tr>
				<td><label for="username">Benutzername*</label></td>
				<td><input autofocus type="text" name="username" required /></td>
			</tr>

			<tr>
				<td><label for="password">Passwort*</label></td>
				<td><input type="password" name="password" required /></td>
			</tr>

			<tr>
				<td><label for="password2">Passwort wiederholen*</label></td>
				<td><input type="password" name="password2" required /></td>
			</tr>
		</table>
		</fieldset>

		<fieldset>
		<legend>E-Mail-Einstellungen</legend>
		<table>
			<tr>
				<td><label for="email">E-Mail-Adresse*</label></td>
				<td><input type="email" name="email" required /></td>
			</tr>

			<tr>
				<td colspan="2"><label for="email_public">E-Mail-Adresse im Profil für alle sichtbar anzeigen</label></td>
			</tr>

			<tr>
				<td><label for="no">Nein</label></td>
				<td><input type="radio" name="email_public" id="no" value="0" checked /></td>
			</tr>

			<tr>
				<td><label for="yes">Ja</label></td>
				<td><input type="radio" name="email_public" id="yes" value="1" /></td>
			</tr>
		</table>
		</fieldset>

		<fieldset>
		<legend>Informationen zur Person</legend>
		<table>
			<tr>
				<td><label for="forename">Vorname</label></td>
				<td><input type="text" name="forename" /></td>
			</tr>

			<tr>
				<td><label for="surname">Nachname</label></td>
				<td><input type="text" name="surname" /></td>
			</tr>

			<tr>
				<td><label for="university">Derzeitige Hochschule</label></td>
				<td><input type="text" name="university" /></td>
			</tr>
		</table>
		</fieldset>

		<p>* Pflichtfelder</p>
		<input type="submit" value="Registrieren" name="Registrieren"/>
	</form>
	';

/* old register form
$the_content = '
	<form action="registeraction.php" method="post">
	<label for="username">Benutzername</label>
		<input type="text" name="username" /><br>
	<label for="password">Passwort</label>
		<input type="password" name="password" /><br>
	<label for="password2">Passwort wiederholen</label>
		<input type="password" name="password2" /><br>
	<label for="email">E-Mail-Adresse</label>
		<input type="text" name="email" /><br>
	<label for="forename">Vorname</label>
		<input type="text" name="forename" /><br>
	<label for="surname">Nachname</label>
		<input type="text" name="surname" /><br>
	<label for="university">Hochschule</label>
		<input type="text" name="university" /><br>
	<input type="submit" name="Registrieren"/>
	</form>
	';
*/

?>

<?php include('single.php'); ?>
