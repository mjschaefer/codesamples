<?php
	extract ($_GET);

	$per_page = 30;
	if(!isset($paginate_page)) {
		$paginate_page = 1;
	}
?>

<script>
	//---------------------------------------------------------------------------------------------------------//
	//---------BEGIN JQUERY -----------------------------------------------------------------------------------//
	//---------------------------------------------------------------------------------------------------------//
	$(document).ready(function() {
		$('.a_customer').each(function () {
			//this will grab the target url
			var c_id = $(this).attr("cust_id");
			var targ = "./views/" + $(this).attr("href") + "?cust_id=" + c_id + "&cond=edit";

			$(this).click(function() {
				$.ajax({
					url: targ,
					success: function(data) {
						$("#dropdown").html(data);
						$("#dropdown").slideDown('fast');

						$("#process_button").click(function() {
							$("form[name=customer_edit]").submit();
							return false;
						});

						$("#cancel_button").click(function() {
							$("#dropdown").slideUp('fast', function() {
								$("#dropdown").html('');
							});

							return false;
						});
					}
				});
				return false;
			});
		});
	});
	//---------------------------------------------------------------------------------------------------------//
	//---------END JQUERY -----------------------------------------------------------------------------------//
	//---------------------------------------------------------------------------------------------------------//
</script>

<?php
	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");
?>

