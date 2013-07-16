<?php
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	extract($_POST);
	extract($_GET);

	if(isset($cond)) {
		if($cond == 'save') {
			$date_standard = explode("/", $date);
			$date_unix = mktime(0, 0, 0, $date_standard[0], $date_standard[1], $date_standard[2]);

			$employee = $_SESSION['name'];

			$query = "INSERT INTO invoices_billed(`invoice_id`,
												  `customer_id`,
												  `date`,
												  `method`,
												  `notes`,
												  `entered_by`) VALUES('$invoice_id',
												  					   '$customer_id',
												  					   '$date_unix',
												  					   '$billing_method',
												  					   '$billing_note',
												  					   '$employee')";

			//echo $query;
			if(mysql_query($query)) {				
				header("Location: ../main.php?page=ui_invoice_view&invoice=$invoice_id");
				exit;
			} else {
				echo mysql_error();
				exit;
			}
		}
	}

	$date = date("n/j/Y", mktime(0, 0, 0, date("m"), date("j"), date("Y")));
?>
<script>
	$(document).ready(function() {		
		$("#date_picker").click(function() {
			$.ajax({
				url: "./views/ajax_calendar.php?targ=date&div=cal",
				success: function(data) {
					$("#cal").html(data);
				}
			});
		});
	});
</script>

<form name="form_billing" method="POST" action="./views/ajax_billing.php?cond=save">
	<a href="#" onclick="return false;" id="date_picker">
		<img src="./img/icon_calendar_16x16.png" class="calendar" />
	</a>
	<input class="input_short" disabled="true" name="date" id="date_input" placeholder="Date" value="<?php echo $date; ?>" />	
	<input type="hidden" name="date" id="date" value="<?php echo $date; ?>" />
	<div id="cal"></div>

	<select name="billing_method">
		<option value="Dropped Off">Dropped Off</option>
		<option value="E-Mail">E-Mail</option>
		<option value="Handed To" selected="selected">Handed To</option>
		<option value="Snail Mail">Smail Mail</option>
	</select>

	<input size="50" name="billing_note" placeholder="Note" />

	<input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>" />
	<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />

	<div id="submit_buttons">
		<a href="#" id="process_button">Save</a>
		<a href="#" id="cancel_button">Cancel</a>
	</div>
</form>