<?php
	if(!isset($_GET['month'])) {
		$_GET['month'] = date('n');
	}

	if(!isset($_GET['year'])) {
		$_GET['year'] = date('Y');
	}

	if(!isset($_GET['location'])) {
		$_GET['location'] = $_SESSION['location'];
	}
	
	$now = getdate();

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

	$unix_month_now = mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']);
	$unix_month_next = mktime(0, 0, 0, $_GET['month'] + 1, 1, $_GET['year']);

	$month_textual = date('F', $unix_month_now);
	
	$location = $_GET['location'];

	$name_limiter = 20;


	
	$page_header_html = '<span id="h5_container">';
		$page_header_html .= '<h5>';
			$page_header_html .= '<select id="month_selector" name="month">';
				for($i = 1; $i <= 12; $i++) {
					if($_GET['month'] == $i) {
						$selected = ' selected="selected"';
					} else {
						$selected = '';
					}
					$page_header_html .= '<option value="' . $i . '"' . $selected . '>' . $months[$i] . '</option>';
				}
			$page_header_html .= '</select>';

			$query = "SELECT `date` FROM invoices ORDER BY `date` LIMIT 1";
			$date_data = mysql_query($query) or die(mysql_error());
			$date_array = mysql_fetch_assoc($date_data);
			$lowest_year = date('Y', $date_array['date']);

			$page_header_html .= '<select id="year_selector" name="year">';
				for($i = $lowest_year; $i <= $now['year']; $i++) {
					if($i == $_GET['year']) {
						$selected = ' selected="selected"';						
					} else {
						$selected = '';
					}

					$page_header_html .= '<option value="' . $i . '"' . $selected . '>' . $i . '</a>';
				}
			$page_header_html .= '</select>';

			$query = "SELECT DISTINCT location FROM invoices ORDER BY location";
			$location_data = mysql_query($query) or die(mysql_error());

			$page_header_html .= '<select id="location_selector" name="location">';
				while($location_array = mysql_fetch_assoc($location_data)) {
					$page_header_html .= '<option value="' . $location_array['location'] . '"';
						if($location == $location_array['location']) {
							$page_header_html .= ' selected="selected"';
						}
					$page_header_html .= '>' . $location_array['location'] . '</option>';
				}
			$page_header_html .= '</select>';
		$page_header_html .= '</h5>';
	$page_header_html .= '</span>';
?>

<script>
		<?php
			echo "var report_month = '" . $month_textual . "';\n";
			echo "var report_year = '" . $_GET['year'] . "';\n";
			echo "var report_location = '" . $location . "';\n";
		?>

		$("#page_title").html('<img src="./img/icon_frequency_48x48.png" style="float: left; margin-right: 10px;" /><h1>Gigahertz Income Report</h1><h2>' + report_month + ' ' + report_year + '</h2><h3>' + report_location + '</h3>');
		$("#page_title").append('<?php echo $page_header_html; ?>');

		$("select").change(function() {
			window.location = "?page=ui_report_income&month=" + $("#month_selector option:selected").val() + "&year=" + $("#year_selector option:selected").val() + "&location=" + $("#location_selector option:selected").val();
		});
</script>

