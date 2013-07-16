<?php
	session_start();
	
	extract($_POST);

	date_default_timezone_set('America/Chicago');
	
	$date = time();

	include('../includes/database.inc.php');

	/*
		table knowledgebase
			$id
			$created_by
			$created_by_username
			$title
			$content
			$tags
			$date_created
	*/

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$query = "INSERT INTO knowledgebase(`created_by`,
										`created_by_username`,
										`title`,
										`content`,
										`tags`,
										`date_created`) VALUES('" . addslashes($_SESSION['name']) . "',
															   '" . addslashes($_SESSION['username']) . "',
															   '" . addslashes($title) . "',
															   '" . htmlentities(addslashes($knowledgebase_editor)) . "',
															   '" . addslashes($tags) . "',
															   '" . addslashes($date) . "')";	

	if(mysql_query($query)) {
		header("Location: ../main.php?page=ui_knowledgebase");
	} else {
		echo $query;
		echo '<br />';
		echo mysql_error();
	}
?>