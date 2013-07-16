<?php
	//find announcements
	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");
	
	$username = $_SESSION['username'];

	//if we're viewing an announcement we've never viewed before, mark it read before it gets
	//the new announcement count.  Then the user won't be told they have one new announcement
	//when they're viewing the new announcement.  It'll already be marked read.
	if(!isset($_GET['page'])) {
		$_GET['page'] = 'ui_notes_new';
	}

	if($_GET['page'] == 'ui_announcements_view') {
		$announcement = $_GET['announcement'];

		$query = "SELECT has_read FROM announcements WHERE id='$announcement'";
		$announcement_data = mysql_query($query) or die(mysql_error());
		$announcement_array = mysql_fetch_assoc($announcement_data);

		$read = false;

		if($announcement_array['has_read'] != '') {
			if(strpos($announcement_array['has_read'], ',' . $_SESSION['username']) === false) {
				//echo 'not read yet';
				$read = false;
			} else {
				//echo 'already read';
				$read = true;
			}
		} else {
			//echo 'nobody has read this yet';
			$read = false;
		}

		if(!$read) {
			$query = "UPDATE announcements SET has_read = CONCAT(has_read,',$username') WHERE id='$announcement'";
			mysql_query($query) or die(mysql_error());		
		}
	}
?>
<script>
	//messages_timer is secs * milliseconds
	var messages_timer = 3 * 1000;

	$(document).ready(function() {
		//search functionality
		$("#searchbar").keyup(function() {
			if($(this).val() != '') {
				var targ = "./views/ajax_generalSearch.php?query=" + $(this).val();

				$.ajax({
					url: targ,
					success: function(data) {
						$("#generalSearcher").html(data);
						$("#generalSearcher").slideDown('fast');
					}
				});
			} else {
				if($('#generalSearcher').is(':visible')) {
					$('#generalSearcher').slideUp('fast');
				}
			}
		});

		$("#searchbar").click(function() {
			if($(this).val() == '') {
				if($('#generalSearcher').is(':visible')) {
					$('#generalSearcher').slideUp('fast');
				}					
			}
		});

		$("#searchbar").blur(function() {
			if($('#generalSearcher').is(':visible')) {
				$('#generalSearcher').delay(100).slideUp('fast');
			}
		});

		<?php
		/*$("#announcements_container_true").hover(function() {
			$(".callout").fadeOut('fast', function() {
				$("#announcements_container_true").callout("destroy");				
			});
			//$(this).callout({ position: "bottom", msg: announcements_string });
		});

		/*$("#announcements_container_false").hover(function() {
			$(this).callout({ position: "bottom", msg: "No New Announcements" });
		}, function() {
			$(this).callout("hide");
		});*/
		?>
	});

	function checkMessages() {
		$.get('./views/ajax_announcements_check.php', function(d) {
			// where there is html element with id 'status' to contain message
			//alert('there are ' + d + ' new message(s)');
			//alert(d);

			var announcement_html = '';
			if(d > 0) {
				announcement_html += '<span id="announcements_container_true" class="announcements_container">';
					announcement_html += '<img width="22" height="22" id="img_announcements_true" src="./img/icon_information_22x22.png" />';
					announcement_html += '<span id="announcements_count">' + d +  '</span>';
				announcement_html += '</span>';
			} else {
				announcement_html += '<span id="announcements_container_false" class="announcements_container">';
					announcement_html += '<img width="22" height="22" id="img_announcements_false" src="./img/icon_information_22x22_grayed.png" />';	
				announcement_html += '</span>';
			}

			$('#announcements_indicator').html(announcement_html);

			$(".announcements_container").click(function() {
				window.location = "main.php?page=ui_announcements";
			});

			var announcements_string = d + ' New Announcement';
			if(d > 1) {
				announcements_string += 's';
			}

			if(d > 0) {
				<?php 
					if($_GET['page'] != 'ui_announcements') {
				?>	
						$("#announcements_callout").html(announcements_string);
						if($("#announcements_callout").is(":visible")) {
							
						} else {
							$("#announcements_callout").fadeIn('slow');
						}
				<?php
					}
				?>	
			}

			if(d == 0) {
				<?php 
					if($_GET['page'] != 'ui_announcements') {
				?>	
						if($("#announcements_callout").is(":visible")) {
							$("#announcements_callout").fadeOut('slow');
						}
				<?php
					}
				?>					
			}

			window.setTimeout(checkMessages, messages_timer);
		});
	}

	checkMessages();
</script>

