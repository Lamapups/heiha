<?php
session_start();

$the_title = 'Profilbearbeitung';

if ($_SESSION['id'])
{
	// form to change profile
	$the_content = '
		<form action="changeprofileaction.php" method="post">
		<p>
			<label for="tobechanged"></label>
				<select autofocus name="tobechanged">
					<option value="forename">Vorname</option>
					<option value="surname">Nachname</option>
					<option value="university">Hochschule</option>
					<option value="email">E-Mail-Adresse</option>
				</select>
			<label for="newvalue">ändern zu</label>
				<input type="text" name="newvalue">
		</p>
		<p>
			<label for="oldpassword">Passwort</label>
				<input type="password" name="oldpassword">
		</p>
		<p>
			<input type="submit" value="Ändern" name="Ändern">
		</p>
		</form>
		';

	//show current profile
	$the_content .= '
		<table>
			<tr>
				<td>Benutzername</td>
				<td>'.htmlspecialchars($_SESSION['username']).'</td>
			</tr>

			<tr>
				<td>E-Mail-Adresse</td>
				<td>'.htmlspecialchars($_SESSION['email']).'</td>
			</tr>

			<tr>
				<td>Vorname</td>
				<td>'.htmlspecialchars($_SESSION['forename']).'</td>
			</tr>

			<tr>
				<td>Nachname</td>
				<td>'.htmlspecialchars($_SESSION['surname']).'</td>
			</tr>

			<tr>
				<td>Hochschule</td>
				<td>'.htmlspecialchars($_SESSION['university']).'</td>
			</tr>
		</table>
		';
}

else
	$the_content .= '<p><a href="loginsite.php">Logge dich ein</a>, um dein Profil zu bearbeiten.</p>';

?>

<?php include('single.php'); ?>
