<?php
session_start();

include('regexp.php');

$the_title = 'Registrierungsbericht';

// This site gets errors from registeraction.php and gives links to registersite.php
switch($_GET['error'])
{
	case 'invalid_username':
	case 'no_username':
		$the_content = '<p>Bitte gib einen gültigen Benutzernamen an.</p>';
		$the_content .= '<p>Er muss zwischen 5 und 50 Zeichen lang sein. Erlaubt sind alle <a href="https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters">druckbaren ASCII-Zeichen</a> und '.$unicode_regexp.'</p>';
		$the_content .= '<p>Du wurdest nicht registriert.</p>';
		$the_content .= '<p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'invalid_password':
	case 'no_password':
		$the_content = '<p>Bitte gib zweimal ein gültiges Passwort ein.</p>';
		$the_content .= '<p>Es muss zwischen 8 und 50 Zeichen lang sein. Erlaubt sind alle <a href="https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters">druckbaren ASCII-Zeichen</a> und '.$unicode_regexp.'</p>';
		$the_content .= '<p>Du wurdest nicht registriert.</p>';
		$the_content .= '<p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'invalid_email':
	case 'no_email':
		$the_content = '<p>Bitte gib eine gültige E-Mail-Adresse ein.</p>';
		$the_content .= '<p>Du wurdest nicht registriert.</p>';
		$the_content .= '<p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'invalid_forename':
		$the_content = '<p>Der Vorname ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 50 Zeichen lang sein. Erlaubt sind alle <a href="https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters">druckbaren ASCII-Zeichen</a> und '.$unicode_regexp.'</p>';
		$the_content .= '<p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'invalid_surname':
		$the_content = '<p>Der Nachname ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 50 Zeichen lang sein. Erlaubt sind alle <a href="https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters">druckbaren ASCII-Zeichen</a> und '.$unicode_regexp.'</p>';
		$the_content .= '<p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'invalid_university':
		$the_content = '<p>Der Name der Hochschule ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 50 Zeichen lang sein. Erlaubt sind alle <a href="https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters">druckbaren ASCII-Zeichen</a> und '.$unicode_regexp.'</p>';
		$the_content .= '<p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'username_taken':
		$the_content = '<p>Der Benutzername ist schon vergeben.</p>';
		$the_content .= '<p>Du wurdest nicht registriert.</p>';
		$the_content .= '<p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'email_taken':
		$the_content = '<p>Die E-Mail-Adresse ist schon vergeben.</p>';
		$the_content .= '<p>Du wurdest nicht registriert.</p>';
		$the_content .= '<p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'passwords_dont_match':
		$the_content = '<p>Die eingegebenen Passwörter stimmen nicht überein.</p>';
		$the_content .= '<p>Dein Passwort wurde nicht geändert.</p>';
		$the_content .= '<p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'connection_failed':
		$the_content = '<p>Verbindung fehlgeschlagen: '.$_GET['connection_error'].'</p> <p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;

	case 'unknown':
		$the_content = '<p>Unbekannter Fehler.</p> <p><a href="registersite.php">Erneut versuchen.</a></p>';
		break;
	case '':
	default:
		$the_content = '<p>Du wurdest erfolgreich registriert.</p> <p><a href="loginsite.php">Logge dich ein!</a></p>';
		break;
}

?>

<?php include('single.php'); ?>
