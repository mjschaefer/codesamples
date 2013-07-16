<?php
	extract($_GET);
?>

<form name="form_payments" method="POST" action="./views/add_payments.php">
	<div style="padding: 5px; overflow: hidden;" id="payment_inputs">
		<div id="process_payments_main" style="float: left;">
			<div id="money_button_group" style="float: left;">
				<div class="money_button_wrapper"><a href="#" class="button_money" id="button_cash" type="cash" style="float: left;">Cash</a><input type="text" class="input_money" name="input_cash" /></div>
				<div class="money_button_wrapper"><a href="#" class="button_money" id="button_check" type="check" style="float: left;">Check</a><input type="text" class="input_money" name="input_check" /></div>
				<div class="money_button_wrapper"><a href="#" class="button_money" id="button_credit" type="credit" style="float: left;">Credit</a><input type="text" class="input_money" name="input_credit" /></div>
			</div>

			<div id="payments_list" style="float: left; width: 160px; padding: 10px;">
				
			</div>
		</div>

		<div id="submit_buttons" style="float: right;">
			<a href="#" id="process_button">Process<br />Payment</a>
			<a href="#" id="cancel_button">Cancel</a>
		</div>

		<span id="grand_total" style="float: right;">
			<input type="hidden" name="payments_balance" value="<?php echo number_format($balance, 2, '.', ''); ?>" />
			$ <span id="span_balance"><?php echo number_format($balance, 2, '.', ''); ?></span>
		</span>

		<input type="hidden" name="payments_array" value="" />
		<input type="hidden" name="invoice_id" value="0" />
		<input type="hidden" name="notes_id" value="0" />
	</div>
</form>