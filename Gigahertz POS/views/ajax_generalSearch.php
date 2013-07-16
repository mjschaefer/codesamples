<?php
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	extract($_GET);
?>

<?php
	$mysql_q = "SELECT id,name FROM customers WHERE name LIKE '%" . addslashes($query) . "%' ORDER BY name LIMIT 5";
	$cust_data = mysql_query($mysql_q) or die(mysql_error());

	if(mysql_num_rows($cust_data)) { 
		echo '<div class="search_header"><img src="./img/icon_customer_16x16.png" /> Customers</div>';
		while($cust_arr = mysql_fetch_assoc($cust_data)) {
			echo '<a href="?page=ui_customers&customer=' . $cust_arr['id'] . '" class="search_listing">' . $cust_arr['name'] . '</a>';
		}		
	}
?>

<?php
	$mysql_q = "SELECT id,customer_id FROM notes WHERE id LIKE '" . addslashes($query) . "%' ORDER BY id DESC LIMIT 5";
	$notes_data = mysql_query($mysql_q) or die(mysql_error());

	if(mysql_num_rows($notes_data)) { 
		echo '<div class="search_header"><img src="./img/icon_note_blank_16x16.png" /> Notes</div>';
		while($notes_arr = mysql_fetch_assoc($notes_data)) {
			$cust_id = $notes_arr['customer_id'];

			$mysql_q = "SELECT name FROM customers WHERE id='$cust_id' ORDER BY name LIMIT 5";
			$cust_data = mysql_query($mysql_q) or die(mysql_error());
			$cust_arr = mysql_fetch_assoc($cust_data);

			echo '<a href="?page=ui_notes_edit&note=' . $notes_arr['id'] . '" class="search_listing">' . $notes_arr['id'] . ' - ' . $cust_arr['name'] . '</a>';
		}		
	}
?>

<?php
	$mysql_q = "SELECT id,customer_id FROM invoices WHERE id LIKE '" . addslashes($query) . "%' ORDER BY id DESC LIMIT 5";
	$invoice_data = mysql_query($mysql_q) or die(mysql_error());

	if(mysql_num_rows($invoice_data)) { 
		echo '<div class="search_header"><img src="./img/icon_invoices_unpaid_16x16.png" /> Invoices</div>';
		while($invoice_arr = mysql_fetch_assoc($invoice_data)) {
			$cust_id = $invoice_arr['customer_id'];

			$mysql_q = "SELECT name FROM customers WHERE id='$cust_id' ORDER BY name LIMIT 5";
			$cust_data = mysql_query($mysql_q) or die(mysql_error());
			$cust_arr = mysql_fetch_assoc($cust_data);

			echo '<a href="?page=ui_invoice_view&invoice=' . $invoice_arr['id'] . '" class="search_listing">' . $invoice_arr['id'] . ' - ' . $cust_arr['name'] . '</a>';
		}		
	}
?>

<?php
	$mysql_q = "SELECT id,title FROM knowledgebase WHERE title LIKE '%" . addslashes($query) . "%' OR tags LIKE '%" . addslashes($query) . "%' ORDER BY title LIMIT 5";
	$kb_data = mysql_query($mysql_q) or die(mysql_error());

	if(mysql_num_rows($kb_data)) { 
		echo '<div class="search_header"><img src="./img/icon_knowledgebase_16x16.png" /> Knowledgebase</div>';
		while($kb_arr = mysql_fetch_assoc($kb_data)) {
			echo '<a href="?page=ui_knowledgebase_view&knowledgebase=' . $kb_arr['id'] . '" class="search_listing">' . $kb_arr['title'] . '</a>';
		}		
	}

?>
