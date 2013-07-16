<?php
	if(!isset($_SESSION)) {
		session_start();		
	}
	
	if($_SESSION['refresh_settings']) {
		mysql_connect($server, $login, $pass) or die("Unable to Connect");
		mysql_select_db($db) or die("Unable to select database");

		$query = "SELECT * FROM settings";
		$settings_data = mysql_query($query) or die(mysql_error());
		$settings_arr = mysql_fetch_assoc($settings_data);

		$query = "SELECT * FROM company_info";
		$comp_data = mysql_query($query) or die(mysql_error());
		$comp_arr = mysql_fetch_assoc($comp_data);		

		$_SESSION['location_based_invoices'] = $settings_arr['location_based_invoices'];

		$_SESSION['company_name'] = $comp_arr['name'];
		$_SESSION['company_street'] = $comp_arr['street'];
		$_SESSION['company_city'] = $comp_arr['city'];
		$_SESSION['company_state'] = $comp_arr['state'];
		$_SESSION['company_zip'] = $comp_arr['zip'];
		$_SESSION['company_phone'] = $comp_arr['phone'];
		$_SESSION['company_email'] = $comp_arr['email'];
		$_SESSION['company_website'] = $comp_arr['website'];

		$_SESSION['global_sales_tax'] = $settings_arr['sales_tax'];
		$_SESSION['global_tax_services'] = $settings_arr['tax_services'];

		$_SESSION['agreement_unpaid'] = $settings_arr['agreement_unpaid'];
		$_SESSION['agreement_paid'] = $settings_arr['agreement_paid'];
		$_SESSION['agreement_dropoff'] = $settings_arr['agreement_dropoff'];

		$_SESSION['invoice_referral_text'] = $settings_arr['invoice_referral_text'];
		$_SESSION['print_invoice_referrals'] = $settings_arr['print_invoice_referral'];

		$_SESSION['refresh_settings'] = false;
	}

	$company_name = $_SESSION['company_name'];
	$company_street = $_SESSION['company_street'];
	$company_city = $_SESSION['company_city'];
	$company_state = $_SESSION['company_state'];
	$company_zip = $_SESSION['company_zip'];
	$company_phone = $_SESSION['company_phone'];
	$company_email = $_SESSION['company_email'];
	$company_website = $_SESSION['company_website'];

	$agreement_unpaid = $_SESSION['agreement_unpaid'];

	$agreement_paid = $_SESSION['agreement_paid'];
	$agreement_dropoff = $_SESSION['agreement_dropoff'];

	$invoice_referral_text = $_SESSION['invoice_referral_text'];
	$print_invoice_referrals = $_SESSION['print_invoice_referrals'];

	$location_based_invoices = $_SESSION['location_based_invoices'];

	date_default_timezone_set('America/Chicago');
	
	$date = time();
?>

<script>
	var global_tax = <?php echo $_SESSION['global_sales_tax']; ?>;
	var global_tax_services = <?php echo $_SESSION['global_tax_services']; ?>;
</script>