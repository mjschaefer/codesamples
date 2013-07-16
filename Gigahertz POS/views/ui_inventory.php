<?php
	$parts_class = '';

	if(!isset($_GET['list_type'])) {
		$_GET['list_type'] = 'services';
	}

	$list_type = urldecode($_GET['list_type']);

	if($list_type == 'services') {
		$search_type = 'services';
	}

	if($list_type == 'parts') {
		if(!isset($_GET['parts_class'])) {
			$_GET['parts_class'] = 'All';
		}

		$parts_class = urldecode($_GET['parts_class']);

		$search_type = $parts_class;
	}
?>

<script>
	$(document).ready(function() {

		$("#page_title").html('<img src="./img/icon_inventory_48x48.png" /><h2 class="title">Inventory</h2>');

		<?php
			$nav_adder = '<span id="h5_container">';
			if($list_type == 'services') {
				$nav_adder .= '<h5>';
					$nav_adder .= '<a href="?page=ui_inventory&list_type=services" class="selected">Services</a>';
					$nav_adder .= '<a href="?page=ui_inventory&list_type=parts">Parts</a>';
				$nav_adder .= '</h5>';
				$nav_adder .= '<h5>';
					$nav_adder .= '<a href="#" class="selected">All</a>';
				$nav_adder .= '</h5>';			
			} else {
				$pc_parts_classes = array(
									  'All'
									);
				$query = "SELECT DISTINCT class FROM inventory WHERE class != 'Services' ORDER BY class";
				$class_set = mysql_query($query) or die(mysql_error());
				
				while($class_array = mysql_fetch_assoc($class_set)) {
					$pc_parts_classes[] = $class_array['class'];
				}

				$nav_adder .= '<h5>';
					$nav_adder .= '<a href="?page=ui_inventory&list_type=services">Services</a>';
					$nav_adder .= '<a href="?page=ui_inventory&list_type=parts" class="selected">Parts</a>';
				$nav_adder .= '</h5>';
				$nav_adder .= '<h5>';
					foreach($pc_parts_classes as $k => $v) {
						$sel_adder = '';
						if($parts_class == $v) {
							$sel_adder = ' class="selected"';
						}
						$nav_adder .= '<a href="?page=ui_inventory&list_type=parts&parts_class=' . $v . '"' . $sel_adder . '>' . $v . '</a>';
					}
				$nav_adder .= '</h5>';
				//$nav_adder .= '<h5><a href="#" class="selected">Testing third row</a></h5>';
				$nav_adder .= '<h5>Show Out of Stock Items <input type="checkbox" name="show_0stock" /></h5>';
			}
			$nav_adder .= '</span>';	
		?>

		$("#page_title").append('<?php echo $nav_adder; ?>');

		<?php
			if(!isset($_GET['show0stock'])) {
				$_GET['show0stock'] = 0;	
			}

			if($_GET['show0stock']) {
				echo '$("input[name=show_0stock]").attr("checked", true);';
			}			
		?>

		$("input[name=show_0stock]").change(function() {
			var loc = String(window.location);
			loc = loc.replace("&show0stock=1", "");
			loc = loc.replace("&show0stock=0", "");

			//alert(loc);

			if($("input[name=show_0stock]").is(":checked")) {
				window.location.replace(loc + "&show0stock=1");
			} else {
				window.location.replace(loc + "&show0stock=0");
			}
		});

		$(".a_inv").each(function () {
			$(this).click(function() {
				return false;
			});
		});

		$(".a_inventory_mod").click(function() {
			$.ajax({
				url: "./views/" + $(this).attr("href") + "&inv_id=" + $(this).attr("inv_id"),
				success: function(data) {

					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#process_button").click(function() {
						$("form[name=inventory]").submit();
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

<?php
	$description_limiter = 60;
	
	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	if($list_type == 'services') {
		$query = "SELECT * FROM inventory WHERE class = 'services' ORDER BY title";
	} else {
		$query_adder = '';
		if(!$_GET['show0stock']) {
			$query_adder = " AND stock>0";
		}

		if($parts_class == 'All') {
			$query = "SELECT * FROM inventory WHERE class != 'services'" . $query_adder . " ORDER BY title";
		} else {
			$query = "SELECT * FROM inventory WHERE class = '$search_type'" . $query_adder . " ORDER BY title";
		}
	}

	$inventory_data = mysql_query($query) or die(mysql_error() . '<br />' . $query);
?>

<div id="wrapper_table">
	<table id="data_table">
		<tr>
			<th>Item</th>
			<th>Description</th>
			<?php
				if($list_type != 'services') {
					echo '<th>In Stock</th>';
				}
			?>
			<th>Sublass</th>
			<th>Price</th>
			<th>&nbsp;</th>
			<th style="margin: 0; padding: 0; text-align: center;"><a href="ajax_inventory.php?cond=add&<?php echo 'list_type=' . $list_type . '&parts_class=' . $parts_class; ?>" class="a_inventory_mod"><img src="./img/icon_add_16x16.png" style="display: block;" /></a></th>
		</tr>
		<?php
			while($inventory_arr = mysql_fetch_assoc($inventory_data)) {
				echo '<tr>';
					echo '<td><a href="#" class="a_inv">' . $inventory_arr['title'] . '</a></td>';
					echo '<td><a href="#" class="a_inv">';
					if(strlen($inventory_arr['description']) >= $description_limiter) {
						for($i = 0; $i <= $description_limiter; $i++) {
							echo $inventory_arr['description'][$i];
						}
						echo '...';
					} else {
						echo $inventory_arr['description'];
					}
					echo '</a></td>';

					if($list_type != 'services') {
						echo '<td><a href="#" class="a_inv">' . $inventory_arr['stock'] . '</a></td>';
					}

					echo '<td><a href="#" class="a_inv">' . $inventory_arr['subclass'] . '</a></td>';
					echo '<td class="price_col"><a href="#" class="a_inv price"><span class="cash_sign">$</span> ' . $inventory_arr['price'] . '</a></td>';
					echo '<td><a href="ajax_inventory.php?cond=edit&list_type=' . $list_type . '&parts_class=' . $parts_class . '" inv_id="' . $inventory_arr['id'] . '" class="a_inventory_mod"><img src="./img/icon_edit_16x16.png" style="display: block;" /></a></td>';
					echo '<td><a href="./views/delete_inventory.php?inventory_id=' . $inventory_arr['id'] . '" onclick="return confirm(\'Are you sure you want to delete this inventory item?\');"><img src="./img/icon_delete_16x16.png" style="display: block;" /></a></td>';
				echo '</tr>';
			}
		?>
	</table>
</div>