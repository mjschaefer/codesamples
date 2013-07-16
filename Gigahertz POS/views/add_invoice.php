<?php
	session_start();
	
	extract($_POST);
	extract($_GET);

	date_default_timezone_set('America/Chicago');
	
	$date = time();
	$location = $_SESSION['location'];

	$note = $note_id;

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
			$tax_exempt

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

	$foundAvailID = false;
	$sql_success = true;

	$entered_by = $_SESSION['name'];

	//if employee did not search for existing customer
	if(!$cust_id) {
		//echo 'did not find customer<br />';
		//keep trying to find a new customer ID
		while(!$foundAvailID) {
			//generate random customer ID
			$random = rand(100000, 999999);

			//check to see if this customer exists
			if(mysql_num_rows(mysql_query("SELECT * FROM customers WHERE id = '$random'"))){
				//customer exists, so we try again by keeping foundAvailID false
			} else {
				//they don't exist, so we set foundAvailID to true, set our cust_id to the random #, and add this customer to the customer list
				$foundAvailID = true;
				$cust_id = $random;

				$query = "INSERT INTO customers VALUES('" . addslashes($cust_id) . "', '" . addslashes($cust_name) . "', '" . addslashes($cust_business) . "', '" . addslashes(null) . "', '" . addslashes($cust_street) . "', '" . addslashes($cust_city) . "', '" . addslashes($cust_state) . "', '" . addslashes($cust_zip) . "', '" . addslashes($cust_phone_primary) . "', '" . addslashes($cust_phone_secondary) . "', '" . addslashes($cust_type) . "')";

				mysql_query($query) or die("Cannot enter customer because: <br /> '$query' <br />" . mysql_error());
			}
		}
	} else {
		//let's update the customer
		$query = "UPDATE customers SET name='" . addslashes($cust_name) . "', business='" . addslashes($cust_business) . "', street='" . addslashes($cust_street) . "', city='" . addslashes($cust_city) . "', state='" . addslashes($cust_state) . "', zip='" . addslashes($cust_zip) . "', phone_primary='" . addslashes($cust_phone_primary) . "', phone_secondary='" . addslashes($cust_phone_secondary) . "', type='" . addslashes($cust_type) . "' WHERE id='" . $cust_id . "'";

		mysql_query($query) or die("Cannot Execute this Query <br /> '$query' <br />" . mysql_error());
	}

	//--------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------

	//save the invoice
	$query = "INSERT INTO invoices(`notes_id`,
								   `customer_id`,
								   `date`,
								   `paid`,
								   `paid_date`,
								   `subtotal`,
								   `tax`,
								   `tax_exempt`,
								   `discounts`,
								   `total`,
								   `profit`,
								   `balance`,
								   `entered_by`,
								   `location`) VALUES('$note',
								   					  '" . addslashes($cust_id) . "',
								   					  '" . addslashes($date) . "',
								   					  '0',
								   					  '',
								   					  '" . addslashes($subtotal) . "',
								   					  '" . addslashes($tax) . "',
								   					  '" . addslashes($tax_exempt) . "',
								   					  '" . addslashes($discount) . "',
								   					  '" . addslashes($grand) . "',
								   					  '" . addslashes($profit) . "',
								   					  '" . addslashes($balance) . "',
								   					  '" . addslashes($entered_by) . "',
								   					  '" . $location . "')";

	//enter the data to database
	mysql_query($query) or die("Cannot enter invoice because: <br /> '$query' <br />" . mysql_error());

	$invoice_id = mysql_insert_id();

	//associate this invoice to the associated note
	$query = "UPDATE notes SET invoice_id='$invoice_id' WHERE id='$note'";
	mysql_query($query) or die("cannot associate invoice num with note: <br /> '$query' <br /> " . mysql_error());

	//save the items to the invoice
	$invoice_items_array = explode(',',addslashes($itemArray));

	foreach($invoice_items_array as $k => $v) {
		$item_title = 'item_' . $v . '_title';
		$item_msrp = 'item_' . $v . '_msrp';
		$item_price = 'item_' . $v . '_price';
		$item_quantity = 'item_' . $v . '_quantity';
		$item_description = 'item_' . $v . '_description';
		$item_taxable = 'item_' . $v . '_taxable';
		$item_discount = 'item_' . $v . '_discount';
		$item_id = 'item_' . $v . '_databaseid';
		$item_class = 'item_' . $v . '_class';
		$item_subclass = 'item_' . $v . '_subclass';

		if(isset($$item_taxable)) {
			$is_item_taxable = '1';			
		} else {
			$is_item_taxable = '0';
		}

		if($$item_id == '') {
			$$item_id = 0;
		}

		$query = "INSERT INTO items_sold(`inventory_id`,
										 `invoice_id`,
										 `title`,
										 `description`,
										 `msrp`,
										 `price`,
										 `quantity`,
										 `discount`,
										 `class`,
										 `subclass`,
										 `taxable`) VALUES ('" . addslashes($$item_id) . "',
										 					'" . $invoice_id . "',
										 					'" . addslashes($$item_title) . "',
										 					'" . addslashes($$item_description) . "',
										 					'" . addslashes($$item_msrp) . "',
										 					'" . addslashes($$item_price) . "',
										 					'" . addslashes($$item_quantity) . "',
										 					'" . addslashes($$item_discount) . "',
										 					'" . addslashes($$item_class) . "',
										 					'" . addslashes($$item_subclass) . "',
										 					'" . $is_item_taxable . "')";

		//enter the data to database
		if(mysql_query($query)) {
			//now we subtract from stock, only if it's not a service.
			if($$item_class != 'Services') {
				$query = "UPDATE inventory SET stock = stock - " . $$item_quantity . " WHERE id='" . $$item_id . "'";
				mysql_query($query) or die("Cannot change stock because: <br /> '$query' <br />" . mysql_error());				
			}
		} else {
			echo "Cannot enter item because: <br /> '$query' <br />" . mysql_error();
		}
	}
	
	if(!$sql_success) {
		echo '<br /><br /><br />';
		echo '<div style="border: 1px solid black">';
			echo 'Invoice:';
				echo '<pre>' . $query . '</pre><br /><br />';
				//echo '<pre>' . $itemArray . '</pre>';

				echo '<table border=1 cellpadding=5>';
				echo '<tr><td>#</td><td>title</td><td>msrp</td><td>price</td><td>quantity</td><td>description</td><td>taxable</td><td>discount</td></tr>';
				foreach($invoice_items_array as $k => $v) {
					$item_title = 'item_' . $v . '_title';
					$item_msrp = 'item_' . $v . '_msrp';
					$item_price = 'item_' . $v . '_price';
					$item_quantity = 'item_' . $v . '_quantity';
					$item_description = 'item_' . $v . '_description';
					$item_taxable = 'item_' . $v . '_taxable';
					$item_discount = 'item_' . $v . '_discount';

					echo '<tr>';
						echo '<td>' . $v . '</td>';
						echo '<td>' . $$item_title . '</td>';
						echo '<td>' . $$item_msrp . '</td>';
						echo '<td>' . $$item_price . '</td>';
						echo '<td>' . $$item_quantity . '</td>';
						echo '<td>' . $$item_description . '</td>';
						if(isset($$item_taxable)) {
							echo '<td>' . $$item_taxable . '</td>';				
						} else {
							echo '<td>&nbsp;</td>';
						}
						echo '<td>' . $$item_discount . '</td>';
					echo '</tr>';
				}
				echo '</table>';
		echo '</div>';
	}

	if(isset($zerobalance)) {
		if($zerobalance) {
			header("Location: ./add_payments.php?note_id=" . $note_id . "&invoice_id=" . $invoice_id . "&payments_balance=0");
			exit;		
		}		
	}

	if($save_unpaid) {		
		header("Location: ../main.php?page=ui_invoice_view&invoice=" . $invoice_id);
		exit;
	} else {
		echo $invoice_id;		
	}
?>