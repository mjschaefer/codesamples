<?php
	extract($_GET);	
?>

<form method="POST" action="#" onsubmit="return false;">
	<div id="customer_info">
		<img src="./img/silhouette_big.png" />
		<input type="radio" name="cust_type" value="Residential" checked> Residential &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="cust_type" value="Business"> Business
		<input name="cust_name" size="42" placeholder="Customer Name" class="required" /><br />
		<input name="cust_business" size="42" placeholder="Business" /><br />
		<input name="cust_street" size="42" placeholder="Street" /><br />
		<input name="cust_city" placeholder="City" />
		<input name="cust_state" size="2" value="IN" />
		<input name="cust_zip" placeholder="Zip" size="5"  /><br />
		<input name="cust_phone_primary" size="18" placeholder="Primary Phone" class="required" />
		<input name="cust_phone_secondary" size="17" placeholder="Secondary Phone" />
		<input type="hidden" name="cust_id" value="0" />

		<input type="submit" style="display: block; float: right;" value="&nbsp;&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;&nbsp;" />
	</div>
</form>
