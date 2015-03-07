<?php
session_start();

$the_title = 'Passwortänderung';

if ($_SESSION['id'])
{
	// form to change profile
	$the_content = '
		<form action="changepasswordaction.php" method="post">
			<table>
				<tr>
					<td><label for="oldpassword">Altes Passwort</label></td>
					<td><input autofocus type="password" name="oldpassword" /></td>
				</tr>

				<tr>
					<td><label for="newpassword">Neues Passwort</label></td>
					<td><input type="password" name="newpassword" /></td>
				</tr>

				<tr>
					<td><label for="newpassword2">Neues Passwort wiederholen</label></td>
					<td><input type="password" name="newpassword2" /></td>
				</tr>
			</table>
			<input type="submit" value="Ändern"  name="Ändern"/>
		</form>
		';

	/*
	//show current profile
	$the_content .= '
		<p>
		<table>
			<tr>
				<td>Benutzername</td>
				<td>'.$_SESSION['username'].'</td>
			</tr>

			<tr>
				<td>E-Mail-Adresse</td>
				<td>'.$_SESSION['email'].'</td>
			</tr>

			<tr>
				<td>Vorname</td>
				<td>'.$_SESSION['forename'].'</td>
			</tr>

			<tr>
				<td>Nachname</td>
				<td>'.$_SESSION['surname'].'</td>
			</tr>

			<tr>
				<td>Hochschule</td>
				<td>'.$_SESSION['university'].'</td>
			</tr>
		</table>
		</p>
		';
	 */
}

else
	$the_content .= '<p><a href="loginsite.php">Logge dich ein</a>, um dein Profil zu bearbeiten.</p>';

?>

<?php include('single.php'); ?>
