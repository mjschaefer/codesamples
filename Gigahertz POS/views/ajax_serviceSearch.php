<?php
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	extract($_GET);

	$service_search_limiter = 10;

	$mysql_q = "SELECT title,id,price FROM inventory WHERE title LIKE '%$query%' ORDER BY title LIMIT $service_search_limiter";
	$inv_data = mysql_query($mysql_q) or die(mysql_error());

	$i = 1;

	while($inv_arr = mysql_fetch_assoc($inv_data)) {
		if($i == 1) {
			$selected = 'service_selected service_first';
		} else {
			$selected = '';
		}

		if($i == $service_search_limiter) {
			$last = 'service_last';
		} else {
			$last = '';
		}
		echo '<a href="#" class="service_entry ' . $selected . ' ' . $last . '" onclick="insert_service(';
			echo '\'' . addslashes($inv_arr['title']) . '\',';
			echo '\'' . addslashes($inv_arr['id']) . '\',';
			echo '\'$' . addslashes($inv_arr['price']) . '\'';
		echo ');return false;">' . $inv_arr['title'] . ' - $' . $inv_arr['price'] . '</a>';
		$i++;
	}

	$mysql_q = "SELECT title,id,price FROM inventory WHERE upc LIKE '%$query%' ORDER BY title LIMIT $service_search_limiter";
	$inv_data = mysql_query($mysql_q) or die(mysql_error());

	$i = 1;

	while($inv_arr = mysql_fetch_assoc($inv_data)) {
		if($i == 1) {
			$selected = 'service_selected service_first';
		} else {
			$selected = '';
		}

		if($i == $service_search_limiter) {
			$last = 'service_last';
		} else {
			$last = '';
		}
		echo '<a href="#" class="service_entry ' . $selected . ' ' . $last . '" onclick="insert_service(';
			echo '\'' . addslashes($inv_arr['title']) . '\',';
			echo '\'' . addslashes($inv_arr['id']) . '\',';
			echo '\'$' . addslashes($inv_arr['price']) . '\'';
		echo ');return false;">' . $inv_arr['title'] . ' - $' . $inv_arr['price'] . '</a>';
		$i++;
	}
?>