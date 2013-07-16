<?php	
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	extract($_GET);	

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	if(isset($cond)) {
		if($cond == 'save') {
			extract($_POST);

			$query = "UPDATE customers SET 
						business_id='" . addslashes($cust_business_id) . "'

						WHERE id='$cust_id'";

			mysql_query($query) or die(mysql_error());

			header("Location: ../main.php?page=ui_customers&customer=$cust_id");
			exit;
		}		
	}

	$query = "SELECT * FROM customers WHERE id='$cust_id'";
	$cust_data = mysql_query($query) or die(mysql_error());
	$cust_arr = mysql_fetch_assoc($cust_data) or die(mysql_error());

	$id = $cust_arr['id'];
	$name = $cust_arr['name'];
	$business = $cust_arr['business'];
	$business_id = $cust_arr['business_id'];
	$street = $cust_arr['street'];
	$city = $cust_arr['city'];
	$state = $cust_arr['state'];
	$zip = $cust_arr['zip'];
	$phone_primary = $cust_arr['phone_primary'];
	$phone_secondary = $cust_arr['phone_secondary'];
	$type = $cust_arr['type'];

	if($type == "Residential") {
		$type_residential = 'checked="checked"';	
		$type_business = '';
	} else {		
		$type_residential = '';	
		$type_business = 'checked="checked"';
	}

	//echo $cust_id;


?>

<div id="wrapper_top">
	<form method="POST" name="customer_edit" action="./views/ajax_exemption_edit.php?cond=save">
		<div id="customer_info">
			<input type="radio" disabled="disabled" name="cust_type" value="Residential" <?php echo $type_residential; ?>> Residential &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" disabled="disabled" name="cust_type" value="Business" <?php echo $type_business; ?>> Business
			<br />
			
			<input name="cust_business_id" size="17" placeholder="Business ID (TID #/FID #)" value="<?php echo $business_id; ?>" /> <-- (Leave this blank for regular taxing)<br />

			<input name="cust_name" size="42" disabled="disabled" placeholder="Customer Name" class="required" value="<?php echo $name; ?>" /><br />
			<input name="cust_business" size="42" disabled="disabled" placeholder="Business" value="<?php echo $business; ?>" /><br />
			<input name="cust_street" size="42" disabled="disabled" placeholder="Street" value="<?php echo $street; ?>" /><br />
			<input name="cust_city" placeholder="City" disabled="disabled" value="<?php echo $city; ?>" />
			<input name="cust_state" size="2" value="IN" disabled="disabled" value="<?php echo $state; ?>" />
			<input name="cust_zip" placeholder="Zip" disabled="disabled" size="5" value="<?php echo $zip; ?>" /><br />
			<input name="cust_phone_primary" size="18" disabled="disabled" placeholder="Primary Phone" class="required" value="<?php echo $phone_primary; ?>" />
			<input name="cust_phone_secondary" size="17" disabled="disabled" placeholder="Secondary Phone" value="<?php echo $phone_secondary; ?>" />
			<input type="hidden" name="cust_id" value="<?php echo $id; ?>" />
		</div>
	</form>

	<div id="submit_buttons">
		<a href="#" id="process_button">Save</a>
		<a href="#" id="cancel_button">Cancel</a>
	</div>
</div>
