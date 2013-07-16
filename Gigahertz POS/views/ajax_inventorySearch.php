<?php
	include('../includes/database.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	if(!isset($_GET['from_scanner'])) {
		$_GET['from_scanner'] = 0;
	}
	if(!isset($_GET['target'])) {
		$_GET['target'] = 0;
	}

	$from_scanner = $_GET['from_scanner'];
	$query = addslashes($_GET['query']);
	$target = $_GET['target'];

	if(!$from_scanner) {
		//by parts
		$mysql_q = "SELECT id,title,msrp,price,description,class,subclass,stock FROM inventory WHERE title LIKE '%$query%' AND stock > 0 ORDER BY title LIMIT 5";
		$inventory_data = mysql_query($mysql_q) or die(mysql_error());

		while($inventory_arr = mysql_fetch_assoc($inventory_data)) {
			if($inventory_arr['class'] != 'Services') {
				$item_taxable = 1;
			} else {
				$item_taxable = 0;
			}

			echo '<a href="#" class="inventory_item" onclick="fill_inventory(';		
				//echo '\'' . addslashes($cust_arr['type']) . '\',';
				echo '\'' . addslashes($inventory_arr['id']) . '\',';
				echo '\'' . $target . '\',';
				echo '\'' . addslashes($inventory_arr['title']) . '\',';
				echo '\'' . addslashes($inventory_arr['msrp']) . '\',';
				echo '\'' . addslashes($inventory_arr['price']) . '\',';
				echo '\'' . addslashes('1') . '\',';	//quantity
				echo '\'' . addslashes(htmlentities($inventory_arr['description'])) . '\',';
				echo '\'' . addslashes(htmlentities($inventory_arr['class'])) . '\',';
				echo '\'' . addslashes(htmlentities($inventory_arr['subclass'])) . '\',';
				echo '\'' . $item_taxable . '\','; //taxable
				echo '\'0\',';	//discount
				echo '\'' . $inventory_arr['stock'] . '\''; //current stock
			echo ', 0);return false;">' . $inventory_arr['title'] . ' - $ ' . $inventory_arr['price'] . '</a>';
		}

		//by description
		$mysql_q = "SELECT id,title,msrp,price,description,class,subclass,stock FROM inventory WHERE description LIKE '%$query%' AND class != 'Services' ORDER BY description LIMIT 5";
		$inventory_data = mysql_query($mysql_q) or die(mysql_error());

		while($inventory_arr = mysql_fetch_assoc($inventory_data)) {
			if($inventory_arr['class'] != 'Services') {
				$item_taxable = 1;
			} else {
				$item_taxable = 0;
			}

			echo '<a href="#" class="inventory_item" onclick="fill_inventory(';		
				//echo '\'' . addslashes($cust_arr['type']) . '\',';
				echo '\'' . addslashes($inventory_arr['id']) . '\',';
				echo '\'' . $target . '\',';
				echo '\'' . addslashes($inventory_arr['title']) . '\',';
				echo '\'' . addslashes($inventory_arr['msrp']) . '\',';
				echo '\'' . addslashes($inventory_arr['price']) . '\',';
				echo '\'' . addslashes('1') . '\',';	//quantity
				echo '\'' . addslashes($inventory_arr['description']) . '\',';
				echo '\'' . addslashes(htmlentities($inventory_arr['class'])) . '\',';
				echo '\'' . addslashes(htmlentities($inventory_arr['subclass'])) . '\',';
				echo '\'' . $item_taxable . '\','; //taxable
				echo '\'0\',';	//discount
				echo '\'' . $inventory_arr['stock'] . '\''; //current stock
			echo ', 0);return false;">' . $inventory_arr['description'] . ' - $ ' . $inventory_arr['price'] . '</a>';
		}

		//by barcode
		$mysql_q = "SELECT id,title,msrp,price,description,class,subclass,stock FROM inventory WHERE upc LIKE '%$query%' AND stock > 0 ORDER BY title LIMIT 5";
		$inventory_data = mysql_query($mysql_q) or die(mysql_error());

		while($inventory_arr = mysql_fetch_assoc($inventory_data)) {
			if($inventory_arr['class'] != 'Services') {
				$item_taxable = 1;
			} else {
				$item_taxable = 0;
			}

			echo '<a href="#" class="inventory_item" onclick="fill_inventory(';		
				//echo '\'' . addslashes($cust_arr['type']) . '\',';
				echo '\'' . addslashes($inventory_arr['id']) . '\',';
				echo '\'' . $target . '\',';
				echo '\'' . addslashes($inventory_arr['title']) . '\',';
				echo '\'' . addslashes($inventory_arr['msrp']) . '\',';
				echo '\'' . addslashes($inventory_arr['price']) . '\',';
				echo '\'' . addslashes('1') . '\',';	//quantity
				echo '\'' . addslashes(htmlentities($inventory_arr['description'])) . '\',';
				echo '\'' . addslashes(htmlentities($inventory_arr['class'])) . '\',';
				echo '\'' . addslashes(htmlentities($inventory_arr['subclass'])) . '\',';
				echo '\'' . $item_taxable . '\','; //taxable
				echo '\'0\',';	//discount
				echo '\'' . $inventory_arr['stock'] . '\''; //current stock
			echo ', 0);return false;">' . $inventory_arr['title'] . ' - $ ' . $inventory_arr['price'] . '</a>';
		}

		//services
		$mysql_q = "SELECT id,title,msrp,price,description,class,subclass,stock FROM inventory WHERE title LIKE '%$query%' AND class='Services' ORDER BY title LIMIT 5";
		$inventory_data = mysql_query($mysql_q) or die(mysql_error());

		while($inventory_arr = mysql_fetch_assoc($inventory_data)) {
			if($inventory_arr['class'] != 'Services') {
				$item_taxable = 1;
			} else {
				$item_taxable = 0;
			}

			echo '<a href="#" class="inventory_item" onclick="fill_inventory(';		
				//echo '\'' . addslashes($cust_arr['type']) . '\',';
				echo '\'' . addslashes($inventory_arr['id']) . '\',';
				echo '\'' . $target . '\',';
				echo '\'' . addslashes($inventory_arr['title']) . '\',';
				echo '\'' . addslashes($inventory_arr['msrp']) . '\',';
				echo '\'' . addslashes($inventory_arr['price']) . '\',';
				echo '\'' . addslashes('1') . '\',';	//quantity
				echo '\'' . addslashes($inventory_arr['description']) . '\',';
				echo '\'' . addslashes(htmlentities($inventory_arr['class'])) . '\',';
				echo '\'' . addslashes(htmlentities($inventory_arr['subclass'])) . '\',';
				echo '\'' . $item_taxable . '\','; //taxable
				echo '\'0\',';	//discount
				echo '\'' . $inventory_arr['stock'] . '\''; //current stock
			echo ', 0);return false;">' . $inventory_arr['title'] . ' - $ ' . $inventory_arr['price'] . '</a>';
		}		
	}

	if($from_scanner) {
		//by barcode
		$mysql_q = "SELECT id,title,msrp,price,description,class,subclass,stock FROM inventory WHERE upc='$query' AND stock > 0 ORDER BY upc LIMIT 1";
		$inventory_data = mysql_query($mysql_q) or die(mysql_error());

		if(mysql_num_rows($inventory_data) == 0) {
			echo '0';
			exit;
		}

		while($inventory_arr = mysql_fetch_assoc($inventory_data)) {
			if($inventory_arr['class'] != 'Services') {
				$item_taxable = 1;
			} else {
				$item_taxable = 0;
			}
			//echo '<a href="#" class="inventory_item" onclick="alert(\'uh oh\');return false;">bleh</a>';
			echo '<a href="#" class="inventory_item" onclick="fill_inventory(';		
				//echo '\'' . addslashes($cust_arr['type']) . '\',';
				echo '\'' . addslashes($inventory_arr['id']) . '\',';
				echo '\'' . $target . '\',';
				echo '\'' . addslashes($inventory_arr['title']) . '\',';
				echo '\'' . addslashes($inventory_arr['msrp']) . '\',';
				echo '\'' . addslashes($inventory_arr['price']) . '\',';
				echo '\'' . addslashes('1') . '\',';	//quantity
				echo '\'' . addslashes(htmlentities($inventory_arr['description'])) . '\',';
				echo '\'' . addslashes(htmlentities($inventory_arr['class'])) . '\',';
				echo '\'' . addslashes(htmlentities($inventory_arr['subclass'])) . '\',';
				echo '\'' . $item_taxable . '\','; //taxable
				echo '\'0\',';	//discount
				echo '\'' . $inventory_arr['stock'] . '\''; //current stock
			echo ', 1);return false;">' . $inventory_arr['title'] . ' - $ ' . $inventory_arr['price'] . '</a>';			
		}		
	}
?>