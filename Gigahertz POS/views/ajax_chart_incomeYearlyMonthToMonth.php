<?php
	include('./ajax_chart_functions.php');
	
	if(!isset($_GET['chart_targ'])) {
		$_GET['chart_targ'] = '1';
	}

	$location = $_GET['location'];
?>
<?php 
	if($location == 'all') {
		$query = "SELECT paid_date FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date <= '" . mktime(23, 0, 0, 12, 31, $_GET['year']) . "' ORDER BY paid_date DESC LIMIT 1";
	} else {
		$query = "SELECT paid_date FROM invoices WHERE paid = '1' AND location = '$location' AND total != '0.00' AND paid_date <= '" . mktime(23, 0, 0, 12, 31, $_GET['year']) . "' ORDER BY paid_date DESC LIMIT 1";
	}
	$latestData = mysql_query($query) or die(mysql_error());
	$latestArr = mysql_fetch_assoc($latestData);

	$latestInvoice = $latestArr['paid_date'];

	$data_arr = '[';
		for($i = 1; $i <= date('n', $latestInvoice); $i++) {
			$days_in_month = cal_days_in_month(CAL_GREGORIAN, $i, $_GET['year']);
			$unix_month_now = mktime(0, 0, 0, $i, 1, $_GET['year']);
			$unix_month_next = mktime(23, 0, 0, $i, $days_in_month, $_GET['year']);

			//echo $unix_week_now . '<br />';
			//echo $unix_week_next . '<br /><br />';

			if($location == 'all') {
				$query = "SELECT SUM(total) FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date >= '" . $unix_month_now . "' AND paid_date <= '" . $unix_month_next . "' ORDER BY paid_date";		
			} else {
				$query = "SELECT SUM(total) FROM invoices WHERE paid = '1' AND location = '$location' AND total != '0.00' AND paid_date >= '" . $unix_month_now . "' AND paid_date <= '" . $unix_month_next . "' ORDER BY paid_date";		
			}

			//echo $query . '<br />';

			$sum_data = mysql_query($query) or die(mysql_error());
			$sum_arr = mysql_fetch_assoc($sum_data);
			
			if($sum_arr['SUM(total)'] != '') {
				$data_arr .= '[' . (mktime(0, 0, 0, $i, 1, $_GET['year']) * 1000) . ',' . $sum_arr['SUM(total)'] . '],';
			} else {
				$data_arr .= '[' . (mktime(0, 0, 0, $i, 1, $_GET['year']) * 1000) . ', 0],';
			}
		}
	$data_arr .= ']';

	$data_arr3 = '[';
		for($i = 1; $i <= date('n', $latestInvoice); $i++) {
			$days_in_month = cal_days_in_month(CAL_GREGORIAN, $i, $_GET['year']);
			$unix_month_now = mktime(0, 0, 0, $i, 1, $_GET['year']);
			$unix_month_next = mktime(23, 0, 0, $i, $days_in_month, $_GET['year']);

			//echo $unix_week_now . '<br />';
			//echo $unix_week_next . '<br /><br />';

			if($location == 'all') {
				$query = "SELECT SUM(cost) FROM expenses WHERE `date` >= '" . $unix_month_now . "' AND `date` <= '" . $unix_month_next . "'";				
			} else {
				$query = "SELECT SUM(cost) FROM expenses WHERE `date` >= '" . $unix_month_now . "' AND `date` <= '" . $unix_month_next . "' AND location = '$location'";
			}

			//echo $query . '<br />';

			$sum_data = mysql_query($query) or die(mysql_error());
			$sum_arr = mysql_fetch_assoc($sum_data);
			
			if($sum_arr['SUM(cost)'] != '') {
				$data_arr3 .= '[' . (mktime(0, 0, 0, $i, 1, $_GET['year']) * 1000) . ',' . $sum_arr['SUM(cost)'] . '],';
			} else {
				$data_arr3 .= '[' . (mktime(0, 0, 0, $i, 1, $_GET['year']) * 1000) . ', 0],';
			}
		}
	$data_arr3 .= ']';

	//echo "alert('" . addslashes($query) . "');";
?>
<script>
	$(document).ready(function() {

		var linedata = <?php echo $data_arr; ?>;
		var linedata3 = <?php echo $data_arr3; ?>;

		var month_this = "<?php echo $months[$_GET['month']]; ?>";
		var year_this = "<?php echo $_GET['year']; ?>";
		var location_this = "<?php echo $_GET['location']; ?>";

		$.jqplot("chart_w2w_<?php echo $_GET['chart_targ']; ?>", [linedata, linedata3], {
			title: {
		        text: 'Income By Month - ' + year_this + ' - for ' + location_this + ' location(s)',   // title for the plot,
		        show: true,
		    },
	    	legend: {
	        	show: true,
	        },
			seriesDefaults: {
				trendline: {
					lineWidth: 3,
					show: true
				}
			},
		    series: [
	            { label: "Net Income" },	            
	            { label: "Expenses" }
	        ],
			axes: {
                xaxis: {
                    label: 'Day',
                    renderer: $.jqplot.DateAxisRenderer,
                    min: "1/1/<?php echo $_GET['year']; ?> 00:00:00",
                    max: "<?php echo date('n', $latestInvoice); ?>/1/<?php echo date('Y', $latestInvoice); ?> 00:00:00",
                    tickInterval: '1 month',
					tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                    tickOptions: { 
	                    formatString: '%B',
						angle: -30,
						fontSize: '10pt'
	                }                    
                },
	            yaxis: {
	            	min: 0,
	            	tickOptions: {
	            		formatString: '$%.2f'
	            	}
	            }
	        },
			highlighter: {
				show: true,
				sizeAdjust: 7.5,
				tooltipAxes: 'y',
				tooltipLocation: 'n',
        		tooltipOffset: 5,    
			},
			cursor: {
				show: false
			}
		});
	});
</script>

<div id="chart_w2w_<?php echo $_GET['chart_targ']; ?>" class="chart_wide"></div>
<div id="info"></div>