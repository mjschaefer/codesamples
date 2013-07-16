<?php include('./includes/tinymce_init.php'); ?>
<script>
	init_announcements_editor();
	$(document).ready(function() {
		$(".button_print").click(function() {
			$.ajax({
				url: "./views/" + $(this).attr("href"),
				success: function(data) {
					$("#dropdown").html(data);
					$("#dropdown").slideDown('fast', function() {						
    					tinyMCE.execCommand('mceFocus',false,'announcements_editor');
					});
					
					//$('#knowledgebase_editor').html(orig_message);

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

		$query = "SELECT * FROM knowledgebase WHERE id='$knowledgebase'";
		$knowledgebase_data = mysql_query($query) or die(mysql_error());
		$knowledgebase_array = mysql_fetch_assoc($knowledgebase_data);
	?>
	<h1>
		<?php echo $knowledgebase_array['title']; ?>
		<?php
			if($knowledgebase_array['created_by'] != $_SESSION['name']) {
		?>
				<a href="ajax_announcement_add.php?re=kb&title=<?php echo $knowledgebase_array['title']; ?>&reply=<?php echo $knowledgebase_array['created_by_username']; ?>&id=<?php echo $knowledgebase_array['id']; ?>" id="button_print" class="action_button button_print">Msg. Author</a>

		<?php
			}
		?>

		<a href="#" onclick="window.print();return false;" id="button_print" class="action_button">Print</a>
	</h1>
	<img src="./img/icon_knowledgebase_64x64.png" />
	
	<h3>submitted by <?php echo $knowledgebase_array['created_by']; ?></h3>
	<h4><?php echo date('m-d-Y \a\t g:i a', $knowledgebase_array['date_created']); ?></h4>
	<h5>Tags: <?php echo $knowledgebase_array['tags']; ?></h5>
	<hr />
	<span class="content">
		<?php echo html_entity_decode(stripslashes($knowledgebase_array['content'])); ?>
	</span>
</div>