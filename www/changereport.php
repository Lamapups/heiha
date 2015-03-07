<?php
session_start();

$the_title = 'Änderungsbericht';

// This site gets errors from changepasswordaction.php and changeprofileaction.php
// It provides links to the user depending on the error that occurred and the site he came from ('origin')
switch($_GET['error'])
{
	case 'invalid_email':
		$the_content = '<p>Bitte gib eine gültige E-Mail-Adresse ein.</p>';
		$the_content .= '<p>Du wurdest nicht registriert.</p>';
		$the_content .= '<p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'invalid_forename':
		$the_content = '<p>Der Vorname ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 30 Zeichen lang sein und darf keine nicht anzeigbaren Zeichen wie Leerzeichen enthalten.</p>';
		break;

	case 'invalid_surname':
		$the_content = '<p>Der Nachname ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 30 Zeichen lang sein und darf keine nicht anzeigbaren Zeichen wie Leerzeichen enthalten.</p>';
		break;

	case 'invalid_university':
		$the_content = '<p>Der Name der Hochschule ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 50 Zeichen lang sein.</p>';
		break;

	case 'wrong_password':
		$the_content = '<p>Falsches Passwort. <a href="'.$_GET['origin'].'.php">Erneut versuchen.</a></p>';
		break;

	case 'connection_failed':
		$the_content = '<p>Verbindung fehlgeschlagen: '.$_GET['connection_error'].'</p> <p><a href="'.$_GET['origin'].'.php">Erneut versuchen.</a></p>';
		break;

	case 'no_rows':
		$the_content = '<p>Deinem Benutzernamen konnte kein Eintrag zugeordnet werden. Bist du schon <a href="registersite.php">registriert?</a></p>';
		break;

	case 'unknown_tobechanged':
		$the_content = '<p>Es wurde nicht erkannt, was du ändern möchstest.</p>';
		break;

	case 'unknown':
		$the_content = '<p>Unbekannter Fehler.</p> <p><a href="'.$_GET['origin'].'.php">Erneut versuchen.</a></p>';
		break;
	case '':
	default:
		$the_content = '<p>Die Änderung war erfolgreich.</p> <p><a href="profile.php">Zum Profil wechseln.</a></p>';
		break;
}

?>

<?php include('single.php'); ?>
