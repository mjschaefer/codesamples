<script>
	<?php
		if(!isset($_GET['location'])) {
			$location = $_SESSION['location'];
		}

		$page_title_html = '<img src="./img/icon_customer_48x48.png" /><h2 class="title">Tax Exemptions</h2>';
	?>

	$(document).ready(function() {
		$("#page_title").html('<?php echo $page_title_html; ?>');
	});
</script>

<?php	
	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$query = "SELECT * FROM customers WHERE `business_id` != '' ORDER BY `name`";
	$exempt_data = mysql_query($query) or die(mysql_error());
?>

<div id="wrapper_table">
	<table id="data_table">
		<tr>
			<th>&nbsp;</th>
			<th>Business Name</th>
			<th>Contact Name</th>
			<th>TID</th>
		</tr>
		<?php
			while($exempt_arr = mysql_fetch_assoc($exempt_data)) {
				echo '<tr>';
					echo '<td><a href="?page=ui_customers&customer=' . $exempt_arr['id'] . '"><img src="./img/icon_business_16x16.png" /></a></td>';
					echo '<td><a href="?page=ui_customers&customer=' . $exempt_arr['id'] . '">' . $exempt_arr['business'] . '</a></td>';
					echo '<td><a href="?page=ui_customers&customer=' . $exempt_arr['id'] . '">' . $exempt_arr['name'] . '</a></td>';
					echo '<td><a href="?page=ui_customers&customer=' . $exempt_arr['id'] . '">' . $exempt_arr['business_id'] . '</a></td>';
				echo '</tr>';
			}
		?>
	</table>
</div>