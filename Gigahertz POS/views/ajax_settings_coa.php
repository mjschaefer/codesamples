<?php
	extract($_GET);
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	if(isset($action)) {
		if($action == 'save') {
			extract($_POST);

			if($account_id == 0) {
				$query = "INSERT INTO accounts(`account_id`,
											   `title`,
											   `title_shorthand`,
											   `description`,
											   `type`) VALUES ('" . addslashes($account_aid) . "',
											   				   '" . addslashes($account_name) . "',
											   				   '" . addslashes($account_name_shorthand) . "',
											   				   '" . addslashes($account_description) . "',
											   				   '" . addslashes($account_type) . "')";
			} else {
				$query = "UPDATE accounts SET account_id='" . addslashes($account_aid) . "',title='" . addslashes($account_name) . "',description='" . addslashes($account_description) . "',type='" . addslashes($account_type) . "',title_shorthand='" . addslashes($account_name_shorthand) . "' WHERE id='$account_id'";
			}

			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=coa");
			} else {
				echo $query . '<br />' . mysql_error();
			}
			exit;
		}
		if($action == 'delete') {
			$query = "DELETE FROM accounts WHERE id='$id'";
			
			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=coa");				
			} else {
				echo mysql_error();
			}
			exit;
		}
	}

	if(isset($id)) {
		$account_id = $id;

		$query = "SELECT * FROM accounts WHERE id='$account_id'";
		$account_data = mysql_query($query) or die(mysql_error());
		$account_arr = mysql_fetch_assoc($account_data);

		$a_id = $account_arr['account_id'];
		$name = $account_arr['title'];
		$shorthand = $account_arr['title_shorthand'];
		$description = $account_arr['description'];
		$type = $account_arr['type'];
	} else {
		$account_id = 0;

		$a_id = '';
		$name = '';
		$shorthand = '';
		$description = '';
		$type = 'credit';
	}
?>

<form method="POST" action="./views/ajax_settings_coa.php?action=save" name="account_form">
	<div id="account_inputs" style="float: left;">
		<input type="hidden" name="account_id" value="<?php echo $account_id; ?>" />
		<input name="account_aid" placeholder="ID" size="6" value="<?php echo $a_id; ?>" />
		<input name="account_name" placeholder="Account Name" size="30" value="<?php echo $name; ?>" />
		<input name="account_name_shorthand" placeholder="Short Name (~8 letters)" size="30" value="<?php echo $shorthand; ?>" />
		<select name="account_type">
			<option value="credit"<?php echo ($type == 'credit') ? ' selected="selected"' : ''; ?>>Credit (+)</option>
			<option value="debit"<?php echo ($type == 'debit') ? ' selected="selected"' : ''; ?>>Debit (-)</option>
		</select><br />
		<input name="account_description" placeholder="Description" size="91" value="<?php echo $description; ?>" />
	</div>

	<div id="submit_buttons" style="float: right;">
		<a href="#" id="process_button">Save</a>
		<a href="#" id="cancel_button">Cancel</a>
	</div>
</form>