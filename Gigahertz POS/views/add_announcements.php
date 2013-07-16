<?php
	session_start();
	$username = $_SESSION['username'];
	
	extract($_POST);

	date_default_timezone_set('America/Chicago');
	
	$date = time();

	include('../includes/database.inc.php');

	/*
		table announcements
			$id
			$created_by
			$created_by_username
			$title
			$content
			$tags
			$intended_for
			$has_read
			$date_created
	*/

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	if(!isset($_GET['archive'])) {
		$query = "INSERT INTO announcements(`created_by`,
											`created_by_username`,
											`title`,
											`content`,
											`tags`,
											`intended_for`,
											`has_read`,
											`has_archived`,
											`date_created`) VALUES('" . addslashes($_SESSION['name']) . "',
																   '" . addslashes($_SESSION['username']) . "',
																   '" . addslashes($title) . "',
																   '" . htmlentities(addslashes($announcements_editor)) . "',
																   '" . addslashes($tags) . "',
																   '" . addslashes($recipiant) . "',
																   '," . addslashes($_SESSION['username']) . "',
																   '',
																   '" . addslashes($date) . "')";	

		if(mysql_query($query)) {
			if(isset($_GET['re'])) {
				header("Location: ../main.php?page=ui_knowledgebase_view&knowledgebase=" . $_GET['id']);
				exit;			
			} else {
				header("Location: ../main.php?page=ui_announcements");		
				exit;	
			}
		} else {
			echo $query;
			echo '<br />';
			echo mysql_error();
			exit;
		}		
	} else {
		$id = $_GET['id'];
		$query = "UPDATE announcements SET has_archived = CONCAT(has_archived,',$username') WHERE id='$id'";
		if(mysql_query($query)) {
			header("Location: ../main.php?page=ui_announcements");	
			exit;			
		} else {
			echo $query;
			echo '<br />';
			echo mysql_error();
			exit;
		}
	}
?>