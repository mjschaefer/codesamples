<?php
	extract($_GET);
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	if(isset($action)) {
		if($action == 'save') {
			extract($_POST);

			if($location_hq == '') {
				$location_hq = 0;
			}

			if($location_id == 0) {
				$query = "INSERT INTO locations(`location_id`,
												`title`,
												`street`,
												`city`,
												`state`,
												`zip`,
												`phone`,
												`headquarters`,
												`email`,
												`website`) VALUES('" . addslashes($location_uid) . "',
																  '" . addslashes($location_name) . "',
																  '" . addslashes($location_street) . "',
																  '" . addslashes($location_city) . "',
																  '" . addslashes($location_state) . "',
																  '" . addslashes($location_zip) . "',
																  '" . addslashes($location_phone) . "',
																  '" . addslashes($location_hq) . "',
																  '" . addslashes($location_email) . "',
																  '" . addslashes($location_website) . "')";
			} else {
				$query = "UPDATE locations SET location_id='" . addslashes($location_uid) . "',title='" . addslashes($location_name) . "',street='" . addslashes($location_street) . "',city='" . addslashes($location_city) . "',state='" . addslashes($location_state) . "',zip='" . addslashes($location_zip) . "',phone='" . addslashes($location_phone) . "',headquarters='" . addslashes($location_hq) . "',email='" . addslashes($location_email) . "',website='" . addslashes($location_website) . "' WHERE id='$location_id'";
			}

			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=location");
			} else {
				echo $query . '<br />' . mysql_error();
			}
			exit;
		}
		if($action == 'delete') {
			$query = "DELETE FROM locations WHERE id='$id'";
			
			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=location");				
			} else {
				echo mysql_error();
			}
			exit;
		}
	}

	if(isset($id)) {
		$loc_id = $id;

		$query = "SELECT * FROM locations WHERE id='$loc_id'";
		$location_data = mysql_query($query) or die(mysql_error());
		$location_arr = mysql_fetch_assoc($location_data);

		$u_id = $location_arr['location_id'];
		$name = $location_arr['title'];
		$street = $location_arr['street'];
		$city = $location_arr['city'];
		$state = $location_arr['state'];
		$zip = $location_arr['zip'];
		$phone = $location_arr['phone'];
		$hq = $location_arr['headquarters'];
		$email = $location_arr['email'];
		$website = $location_arr['website'];
	} else {
		$loc_id = 0;

		$u_id = '';
		$name = '';
		$street = '';
		$city = '';
		$state = '';
		$zip = '';
		$phone = '';
		$hq = false;
		$email = '';
		$website = '';
	}
?>

<form method="POST" action="./views/ajax_settings_locations.php?action=save" name="location_form">
	<div id="location_inputs" style="float: left;">
		<input type="hidden" name="location_id" value="<?php echo $loc_id; ?>" />
		<input name="location_uid" placeholder="ID" size="6" value="<?php echo $u_id; ?>" />
		<input name="location_name" placeholder="Business Name" size="63" value="<?php echo $name; ?>" /><br />
		<input name="location_street" placeholder="Street" size="40" value="<?php echo $street; ?>" />
		<input name="location_city" placeholder="City" size="10" value="<?php echo $city; ?>" />
		<input name="location_state" placeholder="ST" size="1" value="<?php echo $state; ?>" />
		<input name="location_zip" placeholder="Zip"  size="4" value="<?php echo $zip; ?>" /><br />
		<input name="location_phone" placeholder="Phone" value="<?php echo $phone; ?>"  />
		Corporate Location? <input type="checkbox" name="location_hq" value="1" <?php echo ($hq) ? 'checked' : ''; ?> /><br />
		<input name="location_email" placeholder="Email" size="30" value="<?php echo $email; ?>" />
		<input name="location_website" placeholder="Website" size="39" value="<?php echo $website; ?>" />
	</div>

	<div id="submit_buttons" style="float: right;">
		<a href="#" id="process_button">Save</a>
		<a href="#" id="cancel_button">Cancel</a>
	</div>
</form>