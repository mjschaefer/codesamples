<?php
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$cert_id = $_GET['cert_id'];

	$query = "SELECT * FROM gift_certs WHERE id=$cert_id";
	$giftcert_data = mysql_query($query) or die(mysql_error());
	$giftcert_arr = mysql_fetch_assoc($giftcert_data);
?>

<script>
    $(document).ready(function() {
		window.print();
	});
</script>

<style>
	body {
		font-family: sans-serif;
	}

	#gift_wrapper {
		width: 650px;
		margin: 10px;
		padding: 15px;
		border: 3px dashed black;
		text-align: right;
		overflow: hidden;
	}

	#expires {
		float: right;
	}

	#cert_id_wrapper {
		float: left;
	}

	#cert_id_barcode {
		font-family: "Free 3 of 9";
		font-size: 32px;
	}
	#cert_id_plaintext {
		display: block;
		text-align: center;
	}

	h1 {
		margin: 0;
		margin-bottom: 5px;
		font-size: 18pt;
	}

	h2 {
		margin: 0;
		color: gray;
		font-size: 28pt;
		font-weight: normal;
	}

	h3 {
		margin: 0;
		font-weight: normal;
		font-size: 14pt;
	}
</style>

<div id="gift_wrapper">
	<img src="../img/logo_company_invoice.png" />
	<h2>Gift Certificate</h2>
	<h1>Good for one (1) <?php echo $giftcert_arr['for']; ?></h1>
	<h3><?php echo $giftcert_arr['description']; ?></h3>
	<h3>A $<?php echo $giftcert_arr['amount']; ?> value.</h3>
	<br />
	<div id="expires">
		Valid until: <?php echo date("m-d-Y",  $giftcert_arr['expires']); ?>
	</div>

	<div id="cert_id_wrapper">
		<div id="cert_id_barcode">
			<?php
				echo '*' . $giftcert_arr['id'] . '*';
			?>
		</div>
		<div id="cert_id_plaintext">
			<?php
				for($i = 0; $i < 8; $i++) {
					if($i == 4) {
						echo '-';
					}

					echo $giftcert_arr['id'][$i];
				}
			?>
		</div>
	</div>
</div>