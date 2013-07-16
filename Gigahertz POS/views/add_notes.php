<?php
	session_start();

	$location = $_SESSION['location'];
	$employee = $_SESSION['name'];
	
	extract($_POST);

	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	/*
		$cust_name
		$cust_business
		$cust_street
		$cust_city
		$cust_state
		$cust_zip
		$cust_phone_primary
		$cust_phone_secondary
		$cust_id

		$pc_manufacturer
		$pc_model
		$pc_os
		$pc_type

		$problems
		$items_left
		$labor_to_perform
		$notes
	*/

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$foundAvailID = false;


	$entered_by = $_SESSION['name'];

	//if employee did not search for existing customer
	if(!$cust_id) {
		//echo 'did not find customer<br />';
		//keep trying to find a new customer ID
		while(!$foundAvailID) {
			//generate random customer ID
			$random = rand(100000, 999999);

			//check to see if this customer exists
			if(mysql_num_rows(mysql_query("SELECT * FROM customers WHERE id = '$random'"))){
				//customer exists, so we try again by keeping foundAvailID false
			} else {
				//they don't exist, so we set foundAvailID to true, set our cust_id to the random #, and add this customer to the customer list
				$foundAvailID = true;
				$cust_id = $random;

				$query = "INSERT INTO customers VALUES('" . addslashes($cust_id) . "', '" . addslashes($cust_name) . "', '" . addslashes($cust_business) . "', '" . addslashes(null) . "', '" . addslashes($cust_street) . "', '" . addslashes($cust_city) . "', '" . addslashes($cust_state) . "', '" . addslashes($cust_zip) . "', '" . addslashes($cust_phone_primary) . "', '" . addslashes($cust_phone_secondary) . "', '" . addslashes($cust_type) . "')";

				mysql_query($query) or die("Cannot Execute this Query <br /> '$query' <br />" . mysql_error());
			}
		}
	} else {
		//let's update the customer
		$query = "UPDATE customers SET name='" . addslashes($cust_name) . "', business='" . addslashes($cust_business) . "', street='" . addslashes($cust_street) . "', city='" . addslashes($cust_city) . "', state='" . addslashes($cust_state) . "', zip='" . addslashes($cust_zip) . "', phone_primary='" . addslashes($cust_phone_primary) . "', phone_secondary='" . addslashes($cust_phone_secondary) . "', type='" . addslashes($cust_type) . "' WHERE id='" . $cust_id . "'";

		mysql_query($query) or die("Cannot Execute this Query <br /> '$query' <br />" . mysql_error());
	}

	//save the entirity of the dropoff
	$query = "INSERT INTO notes(`customer_id`,
								`invoice_id`,
								`pc_brand`,
								`pc_model`,
								`pc_serial`,
								`pc_os`,
								`pc_type`,
								`pc_password`,
								`date_entered`,
								`date_completed`,
								`problems`,
								`items_left`,
								`status`,
								`complete`,
								`completed_by`,
								`location`,
								`entered_by`) 
							VALUES('" . addslashes($cust_id) . "',
								   '0',
								   '" . addslashes($pc_manufacturer) . "',
								   '" . addslashes($pc_model) . "',
								   '" . addslashes($pc_serial) . "',
								   '" . addslashes($pc_os) . "',
								   '" . addslashes($pc_type) . "',
								   '" . addslashes($pc_password) . "',
								   '" . addslashes($date) . "',
								   '',
								   '" . addslashes(htmlentities($problems)) . "',
								   '" . addslashes(htmlentities($items_left)) . "',
								   'working_on',
								   '0',
								   '',
								   '" . $location . "',
								   '$employee')";
	mysql_query($query) or die("Cannot Save main note info:<br /> Query -- <br /> '$query' <br />" . mysql_error());

	//have to find the note that was just entered so we can insert into the added notes
	$found_note_id = mysql_insert_id();

	//save the associated note to the notes table
	if($notes != '') {
		$query = "INSERT INTO notes_added(`date`,
										  `content`,
										  `note_id`,
										  `entered_by`) VALUES('" . addslashes($date) . "',
										  					   '" . addslashes(htmlentities($notes)) . "',
										  					   '$found_note_id',
										  					   '$entered_by')";

	mysql_query($query) or die("Cannot Save added note:<br /> Query -- <br /> '$query' <br />" . mysql_error());		
	}

	header("Location: ../main.php?page=ui_notes_edit");
?>