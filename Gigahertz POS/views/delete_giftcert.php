<?php
	session_start();

	extract($_GET);

	include('../includes/database.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$now = time();

	$query = "UPDATE gift_certs SET active=0, used='$now' WHERE id='$cert_id'";
	if(mysql_query($query)) {
		header("Location: ../main.php?page=ui_gift_generate");
		exit;
	} else {
		echo 'Could not delete gift certificate: <br />';
		echo mysql_error();
		exit;
	}
?>