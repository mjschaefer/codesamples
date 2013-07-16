function typeDelay(source, url, target) {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(
	    function() {
	        if(source.val() != '') {
				var targ = url + source.val();

				$.ajax({
					url: targ,
					success: function(data) {
						if(source.attr('hasFocus')) {
							$(target).html(data);
							$(target).slideDown('fast');							
						}
					}
				});

				source.blur(function() {					
					$(target).delay(100).slideUp('fast');
				});
			}
	    },
	    doneTypingInterval
    );

    return true;
}

var max_items = 100;
var max_payments = 100;

//keeps track of the items
var item_arr = new Array();

//keeps track of the payments
var payment_arr = new Array();

//for the monies
var subtotal = 0;
var taxable = 0;
var tax = 0;
var discount = 0;
var grand = 0;
var balance = 0;
var payments = 0;

var profit;

var searching_upc = false;
var submitting = false;

//for the checking of a value in an array
Array.prototype.has = function(value) {
	var i;

	for (var i = 0, loopCnt = this.length; i < loopCnt; i++) {
		if (this[i] === value) {
			return true;
		}
	}
	return false;

};

Array.prototype.remove = function(value) {
	var i;

	for (var i = 0, loopCnt = this.length; i < loopCnt; i++) {
		if (this[i] == value) {
			this.splice(i, 1);
			return true;
		}
	}
	return false;

};


function add_item(item_id) {
	if(typeof item_id == 'undefined') {
		item_id = new_item_id();
		if(!item_id) {
			alert('Too many goddamn items');
			return;
		}
	}

	item_arr.push(item_id);

	if(!item_id) {
	} else {
		var returnMe = '';

		returnMe += '<div class="item" id="item_div_' + item_id + '">\n';
			returnMe += '<a href="#" onclick="remove_item(\'' + item_id + '\'); return false;"><img src="./img/icon_delete_24x24.png" class="icon_delete" /></a>';
			returnMe += '<input onfocus="$(this).attr(\'hasFocus\',\'true\')" onblur="$(this).attr(\'hasFocus\',\'false\')" class="item_title required" serial="' + item_id + '" name="item_' + item_id + '_title" size="77" placeholder="Item Title" />';
			returnMe += '<div class="invSearcher" id="invSearcher_' + item_id + '"></div>';
			returnMe += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$<input name="item_' + item_id + '_msrp" size="5" placeholder="MSRP" />';
			returnMe += '$<input name="item_' + item_id + '_price" size="5" placeholder="Price" class="required" />';
			returnMe += ' x <input type="number" class="quantity" name="item_' + item_id + '_quantity" min="1" step="1" value="1" size="2" />';
			returnMe += '<br />';
			returnMe += '<input name="item_' + item_id + '_description" size="85" placeholder="Description" />';
			returnMe += 'Taxable: <input type="checkbox" name="item_' + item_id + '_taxable" /> &nbsp;&nbsp;';
			returnMe += 'Discount: <select name="item_' + item_id + '_discount">';
				returnMe += '<option value="0">0%</option>';
				returnMe += '<option value="5">5%</option>';
				returnMe += '<option value="10">10%</option>';
				returnMe += '<option value="15">15%</option>';
				returnMe += '<option value="20">20%</option>';
				returnMe += '<option value="25">25%</option>';
				returnMe += '<option value="30">30%</option>';
				returnMe += '<option value="35">35%</option>';
				returnMe += '<option value="40">40%</option>';
				returnMe += '<option value="45">45%</option>';
				returnMe += '<option value="50">50%</option>';
				returnMe += '<option value="55">55%</option>';
				returnMe += '<option value="60">60%</option>';
				returnMe += '<option value="65">65%</option>';
				returnMe += '<option value="70">70%</option>';
				returnMe += '<option value="75">75%</option>';
				returnMe += '<option value="80">80%</option>';
				returnMe += '<option value="85">85%</option>';
				returnMe += '<option value="90">90%</option>';
				returnMe += '<option value="95">95%</option>';
				returnMe += '<option value="100">100%</option>';
			returnMe += '</select>';
			returnMe += '<input type="hidden" name="item_' + item_id + '_databaseid" value="" />';
			returnMe += '<input type="hidden" name="item_' + item_id + '_class" value="" />';
			returnMe += '<input type="hidden" name="item_' + item_id + '_subclass" value="" />';
			returnMe += '<input type="hidden" name="item_' + item_id + '_stock" value="" />';
		returnMe += '</div>';
		
		$("#wrapper_items").append(returnMe);

		$('.item_title').unbind('keyup');
		$('.item_title').keyup(function() {
			typeDelay($(this), './views/ajax_inventorySearch.php?target=' + $(this).attr("serial") + '&query=', '#invSearcher_' + $(this).attr("serial"));
		});
	}
}

