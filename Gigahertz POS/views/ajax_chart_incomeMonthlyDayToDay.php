<?php
	include('./ajax_chart_functions.php');
	
	if(!isset($_GET['chart_targ'])) {
		$_GET['chart_targ'] = '1';
	}
?>
<?php 
	$query = "SELECT paid_date FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date <= '" . mktime(23, 0, 0, 12, 31, $_GET['year']) . "' ORDER BY paid_date DESC LIMIT 1";
	$latestData = mysql_query($query) or die(mysql_error());
	$latestArr = mysql_fetch_assoc($latestData);

	$latestInvoice = $latestArr['paid_date'];

	$data_arr = '[';
		$days_in_month = cal_days_in_month(CAL_GREGORIAN, $_GET['month'], $_GET['year']);
		for($i = 1; $i <= $days_in_month; $i++) {
			$unix_day_now = mktime(0, 0, 0, $_GET['month'], $i, $_GET['year']);
			$unix_day_next = mktime(0, 0, 0, $_GET['month'], $i + 1, $_GET['year']);

			if($unix_day_now > mktime(23, 0, 0, date('m'), date('d'), date('Y'))) {
				break;
			}

			$query = "SELECT SUM(total) FROM invoices WHERE paid = '1' AND total != '0.00' AND paid_date >= '" . $unix_day_now . "' AND paid_date < '" . $unix_day_next . "'";

			$sum_data = mysql_query($query) or die(mysql_error());
			$sum_arr = mysql_fetch_assoc($sum_data);

			if($sum_arr['SUM(total)'] != '') {						
				$data_arr .= '[' . (mktime(0, 0, 0, $_GET['month'], $i, $_GET['year']) * 1000) . ',' . $sum_arr['SUM(total)'] . '],';
			} else {					
				$data_arr .= '[' . (mktime(0, 0, 0, $_GET['month'], $i, $_GET['year']) * 1000) . ',0],';
			}
		}
	$data_arr .= ']';

	//echo "alert('" . addslashes($query) . "');";
	//echo $unix_day_now;
?>
<script>
	$(document).ready(function() {
		var linedata = <?php echo $data_arr; ?>;

		var month_this = "<?php echo $months[$_GET['month']]; ?>";
		var year_this = "<?php echo $_GET['year']; ?>";

		$.jqplot("chart_d2d_<?php echo $_GET['chart_targ']; ?>", [linedata], {
			title: {
		        text: 'Income By Day - ' + month_this + ' ' + year_this,   // title for the plot,
		        show: true,
		    },
	    	legend: {
	        	show: false
	        },
			seriesDefaults: {
				trendline: {
					color: '#ffc125',
					lineWidth: 3,
					show: true
				}
			},
			axes: {
                xaxis: {
                    label: 'Day',
                    renderer: $.jqplot.DateAxisRenderer,
                    min: "<?php echo $_GET['month']; ?>/1/<?php echo $_GET['year']; ?> 00:00:00",
                    <?php
						if($unix_day_now > mktime(23, 0, 0, date('m'), date('d'), date('Y'))) {
							echo 'max: "' . date('n') . '/' . date('j') . '/' . date('Y') . ' 00:00:00",';
						} else {							
							echo 'max: "' . $_GET['month'] . '/' . $days_in_month . '/' . $_GET['year'] . ' 00:00:00",';
						}
                    ?>
                    
                    tickInterval: '1 days',
                    tickOptions: { 
	                    formatString: '%#d' 
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

		$("#chart_d2d_<?php echo $_GET['chart_targ']; ?>").bind('jqplotDataClick', 
            function (ev, seriesIndex, pointIndex, data) {
				$('#info').slideUp('fast');	

        		$.get("./views/ajax_chart_fetch_dayData.php?date=" + data[0], function(d) {
					$('#info').html(d);
					$('#info').slideDown('fast');	
				});
            }
        );
	});
</script>

<div id="chart_d2d_<?php echo $_GET['chart_targ']; ?>" class="chart_wide"></div>
<div id="info"></div>