<?php
	include('./ajax_chart_functions.php');

	$unix_date = $_GET['date'] / 1000;

	$unix_day_now = mktime(0, 0, 0, date('m', $unix_date), date('d', $unix_date) + 1, date('y', $unix_date));
	$unix_day_next = mktime(23, 0, 0, date('m', $unix_date), date('d', $unix_date) + 7, date('y', $unix_date));

	$query = "SELECT * FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date >= '" . $unix_day_now . "' AND paid_date < '" . $unix_day_next . "' ORDER BY paid_date";
	$invoice_data = mysql_query($query) or die(mysql_error());

	$name_limiter = 20;

	echo date('m-d-y', $unix_day_now) . ' to ' . date('m-d-y', $unix_day_next)
?>
	<table id="data_table">
		<tr>
			<th>Date</th>
			<th>Name</th>
			<th>Invoice #</th>
			<th>Total</th>
			<th>Services</th>
			<th>Parts</th>
			<th>Sales Tax</th>
			<th>Discounts</th>
		</tr>
		<?php
			$grand_total = 0;
			$grand_services = 0;
			$grand_parts = 0;
			$grand_tax = 0;
			$grand_discounts = 0;

			while($invoice_arr = mysql_fetch_assoc($invoice_data)) {
				$cust_id = $invoice_arr['customer_id'];
				$inv_id = $invoice_arr['id'];

				$query = "SELECT name FROM customers WHERE id='$cust_id'";
				$cust_data = mysql_query($query) or die(mysql_error());
				$cust_arr = mysql_fetch_assoc($cust_data);

				$query = "SELECT price,quantity,discount FROM items_sold WHERE invoice_id='$inv_id' AND taxable != true";
				$service_data = mysql_query($query) or die(mysql_error());
				$service_total = 0;
				$service_discount_total = 0;

				while($service_arr = mysql_fetch_assoc($service_data)) {
					$service_total += ($service_arr['price'] * $service_arr['quantity']);

					if($service_arr['discount'] != 0) {
						$service_discount_total = $service_total * ($service_arr['discount'] / 100);						
					}
				}

				$query = "SELECT price,quantity,discount FROM items_sold WHERE invoice_id='$inv_id' AND taxable = true";
				$parts_data = mysql_query($query) or die(mysql_error());
				$parts_total = 0;
				$parts_discount_total = 0;

				while($parts_arr = mysql_fetch_assoc($parts_data)) {
					$parts_total += ($parts_arr['price'] * $parts_arr['quantity']);

					if($parts_arr['discount'] != 0) {
						$parts_discount_total = $parts_total * ($parts_arr['discount'] / 100);						
					}
				}

				$service_total_echo = ($service_total != 0) ? '$ ' . number_format($service_total, 2, '.', '') : '';
				$parts_total_echo = ($parts_total != 0) ? '$ ' . number_format($parts_total, 2, '.', '') : '';
				$tax_echo = ($invoice_arr['tax'] != 0) ? '$ ' . number_format($invoice_arr['tax'], 2, '.', '') : '';

				$discount_total = $service_discount_total + $parts_discount_total;
				$discount_total_echo = ($discount_total != 0) ? '( $ ' . number_format($discount_total, 2, '.', '') . ' )' : '';

				$customer_name = '';
				if(strlen($cust_arr['name']) > $name_limiter) {
					for($x = 0; $x < $name_limiter; $x++) {
						$customer_name .= $cust_arr['name'][$x];
					}
					$customer_name .= ' ...';
				} else {
					$customer_name = $cust_arr['name'];
				}

				echo '<tr>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" target="_blank">' . date("n/j/Y", $invoice_arr['paid_date']) . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" target="_blank">' . $customer_name . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" target="_blank">' . $invoice_arr['id'] . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" target="_blank">$ ' . number_format($invoice_arr['total'], 2, '.', '') . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" target="_blank">' . $service_total_echo . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" target="_blank">' . $parts_total_echo . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" target="_blank">' . $tax_echo . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" target="_blank">' . $discount_total_echo . '</a></td>';
				echo '</tr>';

				$grand_total += $invoice_arr['total'];
				$grand_services += $service_total;
				$grand_parts += $parts_total;
				$grand_tax += $invoice_arr['tax'];
				$grand_discounts += $discount_total;
			}

			echo '<tr>';
				echo '<th>Totals</th>';
				echo '<th></th>';
				echo '<th></th>';
				echo '<th>$ ' . number_format($grand_total, 2, '.', '') . '</th>';
				echo '<th>$ ' . number_format($grand_services, 2, '.', '') . '</th>';
				echo '<th>$ ' . number_format($grand_parts, 2, '.', '') . '</th>';
				echo '<th>$ ' . number_format($grand_tax, 2, '.', '') . '</th>';
				echo '<th>( $ ' . number_format($grand_discounts, 2, '.', '') . ' )</th>';
			echo '</tr>';
		?>
	</table>