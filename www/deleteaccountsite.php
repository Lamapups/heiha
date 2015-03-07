<?php
session_start();

$the_title = 'Account löschen';

if ($_SESSION['id'])
{
	$the_content = '<p>'.$_SESSION['username'].', bitte gib dein Passwort ein, um deinen Account zu löschen.</p>';
	$the_content .= '
		<form action="deleteaccountaction.php" method="post">
		<p>
			<label for="password">Passwort</label>
				<input type="password" name="password">
		</p>
		<p>
			<input type="submit" value="Account löschen" name="delete">
		</p>
		</form>
		';
}
else
	$the_content = '<p><a href="loginsite.php">Logge dich ein</a>, um deinen Account zu löschen.</p>';


?>

<?php include('single.php'); ?>
