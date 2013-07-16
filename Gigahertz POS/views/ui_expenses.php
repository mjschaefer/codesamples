<script>
	jQuery.validator.messages.required = "";

	<?php
		if(!isset($_GET['location'])) {
			$location = $_SESSION['location'];
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

		$page_title_html = '<img src="./img/icon_expenses_48x48.png" /><h2 class="title">Expenses</h2><span id="h5_container">';
			$page_title_html .= '<h5>';
				for($i = 1; $i <= 12; $i++) {
					if($i == $_GET['month']) {
						$selected = ' class="selected"';						
					} else {
						$selected = '';
					}
					$page_title_html .= '<a href="?page=ui_expenses&month=' . $i . '&year=' . $_GET['year'] . '"' . $selected . '>' . $months[$i] . '</a>';
				}
			$page_title_html .= '</h5>';

			$page_title_html .= '<h5>';
				$query = "SELECT `date` FROM expenses ORDER BY `date` LIMIT 1";
				$date_data = mysql_query($query) or die(mysql_error());
				$date_array = mysql_fetch_assoc($date_data);
				$lowest_year = date('Y', $date_array['date']);
				
				for($i = $lowest_year; $i <= $now['year']; $i++) {
					if($i == $_GET['year']) {
						$selected = ' class="selected"';						
					} else {
						$selected = '';
					}
					$page_title_html .= '<a href="?page=ui_expenses&month=' . $_GET['month'] . '&year=' . $i . '"' . $selected . '>' . $i . '</a>';
				}
			$page_title_html .= '</h5>';
		$page_title_html .= '</span>'
	?>

	$(document).ready(function() {
		$("#page_title").html('<?php echo $page_title_html; ?>');
		
		$(".a_exp").each(function () {
			$(this).click(function() {
				return false;
			});
		});

		$(".a_expense_mod").click(function() {
			$.ajax({
				url: "./views/" + $(this).attr("href") + "&exp_id=" + $(this).attr("exp_id"),
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#date_picker").click(function() {
						$.ajax({
							url: "./views/ajax_calendar.php?targ=date&div=cal",
							success: function(data) {
								$("#cal").html(data);
							}
						});
					});

					$("#process_button").click(function() {
						if($("form[name=expense]").validate()) {
							$("form[name=expense]").submit();
						}
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
</script>

<?php	
	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$unix_month_now = mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']);
	$unix_month_next = mktime(0, 0, 0, $_GET['month'] + 1, 1, $_GET['year']);

	//echo $unix_month_now;

	$query = "SELECT * FROM expenses WHERE `date` >= '$unix_month_now' AND `date` < '$unix_month_next' ORDER BY `date`";
	$expense_data = mysql_query($query) or die(mysql_error());
?>

<div id="wrapper_table">
	<table id="data_table">
		<tr>
			<th>Date</th>
			<th>Account</th>
			<th>Paid To</th>
			<th>For</th>
			<th>Price</th>
			<th class="actions" style="margin: 0; padding: 0; text-align: center;"><a href="ajax_expense.php?cond=add" class="a_expense_mod"><img src="./img/icon_add_16x16.png" style="display: block;" /></a></th>
		</tr>
		<?php
			while($expense_arr = mysql_fetch_assoc($expense_data)) {
				echo '<tr>';
					echo '<td><a href="#" class="a_exp">' . date("n/j/Y", $expense_arr['date']) . '</a></td>';
					echo '<td><a href="#" class="a_exp">' . $expense_arr['account'] . '</a></td>';
					echo '<td><a href="#" class="a_exp">' . $expense_arr['paid_to'] . '</a></td>';
					echo '<td><a href="#" class="a_exp">' . $expense_arr['for'] . '</a></td>';
					echo '<td><a href="#" class="a_exp">$ ' . $expense_arr['cost'] . '</a></td>';
					echo '<td class="actions"><a href="ajax_expense.php?cond=edit" exp_id="' . $expense_arr['id'] . '" class="a_expense_mod"><img src="./img/icon_edit_16x16.png" style="display: block;" /></a></td>';
				echo '</tr>';
			}
		?>
	</table>
</div>