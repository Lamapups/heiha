<?php
session_start();

// Getting title of the paper from searchpaperaction.php and saving it into a simpler variable
// This serves as an identifier
// The paper id could also be used, but this makes the url more readable
// even though this way the url is not alphanumeric
$title = $_GET['title']; 

try {
	$mysqli= new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// Checking connection
	if($mysqli->connect_errno)
		throw new RuntimeException('Verbindung fehlgeschlagen: '.$mysqli->connect_error);


	$mysqli->query("SET NAMES 'utf8'");

	// Getting rows with the title $title
	$query = sprintf("SELECT * FROM papers WHERE title = '%s'", $mysqli->real_escape_string($title));
	$result = $mysqli->query($query);

	if(!$result->num_rows)
		throw new RuntimeException('Arbeit nicht gefunden.');

	while ($row = $result->fetch_assoc())
	{
		$subtitle = $row['subtitle'];
		$abstract = $row['abstract'];
		$url = $row['url'];
		$username = $row['username'];
		$email = $row['email'];
		$email_public = $row['email_public'];
		$forename = $row['forename'];
		$surname = $row['surname'];
		$university = $row['university'];
		$field1 = $row['field1'];
		$field2 = $row['field2'];
		$field3 = $row['field3'];
		$type = $row['type'];
	}
}
catch (RuntimeException $e)
{
	$the_content = '<p>'.$e->getMessage().'</p>';
}

// The title of the site is the same as the papertitle $title
$the_title = htmlspecialchars($title);

// Generating link to profile page of the author
$profile_url = 'viewprofile.php?username='.$username;

// Generating the content of $author from $forename, $surname and $username
if ($username && $forename && $surname)
	$author = $forename.' '.$surname.' (<a href="'.$profile_url.'">'.$username.'</a>)';

else if ($username && ($forename xor $surname))
	$author = $forename.$surname.' (<a href="'.$profile_url.'">'.$username.'</a>)';

else if ($username && !($forename || $surname))
	$author = '<a href="'.$profile_url.'">'.$username.'</a>';

else if (!$username && $forename && $surname)
	$author = $forename.' '.$surname;

else if (!$username && ($forename xor $surname))
	$author = $forename.$surname;

else if (!($username || $forename || $surname))
	$author = 'Nicht bekannt';

// this should not happen
else
	header('Location: error.php');

// Only display the email, if the user wanted it
// The email should not displayed plainly
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
			<td>Titel</td>
			<td>'.htmlspecialchars($title).'</td>
		</tr>

		<tr>
			<td>Untertitel</td>
			<td>'.htmlspecialchars($subtitle).'</td>
		</tr>

		<tr>
			<td>Autor</td>
			<td>'.htmlspecialchars($author).'</td>
		</tr>

		'.htmlspecialchars($display_email).'

		<tr>
			<td>Hochschule</td>
			<td>'.htmlspecialchars($university).'</td>
		</tr>

		<tr>
			<td>Abriss</td>
			<td>'.htmlspecialchars($abstract).'</td>
		</tr>

		<tr>
			<td>Fach 1</td>
			<td>'.htmlspecialchars($field1).'</td>
		</tr>

		<tr>
			<td>Fach 2</td>
			<td>'.htmlspecialchars($field2).'</td>
		</tr>

		<tr>
			<td>Fach 3</td>
			<td>'.htmlspecialchars($field3).'</td>
		</tr>

		<tr>
			<td colspan = "2"><center><a href="'.$url.'">Arbeit lesen</a></center></td>
		</tr>
	</table>
	';


?>

<?php include('single.php'); ?>
