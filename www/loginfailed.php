<?php
session_start();

$the_title = 'Fehler beim Einloggen';

switch($_GET['error'])
{
	case 'unknown_username':
		$the_content = '<p>Unbekannter Benutzername: '.$_GET['username'].'</p> <p><a href="loginsite.php">Erneut versuchen.</a></p>';
		break;
	case 'wrong_password':
		$the_content = '<p>Falsches Passwort.</p> <p><a href="loginsite.php">Erneut versuchen.</a></p>';
		break;
	case 'connection_failed':
		$the_content = '<p>Verbindung fehlgeschlagen: '.$_GET['connection_error'].'</p> <p><a href="loginsite.php">Erneut versuchen.</a></p>';
		break;
	case 'unknown':
	default:
		$the_content = '<p>Unbekannter Fehler.</p> <p><a href="loginsite.php">Erneut versuchen.</a></p>';
		break;
}




?>

<?php include('single.php'); ?>
