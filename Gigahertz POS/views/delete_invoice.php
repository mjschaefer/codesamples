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

	$query = "SELECT * FROM items_sold WHERE invoice_id=$invoice_id";
	$sold_data = mysql_query($query);

	//add the stock back to inventory if it needs to be.
	while($sold_arr = mysql_fetch_assoc($sold_data)) {
		if($sold_arr['class'] != 'Services') {
			$stockToAdd = $sold_arr['quantity'];
			$inventory_id = $sold_arr['inventory_id'];
			$query = "UPDATE inventory SET stock=stock+$stockToAdd WHERE id=$inventory_id";
			mysql_query($query) or die(mysql_error());
		}
	}

	$query = "DELETE FROM payments WHERE invoice_id='$invoice_id'";
	if(mysql_query($query)) {
		$query = "DELETE FROM items_sold WHERE invoice_id='$invoice_id'";
		if(mysql_query($query)) {
			$query = "DELETE FROM invoices WHERE id='$invoice_id'";
			if(mysql_query($query)) {
				if($note_id == 0) {
					if(isset($fromnote)) {
						header("Location: ../main.php?page=ui_notes_edit&cond=active");
						exit;							
					} else {
						header("Location: ../main.php?page=ui_invoices&cond=unpaid");
						exit;							
					}
					exit;				
				} else {
					$query = "UPDATE notes SET invoice_id='0' WHERE id='$note_id'";
					if(mysql_query($query)) {
						header("Location: ../main.php?page=ui_notes_edit&note=$note_id");
						exit;
					} else {
						echo 'Could not unset invoiced in note ' . $note_id . '<br />';
						echo mysql_error();
						exit;
					}
				}
				exit;				
			} else {				
				echo 'Could not delete actual invoice: <br />';
				echo mysql_error();
				exit;
			}			
		} else {			
			echo 'Could not delete from items_sold: <br />';
			echo mysql_error();
			exit;
		}
	} else {
		echo 'Could not delete from payments: <br />';
		echo mysql_error();
		exit;
	}
?>