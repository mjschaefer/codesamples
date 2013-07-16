<?php
	include('../includes/globals.inc.php');
	include('../includes/database.inc.php');

	mysql_connect($server, $login, $pass) or die("Unable to Connect");
	mysql_select_db($db) or die("Unable to select database");

	extract($_GET);	

	//see if we're saving
	if(isset($save)) {
		//double redundancy, make sure save is true
		if($save) {
			if(isset($cond)) {
				if($cond == 'add') {
					$entered_by = $_SESSION['name'];
					$query = "INSERT INTO notes_added(`date`,
													  `content`,
													  `note_id`,
													  `entered_by`) VALUES('" . addslashes($date) . "',
													  					   '" . addslashes(htmlentities($notes)) . "',
													  					   '$note_id',
													  					   '$entered_by')";
					if(mysql_query($query)) {
						echo 'true';
					} else {
						echo mysql_error();
					}
				}
			} else {
				$query = "UPDATE notes_added SET content='" . addslashes(htmlentities($data)) . "' WHERE id='$note_id'";
				if(mysql_query($query)) {
					echo 'true';
				} else {
					echo mysql_error();
				}
			}
		} else {
			echo 'I don\'t know what ' . $save . ' means';
		}
	} else {
		if(isset($note_id)) {
			//for added notes editing.  
			//outputs requested note
			$query = "SELECT * FROM notes_added WHERE id='$note_id'";
			$notes_query = mysql_query($query) or die(mysql_error());
			$notes_data = mysql_fetch_assoc($notes_query);

			echo stripslashes(html_entity_decode($notes_data['content']));
		}
	}
?>