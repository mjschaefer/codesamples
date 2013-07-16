<?php
	header('HTTP/1.0 404 not found'); 
?>
<?php
	function curPageURL() {
		$pageURL = '';
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"> 
<html><head> 
<title>404 Not Found</title> 
</head><body> 
<h1>Not Found</h1> 
<p>The requested URL <?php echo curPageURL(); ?> was not found on this server.</p> 
</body></html> 