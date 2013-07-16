<?php
	extract($_GET);
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	if(isset($action)) {
		if($action == 'save') {
			extract($_POST);

			if($u_id == 0) {
				$query = "INSERT INTO users(`name_real`,
											`name_user`,
											`password`,
											`clearance`) VALUES('" . addslashes($user_real) . "',
																'" . addslashes($user_username) . "',
																'" . md5($user_password) . "',
																'" . addslashes($user_clearance) . "')";
			} else {
				if($user_password == '') {
					$query = "UPDATE users SET name_real='" . addslashes($user_real) . "',name_user='" . addslashes($user_username) . "',clearance='" . addslashes($user_clearance) . "' WHERE id='$u_id'";						
				} else {
					$query = "UPDATE users SET name_real='" . addslashes($user_real) . "',name_user='" . addslashes($user_username) . "',password='" . md5($user_password) . "',clearance='" . addslashes($user_clearance) . "' WHERE id='$u_id'";					
				}
			}

			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=users");
			} else {
				echo $query . '<br />' . mysql_error();
			}
			exit;
		}
		if($action == 'delete') {
			$query = "DELETE FROM users WHERE id='$id'";

			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=users");				
			} else {
				echo mysql_error();
			}
			exit;
		}
	}

	if(isset($id)) {
		$user_id = $id;

		$query = "SELECT * FROM users WHERE id='$user_id'";
		$user_data = mysql_query($query) or die(mysql_error());
		$user_arr = mysql_fetch_assoc($user_data);

		$name_real = $user_arr['name_real'];
		$name_user = $user_arr['name_user'];
		$clearance = $user_arr['clearance'];
	} else {
		$user_id = 0;

		$name_real = '';
		$name_user = '';
		$clearance = '';
	}
?>
<script>
	$(document).ready(function() {
		<?php
			if($user_id > 0) {
		?>
			$('option').removeAttr('selected');
			$('option[value=' + <?php echo $clearance; ?> + ']').attr('selected','selected');
		<?php
			}
		?>
	});
</script>

<form method="POST" action="./views/ajax_settings_employees.php?action=save" name="user_form">
	<div id="user_inputs" style="float: left;">
		<input type="hidden" name="u_id" value="<?php echo $user_id; ?>" />
		<input name="user_real" placeholder="Real Name" size="30" value="<?php echo $name_real ?>" />
		<input name="user_username" placeholder="Login Name" size="20" value="<?php echo $name_user; ?>" />
		<input type="password" name="user_password" placeholder="Password" />
		<select name="user_clearance">
			<option value="10">10 - Secretary</option>
			<option value="40">40 - Intern</option>
			<option value="50" selected>50 - Technician</option>
			<option value="60">60 - Manager</option>
			<option value="70">70 - Manager+</option>
			<option value="99">99 - Owner</option>
		</select>
	</div>

	<div id="submit_buttons" style="float: right;">
		<a href="#" id="process_button">Save</a>
		<a href="#" id="cancel_button">Cancel</a>
	</div>
</form>