<div id="wrapper_table">		
	<table class="data_table">
		<tr>
			<th style="width: 85px;">Date</th>
			<th style="width: 247px;">Name</th>
			<th style="width: 110px;">Invoice #</th>
			<th style="width: 110px;">Total</th>
			<th style="width: 110px;">Services</th>
			<th style="width: 110px;">Parts</th>
			<th style="width: 110px;">Exemptions</th>
			<th style="width: 110px;">Sales Tax</th>
			<th style="width: 110px;">Discounts</th>
		</tr>
		<?php
			$line_tracker = 4;
			$lines_per_page = 26;

			mysql_connect($server, $login, $pass) or die("Unable to Connect");
			mysql_select_db($db) or die("Unable to select database");

			$query = "SELECT * FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date >= '$unix_month_now' AND paid_date < '$unix_month_next' AND location = '$location' ORDER BY paid_date";
			$invoice_data = mysql_query($query) or die(mysql_error());

			$grand_total = 0;
			$grand_services = 0;
			$grand_parts = 0;
			$grand_tax = 0;
			$grand_discounts = 0;
			$grand_exemptions = 0;

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
						$service_discount_total += $service_arr['price'] * ($service_arr['discount'] / 100);
					}
				}

				$query = "SELECT price,quantity,discount FROM items_sold WHERE invoice_id='$inv_id' AND taxable = true";
				$parts_data = mysql_query($query) or die(mysql_error());
				$parts_total = 0;
				$parts_discount_total = 0;
				$exemption_total = 0;

				while($parts_arr = mysql_fetch_assoc($parts_data)) {
					if($invoice_arr['tax_exempt'] == '1') {
						$exemption_total += ($parts_arr['price'] * $parts_arr['quantity']);	
					} else {
						$parts_total += ($parts_arr['price'] * $parts_arr['quantity']);
					}

					if($parts_arr['discount'] != 0) {
						$parts_discount_total += $parts_arr['price'] * ($parts_arr['discount'] / 100);						
					}
				}

				$service_total_echo = ($service_total != 0) ? '$ ' . number_format($service_total, 2, '.', '') : '';
				$parts_total_echo = ($parts_total != 0) ? '$ ' . number_format($parts_total, 2, '.', '') : '';
				$exemption_echo = ($exemption_total != 0) ? '$ ' . number_format($exemption_total, 2, '.', '') : '';

				if($invoice_arr['tax_exempt'] != '1') {
					$tax_echo = ($invoice_arr['tax'] != 0) ? '$ ' . number_format($invoice_arr['tax'], 2, '.', '') : '';
				} else {
					$tax_echo = '';
				}

				$discount_total = $service_discount_total + $parts_discount_total;
				//$discount_total = $service_discount_total;
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
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">' . date("n/j/Y", $invoice_arr['paid_date']) . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">' . $customer_name . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">' . $invoice_arr['id'] . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">$ ' . number_format($invoice_arr['total'], 2, '.', '') . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">' . $service_total_echo . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">' . $parts_total_echo . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">' . $exemption_echo . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">' . $tax_echo . '</a></td>';
					echo '<td><a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">' . $discount_total_echo . '</a></td>';
				echo '</tr>';

				if($invoice_arr['tax_exempt'] == '1') {
					$grand_exemptions += $exemption_total;
				} else {
					$grand_parts += $parts_total;		
					$grand_tax += $invoice_arr['tax'];			
				}
				$grand_total += $invoice_arr['total'];
				$grand_services += $service_total;
				$grand_discounts += $discount_total;

				$line_tracker++;
				if($line_tracker == $lines_per_page) {
					echo '</table><table class="data_table">
							<tr>
								<th style="width: 85px;">Date</th>
								<th style="width: 247px;">Name</th>
								<th style="width: 110px;">Invoice #</th>
								<th style="width: 110px;">Total</th>
								<th style="width: 110px;">Services</th>
								<th style="width: 110px;">Parts</th>
								<th style="width: 110px;">Exemptions</th>
								<th style="width: 110px;">Sales Tax</th>
								<th style="width: 110px;">Discounts</th>
							</tr>';
					$line_tracker = 0;
				}
			}

			echo '<tr>';
				echo '<th>Totals</th>';
				echo '<th></th>';
				echo '<th></th>';
				echo '<th>$ ' . number_format($grand_total, 2, '.', '') . '</th>';
				echo '<th>$ ' . number_format($grand_services, 2, '.', '') . '</th>';
				echo '<th>$ ' . number_format($grand_parts, 2, '.', '') . '</th>';
				echo '<th>$ ' . number_format($grand_exemptions, 2, '.', '') . '</th>';
				echo '<th>$ ' . number_format($grand_tax, 2, '.', '') . '</th>';
				echo '<th>( $ ' . number_format($grand_discounts, 2, '.', '') . ' )</th>';
			echo '</tr>';

		?>
	</table>
</div>