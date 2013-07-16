<?php		
	session_start();
	
	if(!$_SESSION['registered']) {
		header("Location: index.php");
		exit;
	}	

	if(!isset($_GET['page'])) {
		$_GET['page'] = 'ui_notes_new';
	}

	extract($_GET);
	//phpinfo();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		
		<title>Gigahertz POS</title>

		<link rel="shortcut icon" href="favicon.ico" />

		<link rel="stylesheet" href="../min/?g=<?php echo 'css_' . $_GET['page']; ?>" />
		<script src="../min/?g=js_globals"></script>
		<script src="../min/?g=<?php echo 'js_' . $_GET['page']; ?>"></script>

		<script>
			//extend support check for placeholder text
			jQuery(function() {
				jQuery.support.placeholder = false;
				test = document.createElement('input');
				if('placeholder' in test) jQuery.support.placeholder = true;
			});

			//--- disable the autocomplete for browsers all around --//
			$(document).ready(function() {
				$('input').each(function () {
					$(this).attr("autocomplete", "off");
				});
				
				checkPlaceholders();
			});

			function checkPlaceholders() {
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
			}
		</script>
	</head>
	<body> 
		<?php include('./includes/database.inc.php'); ?>
		<?php include('./includes/globals.inc.php'); ?>

		<nav>
			<?php include('menu.php'); ?>
		</nav>

		<section>
			<div id="page_title"></div>
			<?php
				if(file_exists('./views/' . $_GET['page'] . '.php')) {
					include(htmlentities('./views/' . $_GET['page'] . '.php', ENT_QUOTES, 'UTF-8')); 								
				} else {
					echo '<div id="wrapper_main">';
						echo 'Requested Page Doesn\'t exist...yet';
					echo '</div>';
				}
			?>
		</section>

		<div class="footer">
			<?php include('footer.php'); ?>
		</div>
	</body>
</html>