<?php	
	//---------------------------------------------------------------------------------------------------------//
	/* -- IF YOU ARE NOT VIEWING A CUSTOMER, LIST EM    -------------------------------------------------------*/
	//---------------------------------------------------------------------------------------------------------//
	if(!isset($customer)) {
		if(!isset($sort_letter)) {
			$sort_letter = 'a';
		}

		$start = ($paginate_page - 1) * $per_page;
		$limit = $start + $per_page - 1;

		//count pages
		$query = "SELECT COUNT(id) AS num FROM customers WHERE name LIKE 'strtoupper($sort_letter)%' OR name LIKE '$sort_letter%'";
	    $total_customers = mysql_fetch_array(mysql_query($query));
	    $total_customers = $total_customers['num'];
	    $total_pages = ceil($total_customers/$per_page);

		$page_html = '<h5><span id="pagination">';
			for($x = 1; $x <= $total_pages; $x++) {
				$start_temp = ($x - 1) * $per_page;

				$cap = strtoupper($sort_letter);
				$query = "SELECT name FROM customers WHERE ASCII(name) = ASCII('$cap') OR ASCII(name) = ASCII('$sort_letter') ORDER BY name LIMIT $start_temp, $per_page";				
				$cust_data = mysql_query($query) or die(mysql_error());

				$num_cust_rows = mysql_num_rows($cust_data);
				
				if($paginate_page == $x) {
					$current_page = 'class="selected"';
				} else {
					$current_page = '';
				}

				#listing by first letters of name
				$letter_limit = 3;
				$page_html .= '<a href="./main.php?page=ui_customers&sort_letter=' . $sort_letter . '&paginate_page=' . $x . '" ' . $current_page . '>' . substr(mysql_result($cust_data, 0), 0, $letter_limit) . ' ... ' . substr(mysql_result($cust_data, $num_cust_rows - 1), 0, $letter_limit) . '</a> ';
				
				#listing by page number
				//$page_html .= '<a href="./main.php?page=ui_customers&sort_letter=' . $sort_letter . '&paginate_page=' . $x . '" ' . $current_page . '>' . $x . '</a> ';
			}
		$page_html .= '</span></h5>';

		$cap = strtoupper($sort_letter);
		$query = "SELECT name, phone_primary, id, type FROM customers WHERE ASCII(name) = ASCII('$cap') OR ASCII(name) = ASCII('$sort_letter') ORDER BY name LIMIT $start, $per_page";

		//echo $query;
		//$query = "SELECT * FROM customers WHERE name LIKE 'strtoupper($sort_letter)%' OR name LIKE '$sort_letter%' ORDER BY name LIMIT $start, $per_page";
		$cust_data = mysql_query($query) or die(mysql_error());	   
?>

<script>
	$(document).ready(function() {
		$("#page_title").html('<img src="./img/icon_customer_48x48.png" /><h2 class="title">Customers</h2><span id="h5_container"><h5><a href="?page=ui_customers&sort_letter=a" id="lett_a">A</a><a href="?page=ui_customers&sort_letter=b" id="lett_b">B</a><a href="?page=ui_customers&sort_letter=c" id="lett_c">C</a><a href="?page=ui_customers&sort_letter=d" id="lett_d">D</a><a href="?page=ui_customers&sort_letter=e" id="lett_e">E</a><a href="?page=ui_customers&sort_letter=f" id="lett_f">F</a><a href="?page=ui_customers&sort_letter=g" id="lett_g">G</a><a href="?page=ui_customers&sort_letter=h" id="lett_h">H</a><a href="?page=ui_customers&sort_letter=i" id="lett_i">I</a><a href="?page=ui_customers&sort_letter=j" id="lett_j">J</a><a href="?page=ui_customers&sort_letter=k" id="lett_k">K</a><a href="?page=ui_customers&sort_letter=l" id="lett_l">L</a><a href="?page=ui_customers&sort_letter=m" id="lett_m">M</a><a href="?page=ui_customers&sort_letter=n" id="lett_n">N</a><a href="?page=ui_customers&sort_letter=o" id="lett_o">O</a><a href="?page=ui_customers&sort_letter=p" id="lett_p">P</a><a href="?page=ui_customers&sort_letter=q" id="lett_q">Q</a><a href="?page=ui_customers&sort_letter=r" id="lett_r">R</a><a href="?page=ui_customers&sort_letter=s" id="lett_s">S</a><a href="?page=ui_customers&sort_letter=t" id="lett_t">T</a><a href="?page=ui_customers&sort_letter=u" id="lett_u">U</a><a href="?page=ui_customers&sort_letter=v" id="lett_v">V</a><a href="?page=ui_customers&sort_letter=w" id="lett_w">W</a><a href="?page=ui_customers&sort_letter=x" id="lett_x">X</a><a href="?page=ui_customers&sort_letter=y" id="lett_y">Y</a><a href="?page=ui_customers&sort_letter=z" id="lett_z">Z</a></h5></span>');

		<?php if($total_customers > 0) { ?>
			$("#h5_container").append('<?php echo $page_html; ?>');
		<?php } ?>

		<?php
			if(isset($sort_letter)) {
				echo "$('#lett_" . $sort_letter . "').addClass('selected');";
			} else {
				echo "$('#lett_a').addClass('selected');";				
			}
		?>
	});

</script>

<div id="wrapper_table">
	<table id="data_table">
		<tr>
			<th>&nbsp;</th>
			<th>Name</th>
			<th>Primary Phone</th>
			<th>Dropoffs</th>
			<th>Invoices</th>
		</tr>

		<?php
			while($cust_arr = mysql_fetch_assoc($cust_data)) {
				echo '<tr>';
					if($cust_arr['type'] == 'Business') {
						echo '<td><a href="?page=ui_customers&customer=' . $cust_arr['id'] . '"><img src="./img/icon_business_16x16.png" /></a></td>';
					}
					if($cust_arr['type'] == 'Residential') {
						echo '<td><a href=".?page=ui_customers&customer=' . $cust_arr['id'] . '"><img src="./img/icon_residential_16x16.png" /></a></td>';						
					}

					echo '<td><a href="?page=ui_customers&customer=' . $cust_arr['id'] . '">' . $cust_arr['name'] . '</a></td>';
					//echo '<td><a href="?page=ui_customers&customer=' . $cust_arr['id'] . '">' . $cust_arr['street'] . ' - ' . $cust_arr['city'] . ', ' . $cust_arr['state'] . ' ' . $cust_arr['zip'] . '</a></td>';
					echo '<td><a href="?page=ui_customers&customer=' . $cust_arr['id'] . '">' . $cust_arr['phone_primary'] . '</a></td>';

					//echo dropoff/note count
					$query = "SELECT id FROM notes WHERE customer_id='" . $cust_arr['id'] . "'";
					$note_count_data = mysql_query($query) or die(mysql_error());

					echo '<td><a href="?page=ui_customers&customer=' . $cust_arr['id'] . '">' . mysql_num_rows($note_count_data) . '</a></td>';

					//echo invoice count
					$query = "SELECT id FROM invoices WHERE customer_id='" . $cust_arr['id'] . "'";
					$invoice_count_data = mysql_query($query) or die(mysql_error());

					echo '<td><a href="?page=ui_customers&customer=' . $cust_arr['id'] . '">' . mysql_num_rows($invoice_count_data) . '</a></td>';
				echo '</tr>';
			}
		?>
	</table>
</div>

<?php
	/* END IF BEGIN ELSE */

	//---------------------------------------------------------------------------------------------------------//
	/* --VIEWING A CUSTOMER -----------------------------------------------------------------------------------*/
	//---------------------------------------------------------------------------------------------------------//
	} else {

		$query = "SELECT * FROM customers WHERE id='$customer'";
		$customer_data = mysql_fetch_assoc(mysql_query($query));
?>

<div id="wrapper_main">
	<div id="top">
		<div id="view_cust_info" class="view_wrappers">
			<h3 class="first">Customer Info<a href="ajax_customer_edit.php" cust_id="<?php echo $customer_data['id']; ?>" class="a_customer"><img src="./img/icon_edit_24x24.png" style="float: right;" /></a></h3>
			<hr>
			<?php
				if($customer_data['type'] == 'Business') {
					echo '<img src="./img/flag_cust_business.png" />';
					echo '<h2 class="name">' . $customer_data['business'] . '</h2>';
					echo $customer_data['name'];
					echo '<br />';
				} else {
					echo '<img src="./img/flag_cust_residential.png" />';
					echo '<h2 class="name">' . $customer_data['name'] . '</h2>';
					if($customer_data['business'] != '') {
						echo $customer_data['business'];
						echo '<br />';
					}
				}

				foreach($customer_data as $k => $v) {
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
									if($customer_data['city'] != '') {
										echo $v;
										echo '&nbsp;&nbsp;';
									}
									break;
								case 'type':
									echo '';
									break;
								case 'business_id':
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

		<div id="view_exempt_info" class="view_wrappers">
			<h3 class="first">
				Tax Exemption
				<a href="ajax_exemption_edit.php" cust_id="<?php echo $customer_data['id']; ?>" class="a_customer"><img src="./img/icon_edit_24x24.png" style="float: right;" /></a>
			</h3>
			<hr>

			<?php
				if($customer_data['business_id'] != '') {
					echo '<b>Business ID #/TID #/FID #:</b> ' . $customer_data['business_id'];
				} else {
					echo 'Not tax exempt';
				}
			?>
		</div>
	</div>

	<div id="view_dropoff_history" class="view_wrappers">
		<h3>Dropoff History</h3>
		<table id="data_table">
			<tr>
				<th>Dropped Off</th>
				<th>Computer</th>
				<th>Completed</th>
			</tr>
			<?php
				$query = "SELECT * FROM notes WHERE customer_id='$customer' ORDER BY date_entered";
				$dropoff_data = mysql_query($query) or die(mysql_error());

				while($dropoff_arr = mysql_fetch_assoc($dropoff_data)) {
					echo '<tr>';
						echo '<td><a href="?page=ui_notes_edit&note=' . $dropoff_arr['id'] . '">' . date('m-d-y \a\t g:i a', (int)$dropoff_arr['date_entered']) . '</a></td>';
						echo '<td><a href="?page=ui_notes_edit&note=' . $dropoff_arr['id'] . '">' . $dropoff_arr['pc_brand'] . ' - ' . $dropoff_arr['pc_model'] . '</a></td>';
						echo '<td><a href="?page=ui_notes_edit&note=' . $dropoff_arr['id'] . '">';
						if($dropoff_arr['date_completed'] != '') {
							echo date('m-d-y \a\t g:i a', $dropoff_arr['date_completed']);
						} else {
							echo '-';
						}

						echo '</a></td>';
					echo '</tr>';
				}
			?>
		</table>
	</div>

	<div id="view_invoice_history" class="view_wrappers">
		<h3>Invoice History</h3>
		<table id="data_table">
			<tr>
				<th>Invoice Date</th>
				<th>Total</th>
				<th>Balance</th>
				<th>Date Paid</th>
			</tr>
			<?php
				$query = "SELECT * FROM invoices WHERE customer_id='$customer' ORDER BY paid_date";
				$invoice_data = mysql_query($query) or die(mysql_error());

				while($invoice_arr = mysql_fetch_assoc($invoice_data)) {
					echo '<tr>';
						echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">' . date('m-d-y \a\t g:i a', (int)$invoice_arr['date']) . '</a></td>';
						echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">$ ' . $invoice_arr['total'] . '</a></td>';
						echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">$ ' . $invoice_arr['balance'] . '</a></td>';

						if($invoice_arr['paid_date'] != '') {
							echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">' . date('m-d-y \a\t g:i a', (int)$invoice_arr['paid_date']) . '</a></td>';
						} else {
							echo '<td><a href="main.php?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '">-</a></td>';
						}
					echo '</tr>';
				}
			?>
		</table>
	</div>
</div>	
	

<?php
	/* END OF IF/ELSE STATEMENT */
	}
?>