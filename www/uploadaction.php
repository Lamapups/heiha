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
	$username = $_SESSION['username'];

	if(!($title = filter_input(INPUT_POST, 'title', FILTER_VALIDATE_REGEXP, $regexp_options['regexp_title'])))
		throw new RuntimeException('invalid_title');

	$subtitle = filter_input(INPUT_POST, 'subtitle', FILTER_VALIDATE_REGEXP, $regexp_options['regexp_subtitle']);
	// NULL means $subtitle is not set, FALSE means that it is not valid according to the regexp
	if($subtitle === FALSE)
		throw new RuntimeException('invalid_subtitle');

	$abstract = $_POST['abstract'];

	// only validate $email if a value is entered at all
	if (isset($email))
	{
		$email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
		if($email === FALSE)
			throw new RuntimeException('invalid_email');
	}

	if (isset($universiy))
	{
		$university = filter_input(INPUT_POST, 'university', FILTER_VALIDATE_REGEXP, $regexp_options['regexp_university']);
		if($university === FALSE)
			throw new RuntimeException('invalid_university');
	}

	if (isset($forename))
	{
		$forename = filter_input(INPUT_POST, 'forename', FILTER_VALIDATE_REGEXP,$regexp_options['regexp_forename']);
		if($forename === FALSE)
			throw new RuntimeException('invalid_forename');
	}

	if (isset($surname))
	{
		$surname = filter_input(INPUT_POST, 'surname', FILTER_VALIDATE_REGEXP,$regexp_options['regexp_surname']);
		if($surname === FALSE)
			throw new RuntimeException('invalid_surname');
	}

	$field1_id = $_POST['field1_id'];
	$field2_id = $_POST['field2_id'];
	$field3_id = $_POST['field3_id'];
	$type = $_POST['type'];
	$email_public = $_POST['email_public'];

	if (isset($compositiondate))
	{
		$compositiondate = filter_input(INPUT_POST, 'compositiondate', FILTER_VALIDATE_REGEXP,$regexp_options['regexp_date']);
		if($compositiondate === FALSE)
			throw new RuntimeException('invalid_compositiondate');
	}

	$replace_titlepage = $_POST['replace_titlepage'];


	// old code without filtering
	/*
	// saving the data given by the user into simpler variables
	$title = $_POST['title'];
	$subtitle = $_POST['subtitle'];
	$abstract = $_POST['abstract'];

	$username = $_SESSION['username'];
	$email= $_POST['email'];
	$forename = $_POST['forename'];
	$surname= $_POST['surname'];

	$university = $_POST['university'];
	$field1_id = $_POST['field1_id'];
	$field2_id = $_POST['field2_id'];
	$field3_id = $_POST['field3_id'];
	$type = $_POST['type'];
	$email_public = $_POST['email_public'];
	$compositiondate = $_POST['compositiondate'];
	 */

	$id_of_empty_field = 60; // see the table users.fields

	$utc_timestamp = time();
	$cet_timestamp = time() - (60*60);

	$the_title = 'Arbeit hochladen';

	$the_content = '<p>Hochladen &#8230</p>';
	$the_content = '<p>'.$compositiondate.'</p>';
	//$the_content .= '<p>'.ini_get('upload_max_filesize').'</p>';

	$the_content .= '<p>'.$field1_id.'</p>';
	$the_content .= '<p>'.$field2_id.'</p>';
	$the_content .= '<p>'.$field3_id.'</p>';


	$mysqli = new mysqli("localhost", "debian-sys-maint", "U4wbbBBJf8KACpVv", "users");

	// Checking connection
	if($mysqli->connect_errno)
	{
		$connection_error = $mysqli->connect_error;
		throw new RuntimeException('connection_failed');
	}

	$mysqli->query("SET NAMES 'utf8'");

	// Put an empty string into field1 if the user specified the id of the empty field
	if ($field1_id == $id_of_empty_field)
		$field1 = '';
	else
	{
		// Getting all the data from the fields table
		$query_for_field1 = sprintf("SELECT * FROM fields WHERE id='%s'",$mysqli->real_escape_string($field1_id));
		$result_for_field1 = $mysqli->query($query_for_field1);

		if (!$result_for_field1->num_rows)
			throw new RuntimeException('no_field1');

		// Putting the appropriate name into field1
		if ($field1_row = $result_for_field1->fetch_assoc())
			$field1 = $field1_row['field_unicode'];
	}

	// Put an empty string into field2 if the user specified the id of the empty field
	if ($field2_id == $id_of_empty_field)
		$field2 = '';
	else
	{
		// Getting all the data from the fields table
		$query_for_field2 = sprintf("SELECT * FROM fields WHERE id='%s'",$mysqli->real_escape_string($field2_id));
		$result_for_field2 = $mysqli->query($query_for_field2);

		if (!$result_for_field2->num_rows)
			throw new RuntimeException('no_field2');

		// Putting the appropriate name into field2
		if ($field2_row = $result_for_field2->fetch_assoc())
			$field2 = $field2_row['field_unicode'];
	}

	// Put an empty string into field3 if the user specified the id of the empty field
	if ($field3_id == $id_of_empty_field)
		$field3 = '';
	else
	{
		// Getting all the data from the fields table
		$query_for_field3 = sprintf("SELECT * FROM fields WHERE id='%s'",$mysqli->real_escape_string($field3_id));
		$result_for_field3 = $mysqli->query($query_for_field3);

		if (!$result_for_field3->num_rows)
			throw new RuntimeException('no_field3');

		// Putting the appropriate name into field3
		if ($field3_row = $result_for_field3->fetch_assoc())
			$field3 = $field3_row['field_unicode'];
	}


	if(!$title)
		throw new RuntimeException('no_title');



	// Getting rows with same title entered
	$query_for_title= sprintf("SELECT title FROM papers WHERE title = '%s'", $mysqli->real_escape_string($title));
	$result_for_title = $mysqli->query($query_for_title);


	// Checking if title is already taken
	if ($result_for_title->num_rows)
		throw new RuntimeException('title_taken');


	// Undefined | Multiple Files | $_FILES Corruption Attack
	// If this request falls under any of them, treat it invalid.
	if (
		!isset($_FILES['paper']['error']) ||
		is_array($_FILES['paper']['error'])
	) {
		throw new RuntimeException('invalid_parameters');
	}

	// Checking $_FILES['paper']['error'] value.
	switch ($_FILES['paper']['error'])
	{
		case UPLOAD_ERR_OK:
		    break;
		case UPLOAD_ERR_NO_FILE:
		    throw new RuntimeException('no_file');
		case UPLOAD_ERR_PARTIAL:
		    throw new RuntimeException('partial');
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
		    throw new RuntimeException('size');
		default:
		    throw new RuntimeException('unknown');
	}

    /*// Checking file size again.
    if ($_FILES['paper']['size'] > 1000000) {
        throw new RuntimeException('Datei zu groß.');
    }
	*/

    // $_FILES['paper']['mime'] value cannot be trusted.
    // Checking MIME type.
