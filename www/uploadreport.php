<?php
session_start();

include('regexp.php');

$the_title = 'Hochladebericht';

switch($_GET['error'])
{
	case 'invalid_title':
		$the_content = '<p>Der Titel ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 255 Zeichen lang sein.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'invalid_subtitle':
		$the_content = '<p>Der Untertitel ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 255 Zeichen lang sein.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'invalid_email':
		$the_content = '<p>Die E-Mail-Adresse ist ungültig.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'invalid_forename':
		$the_content = '<p>Der Vorname ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 50 Zeichen lang sein. Erlaubt sind alle <a href="https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters">druckbaren ASCII-Zeichen</a> und '.$unicode_regexp.'</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'invalid_surname':
		$the_content = '<p>Der Nachname ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 50 Zeichen lang sein. Erlaubt sind alle <a href="https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters">druckbaren ASCII-Zeichen</a> und '.$unicode_regexp.'</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'invalid_university':
		$the_content = '<p>Der Name der Hochschule ist ungültig.</p>';
		$the_content .= '<p>Er darf höchstens 50 Zeichen lang sein. Erlaubt sind alle <a href="https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters">druckbaren ASCII-Zeichen</a> und '.$unicode_regexp.'</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'invalid_compositiondate':
		$the_content = '<p>Das Datum ist ungültig.</p>';
		$the_content .= '<p>Es muss zwischen 1900 und 2000 liegen und das Format JJJJ-MM-TT haben.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;
	case 'connection_failed':
		$the_content = '<p>Verbindung fehlgeschlagen: '.$_GET['connection_error'];
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'no_field1':
		$the_content = '<p>Fach 1 wurde nicht in der Datenbank gefunden.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'no_field2':
		$the_content = '<p>Fach 2 wurde nicht in der Datenbank gefunden.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'no_field3':
		$the_content = '<p>Fach 3 wurde nicht in der Datenbank gefunden.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'no_title':
		$the_content = '<p>Bitte gib einen Titel ein.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'title_taken':
		$the_content = '<p>Der Titel ist bereits vergeben. Bitte gib einen anderen Titel ein.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'invalid_parameters':
		$the_content = '<p>Mit deiner Datei stimmt etwas nicht.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'no_file':
		$the_content = '<p>Es wurde keine Datei gesendet.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'partial':
		$the_content = '<p>Die Datei wurde nur teilweise hochgeladen.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'size':
		$the_content = '<p>Die Datei ist zu groß.</p>'; // specify max size
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'no_pdf':
		$the_content = '<p>Die Datei ist kein PDF. Nur PDF-Dateien werden unterstützt.</p>'; // specify max size
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case 'not_moved':
		$the_content = '<p>Die Datei konnte nicht in den vorgesehenen Ordner verschoben werden.</p>';
		$the_content .= '<p><a href="uploadsite.php">Erneut versuchen</a>';
		break;

	case '':
	default:
		$the_content = '<p>Die Arbeit wurde erfolgreich hochgeladen. <a href="'.$_GET['viewpaper_url'].'">Ansehen</a>.</p>';
		break;
}
?>

<?php include('single.php'); ?>
