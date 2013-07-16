//document hath loaded, do all this shits
var typingTimer;
var doneTypingInterval = 100;

var items_scannable = false;

$(document).ready(function() {
	//for debugging the payment acceptance thing.
	//will make it pop up on refresh
	//prompt_payments();

	jQuery.validator.messages.required = "*";

	if($('input[name=cust_name]').val() == '') {
		$('input[name=cust_name]').focus();
	}

	$("#new_computer").validate();

	$('#cust_name').keyup(function() {
		typeDelay($(this), "./views/ajax_custSearch.php?query=", "#custSearcher");
	});

	$('#item_add').click(function() {
		add_item();

		checkPlaceholders();
		setup_scanning();

		return false;
	});

	$('form').change(function() {
		update_totals();
	});

	$('#subtotal').html('0.00');
	$('#tax').html('0.00');
	$('#discount').html('0.00');
	$('#total').html('0.00');
});