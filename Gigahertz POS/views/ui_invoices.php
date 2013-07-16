<?php
	extract($_GET);

	include('./includes/html_parser/simple_html_dom.php');
?>
	<?php
		//---------------------------------------------------------------------------------------//
		//--- INVOICE CREATION ------------------------------------------------------------------//
		//---------------------------------------------------------------------------------------//
		if(!isset($cond)) {
			echo '<script>';
			if(!isset($note_id)) {
				$note_id = 0;
				$cust_name = "";
				$cust_type = "";
				$cust_business = "";
				$cust_street = "";
				$cust_city = "";
				$cust_state = "";
				$cust_zip = "";
				$cust_phone_primary = "";
				$cust_phone_secondary = "";
				$cust_id = "";

				$checked_residential = "checked";
				$checked_business = "";
			} else {
				mysql_connect($server, $login, $pass) or die("Unable to Connect");
				mysql_select_db($db) or die("Unable to select database");

				$query = "SELECT * FROM notes WHERE id='$note_id'";
				$note_data = mysql_query($query) or die(mysql_error());
				$note_arr = mysql_fetch_assoc($note_data);

				$cust_id = $note_arr['customer_id'];

				$query = "SELECT * FROM customers WHERE id='$cust_id'";
				$cust_data = mysql_query($query) or die(mysql_error());
				$cust_arr = mysql_fetch_assoc($cust_data);

				//$note_id already set via GET
				$cust_name = $cust_arr['name'];
				$cust_type = $cust_arr['type'];
				$cust_business = $cust_arr['business'];
				$cust_street = $cust_arr['street'];
				$cust_city = $cust_arr['city'];
				$cust_state = $cust_arr['state'];
				$cust_zip = $cust_arr['zip'];
				$cust_phone_primary = $cust_arr['phone_primary'];
				$cust_phone_secondary = $cust_arr['phone_secondary'];

				if($cust_type == "Business") {
					$checked_residential = "";
					$checked_business = "checked";
				} else {
					$checked_residential = "checked";
					$checked_business = "";
				}

				$query = "SELECT * FROM notes_added WHERE note_id='$note_id'";
				$notes_added_data = mysql_query($query) or die(mysql_error());

					echo '$(document).ready(function() {';
						while($notes_added_arr = mysql_fetch_assoc($notes_added_data)) {
							$html = str_get_html(stripslashes(html_entity_decode($notes_added_arr['content'])));

							$buttons = $html->find('input');	

							foreach($buttons as $b) {
								$inv_id = $b->title;

								$query = "SELECT * FROM inventory WHERE id='$inv_id'";
								$inv_data = mysql_query($query) or die(mysql_error());
								$inv_arr = mysql_fetch_assoc($inv_data);

								//echo '$("#wrapper_items").append(add_item());';

								//echo 'alert(item_arr[item_arr.length - 1]);';
								//fill_inventory(id, targ_number, title, msrp, price, quantity, description, classification, subclass, taxable, discount, stock, fromScanner)
								echo 'var new_id = new_item_id();';
								//echo 'alert(new_id);';
								echo "fill_inventory(
													'" . $inv_arr['id'] . "', 
													new_id, 
													'" . $inv_arr['title'] . "', 
													'" . $inv_arr['msrp'] . "', 
													'" . $inv_arr['price'] . "', 
													'1', 
													'" . $inv_arr['description'] . "', 
													'" . $inv_arr['class'] . "', 
													'" . $inv_arr['subclass'] . "', 
													'" . $inv_arr['taxable'] . "', 
													'0', 
													'" . $inv_arr['stock'] . "',
													'0'
												);";
							}
						}
					echo '});';
			}
	?>
</script>

<script>
	$(document).ready(function() {
		if(item_arr.length == 0) {
			add_item();	
	    }

		$('html').click(function() {
			setup_scanning();
		});

	    $("#searchbar").click(function(event) { 
	    	event.stopPropagation(); 
	    });

	    $("#customer_info > input").click(function(event) { 
	    	event.stopPropagation(); 
	    });

	    $(".item > input").click(function(event) { 
	    	event.stopPropagation(); 
	    });

		checkPlaceholders();
	});
</script>

