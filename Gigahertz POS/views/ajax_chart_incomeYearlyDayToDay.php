<?php
	include('./ajax_chart_functions.php');
	
	if(!isset($_GET['chart_targ'])) {
		$_GET['chart_targ'] = '1';
	}
?>
<?php 
	$query = "SELECT paid_date FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date <= '" . mktime(0, 0, 0, 12, 31, $_GET['year']) . "' ORDER BY paid_date DESC LIMIT 1";
	$latestData = mysql_query($query) or die(mysql_error());
	$latestArr = mysql_fetch_assoc($latestData);

	$latestInvoice = $latestArr['paid_date'];

	$data_arr = '[';
		for($i = 1; $i <= date('z', $latestInvoice); $i++) {
			$unix_week_now = mktime(0, 0, 0, 1, $i, $_GET['year']);
			$unix_week_next = mktime(23, 0, 0, 1, $i, $_GET['year']);

			//echo $unix_week_now . '<br />';
			//echo $unix_week_next . '<br /><br />';

			$query = "SELECT SUM(total) FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date >= '" . $unix_week_now . "' AND paid_date <= '" . $unix_week_next . "' ORDER BY paid_date";

			//echo $query . '<br />';

			$sum_data = mysql_query($query) or die(mysql_error());
			$sum_arr = mysql_fetch_assoc($sum_data);
			
			if($sum_arr['SUM(total)'] != '') {
				$data_arr .= '[' . (mktime(0, 0, 0, 1, $i, $_GET['year']) * 1000) . ',' . $sum_arr['SUM(total)'] . '],';
			} else {
				$data_arr .= '[' . (mktime(0, 0, 0, 1, $i, $_GET['year']) * 1000) . ', 0],';
			}
		}
	$data_arr .= ']';

	//echo "alert('" . addslashes($query) . "');";
?>
<script>
	$(document).ready(function() {

		var linedata = <?php echo $data_arr; ?>;

		var month_this = "<?php echo $months[$_GET['month']]; ?>";
		var year_this = "<?php echo $_GET['year']; ?>";

		$.jqplot("chart_w2w_<?php echo $_GET['chart_targ']; ?>", [linedata], {
			title: {
		        text: 'Income By Week - YTD - ' + year_this,   // title for the plot,
		        show: true,
		    },
	    	legend: {
	        	show: false
	        },
	        grid: {
	        	drawGridLines: false,
	        	gridLineColor: '#fff'
	        },
			seriesDefaults: {
				linewidth: .1,
				trendline: {
					color: '#ffc125',
					lineWidth: 3,
					show: true
				},
				fill: true,
				markerOptions: {
					show: false
				}
			},
			axes: {
                xaxis: {
                    renderer: $.jqplot.DateAxisRenderer,
                    min: "1/1/<?php echo $_GET['year']; ?> 00:00:00",
                    max: "<?php echo date('n', $latestInvoice); ?>/<?php echo date('j', $latestInvoice); ?>/<?php echo date('Y', $latestInvoice); ?> 00:00:00",
                    tickInterval: '1 days',
                    tickOptions: { 
	                    formatString: ' ',
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