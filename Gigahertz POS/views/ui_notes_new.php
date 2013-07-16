<?php include('./includes/tinymce_init.php'); ?>
<script>
    init_general_notes_editor()
    
	var dropdown_html = '<div id="dropdown_close"><a href="#" onclick="close_dropdown(); return false;">X</a></div>';
		dropdown_html += '<form id="service_form" onsubmit="return false;">';
			dropdown_html += '<input name="service" type="text" size="55" />';
			//dropdown_html += '<input type="submit" value="Add">';
		dropdown_html += '</form>';
		dropdown_html += '<div id="service_search"></div>';
	//---------------------------------------------------------------------------------------------------------//
	//---------BEGIN JQUERY -----------------------------------------------------------------------------------//
	//---------------------------------------------------------------------------------------------------------//
	jQuery.validator.messages.required = "*";

	$(document).ready(function() {
		$('input[name=cust_name]').focus();

		$("#new_computer").validate();

		$('#cust_name').keyup(function() {
			if($(this).val() != '') {
				var targ = "./views/ajax_custSearch.php?query=" + $(this).val();

				$.ajax({
					url: targ,
					success: function(data) {
						$("#custSearcher").html(data);
						$("#custSearcher").slideDown('fast');
					}
				});
			}
		});

		$('#cust_name').blur(function() {
			$('#custSearcher').delay(50).slideUp('fast');
		});
	});

	function mce_detect_keypress(e) {
		if(e.type == "keydown") {
			//alert(e.keyCode);

			//187 for +
			//50 for @
			//40 for down arrow
			//38 for up arrow
			//27 for escape
			//13 for enter
			if(e.keyCode == "50" && e.shiftKey) {
				$('#dropdown').html(dropdown_html);

				//onkeydown for the service finder
				$('input[name=service]').keydown(function(event_a) {
					if(event_a.keyCode == "27") {
						$('#dropdown').slideUp('fast');
						tinyMCE.execInstanceCommand('textarea_notes', "mceInsertContent", false, '');
					}
				});

				$('input[name=service]').keyup(function(event_b) {
					if(event_b.keyCode != "40" && event_b.keyCode != "38") {
						if($(this).val() != '') {		
							var targ = "./views/ajax_serviceSearch.php?query=" + $(this).val();

							$.ajax({
								url: targ,
								success: function(data) {
									$("#service_search").html(data);
									$("#service_search").slideDown('fast');

									//hover over service entry logic
									$(".service_entry").hover(function() {
										$('.service_entry').removeClass('service_selected');
										$(this).addClass('service_selected');
									});
								}
							});
						}
					}

					//enter button logic
					if(event_b.keyCode == "13") {
						if($('#service_search').is(":visible")) {
							$('.service_selected').click();
						}
					}

					//down arrow logic
					if(event_b.keyCode == "40") {
						if(!($('.service_selected').is('.service_last'))) {
							$(".service_entry.service_selected").nextAll(".service_entry:first").andSelf().toggleClass("service_selected");
						}
					}

					//up arrow logic
					if(event_b.keyCode == "38") {
						if(!($('.service_selected').is('.service_first'))) {
							$(".service_entry.service_selected").prevAll(".service_entry:first").andSelf().toggleClass("service_selected");
						}
					}

					//deleted everything in the input logic
					if($('input[name=service]').val() == '') {
						$('#service_search').slideUp('fast');
					}
				});

				/*$('input[name=service]').blur(function() {
					$('#dropdown').slideUp('fast');
				})*/

				$('#dropdown').slideDown('fast');
				$('input[name=service]').focus();

				//insert_service('Spyware/Virus Removal', '2395728375235');
			}
		}

		return true;
	}

	function insert_service(title, upc, price) {
		if(tinyMCE.activeEditor.getContent().length == 0) {
			nbsp = '&nbsp;';
		} else {
			nbsp = '';
		}

		var html = nbsp + '<input type="button" title="' + upc + '" value="' + title + ' - ' + price + '" disabled="disabled" />';

		tinyMCE.execInstanceCommand('textarea_notes', "mceInsertContent", false, html);

		$('#dropdown').slideUp('fast');
	}

	function close_dropdown() {
		$('#dropdown').slideUp('fast');
	}

	function fill_cust(type, name, business, business_id, street, city, state, zip, phone_primary, phone_secondary, cust_id) {
		if(type == 'Business') {
			$('input[value=Business]').attr('checked', true);
			$('input[value=Residential]').attr('checked', false);
		} 

		if(type == 'Residential') {
			$('input[value=Residential]').attr('checked', true);
			$('input[value=Business]').attr('checked', false);
		}
		$('input[name=cust_name]').val(name);
		$('input[name=cust_business]').val(business);
		$('input[name=cust_street]').val(street);
		$('input[name=cust_city]').val(city);
		$('input[name=cust_state]').val(state);
		$('input[name=cust_zip]').val(zip);
		$('input[name=cust_phone_primary]').val(phone_primary);
		$('input[name=cust_phone_secondary]').val(phone_secondary);
		$('input[name=cust_id]').val(cust_id);
	}

