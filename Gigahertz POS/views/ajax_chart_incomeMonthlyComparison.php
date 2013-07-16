<?php
	include('./ajax_chart_functions.php');

	if(!isset($_GET['chart_targ'])) {
		$_GET['chart_targ'] = '1';
	}
?>
<script>
	$(document).ready(function() {
		var ticks = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

		var b1 = <?php echo get_month2month($_GET['year']); ?>
		var b2 = <?php echo get_month2month($_GET['year'] - 1); ?>

		var year_now = <?php echo $_GET['year']; ?>;
		var year_last = <?php echo $_GET['year'] - 1; ?>

		$.jqplot("chart_mCompare_<?php echo $_GET['chart_targ']; ?>", [b1, b2], {	
			seriesColors: [ "orange", "green" ],	
			title: {
		        text: 'Monthly - ' + year_now + ' vs ' + year_last,   // title for the plot,
		        show: true,
		    },
	    	legend: {
	        	show:true
	        },
	        seriesDefaults: {
				trendline: {
					label: 'steve',
					show: false
				},
	            renderer: $.jqplot.BarRenderer, 
	            rendererOptions: {
	            	barWidth: 8,
	            	barPadding: 1,
	            	shadowAlpha: 0.06,
	            	shadowOffset: 1,
	            	fillToZero: true,
	            },
	            pointLabels: {
		            show: true
		        }
		    },
		    series: [
	            { label: "<?php echo $_GET['year']; ?>" },
	            { label: "<?php echo $_GET['year'] - 1; ?>" }
	        ],
			axes: {
	            xaxis: {
	                renderer: $.jqplot.CategoryAxisRenderer,
	                ticks: ticks,
	                label: 'Month'
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
			},
				cursor: {
				show: false
			}
		});
	});
</script>

<div id="chart_mCompare_<?php echo $_GET['chart_targ']; ?>" class="chart"></div>
<table id="data_table">
	<tr>
		<th>When</th>
		<th>Total</th>
	</tr>
	<tr>
		<td><a href="#" onclick="return false;"><?php echo $months[$_GET['month']] . ' ' . $_GET['year']; ?></a></td>
		<td><a href="#" onclick="return false;">$ <?php echo get_month_total($_GET['month'], $_GET['year']); ?></a></td>
	</tr>

	<tr>
		<td><a href="#" onclick="return false;"><?php echo $months[$_GET['month']] . ' ' . ($_GET['year'] - 1); ?></a></td>
		<td><a href="#" onclick="return false;">$ <?php echo get_month_total($_GET['month'], $_GET['year'] - 1); ?></a></td>
	</tr>
</table>