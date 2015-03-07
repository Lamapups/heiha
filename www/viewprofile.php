<?php
session_start();

// Getting username from another site and saving it into a simpler variable
// This serves as an identifier
// The user id could also be used, but this makes the url more readable
// even though this way the url is not alphanumeric
$username = $_GET['username'];

try {
	$mysqli= new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// Checking connection
	if($mysqli->connect_errno)
		throw new RuntimeException('Verbindung fehlgeschlagen: '.$mysqli->connect_error);


	$mysqli->query("SET NAMES 'utf8'");

	// Getting rows with the title $title
	$query = sprintf("SELECT * FROM usertable WHERE username = '%s'", $mysqli->real_escape_string($username));
	$result = $mysqli->query($query);

	if(!$result->num_rows)
		throw new RuntimeException('Benutzer nicht gefunden.');

	while ($row = $result->fetch_assoc())
	{
		$email = $row['email'];
		$email_public = $row['email_public'];
		$forename = $row['forename'];
		$surname = $row['surname'];
		$university = $row['university'];
	}
}
catch (RuntimeException $e)
{
	$the_content = '<p>'.$e->getMessage().'</p>';
}

$the_title = 'Profil von '.$username;

// Only display the email, if the user wanted it
// The email should not be displayed plainly
if ($email_public == 1)
{
	$display_email = '<tr>
				<td>E-Mail-Adresse</td>
				<td>'.htmlspecialchars($email).'</td>
			</tr>';
}

$the_content = '
	<table>
		<tr>
			<td>Benutzername</td>
			<td>'.htmlspecialchars($username).'</td>
		</tr>

		'.$display_email.'

		<tr>
			<td>Vorname</td>
			<td>'.htmlspecialchars($forename).'</td>
		</tr>

		<tr>
			<td>Nachname</td>
			<td>'.htmlspecialchars($surname).'</td>
		</tr>

		<tr>
			<td>Hochschule</td>
			<td>'.htmlspecialchars($university).'</td>
		</tr>
	</table>
	';

	$the_content .= '<p><a href="searchpaperaction.php?title_subtitle=&abstract=&abstract_title=&username=gorgor&forename=&surname=&and_or=or&field1=&field2=&field3=&type=&searchpaper=Suchen">Arbeiten ansehen</a></p>';
?>

<?php include('single.php'); ?>
