<?php
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	extract($_GET);

	if($cond == 'save') {
		extract($_POST);

		/*echo '<pre>';
		print_r($_POST);
		echo '</pre>';*/

		if(isset($monitor)) {
			$monitor = '1';			
		} else {
			$monitor = '0';
		}

		if($threshold == '') {
			$threshold = 0;
		}	

		if($condition == 'add') {
			$query = "INSERT INTO inventory(`upc`,
											`title`,
											`description`,
											`msrp`,
											`price`,
											`class`,
											`subclass`,
											`taxable`,
											`stock`,
											`monitor`,
											`threshold`,
											`ordered`) VALUES('" . addslashes($upc) . "',
															  '" . addslashes($title) . "',
															  '" . addslashes($description) . "',
															  '" . addslashes($msrp) . "',
															  '" . addslashes($price) . "',
															  '" . addslashes($class) . "',
															  '" . addslashes($subclass) . "',
															  '0',
															  '" . addslashes($stock) . "',
															  '" . addslashes($monitor) . "',
															  '" . addslashes($threshold) . "',
															  '0')";
		}

		if($condition == 'edit') {
			$query = "UPDATE inventory SET title='" . addslashes($title) . "', upc='" . addslashes($upc) . "', description='" . addslashes($description) . "', msrp='" . addslashes($msrp) . "', price='" . addslashes($price) . "', class='" . addslashes($class) . "', subclass='" . addslashes($subclass) . "', stock='" . addslashes($stock) . "', monitor='" . addslashes($monitor) . "', threshold='" . addslashes($threshold) . "' WHERE id='$inv_id'";
		}

		//echo $query;
		//exit;

		mysql_query($query) or die(mysql_error());
		header('Location: ../main.php' . $previous_page);

		exit;
	}

	if($cond == 'add') {
		$id = '';
		$title = '';
		$description = '';
		$price = '';
		$class = '';
		$subclass = '';
		$msrp = '';
		$stock = '';
		$upc = '';
		$monitor = false;
		$threshold = '';
	}
	if($cond == 'edit') {
		$query = "SELECT * FROM inventory WHERE id='$inv_id'";
		$inv_data = mysql_query($query) or die(mysql_error());
		$inv_arr = mysql_fetch_assoc($inv_data) or die(mysql_error());

		$id = $inv_arr['id'];
		$upc = $inv_arr['upc'];
		$title = $inv_arr['title'];
		$description = $inv_arr['description'];
		$price = $inv_arr['price'];
		$class = $inv_arr['class'];
		$subclass = htmlentities($inv_arr['subclass']);
		$msrp = $inv_arr['msrp'];
		$stock = $inv_arr['stock'];
		$monitor = $inv_arr['monitor'];
		$threshold = $inv_arr['threshold'];
	}

	$class_array = array(
						'Services',
						'Cables',
						'CD / DVD Burners',
						'Computer Accessories',
						'Computer Cases',
						'CPUs / Processors',
						'External Enclosures',
						'Fans / Heatsinks',
						'Flash Memory / Readers',
						'Hard Drives',
						'Input Devices',
						'Keyboards / Mice',
						'Memory',
						'Monitors',
						'Motherboards',
						'Networking',
						'Power Protection',
						'Power Supplies',
						'Printers / Scanners',
						'Software',
						'Soundcards, Speakers, Headsets',
						'Video Cards'
					);

	$subclass_array = array('Services' => array('Repair', 'Web Design'),
							'Cables' => array('HDMI', 'Ethernet', 'USB'),
							'Hard Drives' => array('2.5" IDE', '3.5" IDE', '2.5" SATA', '3.5" SATA'),
							'Power Supplies' => array('Desktop PSUs', 'Laptop Power Adapters'),
							'Video Cards' => array('PCI', 'VGA', 'PCI-E')
						   );

	sort($class_array);
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('select[name=class]').change(function() {
			$('select[name=subclass]').html('');	//clear out the current subclasses
			<?php
			foreach($class_array as $k => $v) {
			?>
			if($('select[name=class]').val() == <?php echo "'" . $v . "'"; ?>) {
				<?php
					echo 'var subclassHTML = "';
					if(isset($subclass_array[$v])) {
						$array_size = sizeof($subclass_array[$v]);
						sort($subclass_array[$v]);
					} else {
						$array_size = 0;
					}

					if($array_size == 0) {
						echo '<option value=\"none\">n/a</option>';
					} else {
						foreach($subclass_array[$v] as $k1 => $v1) {
							$selected = '';
							if(htmlentities($v1) == $subclass) {
								$selected = ' selected=\"selected\"';
							}
							echo '<option value=\"' . htmlentities($v1) . '\"' . $selected . '>' . htmlentities($v1) . '</option>';
						}
					}

					echo '";'
				?>

				$('select[name=subclass]').html(subclassHTML);
			}

			<?php
			}
			?>
		});

		$('select[name=class]').change();
	});
</script>
<div id="wrapper_top">
	<form method="POST" name="inventory" action="./views/ajax_inventory.php?cond=save">
		<table id="input_table">
			<tr>
				<td>
					<input name="title" size="39" placeholder="Item" value="<?php echo $title; ?>" />
					<input name="upc" size="39" placeholder="UPC" value="<?php echo $upc; ?>" />
					<textarea name="description" cols="41" rows="8" placeholder="Description"><?php echo $description; ?></textarea>
				</td>
				<td>
					We paid: $ <input name="msrp" size="5" placeholder="MSRP" value="<?php echo $msrp; ?>" /><br />
					Sell for: $ <input name="price" size="5" placeholder="Price" value="<?php echo $price; ?>" />
					 x <input name="stock" size="2" placeholder="Stock" value="<?php echo $stock; ?>" /><br />
					<select name="class" style="width: 245px;">
						<?php
							foreach($class_array as $k => $v) {
								$selected = '';
								if($class == $v) {
									$selected = ' selected';
								}

								echo '<option value="' . $v . '"' . $selected . '>' . $v . '</option>';
							}
						?>
					</select>
					<select name="subclass" style="width: 245px;">
						<option value="tester">Tester</option>
					</select>
					<br />
					<?php
						echo '<input name="monitor" type="checkbox"';
							if($monitor) {
								echo 'checked';
							}
						echo '/> Monitor Stock,';
					?>
					<br />
					and warn when we have <input name="threshold" size="2" value="<?php echo $threshold; ?>" /> or less.
					<input type="hidden" name="condition" value="<?php echo $cond; ?>" />
					<input type="hidden" name="inv_id" value="<?php echo $id; ?>" />
					<?php
						$prev_page_url = '?page=ui_inventory&list_type=' . $list_type . '&parts_class=' . $parts_class;
					?>
					<input type="hidden" name="previous_page" value="<?php echo $prev_page_url; ?>" />
				</td>
			</tr>
		</table>

	</form>

	<div id="submit_buttons">
		<a href="#" onclick="return false;" id="process_button">Save</a>
		<a href="#" id="cancel_button">Cancel</a>
	</div>
</div>