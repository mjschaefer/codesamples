<?php include('./includes/tinymce_init.php'); ?>

<?php	
	extract($_GET);

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");
?>

<script>
	$(document).ready(function() {

		init_agreements_editor();

		$('#unpaid_row').hide();
		$('#paid_row').hide();
		$('#dropoff_agreement_row').hide();
		$('#referral_text_row').hide();

		$("#page_title").html('<img src="./img/icon_settings_48x48.png" /><h2 class="title">Settings</h2>');

		$('#settings_left > a').click(function() {
			change_view($(this).attr('targ'));
		});

		$('#coa_add').click(function() {
			$.ajax({
				url: "./views/ajax_settings_coa.php",
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#process_button").click(function() {
						$('form[name=account_form]').submit();
						return false;
					});

					$("#cancel_button").click(function() {
						$("#dropdown").slideUp('fast', function() {
							$(this).html('');
						});

						return false;
					});
				}
			});

			return false;
		});

		$('.coa_delete').click(function() {
			if(confirm("Are you sure you want to delete " + $(this).attr('account_name') + "?")) {
				window.location = "./views/ajax_settings_coa.php?action=delete&id=" + $(this).attr('account_id');
			}
			return false;
		});

		$('.coa_edit').click(function() {			
			$.ajax({
				url: "./views/ajax_settings_coa.php?id=" + $(this).attr('account_id'),
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#process_button").click(function() {
						$('form[name=account_form]').submit();
						return false;
					});

					$("#cancel_button").click(function() {
						$("#dropdown").slideUp('fast', function() {
							$(this).html('');
						});

						return false;
					});
				}
			});
			return false;
		});

		$('#user_add').click(function() {
			$.ajax({
				url: "./views/ajax_settings_employees.php",
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#process_button").click(function() {
						$('form[name=user_form]').submit();
						return false;
					});

					$("#cancel_button").click(function() {
						$("#dropdown").slideUp('fast', function() {
							$(this).html('');
						});

						return false;
					});
				}
			});

			return false;
		});

		$('.user_delete').click(function() {
			if(confirm("Are you sure you want to delete " + $(this).attr('user_name_real') + "?")) {
				window.location = "./views/ajax_settings_employees.php?action=delete&id=" + $(this).attr('user_id');
			}
			return false;
		});

		$('.user_edit').click(function() {			
			$.ajax({
				url: "./views/ajax_settings_employees.php?id=" + $(this).attr('user_id'),
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#process_button").click(function() {
						$('form[name=user_form]').submit();
						return false;
					});

					$("#cancel_button").click(function() {
						$("#dropdown").slideUp('fast', function() {
							$(this).html('');
						});

						return false;
					});
				}
			});
			return false;
		});

		$('#location_add').click(function() {
			$.ajax({
				url: "./views/ajax_settings_locations.php",
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#process_button").click(function() {
						$('form[name=location_form]').submit();
						return false;
					});

					$("#cancel_button").click(function() {
						$("#dropdown").slideUp('fast', function() {
							$(this).html('');
						});

						return false;
					});
				}
			});

			return false;
		});

		$('.location_delete').click(function() {
			if(confirm("Are you sure you want to delete " + $(this).attr('location_name') + "?")) {
				window.location = "./views/ajax_settings_locations.php?action=delete&id=" + $(this).attr('location_id');
			}
			return false;
		});

		$('.location_edit').click(function() {			
			$.ajax({
				url: "./views/ajax_settings_locations.php?id=" + $(this).attr('location_id'),
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#process_button").click(function() {
						$('form[name=location_form]').submit();
						return false;
					});

					$("#cancel_button").click(function() {
						$("#dropdown").slideUp('fast', function() {
							$(this).html('');
						});

						return false;
					});
				}
			});
			return false;
		});

		$("input[name=sales_tax_rate]").val((global_tax * 100).toFixed(2));

		if(global_tax_services) {
			$("input[name=tax_services]").attr('checked', true);
		}

		$("#button_cancel").click(function() {
			window.location.replace("./main.php");
		});

		<?php
			if(isset($changes)) {
				if($changes == 'none') {
					echo "$('#flash').html('Nothing Changed');";
					echo "$('#flash').slideDown('fast');";
					echo "$('#flash').delay(1000).slideUp('fast');";
				}
			}

			if($location_based_invoices) {
				echo "$('input[name=location_based_invoicing_checkbox]').attr('checked', true);";
			}

			if($print_invoice_referrals) {
				echo "$('input[name=print_referrals_invoice]').attr('checked', true);";
			}

			if(isset($success)) {
				if($success == 'true') {
					switch($changes) {
						case 'personal':
							echo "$('#flash').html('Personal Settings Saved Successfully');";
							echo "$('#flash').slideDown('fast');";
							echo "$('#flash').delay(1000).slideUp('fast');";
							echo "change_view('settings_user', true)";
							break;
						case 'company_info':
							echo "$('#flash').html('Company Info Saved Successfully');";
							echo "$('#flash').slideDown('fast');";
							echo "$('#flash').delay(1000).slideUp('fast');";
							echo "change_view('settings_company', true)";
							break;

						case 'sales_tax':
							echo "$('#flash').html('Sales Tax Settings Saved Successfully');";
							echo "$('#flash').slideDown('fast');";
							echo "$('#flash').delay(1000).slideUp('fast');";
							echo "change_view('settings_sales_tax', true)";
							break;

						case 'invoicing':
							echo "$('#flash').html('Invoice Settings Saved Successfully');";
							echo "$('#flash').slideDown('fast');";
							echo "$('#flash').delay(1000).slideUp('fast');";
							echo "change_view('settings_invoicing', true)";
							break;

						case 'location':
							echo "$('#flash').html('Locations Modified Successfully');";
							echo "$('#flash').slideDown('fast');";
							echo "$('#flash').delay(1000).slideUp('fast');";
							echo "change_view('settings_locations', true)";	
							break;		

						case 'users':
							echo "$('#flash').html('Users Modified Successfully');";
							echo "$('#flash').slideDown('fast');";
							echo "$('#flash').delay(1000).slideUp('fast');";
							echo "change_view('settings_pos_users', true)";	
							break;		

						case 'coa':
							echo "$('#flash').html('Chart of Accounts Modified Successfully');";
							echo "$('#flash').slideDown('fast');";
							echo "$('#flash').delay(1000).slideUp('fast');";
							echo "change_view('settings_accounts', true)";	
							break;	
							
						case 'notes':
							echo "$('#flash').html('Notes Settings Modified Successfully');";
							echo "$('#flash').slideDown('fast');";
							echo "$('#flash').delay(1000).slideUp('fast');";
							echo "change_view('settings_notes', true)";	
							break;
							
						case 'referrals':											
							echo "$('#flash').html('Referrals Settings Modified Successfully');";
							echo "$('#flash').slideDown('fast');";
							echo "$('#flash').delay(1000).slideUp('fast');";
							echo "change_view('settings_referrals', true)";	
							break;				

						default:
							echo "$('#flash').html('Settings Saved Successfully');";
							echo "$('#flash').slideDown('fast');";
							echo "$('#flash').delay(1000).slideUp('fast');";
							break;
													
					}				
				}
			} else {
				if(isset($changes)) {
					echo "change_view('" . $changes . "', true)";
				} else {
					echo "change_view('settings_user', true);";
				}
			}
		?>
	});
