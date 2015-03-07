<?php
session_start();

require('password.php');

// CHanging the data from deleteaccountsite.php into simpler variable(s)
$password = $_POST['password'];

$the_title = 'Account löschen';


$mysqli = new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

if($mysqli->connect_errno)
{
	$the_content .= '<p>Verbindung fehlgeschlagen: ' . $mysqli->connect_error . '</p>';
	exit();
}

$mysqli->query("SET NAMES 'utf8'");

$query = sprintf("SELECT passwordhash FROM usertable WHERE username = '%s'",
		$mysqli->real_escape_string($_SESSION['username']));
$result = $mysqli->query($query);

// Checking whether there is any result
if (!$result->num_rows)
{
	$the_content .= '<p>Unbekannter Benutzername</p>';
	exit();
}

if($row = $result->fetch_assoc())
{
	// If the password given by the user matches the one from the query above,
	// then delete the account

	if (password_verify($password,$row['passwordhash']))
	{
		$query_to_delete = sprintf("DELETE FROM usertable WHERE username='%s'", $mysqli->real_escape_string($_SESSION['username']));

		// Perform query
		$mysqli->query($query_to_delete);

		session_destroy();
		header('Location:accountdeleted.php');
	}
	else
		$the_content .= '<p>Falsches Passwort.</p>';

}

?>

<?php include('single.php'); ?>