function setup_scanning() {
	$("#customer_info > input").unbind('click');
    $("#customer_info > input").click(function(event) { 
    	event.stopPropagation(); 
    });

	$(".item > input").unbind('click');
    $(".item > input").click(function(event) { 
    	event.stopPropagation(); 
    });

	$('input[name=upc_scan]').focus();

	$('input[name=upc_scan]').unbind('keypress');
	$('input[name=upc_scan]').keypress(function(event) {
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13') {
			if($('input[name=upc_scan]').val() != '') {
				$('input[name=upc_scan]').blur();
				if(!searching_upc && !submitting) {
					search_upc();
				}				
			}
			return false;
		}
	});
}

function search_upc() {
	searching_upc = true;
	$('#shadow_box').html('<div id="inventory_catcher"></div>');
	$('#shadow_box').fadeIn('fast', function() {
		//alert('Searching: ' + $('input[name=upc_scan]').val());	
		var opts = {
			lines: 12, // The number of lines to draw
			length: 7, // The length of each line
			width: 5, // The line thickness
			radius: 10, // The radius of the inner circle
			color: '#fff', // #rbg or #rrggbb
			speed: 1, // Rounds per second
			trail: 100, // Afterglow percentage
			shadow: true // Whether to render a shadow
		};
		var spinner = new Spinner(opts).spin(document.getElementById('shadow_box'));
		var targ_url = "../app/views/ajax_inventorySearch.php?query=" + $('input[name=upc_scan]').val() + "&from_scanner=1";

		var itemNo = new_item_id();
		$.ajax({
			url: targ_url + "&target=" + itemNo,
			success: function(data) {
				if(data != 0) {
					$("#inventory_catcher").html('');
					$("#inventory_catcher").html(data);

					$("#inventory_catcher a:first-child").trigger('click');
					//alert($("#inventory_catcher a:first-child").attr('onclick'));
				} else {
					alert('No items found');
				}

				$('#shadow_box').fadeOut('slow');
				searching_upc = false;

				$('input[name=upc_scan]').val('');
				$('input[name=upc_scan]').focus();
			}
		});		
	});
}

function new_item_id() {
	if(item_arr.length < max_items) {
		var item_num_generated = false;
		var random = Math.floor(Math.random() * max_items) + 1;

		while(!item_num_generated) {
			if(!item_arr.has(random)) {
				item_num_generated = true;
			} else {
				random = Math.floor(Math.random() * max_items) + 1;
			}
		}

		return random;
	} else {
		return false;
	}
}

function new_payment_id() {
	if(payment_arr.length < max_payments) {
		var item_num_generated = false;
		var random = Math.floor(Math.random() * max_payments) + 1;

		while(!item_num_generated) {
			if(!payment_arr.has(random)) {
				payment_arr.push(random)
				item_num_generated = true;
			} else {
				random = Math.floor(Math.random() * max_payments) + 1;
			}
		}

		return random;
	} else {
		return false;
	}	
}

function remove_item(item_num) {
	if(item_arr.length > 1) {
		if(item_arr.remove(item_num)) {
			$('#item_div_' + item_num).remove();
			update_totals();
		} else {
			alert('cannot find specified item');
		}		
	}
}