</script>

<div id="wrapper_main">
	<form id="new_computer" method="POST" action="./views/add_notes.php">
		<div id="wrapper_info" class="wrappers">
			<div id="customer_info" class="info_top">
				<img src="./img/silhouette.png" width="96" height="140" />
				<input type="radio" name="cust_type" value="Residential" checked> Residential &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="cust_type" value="Business"> Business
				<input name="cust_name" size="42" id="cust_name" placeholder="Customer Name" class="required" /><br />
					<div id="custSearcher">resulty</div>
				<input name="cust_business" size="42" placeholder="Business" /><br />
				<input name="cust_street" size="42" placeholder="Street" /><br />
				<input name="cust_city" placeholder="City" />
				<input name="cust_state" size="2" maxlength="2" value="IN" />
				<input name="cust_zip" placeholder="Zip" maxlength="5" size="6"  /><br />
				<input name="cust_phone_primary" size="18" placeholder="Primary Phone" class="required" />
				<input name="cust_phone_secondary" id="phone_secondary" size="17" placeholder="Secondary Phone" />
				<input type="hidden" name="cust_id" value="0" />
			</div>
			<div id="computer_info" class="info_top">
				<img src="./img/flag_pc.png" width="96" height="140" />
				<input type="radio" name="pc_type" value="Desktop" checked> Desktop &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="pc_type" value="Laptop"> Laptop &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" name="pc_type" value="Other"> Other
				<br />
				<input name="pc_manufacturer" size="35" placeholder="Manufacturer" class="required" /><br />
				<input name="pc_model" size="35" placeholder="Model Number" class="required" /><br />
				<input name="pc_serial" size="35" placeholder="Serial Number" class="required" /><br />
				<input name="pc_os" size="35" placeholder="OS" /><br />
				<input name="pc_password" size="35" placeholder="Password" class="required" /><br />
			</div>
		</div>

		<div id="wrapper_notes" class="wrappers">
			<div id="problems" class="notes">
				<h3>Problem?</h3>
				<textarea rows="10" cols="47" class="textarea_notes" id="textarea_problems" name="problems"></textarea>		
			</div>

			<div id="items_left" class="notes">
				<div id="items_left">
					<h3>Items Left Behind</h3>
					<textarea rows="10" cols="47" class="textarea_notes" id="textarea_items_left" name="items_left"></textarea>		
				</div>
				<!--
				<div id="labor_to_perform">
					<h3>Labor to Perform <input type="search" size="23" /></h3>
					<input type="hidden" name="labor_to_perform" value="No Labor Estimate" />
				</div>
				-->
			</div>

			<div id="notes" class="notes">
				<h3>Notes (@ to add services)</h3>
				<textarea rows="20" cols="96" class="textarea_notes" id="textarea_notes" name="notes"></textarea>		
			</div>
		</div>

		<div class="wrappers">
			<input type="submit" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" style="float: right" />
		</div>
	</form>
</div>