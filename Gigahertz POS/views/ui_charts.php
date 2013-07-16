<!--[if IE]><script language="javascript" type="text/javascript" 
src="./js/excanvas.min.js"></script><![endif]--> 

<?php
	$wide_chart_array = array(
		'Day to Day - Month' => 'ajax_chart_incomeMonthlyDayToDay.php',
		'Day to Day - YTD' => 'ajax_chart_incomeYearlyDayToDay.php',
		'Week to Week - YTD' => 'ajax_chart_incomeYearlyWeekToWeek.php',
		'Month to Month - YTD' => 'ajax_chart_incomeYearlyMonthToMonth.php'
		);

	$short_chart_array = array(
		'Montly Comparison for Year' => 'ajax_chart_incomeMonthlyComparison.php',
		'Percentage Breakdown' => 'ajax_chart_incomeBreakdown.php'
		);

	$months = array(1 => "January",
					2 => "February",
					3 => "March",
					4 => "April",
					5 => "May",
					6 => "June",
					7 => "July",
					8 => "August",
					9 => "September",
					10 => "October",
					11 => "November",
					12 => "December");

	if(!isset($_GET['year'])) {
		$_GET['year'] = date('Y');
	}

	if(!isset($_GET['month'])) {
		$_GET['month'] = date('n');
	}

	if(!isset($_GET['location'])) {
		$_GET['location'] = 'all';
	}

	$page_header_html = '<span id="h5_container">';
		$page_header_html .= '<h5>';
			$page_header_html .= '<select id="month_selector" name="month" class="select_timeframe">';
				for($i = 1; $i <= 12; $i++) {
					if($_GET['month'] == $i) {
						$selected = ' selected="selected"';
					} else {
						$selected = '';
					}
					$page_header_html .= '<option value="' . $i . '"' . $selected . '>' . $months[$i] . '</option>';
				}
			$page_header_html .= '</select>';

			$query = "SELECT `date` FROM invoices ORDER BY `date` LIMIT 1";
			$date_data = mysql_query($query) or die(mysql_error());
			$date_array = mysql_fetch_assoc($date_data);
			$lowest_year = date('Y', $date_array['date']);

			$page_header_html .= '<select id="year_selector" name="year" class="select_timeframe">';
				for($i = $lowest_year; $i <= date('Y'); $i++) {
					if($i == $_GET['year']) {
						$selected = ' selected="selected"';						
					} else {
						$selected = '';
					}

					$page_header_html .= '<option value="' . $i . '"' . $selected . '>' . $i . '</a>';
				}
			$page_header_html .= '</select>';

			$query = "SELECT DISTINCT location FROM invoices ORDER BY location";
			$location_data = mysql_query($query) or die(mysql_error());

			$page_header_html .= '<select id="location_selector" name="location" class="select_timeframe">';
				$page_header_html .= '<option value="all"';
					if($_GET['location'] == 'all' || !isset($_GET['location'])) {
						$page_header_html .= ' selected="selected"';
					}
				$page_header_html .= '>All Locations</option>';
				while($location_array = mysql_fetch_assoc($location_data)) {
					$page_header_html .= '<option value="' . $location_array['location'] . '"';
						if($_GET['location'] == $location_array['location']) {
							$page_header_html .= ' selected="selected"';
						}
					$page_header_html .= '>' . $location_array['location'] . '</option>';
				}
			$page_header_html .= '</select>';
		$page_header_html .= '</h5>';
	$page_header_html .= '</span>';
?>
<script>
	$(document).ready(function() {
		$("#page_title").html('<img src="./img/icon_chart_48x48.png" /><h2 class="title">Charts</h2>');
		$("#page_title").append('<?php echo $page_header_html; ?>');

		$(".select_timeframe").change(function() {
			window.location = "?page=ui_charts&cond=overall&month=" + $("#month_selector option:selected").val() + "&year=" + $("#year_selector option:selected").val() + "&location=" + $("#location_selector option:selected").val();
		});

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

		var spinner = new Spinner(opts).spin(document.getElementById('spin_1'));
		var spinner = new Spinner(opts).spin(document.getElementById('spin_2'));
		var spinner = new Spinner(opts).spin(document.getElementById('spin_3'));

		$.get("./views/<?php echo $wide_chart_array['Month to Month - YTD']; ?>?month=<?php echo $_GET['month']; ?>&year=<?php echo $_GET['year']; ?>&location=<?php echo $_GET['location']; ?>", function(data) {
			$('#chart_1').html(data);
		});

		$.get("./views/<?php echo $short_chart_array['Percentage Breakdown']; ?>?month=<?php echo $_GET['month']; ?>&year=<?php echo $_GET['year']; ?>&location=<?php echo $_GET['location']; ?>", function(data) {
			$('#chart_2').html(data);			
		});

		$.get("./views/<?php echo $short_chart_array['Montly Comparison for Year']; ?>?month=<?php echo $_GET['month']; ?>&year=<?php echo $_GET['year']; ?>&location=<?php echo $_GET['location']; ?>", function(data) {
			$('#chart_3').html(data);			
		});

		$(".select_chart").change(function() {
			var targ_div = $(this).parent().next().attr('id');

			$("#" + targ_div).html('<h2>Generating Chart...</h2><div id="spin' + targ_div + '" class="spinner"></div>');
			var spinner = new Spinner(opts).spin(document.getElementById('spin' + targ_div));

			$.get("./views/" + $(this).val() + "?month=<?php echo $_GET['month']; ?>&year=<?php echo $_GET['year']; ?>&location=<?php echo $_GET['location']; ?>&chart_targ=" + targ_div, function(data) {
				$("#" + targ_div).html(data);
			});			
		});
	});
</script>

<div id="wrapper_main">
	<div class="chart_wrapper_whole">
		<div class="chart_nav_wrapper">
			<select name="chart_selector" class="select_chart">
				<?php
					foreach($wide_chart_array as $k => $v) {
						if($k == 'Month to Month - YTD') {
							$selected = ' selected = "selected"';
						} else {
							$selected = '';
						}

						echo '<option value="' . $v . '"' . $selected . '>' . $k . '</option>';
					}
				?>
			</select>
		</div>
		<div id="chart_1" class="chart_wrapper chart_wide">
			<h2>Generating Chart...</h2>
			<div id="spin_1" class="spinner"></div>
		</div>
	</div>

	<div class="chart_wrapper_whole">
		<div class="chart_nav_wrapper">
			<select name="chart_selector" class="select_chart">
				<?php
					foreach($short_chart_array as $k => $v) {
						if($k == 'Percentage Breakdown') {
							$selected = ' selected = "selected"';
						} else {
							$selected = '';
						}
						echo '<option value="' . $v . '"' . $selected . '>' . $k . '</option>';
					}
				?>
			</select>
		</div>
		<div id="chart_2" class="chart_wrapper">
			<h2>Generating Chart...</h2>
			<div id="spin_2" class="spinner"></div>
		</div>
	</div>

	<div class="chart_wrapper_whole">
		<div class="chart_nav_wrapper">
			<select name="chart_selector" class="select_chart">
				<?php
					foreach($short_chart_array as $k => $v) {
						if($k == 'Montly Comparison for Year') {
							$selected = ' selected = "selected"';
						} else {
							$selected = '';
						}
						echo '<option value="' . $v . '"' . $selected . '>' . $k . '</option>';
					}
				?>
			</select>
		</div>
		<div id="chart_3" class="chart_wrapper">
			<h2>Generating Chart...</h2>
			<div id="spin_3" class="spinner"></div>
		</div>
	</div>
</div>