</script>

<div id="wrapper_main">
	<div id="flash"></div>
	<div id="settings_left">
		<a href="#" onclick="return false;" class="selected" targ="settings_user">Personal Settings</a>
			<?php
				if($_SESSION['clearance'] >= 70) {				
			?>
					<hr>
					<a href="#" onclick="return false;" targ="settings_locations">Business Locations</a>
					<a href="#" onclick="return false;" targ="settings_accounts">Chart of Accounts</a>
					<a href="#" onclick="return false;" targ="settings_company">Company Information</a>
					<a href="#" onclick="return false;" targ="settings_notes">Dropoffs/Notes</a>
					<a href="#" onclick="return false;" targ="settings_pos_users">Employees / Users</a>
					<a href="#" onclick="return false;" targ="settings_groups">Groups</a>
					<a href="#" onclick="return false;" targ="settings_invoicing">Invoicing</a>
					<a href="#" onclick="return false;" targ="settings_sales_tax">Sales Tax</a>
					<a href="#" onclick="return false;" targ="settings_exemptions">Tax Exemptions</a>
					<a href="#" onclick="return false;" targ="settings_referrals">Invoice Referrals</a>
			<?php
				}
			?>
	</div>
	<div id="settings_right">
		<form method="POST" action="./views/edit_global_settings.php" onsubmit="return validate_settings();">
			<input type="hidden" name="current_view" value="settings_user" />
