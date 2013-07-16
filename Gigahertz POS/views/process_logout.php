<?php
	session_start();
	$business = $_SESSION['company_username'];
	session_destroy();

	header("Location: ../index.php?business=$business&logout=true");
?>