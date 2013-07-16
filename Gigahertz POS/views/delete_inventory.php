<?php
	session_start();

	extract($_GET);

	include('../includes/database.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$query = "DELETE FROM inventory WHERE id='$inventory_id'";
	if(mysql_query($query)) {
		header("Location: ../main.php?page=ui_inventory");
		exit;
	} else {
		echo 'Could not delete inventory: <br />';
		echo mysql_error();
		exit;
	}
?>