<?php
session_start();

$the_title = 'Suchergebnisse';

$title_subtitle = $_GET['title_subtitle'];
$abstract = $_GET['abstract'];
$abstract_title = $_GET['abstract_title'];
$username = $_GET['username'];
$forename = $_GET['forename'];
$surname = $_GET['surname'];
$and_or = $_GET['and_or'];
$field1 = $_GET['field1'];
$field2 = $_GET['field2'];
$field3 = $_GET['field3'];
$type = $_GET['type'];

try{
	// if abstract_title is specified in addition to title_subtitle or abstract
	// tell the user to enter a new query
	// because these fields are mutually exclusive
	if( ($abstract_title && $abstract) || ($abstract_title && $title_subtitle) )
		throw new RuntimeException(
			'Gib entweder etwas ins Feld „Abriss oder Titel/Untertitel enthält“ oder in die Felder 
			„Titel/Untertitel enthält“ und „Abriss enthält“ ein. Nicht in beides.'
		);


	$mysqli= new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// Checking connection
	if($mysqli->connect_errno)
	{
		$connection_error = $mysqli->connect_error;
		throw new RuntimeException('Verbindung fehlgeschlagen: '.$mysqli->connect_error);
	}

	$mysqli->query("SET NAMES 'utf8'");


	// Generating the first part of the query
	//
	// if the user did NOT specify abstract_title
	if (!$abstract_title)
	{
		$query_start = sprintf("SELECT * FROM papers WHERE 
				(title LIKE '%s' OR subtitle LIKE '%s')
				AND abstract LIKE '%s' ",
				$mysqli->real_escape_string('%'.$title_subtitle.'%'),
				$mysqli->real_escape_string('%'.$title_subtitle.'%'),
				$mysqli->real_escape_string('%'.$abstract.'%'));
	}

	// if the user did specify abstract_title
	else if ($abstract_title)
	{
		$query_start = sprintf("SELECT * FROM papers WHERE 
				title LIKE '%s' 
				OR subtitle LIKE '%s'
				OR abstract LIKE '%s' ",
				$mysqli->real_escape_string('%'.$abstract_title.'%'),
				$mysqli->real_escape_string('%'.$abstract_title.'%'),
				$mysqli->real_escape_string('%'.$abstract_title.'%'));
	}

	// this should not happen
	else
		throw new RuntimeException('Unbekannter Fehler bei der Eingabe der Inhaltsparameter.');


	// Generating the middle part of the query
	$query_middle = sprintf("
			AND username LIKE '%s'
			AND forename LIKE '%s'
			AND surname LIKE '%s'
			AND type LIKE '%s' ",
			$mysqli->real_escape_string('%'.$username.'%'),
			$mysqli->real_escape_string('%'.$forename.'%'),
			$mysqli->real_escape_string('%'.$surname.'%'),
			$mysqli->real_escape_string('%'.$type.'%'));

	// Generating the last part of the query 
	//
	// if every field the user has given has to match
	// i. e. $and_or='and'
	if ($and_or === 'and')
	{
		// if the user has given a value for field1
		if ($field1)
		{
			$end1 = sprintf("AND 
				(field1 = '%s'
				OR field2 = '%s'
				OR field3 = '%s') ",
				$mysqli->real_escape_string($field1),
				$mysqli->real_escape_string($field1),
				$mysqli->real_escape_string($field1));
		}

		// if the user has given a value for field2
		if ($field2)
		{
			$end2 = sprintf("AND 
				(field1 = '%s'
				OR field2 = '%s'
				OR field3 = '%s') ",
				$mysqli->real_escape_string($field2),
				$mysqli->real_escape_string($field2),
				$mysqli->real_escape_string($field2));
		}

		// if the user has given a value for field3
		if ($field3)
		{
			$end3 = sprintf("AND 
				(field1 = '%s'
				OR field2 = '%s'
				OR field3 = '%s') ",
				$mysqli->real_escape_string($field3),
				$mysqli->real_escape_string($field3),
				$mysqli->real_escape_string($field3));
		}

		$query_end = $end1.$end2.$end3;
	}

	// if every field the user has given has to match
	// i. e. $and_or='and'
	else if ($and_or === 'or')
	{
		if ($field1 || $field2 || $field3)
		{
			$query_end = sprintf("AND
					(field1 = '%s'
					OR field2 = '%s'
					OR field3 = '%s'

					OR field1 = '%s'
					OR field2 = '%s'
					OR field3 = '%s'

					OR field1 = '%s'
					OR field2 = '%s'
					OR field3 = '%s')",
					$mysqli->real_escape_string($field1),
					$mysqli->real_escape_string($field1),
					$mysqli->real_escape_string($field1),

					$mysqli->real_escape_string($field2),
					$mysqli->real_escape_string($field2),
					$mysqli->real_escape_string($field2),

					$mysqli->real_escape_string($field3),
					$mysqli->real_escape_string($field3),
					$mysqli->real_escape_string($field3));
		}

	}

	// this should not happen
	else
		throw new RuntimeException('Unbekannter Fehler bei der Eingabe der Fachparameter.');

	// save the three query parts into one query
	$query = $query_start.$query_middle.$query_end;

	// display the final query
	//$the_content .= '<p>'.$query.'</p>';

	// Executing the query
	$result = $mysqli->query($query);
	//if (!$result->num_rows)
		//$the_content .= '<p>Kein Ergebnis</p>';
	//
	$the_content .= '<p>'.$result->num_rows.' Ergebnisse</p>';
	//$row = $result->fetch_assoc();
	
	// Printing the results of the query
	while ($row = $result->fetch_assoc())
	{
		if ($row['subtitle'])
			$dot_and_subtitle = '. '.htmlspecialchars($row['subtitle']);

		$viewpaper_url = 'viewpaper.php?title='.$row['title'];

		$the_content .= '<p><a href="'.$viewpaper_url.'">'.htmlspecialchars($row['title']).$dot_and_subtitle.'</a>';

		// Generating link to profile page of the author
		$profile_url = 'viewprofile.php?username='.$row['username'];

		// Generating the content of $author from $row['forename'], $row['surname'] and $row['username']
		if ($row['username'] && $row['forename'] && $row['surname'])
			$author = htmlspecialchars($row['forename']).' '.htmlspecialchars($row['surname']).' (<a href="'.$profile_url.'">'.htmlspecialchars($row['username']).'</a>)';

		else if ($row['username'] && ($row['forename'] xor $row['surname']))
			$author = htmlspecialchars($row['forename']).htmlspecialchars($row['surname']).' (<a href="'.$profile_url.'">'.htmlspecialchars($row['username']).'</a>)';

		else if ($row['username'] && !($row['forename'] || $row['surname']))
			$author = '<a href="'.$profile_url.'">'.htmlspecialchars($row['username']).'</a>';

		else if (!$row['username'] && $row['forename'] && $row['surname'])
			$author = htmlspecialchars($row['forename']).' '.htmlspecialchars($row['surname']);

		else if (!$row['username'] && ($row['forename'] xor $row['surname']))
			$author = htmlspecialchars($row['forename']).htmlspecialchars($row['surname']);

		else if (!($row['username'] || $row['forename'] || $row['surname']))
			$author = '<em>einem unbekannten Autor</em>';

		// this should not happen
		else
			header('Location: error.php');


		// Adding author to the output
		$the_content .= '<br>von '.$author;


		// If any field is specified in the database
		// then get the fields and print them in astonishing beauty
		if ($row['field1'] || $row['field2'] || $row['field3'])
		{
			$the_content .= '<br>';
			$existing_fields = array(1 => 1);

			// Checking every the three field columns in the database for an entry
			// and if there is one, put it into the array $existing_fields
			// The number of the found fields is stored in $fieldcount
			$fieldcount = 0;
			for($i=1; $i <= 3; $i++)
			{
				$checked_field = sprintf("field%d", $i);
				if ($row[$checked_field])
				{
					$fieldcount++;
					$existing_fields[$fieldcount] = $row[$checked_field];
				}
			}

			// Print the fields depending on how many fields were found in the loop above
			switch($fieldcount)
			{
				case 1:
					$the_content .= $existing_fields[1].'.';
					break;
				case 2:
					$the_content .= $existing_fields[1].'. '.$existing_fields[2].'.';
					break;
				case 3:
					$the_content .= $existing_fields[1].'. '.$existing_fields[2].'. '.$existing_fields[3].'.';
					break;
				default:
					throw new RuntimeException('$fieldcount nicht zwischen 1 und 3.');
					break;
			}
		}
					
		$the_content .= '</p>';

	}

}
catch(RuntimeException $e)
{
	$the_content .= '<p>'.$e->getMessage().'</p>';
}

?>

<?php include('single.php'); ?>
