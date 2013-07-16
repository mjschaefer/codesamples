<?php
	session_start();

	extract($_GET);

	include('../includes/database.inc.php');

	/*
		//tables
		//--------------
		//invoice
			$id
			$notes_id
			$customer_id
			$date
			$paid
			$paid_date
			$subtotal
			$tax
			$discounts
			$total
			$profit
			$balance
			$entered_by

		//items_sold
			$id
			$invoice_id
			$title
			$description
			$msrp
			$price
			$discount
			$discount
			$class
			$taxable	
	*/

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$query = "DELETE FROM notes_added WHERE note_id='$note_id'";
	if(mysql_query($query)) {
		$query = "DELETE FROM notes WHERE id='$note_id'";
		if(mysql_query($query)) {
			if($invoice_id == '0') {
				header("Location: ../main.php?page=ui_notes_edit&cond=active");
				exit;				
			} else {
				header("Location: ./delete_invoice.php?fromnote=true&invoice_id=$invoice_id&note_id=0");
				exit;
			}
			exit;		
		} else {			
			echo 'Could not delete actual note: <br />';
			echo mysql_error();
			exit;
		}
	} else {
		echo 'Could not delete any added notes: <br />';
		echo mysql_error();
		exit;
	}
?>