<?php
	//----------------------------------------------------------------------------------------------------
	//---------PERSONAL SETTINGS--------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>
			<div class="setting_wrap" id="settings_user">
				<table>
					<tr class="header_row">
						<td colspan="2">Change Password</td>
					</tr>
					<tr>
						<td class="descript_td">Old Password</td>
						<?php
							if(isset($error) && $error == 'pass_old') {
								echo '<td><input type="password" class="error" name="employee_password_old" /><span class="span_error">Incorrect Old Password</span></td>';
							} else {
								echo '<td><input type="password" name="employee_password_old" /></td>';
							}
						?>
					</tr>
					<tr>
						<td class="descript_td">New Password</td>
						<td><input type="password" <?php if(isset($error) && $error == 'pass_confirm') { echo 'class="error"'; } ?> name="employee_password_new" /><span class="span_error" id="pass_new_error"><?php if(isset($error) && $error == 'pass_confirm') { echo 'These must match and cannot be blank'; } ?></span></td>
					</tr>
					<tr>
						<td class="descript_td">Confirm New Password</td>
						<td><input type="password" <?php if(isset($error) && $error == 'pass_confirm') { echo 'class="error"'; } ?> name="employee_password_new_confirm" /></td>
					</tr>
				</table>
			</div>
			<?php
				if($_SESSION['clearance'] >= 70) {				
			?>
<?php
	//----------------------------------------------------------------------------------------------------
	//---------SALES TAX SETTINGS-------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>
					<div class="setting_wrap" id="settings_sales_tax">
						<table>
							<tr class="header_row">
								<td colspan="2">Tax Settings</td>
							</tr>
							<tr>
								<td class="descript_td">Sales Tax Rate</td>
								<td><input name="sales_tax_rate" size="3" />%</td>
							</tr>
							<tr>
								<td class="descript_td">Charge Tax on Services</td>
								<td><input type="checkbox" name="tax_services" value="1"></td>
							</tr>

							<!--

							<tr><td>&nbsp;</td><td>&nbsp;</td></tr>

							<tr class="header_row">
								<td colspan="2">Other Settings</td>
							</tr>
							<tr>
								<td class="descript_td">Something something</td>
								<td><input name="sales_tax_rate" /></td>
							</tr>
							<tr>
								<td class="descript_td">Other and More</td>
								<td><input name="sales_tax_rate" /></td>
							</tr>

							-->
						</table>
					</div>
<?php
	//----------------------------------------------------------------------------------------------------
	//---------USER ACCOUNTS------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>
					<div class="setting_wrap" id="settings_pos_users">
						<table>
							<tr class="header_row">
								<td colspan="5">Employee Listing</td>
								<td><a href="#" id="user_add"><img src="./img/icon_add_16x16.png" /></a></td>
							</tr>

							<tr>
								<th>Name</th>
								<th>Username</th>
								<th>Password</th>
								<th>Clearance</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>

							<?php
								$query = "SELECT * FROM users ORDER BY name_real";
								$emp_data = mysql_query($query) or die(mysql_error());

								while($emp_arr = mysql_fetch_assoc($emp_data)) {
									echo '<tr>';
										echo '<td>' . $emp_arr['name_real'] . '</td>';
										echo '<td>' . $emp_arr['name_user'] . '</td>';
										echo '<td>************</td>';
										echo '<td>' . $emp_arr['clearance'] . '</td>';
										echo '<td><a href="#" class="user_edit" user_id="' . $emp_arr['id'] . '"><img src="./img/icon_edit_16x16.png" /></a></td>';
										echo '<td><a href="#" class="user_delete" user_id="' . $emp_arr['id'] . '" user_name_real="' . $emp_arr['name_real'] . '"><img src="./img/icon_delete_16x16.png" /></a></td>';
									echo '</tr>';
								}
							?>
						</table>
					</div>
<?php
	//----------------------------------------------------------------------------------------------------
	//---------LOCATION SETTINGS--------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>
					<div class="setting_wrap" id="settings_locations">
						<table>
							<tr class="header_row">
								<td colspan="6">Location Listing</td>
								<td><a href="#" id="location_add"><img src="./img/icon_add_16x16.png" /></a></td>
							</tr>

							<tr>
								<th>&nbsp;</th>
								<th>ID</th>
								<th>Name</th>
								<th>Address</th>
								<th>Phone</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>

							<?php
								$query = "SELECT * FROM locations ORDER BY title";
								$location_data = mysql_query($query) or die(mysql_error());

								while($location_arr = mysql_fetch_assoc($location_data)) {
									echo '<tr>';
										if($location_arr['headquarters']) {
											echo '<td>*</td>';
										} else {
											echo '<td>&nbsp;</td>';
										}
										echo '<td>' . $location_arr['location_id'] . '</td>';
										echo '<td>' . $location_arr['title'] . '</td>';
										echo '<td>' . $location_arr['street'] . ' ' . $location_arr['city'] . ', ' . $location_arr['state'] . ' ' . $location_arr['zip'] . '</td>';
										echo '<td>' . $location_arr['phone'] . '</td>';
										echo '<td><a href="#" class="location_edit" location_id="' . $location_arr['id'] . '"><img src="./img/icon_edit_16x16.png" /></a></td>';
										echo '<td><a href="#" class="location_delete" location_id="' . $location_arr['id'] . '" location_name="' . $location_arr['title'] . '"><img src="./img/icon_delete_16x16.png" /></a></td>';
									echo '</tr>';
								}
							?>
						</table>
					</div>
<?php
	//----------------------------------------------------------------------------------------------------
	//---------CHART OF ACCOUNTS--------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>
					<div class="setting_wrap" id="settings_accounts">
						<table>
							<tr class="header_row">
								<td colspan="6">Accounts List</td>
								<td><a href="#" id="coa_add"><img src="./img/icon_add_16x16.png" /></a></td>
							</tr>

							<tr>
								<th>ID</th>
								<th>Shrt Ttl</th>
								<th>Title</th>
								<th>Description</th>
								<th>Type</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>

							<?php
								$query = "SELECT * FROM accounts ORDER BY account_id";
								$account_data = mysql_query($query) or die(mysql_error());

								while($account_arr = mysql_fetch_assoc($account_data)) {
									echo '<tr>';
										echo '<td>' . $account_arr['account_id'] . '</td>';
										echo '<td>' . $account_arr['title_shorthand'] . '</td>';
										echo '<td>' . $account_arr['title'] . '</td>';
										echo '<td>' . $account_arr['description'] . '</td>';
										echo '<td>' . $account_arr['type'] . '</td>';
										echo '<td><a href="#" class="coa_edit" account_id="' . $account_arr['id'] . '"><img src="./img/icon_edit_16x16.png" /></a></td>';
										echo '<td><a href="#" class="coa_delete" account_id="' . $account_arr['id'] . '" account_name="' . $account_arr['title'] . '"><img src="./img/icon_delete_16x16.png" /></a></td>';
									echo '</tr>';
								}
							?>
						</table>
					</div>
<?php
	//----------------------------------------------------------------------------------------------------
	//---------TAX EXEMPTIONS-----------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>
					<div class="setting_wrap" id="settings_exemptions">
						Exemptions Settings
					</div>
<?php
	//----------------------------------------------------------------------------------------------------
	//---------INVOICE SETTINGS---------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>
					<div class="setting_wrap" id="settings_invoicing">
						<table>
							<tr class="header_row">
								<td colspan="2">General Invoice Settings</td>
							</tr>
							<tr>
								<td class="descript_td">Location Based Invoices</td>
								<td><input type="checkbox" name="location_based_invoicing_checkbox" value="1"></td>
							</tr>
							<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
							<tr class="header_row">
								<td>Unpaid Agreement</td>
								<td><a href="#" onclick="$('#unpaid_row').fadeToggle('fast'); return false;"><img src="./img/icon_edit_16x16.png" /></a></td>
							</tr>
							<tr id="unpaid_row">
								<td colspan="2"><textarea name="agreement_unpaid_textarea" class="agreements" rows="30" cols="70"><?php echo stripslashes(html_entity_decode($agreement_unpaid)); ?></textarea>
							</tr>
							<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
							<tr class="header_row">
								<td>Paid Agreement</td>
								<td><a href="#" onclick="$('#paid_row').fadeToggle('fast'); return false;"><img src="./img/icon_edit_16x16.png" /></a></td>
							</tr>
							<tr id="paid_row">
								<td colspan="2"><textarea name="agreement_paid_textarea" class="agreements" rows="30" cols="70"><?php echo stripslashes(html_entity_decode($agreement_paid)); ?></textarea>
							</tr>
						</table>
					</div>
<?php
	//----------------------------------------------------------------------------------------------------
	//---------COMPANY SETTINGS--------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>
					<div class="setting_wrap" id="settings_company">
						<table>
							<tr class="header_row">
								<td colspan="2">Headquarters Information</td>
							</tr>
							<tr>
								<td class="descript_td">DBA</td>
								<td><input name="company_info_DBA" size="50" value="<?php echo $company_name; ?>" /></td>
							</tr>
							<tr>
								<td class="descript_td">Street Address</td>
								<td><input name="company_info_street" size="40" value="<?php echo $company_street; ?>" /></td>
							</tr>
							<tr>
								<td class="descript_td">City</td>
								<td><input name="company_info_city" size="16" value="<?php echo $company_city; ?>" /></td>
							</tr>
							<tr>
								<td class="descript_td">State</td>
								<td><input name="company_info_state" size="1" value="<?php echo $company_state; ?>" /></td>
							</tr>
							<tr>
								<td class="descript_td">Zip</td>
								<td><input name="company_info_zip" size="6" value="<?php echo $company_zip; ?>" /></td>
							</tr>
							<tr>
								<td class="descript_td">Phone</td>
								<td><input name="company_info_phone" size="10" value="<?php echo $company_phone; ?>" /></td>
							</tr>
							<tr>
								<td class="descript_td">Email</td>
								<td><input name="company_info_email" size="35" value="<?php echo $company_email; ?>"  /></td>
							</tr>
							<tr>
								<td class="descript_td">Website</td>
								<td><input name="company_info_website" size="35" value="<?php echo $company_website; ?>" /></td>
							</tr>
						</table>
					</div>
<?php
	//----------------------------------------------------------------------------------------------------
	//---------GROUP POLICY SETTINGS----------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>					<div class="setting_wrap" id="settings_groups">
						Group Policies.
					</div>
<?php
	//----------------------------------------------------------------------------------------------------
	//---------NOTES SETTINGS-----------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>					<div class="setting_wrap" id="settings_notes">
						<table>
							<tr class="header_row">
								<td colspan="2">General Note Settings</td>
							</tr>
							<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
							<tr class="header_row">
								<td>Dropoff Agreement</td>
								<td><a href="#" onclick="$('#dropoff_agreement_row').fadeToggle('fast'); return false;"><img src="./img/icon_edit_16x16.png" /></a></td>
							</tr>
							<tr id="dropoff_agreement_row">
								<td colspan="2"><textarea name="agreement_dropoff_textarea" class="agreements" rows="30" cols="70"><?php echo stripslashes(html_entity_decode($agreement_dropoff)); ?></textarea>
							</tr>
						</table>
					</div>

<?php
	//----------------------------------------------------------------------------------------------------
	//---------REFERRAL SETTINGS--------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------
?>					<div class="setting_wrap" id="settings_referrals">
						<table>
							<tr class="header_row">
								<td colspan="2">Invoice Referral Settings</td>
							</tr>
							<tr>
								<td class="descript_td">Print Referrals on Invoices</td>
								<td><input type="checkbox" name="print_referrals_invoice" value="1"></td>
							</tr>
							<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
							<tr class="header_row">
								<td>Invoice Referral Text</td>
								<td><a href="#" onclick="$('#referral_text_row').fadeToggle('fast'); return false;"><img src="./img/icon_edit_16x16.png" /></a></td>
							</tr>
							<tr id="referral_text_row">
								<td colspan="2"><textarea name="referral_text_textarea" class="agreements" rows="30" cols="70"><?php echo stripslashes(html_entity_decode($invoice_referral_text)); ?></textarea>
							</tr>
						</table>
					</div>
			<?php
				}
			?>
			<span id="buttons">
				<input type="button" style="float: right;" value="Cancel" id="button_cancel" />
				<input type="submit" style="float: right;" value="Save" id="button_save" />
			</span>
		</form>
	</div>
</div>