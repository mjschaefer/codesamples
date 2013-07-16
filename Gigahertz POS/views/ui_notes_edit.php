<?php include('./includes/tinymce_init.php'); ?>
<script type="text/javascript" src="./js/notes_edit.js"></script>
<?php
	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	if(isset($cond)) {
		if($cond == 'active') {
			$active = 'TRUE';
		}
		if($cond == 'complete') {
			$active = 'FALSE';
		}
	} else {
		$active = 'TRUE';
		$_GET['cond'] = 'active';
	}

	if(isset($_GET['note'])) {
		$note = $_GET['note'];
	}
?>

<?php
	/* BEGIN IF STATEMENT */

	//---------------------------------------------------------------------------------------------------------//
	/* -- IF YOU ARE NOT VIEWING A NOTE, LIST NOTES -----------------------------------------------------------*/
	//---------------------------------------------------------------------------------------------------------//
	if(!isset($note)) {
		if(!isset($location)) {
			$location = $_SESSION['location'];
		}

		if(!isset($_GET['range'])) {
			$_GET['range'] = '7';
		} 

		if(!isset($_GET['sort_by'])) {
			$_GET['sort_by'] = 'time';
		}

		$now = getdate();

		if(!isset($_GET['month'])) {
			$_GET['month'] = $now['mon'];
		}

		if(!isset($_GET['status'])) {
			if($_GET['cond'] == 'complete') {
				$_GET['status'] = 'all';

			} else {
				$_GET['status'] = 'working_on';
			}
		}

		if(!isset($status)) {
			$status = $_GET['status'];
		}

		if(!isset($_GET['year'])) {
			$_GET['year'] = $now['year'];
		}

		if(!isset($_GET['location'])) {
			$_GET['location'] = $_SESSION['location'];
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

		//echo $target_date;

		//							  month        day            			     year
		$range_min = mktime(0, 0, 0, date('n'), date('j') - $_GET['range'] + 1, date('Y'));

		//echo $range_min;

		if($_GET['cond'] == 'active') {
			$order_by = 'date_entered';
		}

		if($_GET['cond'] == 'complete') {
			$order_by = 'date_completed';
		}

		if($_GET['sort_by'] == 'time') {
			$status_insert = '';

			if($status != 'all') {
				$status_insert = " AND status='$status'";
			}

			//$status_query_insert = " AND status='$status'";
			if($status == 'all' || $status == 'done') {
				$query = "SELECT * FROM notes WHERE complete IS NOT $active AND location='$location'" . $status_insert . " AND $order_by > $range_min ORDER BY $order_by";
			} else {
				$query = "SELECT * FROM notes WHERE location='$location'" . $status_insert . " AND $order_by > $range_min ORDER BY $order_by";
			}
		}

		if($_GET['sort_by'] == 'month') {
			$unix_month_now = mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']);
			$unix_month_next = mktime(0, 0, 0, $_GET['month'] + 1, 1, $_GET['year']);

			//echo $unix_month_now;

			$query = "SELECT * FROM notes WHERE complete IS NOT $active AND $order_by >= '$unix_month_now' AND $order_by < '$unix_month_next' AND location='$location' AND status='$status' ORDER BY $order_by";
		}
		$notes_data = mysql_query($query) or die(mysql_error());

		if(!isset($cond)) {
			$cond = 'active';
		}
?>

<?php
		$page_header_html = '<span id="h5_container">';
			$page_header_html .= '<h5>';
				if($_GET['sort_by'] == 'time') {
					$selected = ' class="selected"';
				} else {
					$selected = '';
				}
				$page_header_html .= '<a href="?page=ui_notes_edit&location=' . $_GET['location'] . '&cond=' . $_GET['cond'] . '&sort_by=time"' . $selected . '>Time</a>';

				if($_GET['sort_by'] == 'month') {
					$selected = ' class="selected"';
				} else {
					$selected = '';
				}
				$page_header_html .= '<a href="?page=ui_notes_edit&location=' . $_GET['location'] . '&cond=' . $_GET['cond'] . '&sort_by=month"' . $selected . '>Monthly</a>';
			$page_header_html .= '</h5>';

			$page_header_html .= '<h5>';
				if($_GET['sort_by'] == 'time') {
					$page_header_html .= '<a href="?page=ui_notes_edit&cond=' . $_GET['cond'] . '&range=7&status=' . $_GET['status'] . '&location=' . $location . '" id="7">1 Week</a>';
					$page_header_html .= '<a href="?page=ui_notes_edit&cond=' . $_GET['cond'] . '&range=30&status=' . $_GET['status'] . '&location=' . $location . '" id="30">30 Days</a>';
					$page_header_html .= '<a href="?page=ui_notes_edit&cond=' . $_GET['cond'] . '&range=60&status=' . $_GET['status'] . '&location=' . $location . '" id="60">60 Days</a>';
					$page_header_html .= '<a href="?page=ui_notes_edit&cond=' . $_GET['cond'] . '&range=90&status=' . $_GET['status'] . '&location=' . $location . '" id="90">3 Months</a>';
					$page_header_html .= '<a href="?page=ui_notes_edit&cond=' . $_GET['cond'] . '&range=180&status=' . $_GET['status'] . '&location=' . $location . '" id="180">6 Months</a>';
					$page_header_html .= '<a href="?page=ui_notes_edit&cond=' . $_GET['cond'] . '&range=365&status=' . $_GET['status'] . '&location=' . $location . '" id="365">1 Year</a>';					
				}

				if($_GET['sort_by'] == 'month') {					
					for($i = 1; $i <= 12; $i++) {
						if($i == $_GET['month']) {
							$selected = ' class="selected"';						
						} else {
							$selected = '';
						}
						$page_header_html .= '<a href="?page=ui_notes_edit&cond=' . $_GET['cond'] . '&sort_by=month&location=' . $_GET['location'] . '&month=' . $i . '&year=' . $_GET['year'] . '"' . $selected . '>' . $months[$i] . '</a>';
					}
				}
			$page_header_html .= '</h5>';

			$page_header_html .= '<h5>';
				$statuses = array("all" => "All", "working_on" => "Working On", "waiting_call" => "Waiting on Call", "waiting_parts" => "Waiting on Parts", "done" => "Done");
				//$page_header_html .= '<a href="#"></a>';

				if($_GET['cond'] == 'active') {
					foreach($statuses as $k => $v) {					
						if($_GET['status'] == $k) {
							$selected = ' class="selected"';
						} else {
							$selected = '';
						}

						$page_header_html .= '<a href="?page=ui_notes_edit&cond=' . $_GET['cond'] . '&range=' . $_GET['range'] . '&location=' . $location . '&status=' . $k . '"' . $selected . '>' . $v . '</a>';
					}					
				} else {
					$page_header_html .= '<a href="?page=ui_notes_edit&cond=' . $_GET['cond'] . '&range=' . $_GET['range'] . '&location=' . $location . '&status=all"' . $selected . ' class="selected">All</a>';
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

	if($cond == 'active') {
?>
<script>
	$(document).ready(function() {
		$("#page_title").html('<div id="page_title_echo"><img src="./img/icon_note_blank_48x48.png" /><h2 class="title">Active Work Orders</h2></div>');
		$("#page_title").append('<?php echo $page_header_html; ?>');

		$("#location_selector").change(function() {
			<?php
				if($_GET['sort_by'] == 'time') {
					echo 'window.location = "?page=ui_notes_edit&range=' . $_GET['range'] . '&cond=active&location=" + $("#location_selector option:selected").val();';
				}

				if($_GET['sort_by'] == 'month') {					
					echo 'window.location = "?page=ui_notes_edit&sort_by=month&month=' . $_GET['month'] . '&cond=active&location=" + $("#location_selector option:selected").val();';
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
	if($cond == 'complete') {
?>
<script>
	$(document).ready(function() {
		$("#page_title").html('<div id="page_title_echo"><img src="./img/icon_note_complete_48x48.png" /><h2 class="title">Complete Work Orders</h2></div>');
		$("#page_title").append('<?php echo $page_header_html; ?>');

		$("#location_selector").change(function() {
			window.location = "?page=ui_notes_edit&range=<?php echo $_GET['range'] ?>&cond=complete&location=" + $("#location_selector option:selected").val();
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
	<table id="data_table">
		<tr>
			<th>&nbsp;</th>
			<?php
				if($cond == 'complete') {
					echo '<th>Date Completed</th>';
				} else {
					echo '<th>Date Received</th>';
				}
			?>
			<th>Customer Name</th>
			<th>Computer</th>
			<th>Invoiced</th>
			<!--
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			-->
		</tr>

		<?php
			while($notes_arr = mysql_fetch_assoc($notes_data)) {
				$cust_arr = mysql_fetch_assoc(mysql_query("SELECT * FROM customers WHERE id = '$notes_arr[customer_id]'"));
				
				echo '<tr>';
					if($cust_arr['type'] == 'Business') {
						echo '<td><a href="?page=ui_notes_edit&note=' . $notes_arr['id'] . '"><img src="./img/icon_business_16x16.png" /></a></td>';
					}
					if($cust_arr['type'] == 'Residential') {
						echo '<td><a href="?page=ui_notes_edit&note=' . $notes_arr['id'] . '"><img src="./img/icon_residential_16x16.png" /></a></td>';						
					}

					if($cond != 'complete') {
						echo '<td><a href="?page=ui_notes_edit&note=' . $notes_arr['id'] . '">' . date('m-d-y \a\t g:i a', $notes_arr['date_entered']) . '</a></td>';
					}

					if($cond == 'complete') {
						if($notes_arr['date_completed'] != '') {
							echo '<td><a href="?page=ui_notes_edit&note=' . $notes_arr['id'] . '">' . date('m-d-y \a\t g:i a', $notes_arr['date_completed']) . '</a></td>';
						} else {
						echo '<td>&nbsp;</td>';
						}
					}

					echo '<td><a href="?page=ui_notes_edit&note=' . $notes_arr['id'] . '">' . $cust_arr['name'] . '</a></td>';
					echo '<td><a href="?page=ui_notes_edit&note=' . $notes_arr['id'] . '">' . $notes_arr['pc_brand'] . ' - ' . $notes_arr['pc_model'] . '</a></td>';

					if($notes_arr['invoice_id'] >= 1) {
						echo '<td><a href="?page=ui_notes_edit&note=' . $notes_arr['id'] . '">&#x2713;</a></td>';
					} else {
						echo '<td><a href="?page=ui_notes_edit&note=' . $notes_arr['id'] . '">&nbsp;</a></td>';
					}
					
					/*
					echo '<td class="actions"><a href="?page=ui_notes_edit&note=' . $notes_arr['id'] . '"><img src="./img/icon_magnifying_16x16.png" style="display: block;" /></a></td>';
					
					echo '<td class="actions"><a href="#" onclick="print_note(\'' . $notes_arr['id'] . '\');return false;"><img src="./img/icon_print_16x16.png" style="display: block;" /></a></td>';
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
	/* END IF BEGIN ELSE */

	//---------------------------------------------------------------------------------------------------------//
	/* --VIEWING A NOTE ---------------------------------------------------------------------------------------*/
	//---------------------------------------------------------------------------------------------------------//
	} else {	
		extract ($_GET);

		$query = "SELECT * FROM notes WHERE id='$note'";
		$notes_data = mysql_fetch_assoc(mysql_query($query));
		$customer_id = $notes_data['customer_id'];

		$query = "SELECT * FROM customers WHERE id='$customer_id'";
		$customer_data = mysql_fetch_assoc(mysql_query($query));
?>

<div id="wrapper_main">
	<div id="top">
		<h1>
			Note # <?php echo $note; ?>
			<?php
				if($notes_data['complete']) {
					//echo ' - COMPLETED';
				}
			?>
			<a href="#" onclick="print_note('<?php echo $note; ?>'); return false;" id="button_print">Print</a>
			<?php
				if($notes_data['invoice_id'] >=1) {						
			?>
				<a href="?page=ui_invoice_view&invoice=<?php echo $notes_data['invoice_id']; ?>" onclick="" id="button_invoice_note">View Invoice</a>
			<?php
				} else {
			?>
				<a href="?page=ui_invoices&note_id=<?php echo $note; ?>" onclick="" id="button_invoice_note">Create Invoice</a>
			<?php
				}

				if($_SESSION['clearance'] >= 70) {				
					echo '<a href="./views/delete_note.php?note_id=' . $note . '&invoice_id=' . $notes_data['invoice_id'] . '" onclick="return prompt_delete_note(' . $notes_data['id'] . ');" id="button_delete">Delete</a>';
				}
			?>
		</h1>

		<?php
			if($notes_data['date_completed'] != '') {
				echo '<div id="completed_paragraph">This computer was marked completed on: <b>' . date('m-d-y \a\t g:i a', $notes_data['date_completed']) . ' </b>by<b> ' . $notes_data['completed_by'] . '</b></div>';
			}
		?>
		
		<div id="view_cust_info" class="view_wrappers">
			<!--for whatever reason the google chrome extensions fucks with the clickability of this image via jquery-->
			<h3 class="first">Customer Info<a href="./main.php?page=ui_customers&customer=<?php echo $notes_data['customer_id']; ?>" class="a_customer"><img src="./img/icon_magnifying_24x24.png" style="float: right;" /></a></h3>
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
			?>
			<?php 
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

		<div id="view_comp_info" class="view_wrappers">
			<h3 class="first">PC Info<a href="#" id="edit_pc_info" note_id="<?php echo $notes_data['id']; ?>"><img src="./img/icon_edit_24x24.png" style="float: right;" /></a></h3>
			<hr>
			<img src="./img/flag_pc.png" />

			Status: 
			<select name="note_status" id="note_status" n_id="<?php echo $notes_data['id']; ?>">
			<?php 
				$statuses = array("working_on" => "Working On", "waiting_call" => "Waiting on Call", "waiting_parts" => "Waiting on Parts", "done" => "Done");

				foreach($statuses as $k => $v) {
					$selected = '';
					if($notes_data['status'] == $k) {
						$selected = 'selected="selected"';
					}

					echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
				}
			?>
			</select>
			<div id="status_flash"></div>

			<br />
			<?php 
				echo '<b>Dropped off on: ' . date('m-d-y \a\t g:i a', $notes_data['date_entered']) . '</b><br />';
				echo 'Brand: <b>' . $notes_data['pc_brand'] . '</b><br />';
				echo 'Model: <b>' . $notes_data['pc_model'] . '</b><br />';
				echo 'Serial: <b>' . $notes_data['pc_serial'] . '</b><br />';
				echo 'OS: <b>' . $notes_data['pc_os'] . '</b><br />'; 
				echo 'PC Type: <b>' . $notes_data['pc_type'] . '</b><br />';
				echo 'Password: <b>' . $notes_data['pc_password'] . '</b><br />';			
			?>
		</div>
	</div>

	<div id="view_inhomes" class="view_wrappers">
		<h3>Schedule an InHome</h3>
		<hr>
		Coming soon...
	</div>

	<div id="view_problems" class="view_wrappers">
		<h3>Original Problems<a href="ajax_notes_single.php" table="notes" note_type="problems" note_id="<?php echo $note; ?>" class="a_notes"><img src="./img/icon_edit_24x24.png" style="float: right;" /></a></h3>
		<hr>
		<?php echo stripslashes(html_entity_decode($notes_data['problems'])); ?>
	</div>

	<div id="view_items_left" class="view_wrappers">
		<h3>Items Left<a href="ajax_notes_single.php" table="notes" note_type="items_left" note_id="<?php echo $note; ?>" class="a_notes"><img src="./img/icon_edit_24x24.png" style="float: right;" /></a></h3>
		<hr>
		<?php echo stripslashes(html_entity_decode($notes_data['items_left'])); ?>
	</div>

	<div id="view_notes" class="view_wrappers">
		<div id="notes_header">
			<h3>Notes<a href="#" onclick="return false;" note_id="<?php echo $note; ?>" id="a_add"><img src="./img/icon_add_48x48.png" style="float: right;" /></a></h3>		
			<hr>
		</div>
		<?php
			$query = "SELECT * FROM notes_added WHERE note_id='$note'";
			$notes_added_query = mysql_query($query);

			while($notes_added_data = mysql_fetch_assoc($notes_added_query)) {
				echo '<div class="added_notes">';
					echo '<h5>';
						echo $notes_added_data['entered_by'] . ' @ ';
						echo date('m-d-y \a\t g:i a', $notes_added_data['date']);
						if($_SESSION['name'] == $notes_added_data['entered_by']) {
							echo '<a href="ajax_notes_single.php" note_id="' . $notes_added_data['id'] . '" class="a_notes" table="notes_added" note_type="notes_added">';
							echo '<img src="./img/icon_edit_24x24.png" style="float: right;" />';
							echo '</a>';
						}
					echo '</h5>';
					echo '<hr>';
					echo stripslashes(html_entity_decode($notes_added_data['content']));
				echo '</div>';
			}
		?>
	</div>
</div>

<script type="text/javascript">
        init_notes_editor();
</script>


<?php
	/* END OF IF/ELSE STATEMENT */
	}
?>