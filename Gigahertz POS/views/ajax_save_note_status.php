<?php	
	session_start();

	include('../includes/database.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$query = "UPDATE notes SET status='" . addslashes(htmlentities($_GET['status'])) . "' WHERE id='" . addslashes(htmlentities($_GET['note_id'])) . "'";

	if(mysql_query($query)) {
		echo '<div id="status_flash_success" class="status_fade">SAVED</div>';
	} else {
		echo '<div id="status_flash_fail" class="status_fade">COULD NOT SAVE: ' . mysql_error() . '</div>';
	}
?>