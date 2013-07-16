<?php
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	$query = "SELECT name_real, name_user FROM users ORDER BY name_real";
	$user_data = mysql_query($query) or die(mysql_error());
?>

<div id="wrapper_top">
	<form name="form_announcements" method="POST" action="./views/add_announcements.php<?php echo (isset($_GET['re'])) ? '?re=kb&id=' . $_GET['id'] : ''; ?>">
		<?php
			if(isset($_GET['title'])) {
				$title_prefix = 'RE: ';
				if(isset($_GET['re'])) {
					$title_prefix .= 'KB: ';
				}
			}
		?>

		<input class="required" name="title" size="94" placeholder="Title" <?php echo (isset($_GET['title'])) ? 'value="' . $title_prefix . '' . $_GET['title'] . '"': ''; ?> /><br />
		<input name="tags" size="70" placeholder="Tags" />
		<select name="recipiant">
			<option value="everyone">To Everybody</option>
			<?php
				while($user_array = mysql_fetch_assoc($user_data)) {
					echo '<option value="' . $user_array['name_user'] . '"';
						if(isset($_GET['reply'])) {
							if($user_array['name_user'] == $_GET['reply']) {
								echo ' selected="selected"';
							}
						}
					echo '>' . $user_array['name_real'] . '</option>';
				}
			?>
		</select>
		<textarea name="announcements_editor" id="announcements_editor" class="announcements_editor">
			<?php
				if(!isset($_GET['re'])) {
					if(isset($_GET['id'])) {
						$id = $_GET['id'];
						$query = "SELECT content, created_by, date_created FROM announcements WHERE id='$id'";
						$content_data = mysql_query($query) or die(mysql_error());
						$content_array = mysql_fetch_assoc($content_data);

						echo '<br />';
						echo '<div style="font-size: 10px; padding-left: 10px; border-left: 1px solid gray; font-style: italic;">';
							//echo 'Original Message ------------------------------------------------------------';
							echo '<div style="font-weight: bold;">' . $content_array['created_by'] . ' on ' . date('l \t\h\e jS \of F Y h:i:s A', $content_array['date_created']) . '</div>';
							echo html_entity_decode(stripslashes($content_array['content']));
						echo '</div>';
					}					
				} else {					
					$id = $_GET['id'];
					$query = "SELECT content, created_by, date_created FROM knowledgebase WHERE id='$id'";
					$content_data = mysql_query($query) or die(mysql_error());
					$content_array = mysql_fetch_assoc($content_data);

					echo '<br />';
					echo '<div style="font-size: 10px; padding-left: 10px; border-left: 1px solid gray; font-style: italic;">';
						//echo 'Original Message ------------------------------------------------------------';
						echo '<div style="font-weight: bold;">' . $content_array['created_by'] . ' on ' . date('l \t\h\e jS \of F Y h:i:s A', $content_array['date_created']) . '</div>';
						echo html_entity_decode(stripslashes($content_array['content']));
					echo '</div>';
				}
			?>
		</textarea>

		<a href="#" id="save_note_button" style="float: right; margin: 5px;">Send</a>
		<a href="#" id="cancel_button" style="float: right; margin: 5px;">Cancel</a>
	</form>
</div>

<script>
	init_announcements_editor();
</script>