function fill_cust(type, name, business, business_id, street, city, state, zip, phone_primary, phone_secondary, cust_id) {
	if(business_id != '') {
		$('input[name="tax_exempt"]').val('1');
		$('#invoice_flash').slideDown('fast');
	} else {
		$('#invoice_flash').slideUp('fast');
		$('input[name="tax_exempt"]').val('0');
	}

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

	update_totals();
}

function fill_inventory(id, targ_number, title, msrp, price, quantity, description, classification, subclass, taxable, discount, stock, fromScanner) {
	//first, lets check if it's on the list already, so we can just increment it
	for(var i = 0; i < item_arr.length; i++) {
		if($('input[name=item_' + item_arr[i] + '_title]').val() == title) {
			//alert('found on the list');
			var theoreticalQty = parseInt($('input[name=item_' + item_arr[i] + '_quantity]').val()) + 1;
			if(classification != 'Services') {
				if(theoreticalQty <= stock) {
					$('input[name=item_' + item_arr[i] + '_quantity]').val(theoreticalQty);
					$('#item_div_' + item_arr[i]).animate( { backgroundColor: '#ccc' }, 300).animate( { backgroundColor: 'transparent' }, 300);
				} else {
					alert('We only have ' + stock + ' of ' + title + '.');
				}				
			} else {
				$('input[name=item_' + item_arr[i] + '_quantity]').val(theoreticalQty);
				$('#item_div_' + item_arr[i]).animate( { backgroundColor: '#ccc' }, 300).animate( { backgroundColor: 'transparent' }, 300);
			}
			
			update_totals();
			return;
		}
	}


	if($('input[name=item_' + item_arr[item_arr.length - 1] + '_title]').val() == '') {
		targ_number = item_arr[item_arr.length - 1];
	} else {
		if(fromScanner) {
			add_item(targ_number);
		}
	}

	//alert(id + ', ' + targ_number + ',' + title + ',' + msrp + ',' + price + ',' + quantity + ',' + description + ',' + taxable + ',' + discount);
	$('input[name=item_' + targ_number + '_title]').val(title);
	$('input[name=item_' + targ_number + '_msrp]').val(msrp);
	$('input[name=item_' + targ_number + '_price]').val(price);
	//$('input[name=item_' + targ_number + '_quantity]').val(quantity);
	$('input[name=item_' + targ_number + '_description]').val(description);
	$('input[name=item_' + targ_number + '_databaseid]').val(id);
	$('input[name=item_' + targ_number + '_class]').val(classification);
	$('input[name=item_' + targ_number + '_subclass]').val(subclass);
	$('input[name=item_' + targ_number + '_stock]').val(stock);

	if(taxable == '1') {
		$('input[name=item_' + targ_number + '_taxable]').prop('checked', true);
	} else {
		$('input[name=item_' + targ_number + '_taxable]').prop('checked', false);
	}
	//$('input[name=item_' + targ_number + '_discount]').val(discount);

	update_totals();
	
	$('input[name=item_' + targ_number + '_price]').focus();
	$('input[name=item_' + targ_number + '_price]').blur();
	$('input[name=item_' + targ_number + '_title]').focus();
	$('input[name=item_' + targ_number + '_title]').blur();
}

