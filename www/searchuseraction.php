<?php
session_start();

$the_title = 'Suchergebnisse';

$username = $_GET['username'];
$forename= $_GET['forename'];
$surname = $_GET['surname'];
$university = $_GET['university'];


try {
	$mysqli= new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// Checking connection
	if($mysqli->connect_errno)
	{
		$connection_error = $mysqli->connect_error;
		throw new RuntimeException('Verbindung fehlgeschlagen: '.$mysqli->connect_error);
	}

	$mysqli->query("SET NAMES 'utf8'");

	$query = sprintf("SELECT username, forename, surname, university FROM usertable WHERE
			username LIKE '%s'
			AND forename LIKE '%s'
			AND surname LIKE '%s'
			AND university LIKE '%s'",
			$mysqli->real_escape_string('%'.$username.'%'),
			$mysqli->real_escape_string('%'.$forename.'%'),
			$mysqli->real_escape_string('%'.$surname.'%'),
			$mysqli->real_escape_string('%'.$university.'%'));

	$result = $mysqli->query($query);

	// display the final query
	//$the_content .= '<p>'.$query.'</p>';

	$the_content .= '<p>'.$result->num_rows.' Ergebnisse</p>';

	// Printing user information
	while($row = $result->fetch_assoc())
	{
		// Printing username
		$the_content .= '<p><a href="viewprofile.php?username='.$row['username'].'">'.htmlspecialchars($row['username']).'</a>';

		// Printing the actual name
		if($row['forename'])
			$the_content .= '<br>'.htmlspecialchars($row['forename']).' '.htmlspecialchars($row['surname']);
		else if ($row['surname'])
			$the_content .= '<br>'.htmlspecialchars($row['surname']);

		// Printing the university
		if ($row['university'])
			$the_content .= '<br>'.htmlspecialchars($row['university']);
			
		$the_content .= '</p>';
	}

}
catch(RuntimeException $e)
{
	$the_content .= '<p>'.$e->getMessage().'</p>';
}

?>

<?php include('single.php'); ?>
