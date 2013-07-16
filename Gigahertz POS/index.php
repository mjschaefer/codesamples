<?php
	session_start();

	if(isset($_SESSION['registered'])) {
		if($_SESSION['registered']) {
			header("Location: main.php");
			exit;
		}
	}

	if(!isset($_POST['business'])) {
		$_POST['business'] = 'GigahertzPC';
	}
	
	/*
	mysql_connect('localhost', 'userFetcher', 'dsmSwcftKEKbDZva') or die("Unable to Connect");
		if(isset($_COOKIE['guid']) && isset($_COOKIE['company'])) {
			if(!isset($_POST['remember_me']) && isset($_POST['business'])) {				
				setcookie("guid", '', time() - 3600, "/");
				setcookie("company", '', time() - 3600, "/");
			} else {
				$guid = md5($_COOKIE['guid']);
				$company = md5(htmlentities(addslashes($_COOKIE['company'])));

				$query = "SELECT customer_business_name FROM pos_registrations.users WHERE MD5(customer_business_username) = '$company' AND MD5(login_guid) = '$guid'";
				$user_data = mysql_query($query) or die(mysql_error());
				$user_count = mysql_num_rows($user_data);

				if($user_count == 0) {
					header("Location: ../index.php?error=nobiz");
					exit;		
				}

				$bizName = $company;				
			}

		}

		if(!isset($bizName)) {
			if(!isset($_POST['business'])) {
				header("Location: ../index.php");
				exit;			
			}

			$bizName = md5(htmlentities(addslashes($_POST['business'])));
		}

		if(isset($bizName)) {
			$query = "SELECT customer_business_username, customer_business_password, customer_business_name FROM pos_registrations.users WHERE md5(customer_business_username) = '$bizName' LIMIT 1";
			$user_data = mysql_query($query) or die(mysql_error());
			$user_count = mysql_num_rows($user_data);

			if($user_count == 0) {
				header("Location: ../index.php?error=nobiz");
				exit;		
			}
			
			$user_array = mysql_fetch_row($user_data);

			$_SESSION['company_username'] = $user_array[0];
			$_SESSION['company_password'] = $user_array[1];	
			$company_fullname = $user_array[2];

			if(isset($_POST['remember_me'])) {
				$guid_gen = uniqid('', true);
				$comp = $_POST['business'];

				$query = "UPDATE pos_registrations.users SET login_guid = '$guid_gen' WHERE customer_business_username = '$comp'";
				mysql_query($query) or die(mysql_error());

				setcookie("guid", $guid_gen, time()+60*60*24*30, "/");
				setcookie("company", $_POST['business'], time()+60*60*24*30, "/");
			}	
		} else {
			header("Location: ../index.php");
			exit;			
		}
	mysql_close();
	*/

	//this is to manually override everything for gigahertz
	$_SESSION['company_username'] = '[username for mysql]';
	$_SESSION['company_password'] = '[password for mysql]';	
	$company_fullname = 'GigahertzPC';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?php echo $company_fullname; ?> POS</title>

		<link rel="stylesheet" href="./css/index.css" />

		<link rel="shortcut icon" href="favicon.ico" />

		<script src="./js/jquery-1.6.min.js"></script>
		<script src="./js/jquery.validate.js"></script>
		
		<script>
			//---------------------------------------------------------------------------------------------------------//
			//---------BEGIN JQUERY -----------------------------------------------------------------------------------//
			//---------------------------------------------------------------------------------------------------------//

			jQuery.validator.messages.required = "";

			//extend support check for placeholder text
			jQuery(function() {
				jQuery.support.placeholder = false;
				test = document.createElement('input');
				if('placeholder' in test) jQuery.support.placeholder = true;
			});

			$(document).ready(function() {
				$('#form_login').validate();

				$('input[name=username]').focus();

				$('input').each(function () {
					$(this).attr("autocomplete", "off");
				});

				if(!$.support.placeholder) { 
					var active = document.activeElement;
					$(':text').focus(function () {
						if ($(this).attr('placeholder') != '' && $(this).val() == $(this).attr('placeholder')) {
							$(this).val('').removeClass('hasPlaceholder');
						}
					}).blur(function () {
						if ($(this).attr('placeholder') != '' && ($(this).val() == '' || $(this).val() == $(this).attr('placeholder'))) {
							$(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
						}
					});
					$(':text').blur();
					$(active).focus();
					$('form').submit(function () {
						$(this).find('.hasPlaceholder').each(function() { $(this).val(''); });
					});
				}
			});
		</script>

		<?php
			include('./includes/database.inc.php');

			mysql_connect($server, $login, $pass) or die("Unable to Connect");
			mysql_select_db($db) or die("Unable to select database");

			$query = "SELECT * FROM locations";
			$location_data = mysql_query($query) or die(mysql_error());
		?>
	</head>

	<body>
		<div id="login_wrapper">
			<?php
				if($_POST['business'] == 'sandbox') {					
					echo '<span style="text-align: center; display: block; margin-bottom: 10px; font-size: 12px;">Log into the demo with these credentials<br /><br /><b>username:</b> jschmoe<br /><b>password:</b> password</span>';
				}
			?>
			<div id="login">
				<h2><img src="./img/icon_frequency_32x32.png" /><?php echo $company_fullname; ?></h2>
				<?php
					if(isset($_GET['fail'])) {
						echo '<h5 id="fail">* Fail</h5>';
					}
					if(isset($_GET['logout'])) {
						echo '<h5 id="fail">Logged Out</h5>';
					}
				?>
				
				<form id="form_login" method="POST" action="./views/process_login.php">
					<input size="35" id="user" name="username" class="required text" placeholder="Username" /><br />
					<input size="35" id="pass" name="password" class="required text" placeholder="Password" type="password" /><br />
					<select name="location">
						<?php
							while($location_array = mysql_fetch_assoc($location_data)) {
								echo '<option value="' . $location_array['title'] . '"';								
									if(isset($_COOKIE['location'])) {
										if($_COOKIE['location'] == $location_array['title']) {
											echo ' selected="selected"';
										}
									}
								echo '>' . $location_array['title'] . '</option>';
							}
						?>
					</select>

					<input type="submit" value="Login" style="float: right;" />
				</form>
			</div>
			<div id="logout_business"><a href="../logout_business.php">Not your business?</a></div>
		</div>
	</body>
</html>