function update_totals() {
	subtotal = 0;
	taxable = 0;
	tax = 0;
	discount = 0;
	grand = 0;
	balance = 0;
	payments = 0;

	profit = 0;

	for(var i = 0; i < item_arr.length; i++) {
		var item_total = 0;
		var item_discount = 0;
		var item_tax = 0;
		var item_profit = 0;

		var input_title = "input[name=item_" + item_arr[i] + "_title]";
		var input_price = "input[name=item_" + item_arr[i] + "_price]";
		var input_quantity = "input[name=item_" + item_arr[i] + "_quantity]";
		var input_discount = "select[name=item_" + item_arr[i] + "_discount]";
		var input_taxable = "input[name=item_" + item_arr[i] + "_taxable]";
		var input_msrp = "input[name=item_" + item_arr[i] + "_msrp]";
		var input_stock = "input[name=item_" + item_arr[i] + "_stock]";
		var input_class = "input[name=item_" + item_arr[i] + "_class]";
		
		if($(input_price).val() != '') {
			//if there is no decimal place, add the 00's at the end.
			if($(input_price).val().indexOf(".") == -1) {
				$(input_price).val($(input_price).val() + '.00');
			}
			
			if($(input_msrp).val() != parseFloat($(input_msrp).val())) {
				$(input_msrp).val('0.00');
			}

			if($(input_price).val() != parseFloat($(input_price).val())) {
				$(input_price).val('0.00');
			}
		}

		if($(input_class).val() != 'Services') {
			//alert('qty: ' + $(input_quantity).val() + ', stock: ' + $(input_stock).val());
			if(parseInt($(input_quantity).val()) > parseInt($(input_stock).val())) {
				alert('We only have ' + $(input_stock).val() + ' of ' + $(input_title).val() + '.');
				$(input_quantity).val($(input_stock).val());
				// couldn't get the error outline to work as unfocusing from the thing isn't changing it and
				// this is only called when something gets changed on the item
				//$(input_quantity).addClass('error');
			} else {
				//$(input_quantity).removeClass('error');
			}
		}

		if($(input_price).val() == parseFloat($(input_price).val()).toFixed(2).toString()) {

			item_price = parseFloat($(input_price).val());
			item_msrp = parseFloat($(input_msrp).val());

			item_total = item_price * parseFloat($(input_quantity).val());
			item_discount = item_total * (parseFloat($(input_discount).val()) / 100);

			if($(input_taxable).is(":checked")) {
				item_tax = item_total * global_tax;
			}

			item_profit = ((item_price - item_msrp) * parseFloat($(input_quantity).val())) - item_discount;
			
			$(input_price).removeClass("error");
		} else {
			$(input_price).addClass("error");
		}

		subtotal += item_total;
		discount += item_discount;
		tax += item_tax;
		profit += item_profit;
	}

	if($('input[name="tax_exempt"]').val() == '1') {
		grand = subtotal - discount;
		$('#tax').html('EXEMPT');	
	} else {
		grand = subtotal + tax - discount;	
		$('#tax').html(tax.toFixed(2));	
	}
	balance = grand - payments;

	$('#subtotal').html(subtotal.toFixed(2));
	$('#discount').html(discount.toFixed(2));
	$('#total').html(grand.toFixed(2));
}

function prepare_save() {
	update_totals();

	$('input[name=subtotal]').val(subtotal.toFixed(2));
	$('input[name=tax]').val(tax.toFixed(2));
	$('input[name=discount]').val(discount.toFixed(2));
	$('input[name=grand]').val(grand.toFixed(2));
	$('input[name=profit]').val(profit.toFixed(2));
	$('input[name=balance]').val(balance.toFixed(2));
	
	$('input[name=itemArray]').val(item_arr.toString());
}

function save_unpaid() {
	prepare_save();

	$('input[name=save_unpaid]').val('1');
	$('form[name=invoice]').submit();
	$('#dropdown').slideUp('fast');
}

