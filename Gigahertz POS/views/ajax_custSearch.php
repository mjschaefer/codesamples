<?php
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	extract($_GET);

	$mysql_q = "SELECT type,name,business,business_id,street,city,state,zip,phone_primary,phone_secondary,id FROM customers WHERE name LIKE '%" . addslashes($query) . "%' LIMIT 5";
	$cust_data = mysql_query($mysql_q) or die(mysql_error());

	while($cust_arr = mysql_fetch_assoc($cust_data)) {
		echo '<a href="#" class=".customer" onclick="fill_cust(';
			echo '\'' . addslashes($cust_arr['type']) . '\',';
			echo '\'' . addslashes($cust_arr['name']) . '\',';
			echo '\'' . addslashes($cust_arr['business']) . '\',';
			echo '\'' . addslashes($cust_arr['business_id']) . '\',';
			echo '\'' . addslashes($cust_arr['street']) . '\',';
			echo '\'' . addslashes($cust_arr['city']) . '\',';
			echo '\'' . addslashes($cust_arr['state']) . '\',';
			echo '\'' . addslashes($cust_arr['zip']) . '\',';
			echo '\'' . addslashes($cust_arr['phone_primary']) . '\',';
			echo '\'' . addslashes($cust_arr['phone_secondary']) . '\',';
			echo '\'' . addslashes($cust_arr['id']) . '\'';
		echo ');return false;">' . $cust_arr['name'] . ' - ' . $cust_arr['phone_primary'] . '</a>';
		//echo '<a href="#" class=".customer" onclick="return false;">' . $cust_arr['name'] . '</a>';
	}
?>