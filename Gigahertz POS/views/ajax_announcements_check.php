<?php
	session_start();

	include('../includes/database.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$username = $_SESSION['username'];

	$hire_date = $_SESSION['hire_date'];
	
	$query = "SELECT count(*) as num FROM announcements WHERE has_read NOT LIKE '%,$username%' AND (intended_for = 'everyone' OR intended_for = '$username') AND date_created >= '$hire_date'";
	$announcement_data = mysql_query($query) or die(mysql_error());
	$announcement_num = mysql_fetch_object($announcement_data);

	$announcements_count = $announcement_num->num;

	echo $announcements_count;
?>