function prompt_payments(invoice_num, notes_num, l_balance) {
	invoice_num = typeof(invoice_num) != 'undefined' ? invoice_num : 0;
	notes_num = typeof(notes_num) != 'undefined' ? notes_num : 0;
	l_balance = typeof(l_balance) != 'undefined' ? l_balance : balance;
	
	var targ = "./views/ajax_payment.php?balance=" + l_balance + "&invoice_num=" + invoice_num;

	$.ajax({
		url: targ,
		success: function(data) {
		    //alert('loaded');
			$('#dropdown').html(data);
			$('#dropdown').slideDown('fast');


			//$("#payment_inputs > input").unbind('click');
		    $(".input_money").click(function(event) {
		    	//alert('clicked');
		    	event.stopPropagation(); 
		    });

			$('.button_money').click(function() {				
				input_target = 'input[name=input_' + $(this).attr('type') + ']';

				//alert($(input_target).val().indexOf("."));

				//if there is no decimal place, add the 00's at the end.
				if($(input_target).val().indexOf(".") == -1) {
					$(input_target).val($(input_target).val() + '.00');
				}

				if($(input_target).val() == parseFloat($(input_target).val()).toFixed(2).toString()) {
					$('.input_money').removeClass('error');

					payment_id = new_payment_id();

					html_add = '';
					html_add += '<div class="payment_listing">'
						html_add += '<input type="hidden" name="payment_' + payment_id + '_type" value="' + $(this).attr('type') + '" />';
						html_add += '<input type="hidden" name="payment_' + payment_id + '_value" value="' + parseFloat($(input_target).val()).toFixed(2) + '" />';
						html_add += $(this).attr('type') + ' -- $ ' + parseFloat($(input_target).val()).toFixed(2);
					html_add += '</div>';
					
					$('#payments_list').append(html_add);

					balance_new = parseFloat($('input[name=payments_balance]').val()) - parseFloat($(input_target).val())

					$('input[name=payments_balance]').val(balance_new.toFixed(2));
					$('#span_balance').html($('input[name=payments_balance]').val());

					$('.input_money').val('');
				} else {
					$(input_target).addClass('error');
				}

				return false;
			});

			$('#process_button').click(function() {
				if(payment_arr.length > 0) {
					if(invoice_num == 0) {
						var dataString = $('form[name=invoice]').serialize();

						$.ajax({  
							type: "POST",  
							url: "./views/add_invoice.php",  
							data: dataString,
							success: function(data) {
								//data will return the invoice number just submitted.
								//now we can submit any payments to add to the invoice based on id we just received
								$('input[name=invoice_id]').val(data);
								$('input[name=notes_id]').val($('input[name=note_id]').val());
								$('input[name=payments_array]').val(payment_arr.toString());

								$('form[name=form_payments]').submit();
							}
						});
					} else {
						$('input[name=invoice_id]').val(invoice_num);
						$('input[name=notes_id]').val(notes_num);
						$('input[name=payments_array]').val(payment_arr.toString());

						$('form[name=form_payments]').submit();					
					}
					return false;
				} else {
					return false;
				}
			});

			$('#cancel_button').click(function() {
				$('#dropdown').slideUp('fast', function() {
					$('#dropdown').html('');
				});
				$('#shadow_box').fadeOut('slow');
				return false;
			});
		}
	});
}

function print_invoice(inv_num) {
	window.open('./views/ui_invoice_print.php?invNum=' + inv_num);
}

function submit_invoice() {
	$('form[name=invoice]').validate();
	submitting = true;

	if($('form[name=invoice]').valid()) {
		prepare_save();
		$('#custSearcher').slideUp('fast');
		$('.invSearcher').slideUp('fast');

		$('#shadow_box').fadeIn('slow');

		$('#dropdown').html('<div style="padding: 5px;" id="invoice_buttons"><img src="./img/icon_warning_32x32.png" style="float: left;" /><h3 style="float: left;">What do?</h3><input type="submit" value="Take Money" id="save_note_button" style="float: right;" /><input type="submit" value="Save as Unpaid" id="save_unpaid_button" style="float: right" /><input type="submit" value="Cancel" id="cancel_button" style="float: right;" /></div>');
		
		$('#cancel_button').click(function() {
			$('#dropdown').slideUp('fast');
			$('#shadow_box').fadeOut('slow');

			return false;
		});

		$('#save_note_button').click(function() {
			$('#dropdown').slideUp('fast', function() {
				prompt_payments();
			});

			return false;
		});

		$('#save_unpaid_button').click(function() {
			save_unpaid();
			return false;
		});

		$('#dropdown').slideDown('fast');
	}

	return false;
}

function prompt_delete_invoice(invoice_id) {
	return confirm('This will delete payments and any items sold associated with invoice #' + invoice_id + '.\n\nThis operation cannot be undone.');
}