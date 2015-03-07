<?php
session_start();

require('password.php');

$oldpassword = $_POST['oldpassword'];
$newpassword = $_POST['newpassword'];
$newpassword2 = $_POST['newpassword2'];


$the_title = 'Passwortänderung';

$the_content .= '<p>Passwort wird geändert &#8230</p>';

try {
	$mysqli= new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// Checking connection
	if($mysqli->connect_errno)
	{
		$connection_error = $mysqli->connect_error;
		throw new RuntimeException('connection_failed');
	}


	$mysqli->query("SET NAMES 'utf8'");

	$the_content .= '<p>Benutzername: '.$_SESSION['username'].'</p>';

	$query = sprintf("SELECT passwordhash FROM usertable WHERE username = '%s'",
			$mysqli->real_escape_string($_SESSION['username']));

	$result = $mysqli->query($query);

	/* debugging stuff
	$the_content .= '<p>'.$query.'</p>';

	if($result)
		$the_content .= '<p>$result exists</p>';
	else if(!$result)
		$the_content .= '<p>$result does not exist</p>';
	 */

	if (!$result->num_rows) //if the query does not return a row
		throw new RuntimeException('no_rows');

	while ($row = $result->fetch_assoc())
	{
		if(!($newpassword === $newpassword2))
			throw new RuntimeException('passwords_dont_match');

		else if($newpassword === $newpassword2 && password_verify($oldpassword, $row['passwordhash'])) // only change $tobechanged if user supplies the correct password
		{
			$newpasswordhash = password_hash($newpassword, PASSWORD_DEFAULT);
			$query_to_change = sprintf("UPDATE usertable SET passwordhash = '%s' WHERE username = '%s'",
						$mysqli->real_escape_string($newpasswordhash),
						$mysqli->real_escape_string($_SESSION['username']));

			$mysqli->query($query_to_change);
			header('Location:changereport.php');
			exit();

			// one could check if the change was successful … maybe later
		}
		else
			throw new RuntimeException('wrong_password');

	}
}
catch (RuntimeException $e)
{
	header('Location:changereport.php?error='.$e->getMessage().'&origin=changepasswordsite'.'&connection_error='.$connection_error);
	exit();
}

?>

<?php include('single.php'); ?>
