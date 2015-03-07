<?php
session_start();

$the_title = 'Ausloggen';

session_destroy();
header('Location:index.php');

?>

<?php include('single.php'); ?>
