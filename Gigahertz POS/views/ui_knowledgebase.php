<?php include('./includes/tinymce_init.php'); ?>
<script>
	init_knowledgebase_editor();

	jQuery.validator.messages.required = "";
	$(document).ready(function() {
		$("#page_title").html('<img src="./img/icon_knowledgebase_48x48.png" /><h2 class="title">Knowledgebase</h2>');

		<?php
			if(!isset($_GET['tag'])) {
				$_GET['tag'] = 'all';
			}
			switch($_GET['tag']) {
				case 'all':
					$query = "SELECT id,date_created,title,created_by,tags FROM knowledgebase";
					break;
				
				default:
					break;
			}
		?>

		$("#knowledgebase_add").click(function() {
			$.ajax({
				url: "./views/" + $(this).attr("href"),
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast');

					$("#save_note_button").click(function() {
						if($("form[name=form_knowledgebase]").validate()) {
							$("form[name=form_knowledgebase]").submit();
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
	<table id="data_table" class="knowledgebase_table">
		<tr>
			<th></th>
			<th>Title</th>
			<th>From</th>
			<th>Date</th>
			<th>Tags</th>
			<th style="margin: 0; padding: 0; text-align: center; width: 16px;"><a href="ajax_knowledgebase_add.php" id="knowledgebase_add"><img src="./img/icon_add_16x16.png" style="display: block;" /></a></th>
		</tr>
		<?php
			$knowledgebase_data = mysql_query($query) or die(mysql_error());

			while($knowledgebase_array = mysql_fetch_assoc($knowledgebase_data)) {
				echo '<tr>';
					echo '<td><a href="?page=ui_knowledgebase_view&knowledgebase=' . $knowledgebase_array['id'] . '"></a></td>';

					echo '<td><a href="?page=ui_knowledgebase_view&knowledgebase=' . $knowledgebase_array['id'] . '">';
						$title_limiter = 50;
						if(strlen($knowledgebase_array['title']) > $title_limiter) {
							for($x = 0; $x < $title_limiter; $x++) {
								echo $knowledgebase_array['title'][$x];
							}
							echo ' ...';
						} else {
							echo $knowledgebase_array['title'];
						}
					echo '</a></td>';

					echo '<td><a href="?page=ui_knowledgebase_view&knowledgebase=' . $knowledgebase_array['id'] . '">' . $knowledgebase_array['created_by'] . '</a></td>';

					echo '<td><a href="?page=ui_knowledgebase_view&knowledgebase=' . $knowledgebase_array['id'] . '">' . date('m-d-y \a\t g:i a', $knowledgebase_array['date_created']) . '</a></td>';

					echo '<td><a href="?page=ui_knowledgebase_view&knowledgebase=' . $knowledgebase_array['id'] . '">' . $knowledgebase_array['tags'] . '</a></td>';

					echo '<td><a href="?page=ui_knowledgebase_view&knowledgebase=' . $knowledgebase_array['id'] . '">&nbsp;</a></td>';
				echo '</tr>';
			}
		?>
	</table>
</div>