<ul id="menu">
	<li>
		<a href="#" onclick="return false;" id="logo_gigahertz"><img width="16" height="16" src="./img/icon_gigahertz_16x16.png" /></a>
		<div class="dropdown">
			<img width="16" height="16" src="./img/icon_user_32x32.png" style="margin-top: 2px;" />
			<h5><?php echo $_SESSION['name']; ?><br /><?php echo $_SESSION['location']; ?></h5>
			<hr>
			<a href="?page=ui_announcements"><img width="16" height="16" src="./img/icon_announcement_16x16.png" />Announcements</a>
			<a href="?page=ui_knowledgebase"><img width="16" height="16" src="./img/icon_knowledgebase_16x16.png" />Knowledgebase</a>
			<a href="?page=ui_global_settings"><img width="16" height="16" src="./img/icon_settings_16x16.png" />Settings</a>
			<hr>
			<a href="./views/process_logout.php"><img width="16" height="16" src="./img/icon_logout_16x16.png" />Logout</a>
		</div>
	</li>

	<li>
		<a href="?page=ui_notes_new">New Work Order</a>
	</li>

	<li class="dropdown_bullet">
		<a href="#" onclick="return false;">Invoices &#x25BE;</a>
		<div class="dropdown">
			<a href="?page=ui_invoices"><img width="16" height="16" src="./img/icon_invoices_new_16x16.png" />New</a>
			<hr>
			<a href="?page=ui_invoices&cond=unpaid"><img width="16" height="16" src="./img/icon_invoices_unpaid_16x16.png" />Unpaid</a>
			<a href="?page=ui_invoices&cond=paid"><img width="16" height="16" src="./img/icon_invoices_paid_16x16.png" />Paid</a>
		</div>
	</li>

	<li class="dropdown_bullet">
		<a href="?page=ui_notes_edit" onclick="return false;">Work Orders &#x25BE;</a>
		<div class="dropdown">
			<a href="?page=ui_notes_new"><img width="16" height="16" src="./img/icon_note_blank_16x16.png" />New</a>
			<hr>
			<a href="?page=ui_notes_edit&cond=active"><img width="16" height="16" src="./img/icon_note_blank_16x16.png" />Active</a>
			<a href="?page=ui_notes_edit&cond=complete"><img width="16" height="16" src="./img/icon_note_complete_16x16.png" />Complete</a>
		</div>
	</li>

	<li class="dropdown_bullet"><a href="#" onclick="return false;">Inventory &#x25BE;</a>
		<div class="dropdown">
			<?php
			if($_SESSION['clearance'] >= 70) {
				echo '<a href="?page=ui_inventory"><img width="16" height="16" src="./img/icon_inventory_16x16.png" />Inventory</a>';
			}
			?>
			<hr>
			<a href="?page=ui_expenses"><img width="16" height="16" src="./img/icon_expenses_16x16.png" />Expenses</a>
		</div>
	</li>

	<li class="dropdown_bullet"><a href="#" onclick="return false;">Gift Certs. &#x25BE;</a>
		<div class="dropdown">
			<a href="?page=ui_gift_generate"><img width="16" height="16" src="./img/icon_blank_16x16.png" />Generate</a>
		</div>
	</li>

	<li class="dropdown_bullet"><a href="#" onclick="return false;">Customers &#x25BE;</a>
		<div class="dropdown">
			<a href="?page=ui_customers"><img width="16" height="16" src="./img/icon_customer_16x16.png" />All Customers</a>
			<hr>
			<a href="?page=ui_taxexemptions"><img width="16" height="16" src="./img/icon_customer_16x16.png" />Tax Exemptions</a>
		</div>
	</li>
	
	<?php
		if($_SESSION['clearance'] >= 60) {
			echo '<li class="dropdown_bullet"><a href="#" onclick="return false;">Reports &#x25BE;</a>';
				echo '<div class="dropdown">';
					echo '<a href="?page=ui_report_income"><img width="16" height="16" src="./img/icon_income_16x16.png" />Income Report</a>';
					echo '<a href="?page=ui_report_expense"><img width="16" height="16" src="./img/icon_expenses_2_16x16.png" />Expense Report</a>';
				echo '</div>';
			echo '</li>';
		}
	?>
	
	<?php
		if($_SESSION['clearance'] >= 60) {
			echo '<li class="dropdown_bullet"><a href="#" onclick="return false;">Charts &#x25BE;</a>';
				echo '<div class="dropdown">';
					echo '<a href="?page=ui_charts&cond=overall"><img width="16" height="16" src="./img/icon_chart_16x16.png" />Overall</a>';
					echo '<hr>';
					echo '<a href="?page=ui_charts&cond=income"><img width="16" height="16" src="./img/icon_chart_16x16.png" />Income</a>';
					echo '<a href="?page=ui_charts&cond=expenses"><img width="16" height="16" src="./img/icon_chart_16x16.png" />Expense</a>';
				echo '</div>';
			echo '</li>';
		}
	?>

	<form method="POST" action="#" onsubmit="return false;">
		<input type="search" placeholder="Search..." name="searchbar" id="searchbar" size="30" />
	</form>
	<div id="generalSearcher"></div>

	<div id="hud_icons">
		<!--<img width="16" height="16" id="img_bug" src="./img/icon_bug_22x22.png" />-->
		<div id="announcements_indicator"></div>
		<div id="announcements_callout"></div>
	</div>
</ul>	


<div id="dropdown"></div>
<div id="dropdown2"></div>
