<?php include('./includes/tinymce_init.php'); ?>
<script>
	init_announcements_editor();
	$(document).ready(function() {
		$("#button_print").click(function() {
			$.ajax({
				url: "./views/" + $(this).attr("href"),
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast', function() {						
    					tinyMCE.execCommand('mceFocus',false,'announcements_editor');
					});
					
					//$('#announcements_editor').html(orig_message);

					$("#save_note_button").click(function() {
						$("form[name=form_announcements]").submit();
						return false;
					});

					$("#cancel_button").click(function() {
						$("#dropdown").slideUp('fast', function() {
						});

						return false;
					});
				}
			});
			return false;			
		});
	});
</script>

<div id="wrapper_main">
	<?php
		extract($_GET);
		$hire_date = $_SESSION['hire_date'];

		$query = "SELECT * FROM announcements WHERE id='$announcement'";
		$announcement_data = mysql_query($query) or die(mysql_error());
		$announcement_array = mysql_fetch_assoc($announcement_data);
	?>
	<h1>
		<?php echo $announcement_array['title']; ?>
		<?php
			if($announcement_array['created_by'] != $_SESSION['name']) {
		?>
				<a href="ajax_announcement_add.php?title=<?php echo $announcement_array['title']; ?>&reply=<?php echo $announcement_array['created_by_username']; ?>&id=<?php echo $announcement_array['id']; ?>" id="button_print">Reply</a>
		<?php
			}
		?>
		<a href="./views/add_announcements.php?archive=true&id=<?php echo $announcement_array['id']; ?>" id="button_payment">Archive</a>
	</h1>
	<img src="./img/icon_announcement_64x64.png" />
	
	<h3>from: <?php echo $announcement_array['created_by']; ?></h3>
	<h3>to: <?php echo $announcement_array['intended_for']; ?></h3>
	<br />
	<h4><?php echo date('m-d-Y \a\t g:i a', $announcement_array['date_created']); ?></h4>
	<h5>Tags: <?php echo $announcement_array['tags']; ?></h5>
	<hr />
	<span class="content">
		<?php echo html_entity_decode(stripslashes($announcement_array['content'])); ?>
	</span>
</div>