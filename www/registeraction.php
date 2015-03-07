<?php
session_start();

require('password.php');

try {

	// including the file containing definitions for the regular expressions
	include('regexp.php');

	// filtering the POST data using the function filter_input
	// saving the data given by the user into simpler variables, e. g. $username
	// and validating type etc
	//
	if(!($username = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_REGEXP, $regexp_options['regexp_username'])))
		throw new RuntimeException('invalid_username');

	if(!($password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, $regexp_options['regexp_password'])))
		throw new RuntimeException('invalid_password');

	if(!($password2 = filter_input(INPUT_POST, 'password2', FILTER_VALIDATE_REGEXP, $regexp_options['regexp_password'])))
		throw new RuntimeException('invalid_password');

	if(!($email= filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)))
		throw new RuntimeException('invalid_email');

	// only validate $university when a value is entered at all
	if(isset($university))
	{
		$university = filter_input(INPUT_POST, 'university', FILTER_VALIDATE_REGEXP, $regexp_options['regexp_university']);
		// NULL means $university is not set, FALSE means that it is not valid according to the regexp if($university === FALSE)
		if($university === FALSE)
			throw new RuntimeException('invalid_university');
	}

	if(isset($university))
	{
		$forename = filter_input(INPUT_POST, 'forename', FILTER_VALIDATE_REGEXP,$regexp_options['regexp_forename']);
		if($forename === FALSE)
			throw new RuntimeException('invalid_forename');
	}

	if(isset($university))
	{
		$surname = filter_input(INPUT_POST, 'surname', FILTER_VALIDATE_REGEXP,$regexp_options['regexp_surname']);
		if($surname === FALSE)
			throw new RuntimeException('invalid_surname');
	}

	
	// old code without filtering
	/*
	$username = $_POST['username'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
	$email = $_POST['email'];
	$university = $_POST['university'];
	$forename = $_POST['forename'];
	$surname= $_POST['surname'];
	 */

	$the_title = 'Registrieren';

	$the_content = '<p>Registrieren &#8230</p>';


	$mysqli= new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// Checking connection
	if($mysqli->connect_errno)
	{
		$connection_error = $mysqli->connect_error;
		throw new RuntimeException('connection_failed');
	}


	$mysqli->query("SET NAMES 'utf8'");

	// get rows with username entered
	$query_for_username = sprintf("SELECT username FROM usertable WHERE username = '%s'", $mysqli->real_escape_string($username));
	$result_for_username = $mysqli->query($query_for_username);

	// get rows with email entered
	$query_for_email = sprintf("SELECT username FROM usertable WHERE email= '%s'", $mysqli->real_escape_string($email));
	$result_for_email = $mysqli->query($query_for_email);


	// only register the user if certain criteria are met (not already registered etc.)
	// checking some things again
	// some of which were already checked above

	if (!$username)
		throw new RuntimeException('no_username');

	else if (!$password || !$password2)
		throw new RuntimeException('no_password');

	else if (!$email)
		throw new RuntimeException('no_email');

	else if ($rows_with_username = $result_for_username->num_rows)
		throw new RuntimeException('username_taken');

	else if ($rows_with_email = $result_for_email->num_rows)
		throw new RuntimeException('email_taken');

	else if(!($password === $password2) && !$rows_with_username && !$rows_with_email)
		throw new RuntimeException('passwords_dont_match');

	else if($password === $password2 && !$rows_with_username && !$rows_with_email)
	{
		$passwordhash = password_hash($password,PASSWORD_DEFAULT);
		$query = sprintf("INSERT INTO `users`.`usertable` (`id`, `username`, `passwordhash`, `forename`, `surname`, `university`, `email`)
					VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s')",
				$mysqli->real_escape_string($username),
				$mysqli->real_escape_string($passwordhash),
				$mysqli->real_escape_string($forename),
				$mysqli->real_escape_string($surname),
				$mysqli->real_escape_string($university),
				$mysqli->real_escape_string($email)
			);

		$mysqli->query($query);
		header('Location:registerreport.php');
		exit();
	}

	else
		throw new RuntimeException('unknown');
}
catch (RuntimeException $e)
{
	header('Location:registerreport.php?error='.$e->getMessage().'&connection_error='.$connection_error);
	exit();
}


?>

<?php include('single.php'); ?>
