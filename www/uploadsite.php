<?php
session_start();

$the_title = 'Arbeit hochladen';

if (!$_SESSION['id'])
{
	$the_content .= '<p>Bitte überlege dir, ob du dich <a href="registersite.php">registrieren</a> möchtest, bevor du eine Arbeit hochlädst.</p>';
}

$the_content .= '<p>Auf dieser Seite können Arbeiten hochgeladen werden. Abgesehen vom Titel muss kein Feld ausgefüllt werden. Mit der Option „Titelseite durch neu generierte Titelseite ersetzen“ wird die erste Seite der hochgeladenen Arbeit gelöscht und an ihrer Stelle eine neue Seite eingefügt, die den Titel, den Untertitel, den Vor- und den Nachnamen, die Hochschule und das Datum der Abfassung enthält.</p>';

unset($_POST);

// Generating the string $fieldselection
// for the upload form
// with the data from mysql table users.fields

try {

	$mysqli= new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// Checking connection
	if($mysqli->connect_errno)
		throw new RuntimeException('Verbindung fehlgeschlagen: '.$mysqli->connect_error);


	$mysqli->query("SET NAMES 'utf8'");

	// Getting all data from the fields table
	$query_for_fields= "SELECT * FROM fields ORDER BY field_unicode";
	$result_for_fields= $mysqli->query($query_for_fields);

	if(!$result_for_fields->num_rows)
		throw new RuntimeException('Keine Fächer gefunden.');

	// Generating fieldselection
	$fieldselection = '';
	while($row = $result_for_fields->fetch_assoc())
	{
		$fieldselection .= sprintf('<option value="%s">%s</option>', $row['id'], $row['field_unicode']);
	}



} catch (RuntimeException $e) {

    $the_content .= '<p>'.$e->getMessage().'</p>';

}


// This is the upload form for the user
$the_content .= '
	<form action="uploadaction.php" method="post" enctype="multipart/form-data">

		<fieldset>
		<legend>Inhalt</legend>
		<table>
			<tr>
				<td><label for="paper">PDF-Datei*</label></td>
				<td><input autofocus type="file" name="paper" /></td>
			</tr>

			<tr>
				<td><label for="title">Titel*</label></td>
				<td><input type="textarea " size=52 name="title" /></td>
			</tr>

			<tr>
				<td><label for="subtitle">Untertitel</label></td>
				<td><input type="text" size=52 name="subtitle" /></td>
			</tr>

			<tr>
				<td><label for="abstract">Abriss</label></td>
				<td><textarea cols=60 rows=15 name="abstract" ></textarea></td>
			</tr>
		</table>
		</fieldset>

		<fieldset>
		<legend>E-Mail-Einstellungen</legend>
		<table>
			<tr>
				<td><label for="email">E-Mail-Adresse</label></td>
				<td><input type="email" size=35 value="'.$_SESSION['email'].'" name="email" /></td>
			</tr>

			<tr>
				<td colspan="2"><label for="email_public">E-Mail-Adresse auf der Seite der Arbeit für alle sichtbar anzeigen</label></td>
			</tr>

			<tr>
				<td><input type="radio" name="email_public" id="no" value="0" /></td>
				<td><label for="no">Nein</label></td>
			</tr>

			<tr>
				<td><input type="radio" name="email_public" id="yes" value="1" /></td>
				<td><label for="yes">Ja</label></td>
			</tr>
		</table>
		</fieldset>

		<fieldset>
		<legend>Autor</legend>
		<table>
			<tr>
				<td><label for="forename">Vorname</label></td>
				<td><input type="text" value="'.$_SESSION['forename'].'" name="forename" /></td>
			</tr>

			<tr>
				<td><label for="surname">Nachname</label></td>
				<td><input type="text" value="'.$_SESSION['surname'].'" name="surname" /></td>
			</tr>
		</table>
		</fieldset>

		<fieldset>
		<legend>Arbeit</legend>
		<table>
			<tr>
				<td colspan="2"><label for="replace_titlepage">Titelseite durch neu generierte Titelseite ersetzen</label></td>
			</tr>

			<tr>
				<td><input type="radio" name="replace_titlepage" id="not_replace" value="0" /></td>
				<td><label for="not_replace">Nein</label></td>
			</tr>

			<tr>
				<td><input type="radio" name="replace_titlepage" id="do_replace" value="1" /></td>
				<td><label for="do_replace">Ja</label></td>
			</tr>
			<tr>
				<td><label for="university">Hochschule</label></td>
				<td><input type="text" value="'.$_SESSION['university'].'" name="university" /></td>
			</tr>

			<tr>
				<!-- include field1 selection -->
				<td><label for="field1_id">Fach 1</label></td>
				<td>
					<select name="field1_id">
						'.$fieldselection.'
					</select>
				</td>
			</tr>

			<tr>
				<!-- include field2 selection -->
				<td><label for="field2_id">Fach 2</label></td>
				<td>
					<select name="field2_id">
						'.$fieldselection.'
					</select>
				</td>
			</tr>

			<tr>
				<!-- include field3 selection -->
				<td><label for="field3_id">Fach 3</label></td>
				<td>
					<select name="field3_id">
						'.$fieldselection.'
					</select>
				</td>

			</tr>

			<tr>
				<td><label for="compositiondate">Datum der Abfassung<br>(Format: JJJJ-MM-TT)</label></td>
				<td><input type="text" name="compositiondate" pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" /></td>
			</tr>

			<tr>
				<td><label for="type">Art der Arbeit</label></td>
				<td>
					<select name="type">
						<option value="proseminar">Proseminarsarbeit</option>
						<option value="hauptseminar">Hauptseminarsarbeit</option>
						<option value="oberseminar">Oberseminarsarbeit</option>
						<option value="zula">Zulassungsarbeit</option>
						<option value="bachelor">Bachelorarbeit</option>
						<option value="master">Masterarbeit</option>
						<option value="magister">Magisterarbeit</option>
						<option value="diplom">Diplomarbeit</option>
						<option value="promotion">Dissertation</option>
						<option value="habilitation">Habilitation</option>
						<option value="other">Andere Arbeit</option>
					</select>
				</td>
			</tr>
			</table>
			</fieldset>

			<p>* Pflichtfelder</p>

			<center><input type="submit" value="Hochladen" name="upload"/></center>
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
