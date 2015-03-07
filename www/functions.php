<?php
	include('includes/class-theme-methods.php');

	function do_main_nav() {
		global $dtm;
		
		$class = "main_nav";
		
		$items_array = array ( 
						array('text' => 'Startseite', 'url' => '/'),
						array('text' => 'Arbeit hochladen', 'url' => 'uploadsite.php'),
						array('text' => 'Arbeit suchen', 'url' => 'searchpapersite.php'),
					);

				if ($_SESSION['username']) //navigation for users who are logged in
				{
					array_push (	$items_array,
							array('text' => 'Profil', 'url' => 'profile.php'),
							array('text' => 'Ausloggen', 'url' => 'logout.php')
							);
				}
				else //navigation for users who are NOT logged in
				{
					array_push (	$items_array,
							array('text' => 'Einloggen', 'url' => 'loginsite.php'),
							array('text' => 'Registrieren', 'url' => 'registersite.php')
							);
				}

		
		return $dtm->navigation($items_array, $class);
	}
	
	function do_html_title($page_title) {
		$title = $page_title." | HeiHa";
		
		return $title;
	}

?>
