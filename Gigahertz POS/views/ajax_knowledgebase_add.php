<?php
	include('../includes/database.inc.php');
	include('../includes/globals.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");
?>

<div id="wrapper_top">
	<form name="form_knowledgebase" method="POST" action="./views/add_knowledgebase.php">
		<input class="required" name="title" size="94" placeholder="Title" /><br />
		<input name="tags" size="94" placeholder="Tags" />

		<textarea name="knowledgebase_editor" id="knowledgebase_editor" class="knowledgebase_editor">
		</textarea>

		<a href="#" id="save_note_button" style="float: right; margin: 5px;">Send</a>
		<a href="#" id="cancel_button" style="float: right; margin: 5px;">Cancel</a>
	</form>
</div>

<script>
	init_knowledgebase_editor();
</script>