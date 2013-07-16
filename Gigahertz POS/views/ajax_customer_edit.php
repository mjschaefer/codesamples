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
						name='" . addslashes($cust_name) . "', 
						business='" . addslashes($cust_business) . "', 
						street='" . addslashes($cust_street) . "', 
						city='" . addslashes($cust_city) . "', 
						state='" . addslashes($cust_state) . "', 
						zip='" . addslashes($cust_zip) . "', 
						phone_primary='" . addslashes($cust_phone_primary) . "', 
						phone_secondary='" . addslashes($cust_phone_secondary) . "', 
						type='" . addslashes($cust_type) . "' 

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
	<form method="POST" name="customer_edit" action="./views/ajax_customer_edit.php?cond=save">
		<div id="customer_info">
			<input type="radio" name="cust_type" value="Residential" <?php echo $type_residential; ?>> Residential &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="radio" name="cust_type" value="Business" <?php echo $type_business; ?>> Business
			<br />
			<input name="cust_name" size="42" placeholder="Customer Name" class="required" value="<?php echo $name; ?>" /><br />
			<input name="cust_business" size="42" placeholder="Business" value="<?php echo $business; ?>" /><br />
			<input name="cust_street" size="42" placeholder="Street" value="<?php echo $street; ?>" /><br />
			<input name="cust_city" placeholder="City" value="<?php echo $city; ?>" />
			<input name="cust_state" size="2" value="IN" value="<?php echo $state; ?>" />
			<input name="cust_zip" placeholder="Zip" size="5" value="<?php echo $zip; ?>" /><br />
			<input name="cust_phone_primary" size="18" placeholder="Primary Phone" class="required" value="<?php echo $phone_primary; ?>" />
			<input name="cust_phone_secondary" size="17" placeholder="Secondary Phone" value="<?php echo $phone_secondary; ?>" />
			<input type="hidden" name="cust_id" value="<?php echo $id; ?>" />
		</div>
	</form>

	<div id="submit_buttons">
		<a href="#" id="process_button">Save</a>
		<a href="#" id="cancel_button">Cancel</a>
	</div>
</div>
