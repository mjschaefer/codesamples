<?php	
	
?>

<script>
	$(document).ready(function() {
		$("#page_title").html('<img src="./img/icon_inventory_48x48.png" /><h2 class="title">Gift Certificates</h2>');

		$(".a_cert_add").click(function() {		
			$.ajax({
				url: "./views/" + $(this).attr("href"),
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#process_button").click(function() {
						$("form[name=giftcert]").submit();
					});

					$("#cancel_button").click(function() {
						$("#dropdown").slideUp('fast', function() {
							$("#dropdown").html('');
						});

						return false;
					});
				}
			});
			return false;
		});

		$(".a_giftcert_mod").click(function() {		
			$.ajax({
				url: "./views/" + $(this).attr("href") + "&cert_id=" + $(this).attr("cert_id"),
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#process_button").click(function() {
						$("form[name=giftcert]").submit();
					});

					$("#cancel_button").click(function() {
						$("#dropdown").slideUp('fast', function() {
							$("#dropdown").html('');
						});

						return false;
					});
				}
			});
			return false;
		});
	});
</script>

<div id="wrapper_table">
	<table id="data_table">
		<tr>
			<th>Number</th>
			<th>For</th>
			<th>Issued</th>
			<th>Expires</th>
			<th>&nbsp;</th>
			<th style="margin: 0; padding: 0"><a href="ajax_giftcert.php?cond=add" class="a_cert_add"><img src="./img/icon_add_16x16.png" style="display: block;" /></a></th>
		</tr>
		<?php
			mysql_connect($server, $login, $pass) or die("Unable to Connect");
			mysql_select_db($db) or die("Unable to select database");

			$query = "SELECT * FROM gift_certs WHERE active=1 ORDER BY issued";
			$giftcert_data = mysql_query($query) or die(mysql_error());
		?>
		<?php
			while($giftcert_arr = mysql_fetch_assoc($giftcert_data)) {
				$giftcert_date_issued = date('m-d-y', (int)$giftcert_arr['issued']);
				$giftcert_date_expires = date('m-d-y', (int)$giftcert_arr['expires']);

				echo '<tr>';
					echo '<td><a href="./views/ui_gift_print.php?cert_id=' . $giftcert_arr['id'] . '" target="_blank" class="a_giftcert">' . $giftcert_arr['id'] . '</a></td>';
					echo '<td><a href="./views/ui_gift_print.php?cert_id=' . $giftcert_arr['id'] . '" target="_blank" class="a_giftcert">' . $giftcert_arr['for'] . '</a></td>';
					echo '<td><a href="./views/ui_gift_print.php?cert_id=' . $giftcert_arr['id'] . '" target="_blank" class="a_giftcert">' . $giftcert_date_issued . '</a></td>';
					echo '<td><a href="./views/ui_gift_print.php?cert_id=' . $giftcert_arr['id'] . '" target="_blank" class="a_giftcert">' . $giftcert_date_expires . '</a></td>';
					echo '<td><a href="ajax_giftcert.php?cond=edit" cert_id="' . $giftcert_arr['id'] . '" class="a_giftcert_mod"><img src="./img/icon_edit_16x16.png" style="display: block;" /></a></td>';
					echo '<td><a href="./views/delete_giftcert.php?cert_id=' . $giftcert_arr['id'] . '" onclick="return confirm(\'Are you sure you want to deactivate this gift certificate?\');"><img src="./img/icon_delete_16x16.png" style="display: block;" /></a></td>';
				echo '</tr>';
			}
		?>
	</table>
</div>