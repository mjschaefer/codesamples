<?php include('./includes/tinymce_init.php'); ?>
<script>
	init_announcements_editor();

	jQuery.validator.messages.required = "";
	$(document).ready(function() {
		$("#page_title").html('<img src="./img/icon_announcement_48x48.png" /><h2 class="title">Announcements</h2><span id="h5_container"><h5><a href="?page=ui_announcements&view=inbox" id="inbox">Inbox</a><a href="?page=ui_announcements&view=sent" id="sent">Sent</a><a href="?page=ui_announcements&view=archive" id="archive">Archive</a><br /></h5></span>');

		<?php
			$hire_date = $_SESSION['hire_date'];
			
			if(!isset($_GET['paginate_page'])) {
				$_GET['paginate_page'] = 1;
			}

			if(!isset($_GET['view'])) {
				$_GET['view'] = 'inbox';
			}

			$per_page = 20;
			$start = ($_GET['paginate_page'] - 1) * $per_page;

			$username = $_SESSION['username'];
			$fullname = $_SESSION['name'];

			switch($_GET['view']) {
				case 'inbox':					
					echo "$('#inbox').addClass('selected');";

					$query = "SELECT id FROM announcements WHERE (intended_for = 'everyone' OR intended_for = '$username') AND date_created >= '$hire_date' AND has_archived NOT LIKE '%,$username%' ORDER BY date_created DESC";
					$count_data = mysql_query($query) or die(mysql_error());
					$total_announcements = mysql_num_rows($count_data);

					$query = "SELECT id, date_created, title, created_by, intended_for, has_read FROM announcements WHERE (intended_for = 'everyone' OR intended_for = '$username') AND date_created >= '$hire_date' AND has_archived NOT LIKE '%,$username%' ORDER BY date_created DESC LIMIT $start, $per_page";
					break;
				case 'sent':
					echo "$('#sent').addClass('selected');";

					$query = "SELECT id FROM announcements WHERE created_by = '$fullname' AND date_created >= '$hire_date' ORDER BY date_created DESC";
					$count_data = mysql_query($query) or die(mysql_error());
					$total_announcements = mysql_num_rows($count_data);

					$query = "SELECT id, date_created, title, created_by, intended_for, has_read FROM announcements WHERE created_by = '$fullname' AND date_created >= '$hire_date' ORDER BY date_created DESC LIMIT $start, $per_page";
					break;
				case 'archive':
					echo "$('#archive').addClass('selected');";

					$query = "SELECT id FROM announcements WHERE (intended_for = 'everyone' OR intended_for = '$username') AND date_created >= '$hire_date' ORDER BY date_created DESC";
					$count_data = mysql_query($query) or die(mysql_error());
					$total_announcements = mysql_num_rows($count_data);

					$query = "SELECT id, date_created, title, created_by, intended_for, has_read FROM announcements WHERE (intended_for = 'everyone' OR intended_for = '$username') AND date_created >= '$hire_date' AND has_archived LIKE '%,$username%' ORDER BY date_created DESC LIMIT $start, $per_page";
					break;
				default:
					break;
			}

			$pages = ceil($total_announcements / $per_page);
			$pagination_html = '';
			if($_GET['paginate_page'] < $pages) {
				$pagination_html .= '<a href="?page=ui_announcements&view=' . $_GET['view'] . '&paginate_page=' . ($_GET['paginate_page'] + 1) . '">Next ' . $per_page . ' &gt;&gt;</a>';				
			}

			if($_GET['paginate_page'] > 1) {
				$pagination_html = '<a href="?page=ui_announcements&view=' . $_GET['view'] . '&paginate_page=' . ($_GET['paginate_page'] - 1) . '">&lt;&lt; Previous ' . $per_page . '</a>' . $pagination_html;
			}
		?>
		$("#h5_container").append('<h5><?php echo $pagination_html; ?></h5>');

		$("#announcement_add").click(function() {
			$.ajax({
				url: "./views/" + $(this).attr("href"),
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#save_note_button").click(function() {
						if($("form[name=form_announcements]").validate()) {
							$("form[name=form_announcements]").submit();
						}

						return false;
					});

					$("#cancel_button").click(function() {
						$("#dropdown").slideUp('fast', function() {
							$("#dropdown").html('');
						});

						return false;
					});
				}
			});
			return false;
		});
	});
</script>

<div id="wrapper_table">
	<table id="data_table" class="announcement_table">
		<tr>
			<th></th>
			<th>Date</th>
			<th>From</th>
			<th>Title</th>
			<th>To</th>
			<th style="margin: 0; padding: 0; text-align: center; width: 16px;"><a href="ajax_announcement_add.php" id="announcement_add"><img src="./img/icon_add_16x16.png" style="display: block;" /></a></th>
		</tr>
		<?php
			$announcement_data = mysql_query($query) or die(mysql_error());

			while($announcement_array = mysql_fetch_assoc($announcement_data)) {
				//figure out if the entry has been read by user
				$read = false;

				if($announcement_array['has_read'] != '') {
					if(strpos($announcement_array['has_read'], ',' . $_SESSION['username']) !== false) {
						$read = true;
					}
				}

				echo '<tr';
				echo ($read) ? '' : ' class="announcement_unread"';
				echo '>';
					echo '<td><a href="?page=ui_announcements_view&announcement=' . $announcement_array['id'] . '">';
						if(!$read) {							
							echo '<img src="./img/icon_new_16x16.png" />';
						} else {
							echo '&nbsp;';
						}
					echo '</a></td>';
					
					echo '<td><a href="?page=ui_announcements_view&announcement=' . $announcement_array['id'] . '">' . date('m-d-y \a\t g:i a', $announcement_array['date_created']) . '</a></td>';

					echo '<td><a href="?page=ui_announcements_view&announcement=' . $announcement_array['id'] . '">' . $announcement_array['created_by'] . '</a></td>';

					echo '<td><a href="?page=ui_announcements_view&announcement=' . $announcement_array['id'] . '">';
						$title_limiter = 75;
						if(strlen($announcement_array['title']) > $title_limiter) {
							for($x = 0; $x < $title_limiter; $x++) {
								echo $announcement_array['title'][$x];
							}
							echo ' ...';
						} else {
							echo $announcement_array['title'];
						}
					echo '</a></td>';

					echo '<td><a href="?page=ui_announcements_view&announcement=' . $announcement_array['id'] . '">' . $announcement_array['intended_for'] . '</a></td>';

					echo '<td><a href="?page=ui_announcements_view&announcement=' . $announcement_array['id'] . '">&nbsp;</a></td>';
				echo '</tr>';
			}
		?>
	</table>
</div>