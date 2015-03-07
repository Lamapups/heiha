<?php
session_start();

$the_title = 'Arbeit suchen';
$the_content = '<p>Diese Seite ermöglicht die Suche nach einer Arbeit. <a href="searchusersite.php">Stattdessen nach einem Benutzer suchen.</a></p>';

try{
	$mysqli= new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// Checking connection
	if($mysqli->connect_errno)
		throw new RuntimeException('Verbindung fehlgeschlagen: '.$mysqli->connect_error);


	$mysqli->query("SET NAMES 'utf8'");

	// Getting rows with username entered
	// Instead of field_ascii one could use the id as an identifier
	$query_for_fields= "SELECT id, field_unicode , field_ascii FROM fields ORDER BY field_unicode";
	$result_for_fields= $mysqli->query($query_for_fields);

	if(!$result_for_fields->num_rows)
		throw new RuntimeException('Keine Fächer gefunden.');

	// Generating fieldselection
	$fieldselection = '';
	while($row = $result_for_fields->fetch_assoc())
	{
		// maybe id should be used for the value
		$fieldselection .= sprintf('<option value="%s">%s</options>', $row['field_ascii'], $row['field_unicode']);
	}
} catch (RuntimeException $e) {

    $the_content .= '<p>'.$e->getMessage().'</p>';

}

$the_content .= '
	<form action="searchpaperaction.php" method="get">

		<fieldset>
		<legend>Inhaltsparameter</legend>
		<table>
			<tr>
				<td><label for="title_subtitle">Titel/Untertitel enthält</label></td>
				<td><input type="text" name="title_subtitle" /></td>
			</tr>

			<tr>
				<td><label for="abstract">Abriss enthält</label></td>
				<td><input type="text" name="abstract" /></td>
			</tr>

			<tr>
				<td colspan="2"><center>ODER</center></td>
			</tr>

			<tr>
				<td><label for="abstract_title">Abriss oder Titel/Untertitel enthält</label></td>
				<td><input type="text" name="abstract_title" /></td>
			</tr>
		</table>

		</fieldset>

		<fieldset>
		<legend>Autorparameter</legend>
		<table>
			<tr>
				<td><label for="username">Benutzername</label></td>
				<td><input type="text"  name="username" /></td>
			</tr>

			<tr>
				<td><label for="forename">Vorname</label></td>
				<td><input type="text"  name="forename" /></td>
			</tr>

			<tr>
				<td><label for="surname">Nachname</label></td>
				<td><input type="text"  name="surname" /></td>
			</tr>
		</table>
		</fieldset>

		<fieldset>
		<legend>Fachparameter</legend>
		<table>
			<tr>
					<td><label for="or">Eines der Fächer</label></td>
					<td><input type="radio" name="and_or" id="or" value="or" checked/></td>
			<tr>

			<tr>
					<td><label for="and">Alle der Fächer</label></td>
					<td><input type="radio" name="and_or" id="and" value="and" /></td>
			<tr>

			<tr>
				<!-- include field1 selection -->
				<td><label for="field1">Fach 1</label></td>
				<td>
					<select name="field1">
						'.$fieldselection.'
					</select>
				</td>
			</tr>

			<tr>
				<!-- include field2 selection -->
				<td><label for="field2">Fach 2</label></td>
				<td>
					<select name="field2">
						'.$fieldselection.'
					</select>
				</td>
			</tr>

			<tr>
				<!-- include field3 selection -->
				<td><label for="field3">Fach 3</label></td>
				<td>
					<select name="field3">
						'.$fieldselection.'
					</select>
				</td>

			</tr>
		</table>
		</fieldset>

		<fieldset>
		<legend>Arbeitsparameter</legend>
		<table>
			<tr>
				<td><label for="type">Art der Arbeit</label></td>
				<td>
					<select name="type">
						<option value="">-----</option>
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
						<option value="other">Andere Art</option>
					</select>
				</td>
			</tr>

		</table>
		</fieldset>

		<center><input type="submit" value="Suchen" name="searchpaper"/></center>
	</form>
	';




?>

<?php include('single.php'); ?>
