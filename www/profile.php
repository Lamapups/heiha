<?php
session_start();

$the_title = 'Profil';

if ($_SESSION['id'])
{
	//$the_content .= '<p>ID: '.$_SESSION['id'].'</p>'; //this is the ID in the user database, not the session id

	$the_content = '
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

	/* old profile
	$the_content .= '<p>Benutzername: '.$_SESSION['username'].'</p>';
	$the_content .= '<p>E-Mail-Adresse: '.$_SESSION['email'].'</p>';
	$the_content .= '<p>Vorname: '.$_SESSION['forename'].'</p>';
	$the_content .= '<p>Nachname: '.$_SESSION['surname'].'</p>';
	$the_content .= '<p>Hochschule: '.$_SESSION['university'].'</p>';
	*/
	//$the_content .= '<p>Session ID: '.session_id().'</p>';
	$the_content .= '<p><a href="changeprofilesite.php">Profil bearbeiten</a></p>';
	$the_content .= '<p><a href="changepasswordsite.php">Passwort ändern</a></p>';
	$the_content .= '<p><a href="deleteaccountsite.php">Account löschen</a></p>';
}
else
	$the_content .= '<p><a href="loginsite.php">Logge dich ein</a>, um dein Profil anzusehen.</p>';

?>

<?php include('single.php'); ?>
