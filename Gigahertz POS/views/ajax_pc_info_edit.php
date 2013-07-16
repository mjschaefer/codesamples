<?php	
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	extract($_GET);	

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	if(isset($cond)) {
		if($cond == 'save') {
			extract($_POST);

			$query = "UPDATE notes SET 
						pc_brand='" . addslashes($pc_manufacturer) . "', 
						pc_model='" . addslashes($pc_model) . "', 
						pc_serial='" . addslashes($pc_serial) . "',
						pc_os='" . addslashes($pc_os) . "', 
						pc_type='" . addslashes($pc_type) . "', 
						pc_password='" . addslashes($pc_password) . "'

						WHERE id='$note_id'";

			mysql_query($query) or die(mysql_error());

			header("Location: ../main.php?page=ui_notes_edit&note=$note_id");
			exit;
		}		
	}

	$query = "SELECT id,pc_brand,pc_model,pc_os,pc_type,pc_password,pc_serial FROM notes WHERE id='$note_id'";
	$cust_data = mysql_query($query) or die(mysql_error());
	$cust_arr = mysql_fetch_assoc($cust_data) or die(mysql_error());

	$id = $cust_arr['id'];
	$brand = $cust_arr['pc_brand'];
	$model = $cust_arr['pc_model'];
	$serial = $cust_arr['pc_serial'];
	$os = $cust_arr['pc_os'];
	$type = $cust_arr['pc_type'];
	$password = $cust_arr['pc_password'];

	switch($type) {
		case 'Desktop':
			$pc_checked_desktop = 'checked="checked"';
			$pc_checked_laptop = '';
			$pc_checked_other = '';
			break;
		case 'Laptop':
			$pc_checked_desktop = '';
			$pc_checked_laptop = 'checked="checked"';
			$pc_checked_other = '';
			break;
		case 'Other':
			$pc_checked_desktop = '';
			$pc_checked_laptop = '';
			$pc_checked_other = 'checked="checked"';
			break;

		default:
			$pc_checked_desktop = 'checked="checked"';
			$pc_checked_laptop = '';
			$pc_checked_other = '';
			break;
	}

	//echo $cust_id;


?>

<div id="wrapper_top">
	<form method="POST" name="pc_info_edit" action="./views/ajax_pc_info_edit.php?cond=save">
		<div id="customer_info">
			<input name="pc_manufacturer" size="35" placeholder="Manufacturer" value="<?php echo $brand;?>" class="required" /><br />
			<input name="pc_model" size="35" placeholder="Model Number" value="<?php echo $model;?>" class="required" /><br />
			<input name="pc_serial" size="35" placeholder="Serial Number" value="<?php echo $serial;?>" class="required" /><br />
			<input name="pc_os" size="35" value="<?php echo $os;?>" placeholder="OS" /><br />
			<input name="pc_password" size="35" placeholder="Password" value="<?php echo $password;?>" class="required" /><br />

			<input type="radio" name="pc_type" value="Desktop" <?php echo $pc_checked_desktop; ?>> Desktop 
			<input type="radio" name="pc_type" value="Laptop" <?php echo $pc_checked_laptop; ?>> Laptop 
			<input type="radio" name="pc_type" value="Other" <?php echo $pc_checked_other; ?>> Other

			<input type="hidden" name="note_id" value="<?php echo $note_id; ?>" />
		</div>
	</form>

	<div id="submit_buttons">
		<a href="#" id="process_button">Save</a>
		<a href="#" id="cancel_button">Cancel</a>
	</div>
</div>