/*
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['paper']['tmp_name']),
        array(
            'pdf' => 'application/pdf',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }
 */

	// Checking if file is a pdf
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	if (!($finfo->file($_FILES['paper']['tmp_name']) === 'application/pdf'))
		throw new RuntimeException('no_pdf');


	// Moving paper to directory /papers/
	$newlocation = sprintf('papers/%s.pdf', $title);
	$file_moved = move_uploaded_file($_FILES['paper']['tmp_name'],$newlocation);
	//$the_content .= '<p>'.$newlocation.'</p>';

	// Checking if the file was successfully moved
	if (!$file_moved)
		throw new RuntimeException('not_moved');

	// Replacing titlepage if the user wishes so
	if($replace_titlepage == 1)
	{
	// Defining a command to delete the titlepage
	// using the bash script deletetitlepage.sh
	// Setting locale to allow for unicode shell arguments
	setlocale(LC_TYPE, "en_US.UTF-8");
	$cmd = sprintf("./deletetitlepage.sh %s", escapeshellarg($newlocation));

	// Executing the command
	exec($cmd);
	}

	$viewpaper_url = 'viewpaper.php?title='.$title;

	$query_to_insert = sprintf("INSERT INTO users.papers
				(id, url, title, subtitle, abstract, username, email, email_public, forename, surname, university, field1, field2, field3, type, uploaddate, compositiondate)
				VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', FROM_UNIXTIME(%s), '%s')",
			$mysqli->real_escape_string($newlocation),
			$mysqli->real_escape_string($title),
			$mysqli->real_escape_string($subtitle),
			$mysqli->real_escape_string($abstract),
			$mysqli->real_escape_string($username),
			$mysqli->real_escape_string($email),
			$mysqli->real_escape_string($email_public),
			$mysqli->real_escape_string($forename),
			$mysqli->real_escape_string($surname),
			$mysqli->real_escape_string($university),
			$mysqli->real_escape_string($field1),
			$mysqli->real_escape_string($field2),
			$mysqli->real_escape_string($field3),
			$mysqli->real_escape_string($type),
			$mysqli->real_escape_string($cet_timestamp),
			$mysqli->real_escape_string($compositiondate)
		);

	$mysqli->query($query_to_insert);
	//$the_content .= '<p>'.$query_to_insert.'</p>';
	header('Location:uploadreport.php?viewpaper_url='.$viewpaper_url);
	exit();

} catch (RuntimeException $e) {

	header('Location:uploadreport.php?error='.$e->getMessage().'&connection_error='.$connection_error);
	exit();
}

unset($_POST);
?>

<?php include('single.php'); ?>
