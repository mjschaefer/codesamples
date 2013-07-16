<?php
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$months = array(1 => "January",
					2 => "February",
					3 => "March",
					4 => "April",
					5 => "May",
					6 => "June",
					7 => "July",
					8 => "August",
					9 => "September",
					10 => "October",
					11 => "November",
					12 => "December");

	if(!isset($_GET['year'])) {
		$_GET['year'] = date('Y');
	}

	if(!isset($_GET['month'])) {
		$_GET['month'] = date('n');
	}

	if(!isset($_GET['location'])) {
		$_GET['location'] = $_SESSION['location'];
	}
	
	function get_month2month($year) {
		$output_array = "[";

		for($i = 0; $i < 12; $i++) {
			$unix_month_now = mktime(0, 0, 0, $i + 1, 1, $year);
			$unix_month_next = mktime(0, 0, 0, $i + 2, 1, $year);

			$query = "SELECT SUM(total) FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date >= '$unix_month_now' AND paid_date < '$unix_month_next'";
			$sum_data = mysql_query($query) or die(mysql_error());
			$sum_arr = mysql_fetch_assoc($sum_data);

			if($sum_arr['SUM(total)'] != '') {
				$output_array .= $sum_arr['SUM(total)'] . ', ';					
			}
		}
		$output_array .= "]\n";

		return $output_array;
	}
	function get_month_total($month, $year) {
		$unix_month_now = mktime(0, 0, 0, $month, 1, $year);
		$unix_month_next = mktime(0, 0, 0, $month + 1, 1, $year);

		$query = "SELECT SUM(total) FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date >= '$unix_month_now' AND paid_date < '$unix_month_next'";
		$sum_data = mysql_query($query) or die(mysql_error());
		$sum_arr = mysql_fetch_assoc($sum_data);

		return number_format($sum_arr['SUM(total)'], 2, '.', '');
	}
?>	