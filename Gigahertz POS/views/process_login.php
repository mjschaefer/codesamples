<?php
	include("../includes/database.inc.php");

	$username = md5($_POST['username']);
	$password = md5($_POST['password']);

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$query = "SELECT * FROM users WHERE md5(name_user)='$username' AND password='$password'";

	$result = mysql_query($query) or die(mysql_error());

	//echo $query;

	if (mysql_num_rows($result)!=0) {
		//mark as valid user
		$user_arr = mysql_fetch_assoc($result);

		session_start();

		$_SESSION['registered'] = true;
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['name'] = $user_arr['name_real'];
		$_SESSION['location'] = $_POST['location'];
		$_SESSION['clearance'] = $user_arr['clearance'];
		$_SESSION['hire_date'] = $user_arr['hire_date'];


		$query = "SELECT * FROM locations WHERE title='" . $_POST['location'] . "'";
		$location_data = mysql_query($query) or die(mysql_error());
		$location_arr = mysql_fetch_assoc($location_data);

		$_SESSION['location_street'] = $location_arr['street'];
		$_SESSION['location_city'] = $location_arr['city'];
		$_SESSION['location_state'] = $location_arr['state'];
		$_SESSION['location_zip'] = $location_arr['zip'];
		$_SESSION['location_phone'] = $location_arr['phone'];
		$_SESSION['location_email'] = $location_arr['email'];
		$_SESSION['location_website'] = $location_arr['website'];

		$_SESSION['refresh_settings'] = true;


		/*$query = "SELECT * FROM settings";
		$settings_data = mysql_query($query) or die(mysql_error());
		$settings_arr = mysql_fetch_assoc($settings_data);

		$_SESSION['location_based_invoices'] = $settings_arr['location_based_invoices'];*/

		setcookie("location", $_POST['location'], time()+60*60*24*30, "/");

		header("Location: ../main.php");
	    exit;
	}

	//if the code reaches this part then the login failed
	//wrong username/password

	header("Location: ../index.php?business=$login&fail=true");
?>