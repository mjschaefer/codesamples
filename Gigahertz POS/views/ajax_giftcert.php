<?php
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	extract($_GET);

	if($cond == 'save') {
		extract($_POST);

		echo '<pre>';
		print_r($_POST);
		echo '</pre>';

		if($condition == 'add') {
			$issued = time();
			$expires = strtotime("+6 months");

			$query = "INSERT INTO gift_certs(`type`,
											 `for`,
											 `description`,
											 `amount`,
											 `issued`,
											 `expires`,
											 `used`,
											 `applied_to`,
											 `active`) VALUES('" . addslashes($type) . "',
											 				  '" . addslashes($for) . "',
											 				  '" . addslashes($description) . "',
											 				  '" . addslashes($amount) . "',
											 				  '" . addslashes($issued) . "',
											 				  '" . addslashes($expires) . "',
											 				  '0',
											 				  '0',
											 				  '1')";
		}

		if($condition == 'edit') {
			$query = "UPDATE gift_certs SET `type`='" . addslashes($type) . "', 
											`for`='" . addslashes($for) . "', 
											`description`='" . addslashes($description) . "', 
											`amount`='" . addslashes($amount) . "', 
											`issued`='" . addslashes($issued) . "', 
											`expires`='" . addslashes($expires) . "' WHERE id='$cert_id'";
		}

		echo $query . '<br />';
		mysql_query($query) or die(mysql_error());


		header("Location: ../main.php?page=ui_gift_generate");

		exit;
	}

	if($cond == 'add') {
		$id = '';
		$type = '';
		$for = '';
		$description = '';
		$amount = '';
		$issued = '';
		$expires = '';
	}
	if($cond == 'edit') {
		$query = "SELECT * FROM gift_certs WHERE id='$cert_id'";
		$cert_data = mysql_query($query) or die(mysql_error());
		$cert_arr = mysql_fetch_assoc($cert_data) or die(mysql_error());

		$id = $cert_arr['id'];
		$type = $cert_arr['type'];
		$for = $cert_arr['for'];
		$description = $cert_arr['description'];
		$amount = $cert_arr['amount'];
		$issued = $cert_arr['issued'];
		$expires = $cert_arr['expires'];
		$active = $cert_arr['active'];
	}
?>
<div id="wrapper_top">
	<form method="POST" name="giftcert" action="./views/ajax_giftcert.php?cond=save">
		<table id="input_table">
			<tr>
				<td>
					Type of gift certificate:
					<select name="type" style="width: 245px;">
						<option value="set_amount">Set Amount</option>
						<option value="percent_off">Percent Off</option>
					</select>
					<br />
					<input name="for" size="60" placeholder="For" value="<?php echo $for; ?>" /><br />
					<input name="description" size="80" placeholder="Description" value="<?php echo $description; ?>" /><br />

					<input name="amount" size="8" placeholder="Amount" value="<?php echo $amount; ?>" /><br />

					<!--<input name="stock" size="2" placeholder="Stock" /><br />-->
					<input type="hidden" name="condition" value="<?php echo $cond ?>" />
					<input type="hidden" name="issued" value="<?php echo $issued ?>" />
					<input type="hidden" name="expires" value="<?php echo $expires ?>" />
					<input type="hidden" name="cert_id" value="<?php echo $id ?>" />
					<!--<input type="submit" id="submit_inventory" value="Save Item" style="float: right;" />-->
				</td>
			</tr>
		</table>

	</form>

	<div id="submit_buttons">
		<a href="#" onclick="return false;" id="process_button">Save</a>
		<a href="#" id="cancel_button">Cancel</a>
	</div>
</div>