<div id="wrapper_main">
	<?php
		if($note_id >= 1) {
	?>
		<h3 class="note_marker_h3">
			<img src="./img/icon_warning_16x16.png" /> The note associated with this invoice (#<?php echo $note_id; ?>) will be marked completed when balance is 0.
		</h3>
	<?php
		}
	?>

	<form id="new_computer" name="invoice" method="POST" action="./views/add_invoice.php">
		<div id="wrapper_info" class="wrappers">
			<div id="customer_info" class="info_top">
				<img src="./img/silhouette.png" />
				<input type="radio" name="cust_type" value="Residential" <?php echo $checked_residential; ?>> Residential &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="cust_type" value="Business" <?php echo $checked_business; ?>> Business
				<input name="cust_name" onfocus="$(this).attr('hasFocus','true')" onblur="$(this).attr('hasFocus','false')" size="42" id="cust_name" placeholder="Customer Name" class="required" value="<?php echo $cust_name; ?>" /><br />
					<div id="custSearcher">resulty</div>
				<input name="cust_business" size="42" placeholder="Business" value="<?php echo $cust_business; ?>" /><br />
				<input name="cust_street" size="42" placeholder="Street" value="<?php echo $cust_street; ?>" /><br />
				<input name="cust_city" placeholder="City" value="<?php echo $cust_city; ?>" />
				<input name="cust_state" size="2" value="IN" maxlength="2" value="<?php echo $cust_state; ?>" />
				<input name="cust_zip" placeholder="Zip" size="6" maxlength="5" value="<?php echo $cust_zip; ?>" /><br />
				<input name="cust_phone_primary" size="18" placeholder="Primary Phone" class="required" value="<?php echo $cust_phone_primary; ?>" />
				<input name="cust_phone_secondary" id="phone_secondary" size="17" placeholder="Secondary Phone" value="<?php echo $cust_phone_secondary; ?>" />
				<input type="hidden" name="cust_id" value="<?php echo $cust_id; ?>" />
				<input type="hidden" name="note_id" value="<?php echo $note_id; ?>" />
				<input type="hidden" name="save_unpaid" value="0" />
			</div>

			<div id="submit_buttons">
				<a href="#" onclick="submit_invoice();" id="process_button">Process</a>
				<!--<a href="#" onclick="take_money(); return false;" id="process_button">Process</a>-->
			</div>

			<div id="inputs_hidden">
				<input type="hidden" name="tax_exempt" value="0" />
				<input type="hidden" name="subtotal" value="0" />
				<input type="hidden" name="tax" value="0" />
				<input type="hidden" name="discount" value="0" />
				<input type="hidden" name="grand" value="0" />
				<input type="hidden" name="profit" value="0" />
				<input type="hidden" name="balance" value="0" />

				<input type="hidden" name="itemArray" value="" />
			</div>

			<table id="invoice_totals">
				<tr>
					<td class="price_descripts">Subtotal $&nbsp;&nbsp;&nbsp;</td>
					<td class="prices" id="subtotal"></td>
				</tr>
				<tr>
					<td class="price_descripts">Tax $&nbsp;&nbsp;&nbsp;</td>
					<td class="prices" id="tax"></td>
				</tr>
				<tr>
					<td class="price_descripts">Discount $&nbsp;&nbsp;&nbsp;</td>
					<td class="prices" id="discount"></td>
				</tr>
				<tr id="grand_total">
					<td class="price_descripts">$&nbsp;&nbsp;&nbsp;</td>
					<td class="prices" id="total"></td>
				</tr>
			</table>
		</div>
		<hr>
		<div id="invoice_flash">
			This business is tax exempt.
		</div>

		<div id="item_scanner_wrapper">
			<input type="text" name="upc_scan" />
		</div>

		<div id="shadow_box">
			<div id="inventory_catcher"></div>
		</div>

		<div id="wrapper_items" class="wrappers">
		</div>
		<div id="wrapper_add_button" class="wrappers">
			<a href="#" id="item_add"><img src="./img/icon_add_48x48.png" style="float: right" /></a>
		</div>
	</form>
</div>


<?php
		
	}
		if(!isset($cond)) {
			?>
				<script>
					$(document).ready(function() {
						$("#page_title").html('<img src="./img/icon_invoices_new_48x48.png" /><h2 class="title">Invoice Creation</h2>');
					});
				</script>
			<?php
		} else {
			mysql_connect($server, $login, $pass) or die("Unable to Connect");
			mysql_select_db($db) or die("Unable to select database");

			if(!isset($location)) {
				$_GET['location'] = $_SESSION['location'];
			}
			
			if(!isset($_GET['range'])) {
				$_GET['range'] = 7;
			} 

			if(!isset($_GET['sort_by'])) {
				$_GET['sort_by'] = 'time';
			}

			$now = getdate();

			if(!isset($_GET['month'])) {
				$_GET['month'] = $now['mon'];
			}

			if(!isset($_GET['year'])) {
				$_GET['year'] = $now['year'];
			}

			$months = array(1 => "Jan",
							2 => "Feb",
							3 => "Mar",
							4 => "Apr",
							5 => "May",
							6 => "Jun",
							7 => "Jul",
							8 => "Aug",
							9 => "Sep",
							10 => "Oct",
							11 => "Nov",
							12 => "Dec");

			//							 month      day   	        			year
			$range_min = mktime(0, 0, 0, date('n'), date('j') - $_GET['range'] + 1, date('Y'));

			$location = $_GET['location'];
			
			if($cond == "paid") {
				$paid = "TRUE";
				$compare_to = "paid_date";
			}
			if($cond == "unpaid") {
				$paid = "FALSE";
				$compare_to = "`date`";
			}

			if($_GET['sort_by'] == 'time') {
				$query = "SELECT * FROM invoices WHERE paid = $paid AND location='$location' AND $compare_to > $range_min ORDER BY $compare_to";	
			}

			if($_GET['sort_by'] == 'month') {
				$unix_month_now = mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']);
				$unix_month_next = mktime(0, 0, 0, $_GET['month'] + 1, 1, $_GET['year']);

				//echo $unix_month_now;

				$query = "SELECT * FROM invoices WHERE paid = $paid AND $compare_to >= '$unix_month_now' AND $compare_to < '$unix_month_next' AND location='$location' ORDER BY $compare_to";
			}
			$invoice_data = mysql_query($query) or die(mysql_error());

			$page_header_html = '<span id="h5_container">';				
				$page_header_html .= '<h5>';
					if($_GET['sort_by'] == 'time') {
						$selected = ' class="selected"';
					} else {
						$selected = '';
					}
					$page_header_html .= '<a href="?page=ui_invoices&location=' . $location . '&cond=' . $_GET['cond'] . '&sort_by=time"' . $selected . '>Time</a>';

					if($_GET['sort_by'] == 'month') {
						$selected = ' class="selected"';
					} else {
						$selected = '';
					}
					$page_header_html .= '<a href="?page=ui_invoices&location=' . $location . '&cond=' . $_GET['cond'] . '&sort_by=month"' . $selected . '>Monthly</a>';
				$page_header_html .= '</h5>';

				$page_header_html .= '<h5>';
					if($_GET['sort_by'] == 'time') {
						$page_header_html .= '<a href="?page=ui_invoices&cond=' . $_GET['cond'] . '&location=' . $location . '" id="7">1 Week</a>';
						$page_header_html .= '<a href="?page=ui_invoices&cond=' . $_GET['cond'] . '&range=30&location=' . $location . '" id="30">30 Days</a>';
						$page_header_html .= '<a href="?page=ui_invoices&cond=' . $_GET['cond'] . '&range=60&location=' . $location . '" id="60">60 Days</a>';
						$page_header_html .= '<a href="?page=ui_invoices&cond=' . $_GET['cond'] . '&range=90&location=' . $location . '" id="90">3 Months</a>';
						$page_header_html .= '<a href="?page=ui_invoices&cond=' . $_GET['cond'] . '&range=180&location=' . $location . '" id="180">6 Months</a>';
						$page_header_html .= '<a href="?page=ui_invoices&cond=' . $_GET['cond'] . '&range=365&location=' . $location . '" id="365">1 Year</a>';					
					}

					if($_GET['sort_by'] == 'month') {					
						for($i = 1; $i <= 12; $i++) {
							if($i == $_GET['month']) {
								$selected = ' class="selected"';						
							} else {
								$selected = '';
							}
							$page_header_html .= '<a href="?page=ui_invoices&cond=' . $_GET['cond'] . '&sort_by=month&location=' . $location . '&month=' . $i . '&year=' . $_GET['year'] . '"' . $selected . '>' . $months[$i] . '</a>';
						}
					}
				$page_header_html .= '</h5>';

				$page_header_html .= '<h5>';
					$query = "SELECT * FROM locations";
					$location_data = mysql_query($query) or die(mysql_error());

					$page_header_html .= '<select id="location_selector" name="location">';
						while($location_array = mysql_fetch_assoc($location_data)) {
							$page_header_html .= '<option value="' . $location_array['title'] . '"';
								if($location == $location_array['title']) {
									$page_header_html .= ' selected="selected"';
								}
							$page_header_html .= '>' . $location_array['title'] . '</option>';
						}
					$page_header_html .= '</select>';
				$page_header_html .= '</h5>';

			$page_header_html .= '</span>';

			if($cond == "paid") {
			?>
				<script>
					$(document).ready(function() {
						$("#page_title").html('<div id="page_title_echo"><img src="./img/icon_invoices_paid_48x48.png" /><h2 class="title">Paid Invoices</h2></div>');

						$("#page_title").append('<?php echo $page_header_html; ?>');

						$("#location_selector").change(function() {
							<?php
								if($_GET['sort_by'] == 'time') {
									echo 'window.location = "?page=ui_invoices&range=' . $_GET['range'] . '&cond=paid&location=" + $("#location_selector option:selected").val();';
								}

								if($_GET['sort_by'] == 'month') {					
									echo 'window.location = "?page=ui_invoices&sort_by=month&month=' . $_GET['month'] . '&cond=paid&location=" + $("#location_selector option:selected").val();';
								}
							?>
						});

						<?php
							echo "$('a[id=" . $_GET['range'] . "]').addClass('selected');";
						?>
					});
				</script>
			<?php			
			}
			if($cond == "unpaid") {
			?>
				<script>
					$(document).ready(function() {
						$("#page_title").html('<div id="page_title_echo"><img src="./img/icon_invoices_unpaid_48x48.png" /><h2 class="title">Unpaid Invoices</h2></div>');

						$("#page_title").append('<?php echo $page_header_html; ?>');

						$("#location_selector").change(function() {
							<?php
								if($_GET['sort_by'] == 'time') {
									echo 'window.location = "?page=ui_invoices&range=' . $_GET['range'] . '&cond=unpaid&location=" + $("#location_selector option:selected").val();';
								}

								if($_GET['sort_by'] == 'month') {					
									echo 'window.location = "?page=ui_invoices&sort_by=month&month=' . $_GET['month'] . '&cond=unpaid&location=" + $("#location_selector option:selected").val();';
								}
							?>
						});
						
						<?php
							echo "$('a[id=" . $_GET['range'] . "]').addClass('selected');";
						?>
					});
				</script>
			<?php
			}
	?>
		<div id="wrapper_table">
			<table id="data_table" class="invoice_table">
				<tr>
					<th>#</th>
					<th>Date</th>
					<th>Customer Name</th>
					<th>Total</th>
					<th>Profit</th>
					<th>Balance</th>
					<th>Billed</th>
					<!--
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					-->
				</tr>
				<?php
					while($invoice_arr = mysql_fetch_assoc($invoice_data)) {
						$cust_id = $invoice_arr['customer_id'];

						$query = "SELECT name FROM customers WHERE id='$cust_id'";
						$customer_data = mysql_query($query) or die(mysql_error());

						$customer_arr = mysql_fetch_assoc($customer_data);
						
						if($cond == "paid") {
							$inv_date = date('m-d-y', $invoice_arr['paid_date']);
						}
						if($cond == "unpaid") {
							$inv_date = date('m-d-y', $invoice_arr['date']);							
						}
						echo '<tr>';
							echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" class="a_inv">' . $invoice_arr['id'] . '</a></td>';
							echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" class="a_inv">' . $inv_date . '</a></td>';
							echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" class="a_inv">' . $customer_arr['name'] . '</a></td>';
							echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" class="a_inv">$ ' . $invoice_arr['total'] . '</a></td>';
							echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" class="a_inv">$ ' . $invoice_arr['profit'] . '</a></td>';
							echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" class="a_inv">$ ' . $invoice_arr['balance'] . '</a></td>';

							$query = "SELECT id FROM invoices_billed WHERE invoice_id=" . $invoice_arr['id'];
							$billed_data = mysql_query($query) or die(mysql_error());

							if(mysql_num_rows($billed_data) > 0) {
								echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" class="a_inv">&#x2713;</a></td>';
							} else {
								echo '<td></td>';
							}
							/*
							echo '<td class="actions"><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '"><img src="./img/icon_magnifying_16x16.png" style="display: block;" /></a></td>';
							echo '<td class="actions"><a href="#" class="a_invoice_mod" onclick="alert(\'ah ah ah, you didnt say the magic word...or editing doesnt exist yet.  calm down.\');return false;"><img src="./img/icon_edit_16x16.png" style="display: block;" /></a></td>';
							echo '<td class="actions"><a href="#" class="a_invoice_mod" onclick="print_invoice(\'' . $invoice_arr['id'] . '\');;return false;"><img src="./img/icon_print_16x16.png" style="display: block;" /></a></td>';
							if($_SESSION['clearance'] > 50) {
								echo '<td class="actions"><a href="#" class="a_invoice_mod" onclick="alert(\'ah ah ah, you didnt say the magic word...or deleting doesnt exist yet.  calm down.\');return false;"><img src="./img/icon_delete_16x16.png" style="display: block;" /></a></td>';								
							} else {
								echo '<td class="actions"><a href="#" onclick="return false;">&nbsp;</a></td>';
							}*/
						echo '</tr>';
					}
				?>
			</table>
		</div>
	<?php
		}
	?>
</div>