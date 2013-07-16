<?php
	function shorten_title($title) {
		$new_title = '';
		$title_shortener = 8;
		if(strlen($title) > $title_shortener) {
			for($i = 0; $i < $title_shortener; $i++) {
				$new_title .= $title[$i];
			}
		} else {
			$new_title = $title;
		}

		return $new_title;
	}

	if(!isset($_GET['month'])) {
		$_GET['month'] = date('n');
	}

	if(!isset($_GET['year'])) {
		$_GET['year'] = date('Y');
	}

	if(!isset($_GET['location'])) {
		$_GET['location'] = $_SESSION['location'];
	}

	if(!isset($_GET['account'])) {
		$_GET['account'] = 'All';
	}
	
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

			$query = "SELECT `date` FROM expenses ORDER BY `date` LIMIT 1";
			$date_data = mysql_query($query) or die(mysql_error());
			$date_array = mysql_fetch_assoc($date_data);
			$lowest_year = date('Y', $date_array['date']);

			$page_header_html .= '<select id="year_selector" name="year">';
				for($i = $lowest_year; $i <= date('Y'); $i++) {
					if($i == $_GET['year']) {
						$selected = ' selected="selected"';						
					} else {
						$selected = '';
					}

					$page_header_html .= '<option value="' . $i . '"' . $selected . '>' . $i . '</a>';
				}
			$page_header_html .= '</select>';

			$query = "SELECT DISTINCT location FROM expenses ORDER BY location";
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

			$page_header_html .= '<select id="account_selector" name="account">';
				$accounts = array('Cash', 'Checking', 'Credit');

				$page_header_html .= '<option value="All">All</option>';

				foreach($accounts as $k => $v) {
					$page_header_html .= '<option value="' . $v . '"';
						if($_GET['account'] == $v) {
							$page_header_html .= ' selected="selected"';
						}
					$page_header_html .= '>' . $v . '</option>';
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
			echo "var report_account = '" . $_GET['account'] . "';\n";
		?>

		$("#page_title").html('<img src="./img/icon_frequency_48x48.png" style="float: left; margin-right: 10px;" /><h1>Gigahertz Expense Report - ' + report_account + ' Account</h1><h2>' + report_month + ' ' + report_year + '</h2><h3>' + report_location + '</h3>');
		$("#page_title").append('<?php echo $page_header_html; ?>');

		$("select").change(function() {
			window.location = "?page=ui_report_expense&month=" + $("#month_selector option:selected").val() + "&year=" + $("#year_selector option:selected").val() + "&location=" + $("#location_selector option:selected").val() + "&account=" + $("#account_selector option:selected").val();
		});
</script>

<div id="wrapper_table">		
	<table class="data_table">
		<?php			
			$query = "SELECT DISTINCT account FROM expenses WHERE `date` >= '$unix_month_now' AND `date` < '$unix_month_next' ORDER BY account";
			$account_data = mysql_query($query) or die(mysql_error());

			if(mysql_num_rows($account_data) > 0) {

				while($account_arr = mysql_fetch_assoc($account_data)) {
					$account_list[$account_arr['account']] = '';
				}

				$query = "SELECT title_shorthand as title, account_id FROM accounts";
				$acct_data = mysql_query($query) or die(mysql_error());

				while($acct_arr = mysql_fetch_assoc($acct_data)) {
					if(isset($account_list[$acct_arr['account_id']])) {
						$account_list[$acct_arr['account_id']] = $acct_arr['title'];
					}
				}

				foreach($account_list as $k => $v) {
					$totals_array[$k] = 0;
				}
		?>
				<tr>
					<th>Date</th>
					<th style="width: 90px;">Paid To</th>
					<th>Total</th>
					<?php
						foreach($account_list as $k => $v) {
							echo '<th class="title_acct">' . shorten_title($v) . '</th>';
						}
					?>
				</tr>
				<?php
					$line_tracker = 4;
					$lines_per_page = 30;

					$q_account = $_GET['account'];

					if($q_account == 'All') {
						$query = "SELECT * FROM expenses WHERE `date` >= '$unix_month_now' AND `date` < '$unix_month_next' AND location = '$location' ORDER BY `date`";
					} else {
						$query = "SELECT * FROM expenses WHERE `date` >= '$unix_month_now' AND `date` < '$unix_month_next' AND location = '$location' AND paid_with LIKE '$q_account' ORDER BY `date`";
					}
					$expense_data = mysql_query($query) or die(mysql_error());

					$grand_total = 0;

					while($expense_arr = mysql_fetch_assoc($expense_data)) {
						echo '<tr>';
							echo '<td><a href="#" onclick="return false;">' . date("n/j/Y", $expense_arr['date']) . '</a></td>';

							echo '<td><a href="#" onclick="return false;">';
								if(strlen($expense_arr['paid_to']) > 8) {
									for($i = 0; $i <= 8; $i++) {
										echo $expense_arr['paid_to'][$i];
									}
									echo '...';
								} else {
									echo $expense_arr['paid_to'];
								}
							echo '</a></td>';

							//echo '<td><a href="#" onclick="return false;">' . $expense_arr['for'] . '</a></td>';
							echo '<td class="col_total"><a href="#" onclick="return false;">' . number_format($expense_arr['cost'], 2, '.', '') . '</a></td>';
							
							$i = 1;	
							foreach($account_list as $k => $v) {
								echo '<td class="col_acct"><a href="#" onclick="return false;">';
									if($expense_arr['account'] == $k) {
										echo $expense_arr['cost'];								
									}
								echo '</a></td>';
							}
						echo '</tr>';

						$grand_total += $expense_arr['cost'];
						$totals_array[$expense_arr['account']] += $expense_arr['cost'];

						$line_tracker++;
						if($line_tracker == $lines_per_page) {
							echo '</table><table class="data_table table_break">
								<tr>
									<th>Date</th>
									<th style="width: 90px;">Paid To</th>
									<th>Total</th>';
									foreach($account_list as $k => $v) {
										echo '<th class="title_acct">' . shorten_title($v) . '</th>';
									}
							echo '</tr>';

							$line_tracker = 0;
						}/**/
					}

					echo '<tr>';
						echo '<th>Totals</th>';
						echo '<th></th>';
						echo '<th>' . number_format($grand_total, 2, '.', '') . '</th>';

						foreach($account_list as $k => $v) {
							echo '<th class="title_acct">' . number_format($totals_array[$k], 2, '.', '') . '</th>';
						}
					echo '</tr>';
			} else {
				echo 'Nothing here.';
			}

				?>
	</table>
</div>