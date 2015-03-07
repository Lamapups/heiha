<?php
session_start();

require('password.php');

try{
	// for debugging
	//$the_content .= '<p>'.$tobechanged.': '.$newvalue.'</p>';

	// filtering the POST data using the function filter_input

	// characters in $unicode_regexp match printable unicode characters like a, 1, ´, = or ;
	$unicode_regexp = '[\p{L}\p{N}\p{M}\p{S}\p{P}]';
	
	// options for the filters 
	$regexp_options = array(
		'regexp_username' => array('options' => array('regexp' => '/^'.$unicode_regexp.'{5,30}$/')),
		'regexp_password' => array('options' => array('regexp' => '/^'.$unicode_regexp.'{8,50}$/')),
		'regexp_forename' => array('options' => array('regexp' => '/^'.$unicode_regexp.'{0,30}$/')),
		'regexp_surname' => array('options' => array('regexp' => '/^'.$unicode_regexp.'{0,30}$/')),
		'regexp_university' => array('options' => array('regexp' => '/^.{0,50}$/')),
		);

	// filtering:
	// saving the data given by the user into simpler variables, e. g. $username
	// and validating type etc
	//
	$password = $_POST['oldpassword']; // saving the data given by the user into simpler variables

	$tobechanged = $_POST['tobechanged']; // possible values are 'email', 'university', 'forename' and 'surname'
	if ($tobechanged == 'email')
	{
		if(!($email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)))
			throw new RuntimeException('invalid_email');
	}

	else if ($tobechanged == 'university' || $tobechanged == 'forename' || $tobechanged == 'surname')
	{
		$newvalue = filter_input(INPUT_POST, 'newvalue', FILTER_VALIDATE_REGEXP, $regexp_options['regexp_'.$tobechanged]);
		// NULL means $newvalue is not set, FALSE means that it is not valid according to the regexp
		if($newvalue === FALSE)
			throw new RuntimeException('invalid_'.$tobechanged);
	}

	else
		throw new RuntimeException('unknown_tobechanged');


	// old code without filtering 
	/*
	$password = $_POST['oldpassword']; // saving the data given by the user into simpler variables
	$tobechanged = $_POST['tobechanged']; // possible values are 'email, 'university', 'forename' and 'surname'
	$newvalue = $_POST['newvalue']; // This is the new value of $tobechanged
	 */

	$the_title = 'Profilbearbeitung';

	$mysqli= new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// Checking connection
	if($mysqli->connect_errno)
	{
		$connection_error = $mysqli->connect_error;
		throw new RuntimeException('connection_failed');
	}


	$mysqli->query("SET NAMES 'utf8'");

	$query = sprintf("SELECT passwordhash, %s FROM usertable WHERE username = '%s'",
			$mysqli->real_escape_string($tobechanged),
			$mysqli->real_escape_string($_SESSION['username']));

	$result = $mysqli->query($query);

	// Code for debugging
	/*
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
		// Code for debugging
		//$the_content .= '<p>Passwordhash: '.$row['passwordhash'].'</p>';
		//$the_content .= '<p>Passwort: '.$password.'</p>';
		//$the_content .= '<p>Alter Wert von '.$tobechanged.': '.$row[$tobechanged].'</p>';

		if(password_verify($password, $row['passwordhash'])) // only change $tobechanged if user supplies the correct password
		{

			$query_to_change = sprintf("UPDATE usertable SET %s = '%s' WHERE username = '%s'",
						$mysqli->real_escape_string($tobechanged),
						$mysqli->real_escape_string($newvalue),
						$mysqli->real_escape_string($_SESSION['username']));

			$mysqli->query($query_to_change);


			// update session variable so that the user can see the change without having to log in again

			$_SESSION[$tobechanged] = $newvalue;

			// this is somewhat unclean because it puts the actual supplied data into the session variable, not the data from the database
			// but in principle it is the same.
			// a cleaner way would be a new query to the database and so on 
			// this is faster though

			// one could check if the change was actually successful … maybe later
			header('Location:changereport.php');
			exit();
		}
		else
			throw new RuntimeException('wrong_password');

	}
}
catch (RuntimeException $e)
{
	header('Location:changereport.php?error='.$e->getMessage().'&origin=changeprofilesite'.'&connection_error='.$connection_error);
	exit();
}


?>

<?php include('single.php'); ?>
