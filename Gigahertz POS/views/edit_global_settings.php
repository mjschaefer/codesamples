<?php
	session_start();
	extract($_POST);

	include('../includes/database.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$_SESSION['refresh_settings'] = true;

	switch($current_view) {

		case 'settings_user':
			$username = md5($_SESSION['username']);

			$query = "SELECT * FROM users WHERE md5(name_user)='$username'";
			$emp_data = mysql_query($query) or die(mysql_error());
			$emp_arr = mysql_fetch_assoc($emp_data);

			if(md5($employee_password_old) == $emp_arr['password']) {
				if($employee_password_new === $employee_password_new_confirm && $employee_password_new != '') {
					$query = "UPDATE users SET password='" . md5($employee_password_new) . "' WHERE md5(name_user)='$username'";
					if(mysql_query($query)) {
						header("Location: ../main.php?page=ui_global_settings&success=true&changes=personal");
						break;
					} else {
						echo mysql_error();
					}
				} else {
					header("Location: ../main.php?page=ui_global_settings&error=pass_confirm");
					break;
				}
			} else {
				header("Location: ../main.php?page=ui_global_settings&error=pass_old");
				break;
			}
			break;
		
		case 'settings_company':
			/*
			company_info_DBA
			company_info_street
			company_info_city
			company_info_state
			company_info_zip
			company_info_phone
			company_info_email
			company_info_website
			*/

			$query = "UPDATE company_info SET name='" . addslashes($company_info_DBA) . "', street='" . addslashes($company_info_street) . "', city='" . addslashes($company_info_city) . "', state='" . addslashes($company_info_state) . "', zip='" . addslashes($company_info_zip) . "', phone='" . addslashes($company_info_phone) . "', email='" . addslashes($company_info_email) . "', website='" . addslashes($company_info_website) . "'";
			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=company_info");
				break;
			} else {
				echo mysql_error();
			}
			break;

		case 'settings_sales_tax':
			/*
			sales_tax_rate
			tax_services
			*/

			$sales_tax_calc = $sales_tax_rate / 100;
			$query = "UPDATE settings SET sales_tax='" . addslashes($sales_tax_calc) . "', tax_services='" . addslashes($tax_services) . "'";
			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=sales_tax");
				break;
			} else {
				echo mysql_error();
			}
			break;

		case 'settings_invoicing':
			$query = "UPDATE settings SET location_based_invoices='" . addslashes($location_based_invoicing_checkbox) . "', agreement_unpaid='" . addslashes(htmlentities($agreement_unpaid_textarea)) . "', agreement_paid='" . addslashes(htmlentities($agreement_paid_textarea)) . "'";
			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=invoicing");
				break;
			} else {
				echo mysql_error();
			}
			break;

		case 'settings_notes':
			$query = "UPDATE settings SET agreement_dropoff='" . addslashes(htmlentities($agreement_dropoff_textarea)) . "'";
			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=notes");
				break;
			} else {
				echo mysql_error();
			}
			break;

		case 'settings_referrals':
			$query = "UPDATE settings SET print_invoice_referral='" . $print_referrals_invoice . "', invoice_referral_text='". addslashes(htmlentities($referral_text_textarea)) . "'";
			if(mysql_query($query)) {
				header("Location: ../main.php?page=ui_global_settings&success=true&changes=referrals");
				break;
			} else {
				echo mysql_error();
			}
			break;

		default:
			header("Location: ../main.php?page=ui_global_settings&changes=" . $current_view);
			break;
	}
?>