<?php
session_start();

require('password.php');

$the_title = 'Einloggen';

$username = $_POST['username']; //saving the data given by the user into simpler variables
$password = $_POST['password'];

$the_content = '<p>Einloggen &#8230</p>';

//username entered by the user
//$the_content .= '<p>Benutzername: '. $_POST['username'].'<br>';
//$the_content .= 'Benutzername: '. $username.'</p>';

try {
	$mysqli= new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// checking connection
	if($mysqli->connect_errno)
	{
		$connection_error = $mysqli->connect_error;
		throw new RuntimeException('connection_failed');
	}


	$mysqli->query("SET NAMES 'utf8'");

	$query = sprintf("SELECT * FROM usertable WHERE username = '%s'", $mysqli->real_escape_string($username));
	$result = $mysqli->query($query);


	if (!$result->num_rows)//if user entered unknown username
		throw new RuntimeException('unknown_username');

	while ($row = $result->fetch_assoc())
	{

		//logging in (or not logging in, that is the question)
		if ($username == $row['username'] && password_verify($password, $row['passwordhash']))
		{
			$_SESSION['id'] = $row['id']; //passing database information to the superglobal _SESSION
			$_SESSION['username'] = $row['username'];
			$_SESSION['passwordhash'] = $row['passwordhash'];
			$_SESSION['forename'] = $row['forename'];
			$_SESSION['surname'] = $row['surname'];
			$_SESSION['university'] = $row['university'];
			$_SESSION['email'] = $row['email'];

			//$the_content .= '<p>'.$row['university'].'</p>';
			//$the_content .= '<p>Erfolgreich eingeloggt. <a href="profile.php">Zum Profil wechseln.</a></p>';
			
			header('Location: profile.php');
			exit();

		}
		else if ($username == $row['username'] && !password_verify($password, $row['passwordhash']))
			throw new RuntimeException('wrong_password');

		//this should not happen, because the data is selected from the database using the username
		else if ($username != $row['username'] && password_verify($password, $row['passwordhash']))
			throw new RuntimeException('unknown_username');
		else
		{
			throw new RuntimeException('unknown');
		}

	}

	$mysqli->close();
}
catch(RuntimeException $e)
{
	header('Location:loginfailed.php?error='.$e->getMessage().'&username='.$username.'&connection_error='.$connection_error);
	exit();
}



?>

<?php include('single.php'); ?>
