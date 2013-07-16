<?php
	include('./ajax_chart_functions.php');

	if(!isset($_GET['chart_targ'])) {
		$_GET['chart_targ'] = '1';
	}
?>
<?php

	$unix_month_now = mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']);
	$unix_month_next = mktime(0, 0, 0, $_GET['month'] + 1, 1, $_GET['year']);

	$query = "SELECT * FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date >= '$unix_month_now' AND paid_date < '$unix_month_next'";
	$invoice_data = mysql_query($query) or die(mysql_error());

	$grand_services = 0;
	$grand_parts = 0;
	$grand_tax = 0;
	$grand_discounts = 0;

	while($invoice_arr = mysql_fetch_assoc($invoice_data)) {
		$inv_id = $invoice_arr['id'];

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
		
		$discount_total = $service_discount_total + $parts_discount_total;


		$grand_services += $service_total;
		$grand_parts += $parts_total;
		$grand_tax += $invoice_arr['tax'];
		$grand_discounts += $discount_total;
	}
?>
<script>
	$(document).ready(function() {
		var data = [
				['Services', <?php echo number_format($grand_services, 2, '.', ''); ?>],
				['Parts', <?php echo number_format($grand_parts, 2, '.', ''); ?>], 
				['Tax', <?php echo number_format($grand_tax, 2, '.', ''); ?>], 
				['Discount', <?php echo number_format($grand_discounts, 2, '.', ''); ?>]
			];
			
		var month_this = "<?php echo $months[$_GET['month']]; ?>";
		var year_this = "<?php echo $_GET['year']; ?>";

		$.jqplot("chart_incBreakdown_<?php echo $_GET['chart_targ']; ?>", [data], { 
			seriesColors: [ "green", "orange" , 'blue', 'red'],
			title: {
		        text: 'Income Breakdown - ' + month_this + ' ' + year_this,   // title for the plot,
		        show: true,
		    },
			seriesDefaults: {
				// Make this a pie chart.
				renderer: jQuery.jqplot.PieRenderer, 
				rendererOptions: {
					// Put data labels on the pie slices.
					// By default, labels show the percentage of the slice.
					sliceMargin: 5,
			        showDataLabels: true,
			        // By default, data labels show the percentage of the donut/pie.
			        // You can show the data 'value' or data 'label' instead.
			        dataLabels: 'percent',
			        //dataLabelFormatString: '$ %d'
				}
			},
			legend: { 
				show: true, location: 'e' 
			},
			highlighter: {
				show: true,
				sizeAdjust: 7.5
			},
				cursor: {
				show: false
			}
		});
	});
</script>

<div id="chart_incBreakdown_<?php echo $_GET['chart_targ']; ?>" class="chart"></div>
<table id="data_table">
	<tr>
		<th>Type</th>
		<th>Total</th>
	</tr>
	<tr>
		<td><a href="#" onclick="return false;">Services</a></td>
		<td><a href="#" onclick="return false;">$ <?php echo number_format($grand_services, 2, '.', ''); ?></a></td>
	</tr>

	<tr>
		<td><a href="#" onclick="return false;">Parts</a></td>
		<td><a href="#" onclick="return false;">$ <?php echo number_format($grand_parts, 2, '.', ''); ?></a></td>
	</tr>

	<tr>
		<td><a href="#" onclick="return false;">Sales Tax</a></td>
		<td><a href="#" onclick="return false;">$ <?php echo number_format($grand_tax, 2, '.', ''); ?></a></td>
	</tr>

	<tr>
		<td><a href="#" onclick="return false;">Discounts</a></td>
		<td><a href="#" onclick="return false;">$ <?php echo number_format($grand_discounts, 2, '.', ''); ?></a></td>
	</tr>
</table>