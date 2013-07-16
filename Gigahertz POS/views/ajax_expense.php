<?php
	session_start();

	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	extract($_POST);

	$entered_by = $_SESSION['name'];

	if(!isset($_GET['condition'])) {
		$_GET['condition'] = '';
	}

	if($notes_id == '') {
		$notes_id = 0;
	}

	$condition = $_GET['condition'];

	if($condition == 'save') {
		$date_standard = explode("/", $date);
		$date_unix = mktime(0, 0, 0, $date_standard[0], $date_standard[1], $date_standard[2]);

		if($cond == 'add') {
			//id
			//date
			//paid_to
			//cost
			//for
			//account
			//notes_id
			//entered_by
			//location

			$query = "INSERT INTO expenses(`date`,
										   `paid_to`,
										   `cost`,
										   `for`,
										   `account`,
										   `paid_with`,
										   `notes_id`,
										   `entered_by`,
										   `location`) VALUES('" . addslashes($date_unix) . "',
										   					  '" . addslashes($paid_to) . "',
										   					  '" . addslashes($cost) . "',
										   					  '" . addslashes($for) . "',
										   					  '" . addslashes($account) . "',
										   					  '" . addslashes($paid_with) . "',
										   					  '" . addslashes($notes_id) . "',
										   					  '" . addslashes($entered_by) . "',
										   					  '" . addslashes($location) . "')";
		}

		if($cond == 'edit') {
			$query = "UPDATE expenses SET 
				`date` = '" . addslashes($date_unix) . "', 
				`paid_to` = '" . addslashes($paid_to) . "', 
				`paid_with` = '" . addslashes($paid_with) . "',
				`cost` = " . $cost . ", 
				`for` = '" . addslashes($for) . "', 
				`account` = '" . addslashes($account) . "', 
				`notes_id` = '" . addslashes($notes_id) . "',
				`location` = '" . addslashes($location) . "'

				WHERE `id` = '$exp_id'";
		}

		/*echo '<pre>';
		print_r($_REQUEST);
		print_r($_POST);
		echo '</pre>';
		echo $query;*/

		mysql_query($query) or die(mysql_error());
		header("Location: ../main.php?page=ui_expenses");

		exit;
	}

	extract($_REQUEST);

	$id = '';
	$date = date("n/j/Y", mktime(0, 0, 0, date("m"), date("j"), date("Y")));
	$paid_to = '';
	$paid_with = '';
	$cost = '';
	$for = '';
	$account = '';
	$notes_id = '';
	$location = '';


	if($cond == 'edit') {
		$query = "SELECT * FROM expenses WHERE id='$exp_id'";
		$exp_data = mysql_query($query) or die(mysql_error());
		$exp_arr = mysql_fetch_assoc($exp_data) or die(mysql_error());

		$id = $exp_arr['id'];
		$date = date("n/j/Y", $exp_arr['date']);
		$paid_to = $exp_arr['paid_to'];
		$paid_with = $exp_arr['paid_with'];
		$cost = $exp_arr['cost'];
		$for = $exp_arr['for'];
		$account = $exp_arr['account'];
		$notes_id = $exp_arr['notes_id'];
		$location = $exp_arr['location'];
	}
?>

<div id="wrapper_top" class="expense_div">
	<form method="POST" name="expense" action="./views/ajax_expense.php?condition=save">
		<table id="input_table" class="expense_table">
			<tr>
				<td class="td_descript">Account:</td>
				<td>
					<select name="account" id="select_account">
						<?php
							$query = "SELECT * FROM accounts WHERE type='debit' ORDER BY title";
							$acct_data = mysql_query($query) or die(mysql_error());

							while($acct_array = mysql_fetch_array($acct_data)) {
								echo '<option value="' . $acct_array['account_id'] . '"';

								if($account != '') {
									if($acct_array['account_id'] == $account) {
										echo 'selected="true"';
									}
								} else {
									if($acct_array['account_id'] == '510') {
										echo 'selected="true"';
									}
								}

								echo '>[ ' . $acct_array['title'] . ' ] [ ' . $acct_array['account_id'] . ' ]  <span id="account_descript">' . $acct_array['description'] . '</span></option>';
							}
						?>
					</select>
				</td>

				<td class="td_descript">Paid With:</td>
				<td>
					<select name="paid_with" id="select_paid_with">
						<?php
							$paid_withs = array('Checking Account' => 'Checking', 'Cash' => 'Cash', 'Credit Cards' => 'Credit');

							foreach($paid_withs as $k => $v) {
								echo '<option value="' . $v . '"';
									if($paid_with == $v) {
										echo ' selected="selected"';
									}
								echo '>' . $k . '</option>';
							}
						?>
					</select>
				</td>

				<td class="td_input_short">
					<a href="#" onclick="return false;" id="date_picker">
						<img src="./img/icon_calendar_16x16.png" class="calendar" />
					</a>
					<input class="input_short" disabled="true" name="date" id="date_input" placeholder="Date" value="<?php echo $date; ?>" />	
					<input type="hidden" name="date" id="date" value="<?php echo $date; ?>" />
					<div id="cal"></div>
				</td>
			</tr>
			<tr>
				<td class="td_descript">Pay to the order of:</td>
				<td colspan="3">
					<input class="required" name="paid_to" placeholder="Paid To" value="<?php echo $paid_to; ?>" />	
				</td>
				<td class="td_input_short">
					&nbsp;$ <input class="input_short required" name="cost" placeholder="Cost" value="<?php echo $cost; ?>" />	
				</td>
			</tr>
			<tr>
				<td class="td_descript">Memo:</td>
				<td colspan="3">
					<input name="for" placeholder="For" value="<?php echo $for; ?>" />
				</td>
				<td>
					&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;

					<select name="location">
						<?php						
							$query = "SELECT title FROM locations";
							$location_data = mysql_query($query) or die(mysql_error());

							while($location_array = mysql_fetch_assoc($location_data)) {
								echo '<option value="' . $location_array['title'] . '" ';
									if($location == '') {
										if($_SESSION['location'] == $location_array['title']) {
											echo 'selected="true"';
										}
									} else {
										if($location == $location_array['title']) {
											echo 'selected="true"';
										}
									}
								echo '>' . $location_array['title'] . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
		</table>

		<input type="hidden" name="notes_id" value="<?php echo $notes_id; ?>" />
		<input type="hidden" name="exp_id" value="<?php echo $exp_id; ?>" />
		<input type="hidden" name="cond" value="<?php echo $cond; ?>" />
	</form>

	<div id="submit_buttons">
		<a href="#" onclick="return false;" id="process_button">Save</a>
		<a href="#" id="cancel_button">Cancel</a>
	</div>
</div>