<?php
	session_start();
	
	extract($_POST);
	extract($_GET);

	date_default_timezone_set('America/Chicago');
	
	$date = time();
	$location = $_SESSION['location'];
	$employee = $_SESSION['name'];

	$errors = false;

	include('../includes/database.inc.php');

	/*
		table payments
			$id
			$invoice_id
			$total
			$type
			$location
			$entered_by
			$date
	*/

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$entered_by = $_SESSION['name'];

	//save the items to the invoice
	$payments_array = explode(',',addslashes($payments_array));

	if($payments_balance <= 0) {
		$inv_balance = 0;

		//mark associated note as completed
		if($notes_id >= 1) {
			$query = "UPDATE notes SET complete='1', status='done' WHERE id='$notes_id'";
			if(mysql_query($query)) {
				//mark the time completed
				$query = "UPDATE notes SET date_completed='$date' WHERE id='$notes_id'";
				mysql_query($query) or die("Cannot set date completed");

				$query = "UPDATE notes SET completed_by='$employee' WHERE id='$notes_id'";
				mysql_query($query) or die("Cannot set completed by");

				
			} else {
				"Cannot mark note as completed: <br /> '$query' <br />" . mysql_error();
			}
		}

		$query = "UPDATE invoices SET paid='1', paid_date='$date' WHERE id='$invoice_id'";
		mysql_query($query) or die(mysql_error());
	} else {
		$inv_balance = $payments_balance;
	}

	$query = "UPDATE invoices SET balance='$inv_balance' WHERE id='$invoice_id'";
	mysql_query($query) or die(mysql_error());

	foreach($payments_array as $k => $v) {
		$type_name = 'payment_' . $v . '_type';
		$value_name = 'payment_' . $v . '_value';

		$type = $$type_name;
		$amt = $$value_name;

		$query = "INSERT INTO payments(`invoice_id`,
									   `total`,
									   `type`,
									   `location`,
									   `entered_by`,
									   `date`) VALUES('$invoice_id',
									   				  '$amt',
									   				  '$type',
									   				  '$location',
									   				  '$entered_by',
									   				  '$date')";

		if(!mysql_query($query)) {
			$errors = true;
		}
	}

	if(!$errors) {
		if($inv_balance == 0) {
			header("Location: ../main.php?page=ui_invoice_view&invoice=" . $invoice_id);
			//header("Location: ../main.php?page=ui_invoices&cond=paid");		
		} else {
			header("Location: ../main.php?page=ui_invoice_view&invoice=" . $invoice_id);
			//header("Location: ../main.php?page=ui_invoices&cond=unpaid");
		}
	} else {
		echo mysql_error();
	}
?>