<?php
	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	extract($_GET);

	$query = "SELECT * FROM invoices WHERE id='$invoice'";
	$invoice_data = mysql_query($query) or die(mysql_error());
	$invoice_arr = mysql_fetch_assoc($invoice_data);

	$cust_id = $invoice_arr['customer_id'];

	$query = "SELECT * FROM customers WHERE id='$cust_id'";
	$cust_data = mysql_query($query) or die(mysql_error());
	$cust_arr = mysql_fetch_assoc($cust_data);

?>

<script>
	function print_invoice(inv_num) {
		window.open('./views/ui_invoice_print.php?invNum=' + inv_num);
	}
</script>

<div id="wrapper_main">
	<h1>
		Invoice # <?php echo $invoice_arr['id'] ?>
		<?php
			if($invoice_arr['paid']) {
				echo ' - PAID IN FULL';
			}
		?>

		<a href="#" onclick="print_invoice('<?php echo $invoice_arr['id'] ?>'); return false;" id="button_print">Print</a>
		<?php
			if(!$invoice_arr['paid']) {
				if($invoice_arr['notes_id'] == '') {
					$note = 0;
				} else {
					$note = $invoice_arr['notes_id'];
				}
				
				echo '<a href="#" onclick="prompt_payments(\'' . $invoice_arr['id'] . '\',\'' . $invoice_arr['notes_id'] . '\', \'' . $invoice_arr['balance'] . '\');return false;" id="button_payment">Payment</a>';
			}
			
			if($invoice_arr['notes_id'] >= 1) {
				echo '<a href="?page=ui_notes_edit&note=' . $invoice_arr['notes_id'] . '" id="button_payment">View Note</a>';
			}

			if(!$invoice_arr['paid']) {
				if($_SESSION['clearance'] >= 70) {
					echo '<a href="./views/delete_invoice.php?note_id=' . $invoice_arr['notes_id'] . '&invoice_id=' . $invoice_arr['id'] . '" onclick="return prompt_delete_invoice(' . $invoice_arr['id'] . ');" id="button_delete">Delete</a>';
				}
			}
		?>
	</h1>
	<div id="top">
		<div id="view_cust_info" class="view_wrappers">
			<h3 class="first">Customer Info<a href="./main.php?page=ui_customers&customer=<?php echo $invoice_arr['customer_id']; ?>" class="a_customer"><img src="./img/icon_magnifying_24x24.png" style="float: right;" /></a></h3>
			<hr>
			<?php
				if($cust_arr['type'] == 'Business') {
					echo '<img src="./img/flag_cust_business.png" />';
					echo '<h2 class="name">' . $cust_arr['business'] . '</h2>';
					echo $cust_arr['name'];
					echo '<br />';
				} else {
					echo '<img src="./img/flag_cust_residential.png" />';
					echo '<h2 class="name">' . $cust_arr['name'] . '</h2>';
					if($cust_arr['business'] != '') {
						echo $cust_arr['business'];
						echo '<br />';
					}
				}
			?>
			<?php 
				foreach($cust_arr as $k => $v) {
					if($k != 'id') {
						if($v == '' || $v == 'null' || $v == null || !isset($v)) {
							
						} else {
							switch ($k) {
								case 'name':
									//echo '<h2 class="name">' . $v . '</h2>';
									echo '';
									break;
								case 'business':
									echo '';
									break;
								case 'city':
									echo $v;
									echo ', ';
									break;
								case 'state':
									if($cust_arr['city'] != '') {
										echo $v;
										echo '&nbsp;&nbsp;';
									}
									break;
								case 'type':
									echo '';
									break;
								default:
									echo $v;
									echo '<br />';
									break;
							}
						}
					}
				}
			?>		
		</div>

		<div id="view_payment_history" class="view_wrappers">
			<h3>Payment History</h3>
			<table id="data_table">
				<tr>
					<th>Date</th>
					<th>Type</th>
					<th>Amount</th>
				</tr>
				<?php
					$query = "SELECT * FROM payments WHERE invoice_id='$invoice' ORDER BY `date`";
					$payment_data = mysql_query($query) or die(mysql_error());

					while($payment_arr = mysql_fetch_assoc($payment_data)) {
						echo '<tr>';
							echo '<td>' . date('m-d-y \a\t g:i a', (int)$payment_arr['date']) . '</td>';
							echo '<td>' . $payment_arr['type'] . '</td>';
							echo '<td>$ ' . $payment_arr['total'] . '</td>';
						echo '</tr>';
					}
				?>
			</table>
			<h3>Billing History</h3>
			<table id="data_table" class="billing_table">
				<tr>
					<th>Date</th>
					<th>Method</th>
					<th>Employee</th>
					<th class="th_add" style="padding: 0;"><a href="./views/ajax_billing.php?invoice_id=<?php echo $invoice_arr['id']; ?>&customer_id=<?php echo $invoice_arr['customer_id']; ?>" id="billing_add"><img src="./img/icon_add_16x16.png" style="display: block;" /></a></th>
				</tr>
				<?php
					$query = "SELECT * FROM invoices_billed WHERE invoice_id='$invoice' ORDER BY `date`";
					$billing_data = mysql_query($query) or die(mysql_error());

					while($billing_arr = mysql_fetch_assoc($billing_data)) {
						echo '<tr>';
							echo '<td><span class="billing_list_item">' . date('m-d-y', (int)$billing_arr['date']) . '</span></td>';
							echo '<td><span class="billing_list_item">' . $billing_arr['method'] . '</span></td>';
							echo '<td colspan="2"><span class="billing_list_item">' . $billing_arr['entered_by'] . '</span></td>';
							//echo '<td><span class="billing_list_item">' . $billing_arr['notes'] . '</span></td>';
						echo '</tr>';
					}	
				?>
			</table>
		</div>
	</div>

	<div id="view_invoice_history" class="view_wrappers" style="display: block;">
		<h3>Items Sold</h3>
		<table id="data_table" class="data_table_invoice">
			<?php
				$query = "SELECT * FROM items_sold WHERE invoice_id='$invoice'";
				$items_data = mysql_query($query) or die(mysql_error());

				echo '<tr>';
					echo '<th>Title</th>';
					echo '<th>Description</th>';
					echo '<th>MSRP</th>';
					echo '<th>Price</th>';
					echo '<th>Qty</th>';
					echo '<th>Class</th>';
				echo '</tr>';

				while($items_arr = mysql_fetch_assoc($items_data)) {
					echo '<tr>';
						echo '<td><span class="item_list_td">' . $items_arr['title'] . '</span></td>';
						echo '<td><span class="item_list_td">' . $items_arr['description'] . '</span></td>';
						echo '<td><span class="item_list_td">$ ' . $items_arr['msrp'] . '</span></td>';
						echo '<td><span class="item_list_td">$ ' . $items_arr['price'] . '</span></td>';
						echo '<td><span class="item_list_td">' . $items_arr['quantity'] . '</span></td>';
						echo '<td><span class="item_list_td">' . $items_arr['class'] . '</span></td>';
					echo '</tr>';
				}
			?>
		</table>
		
		<hr>

		<h3 style="text-align: right;">Totals</h3>
		<table id="data_table" style="width: 250px;float: right;text-align: right;">
			<tr>
				<td>Subtotal</td>
				<td>$ <?php echo $invoice_arr['subtotal']; ?></td>
			</tr>
			<tr>
				<td>Discounts</td>
				<td>$ <?php echo $invoice_arr['discounts']; ?></td>
			</tr>
			<tr>
				<td>Tax</td>
				<td>
					<?php
                        if($invoice_arr['tax_exempt'] == '1') {
                            echo 'EXEMPT';
                        } else {
                            echo '$ ' . $invoice_arr['tax'];
                        }
                    ?>
				</td>
			</tr>
			<tr>
				<td>Total</td>
				<td>$ <?php echo $invoice_arr['total']; ?></td>
			</tr>
			<tr style="font-weight: bold;">
				<td>Balance</td>
				<td>$ <?php echo $invoice_arr['balance']; ?></td>
			</tr>
		</table>
	</div>
</div>	