var tinyMCE_html = '<span style="padding: 5px; font-size: 12px; font-weight: bold; display: block;">(@ to add services)</span><textarea name="notes_editor" id="notes_editor" class="notes_editor"></textarea><input type="submit" value="Save Note" id="save_note_button" style="float: right" note_id="" table="" note_type="" mode="edit" /><input type="submit" value="Cancel" id="cancel_button" style="float: right" />';

$('#dropdown').html(tinyMCE_html);

$(document).ready(function() {
	$('#edit_pc_info').click(function() {
		//this will grab the target url
		var c_id = $(this).attr("note_id");
		var targ = "./views/ajax_pc_info_edit.php?note_id=" + c_id + "&cond=edit";

		$.ajax({
			url: targ,
			success: function(data) {
				$("#dropdown").html(data);
				$("#dropdown").slideDown('fast');

				$("#process_button").click(function() {
					$("form[name=pc_info_edit]").submit();
					return false;
				});

				$("#cancel_button").click(function() {
					close_dropdown();

					$("#dropdown").slideUp('fast', function() {
						$("#dropdown").html(tinyMCE_html);
					});

					return false;
				});
			}
		});
		return false;
	});

	$('#note_status').change(function() {
		var temp_status = $(this).val();
		var n_id = $(this).attr("n_id");
		var targ = "./views/ajax_save_note_status.php?note_id=" + n_id + "&status=" + temp_status;

		$("#status_flash").html("<div id=\"spin\"></div>");

		var opts = {
			lines: 6, // The number of lines to draw
			length: 2, // The length of each line
			width: 2, // The line thickness
			radius: 2, // The radius of the inner circle
			color: '#fff', // #rbg or #rrggbb
			speed: 1, // Rounds per second
			trail: 100, // Afterglow percentage
			shadow: true // Whether to render a shadow
		};

		var spinner = new Spinner(opts).spin(document.getElementById('spin'));

		$.ajax({
			url: targ,
			success: function(data) {
				$("#status_flash").html(data);

				$(".status_fade").delay(1000).fadeOut('slow');
			}
		});
	});

	//pops up the dialog with requested note for editing.
	$('.a_notes').each(function () {
		//this will grab the target url
		var n_id = $(this).attr("note_id");
		var table = $(this).attr("table");
		var type = $(this).attr("note_type");
		var targ = "./views/" + $(this).attr("href") + "?note_id=" + n_id + "&table=" + table + "&type=" + type;

		$(this).click(function() {
			$('#dropdown').html(tinyMCE_html);
			init_notes_editor();

			//tinyMCE.get('notes_editor').setProgressState(1);

			$.ajax({
				url: targ,
				success: function(data) {
					tinyMCE.get('notes_editor').setContent(data);
					//tinyMCE.get('notes_editor').setProgressState(0);
				}
			});

			$('#save_note_button').attr("note_id", n_id);
			$('#save_note_button').attr("mode", "edit");
			$('#save_note_button').attr("table", table);
			$('#save_note_button').attr("note_type", type);

			save_note_button_init();

			$("#cancel_button").click(function() {
				close_dropdown();

				$("#dropdown").slideUp('fast', function() {
					tinyMCE.get('notes_editor').setContent('');
				});

				return false;
			});

			$('#dropdown').slideDown('fast');
    		//tinyMCE.execCommand('mceFocus',false,'notes_editor');
			return false;
		});
	});

	$('#a_add').each(function() {		
		var note_add_id = $(this).attr("note_id");

		$(this).click(function() {
			$('#dropdown').html(tinyMCE_html);
			init_notes_editor();

			$('#save_note_button').attr("note_id", note_add_id);
			$('#save_note_button').attr("mode", "add");
			$('#dropdown').slideDown('fast');
    		//tinyMCE.execCommand('mceFocus',false,'notes_editor');	

			save_note_button_init();

			$("#cancel_button").click(function() {
				close_dropdown();
				
				$("#dropdown").slideUp('fast', function() {
					tinyMCE.get('notes_editor').setContent('');
				});

				return false;
			});

			return false;				
		});
	});
});

function save_note_button_init() {
	//for saving edited notes
	$('#save_note_button').each(function() {
		//this is loaded when the page is...it will be blank if any vars are set here
		//for the love of fucking god don't put any goddamn variable declarations here

		$(this).click(function () {
			if($(this).attr("mode") == "edit") {
				//tinyMCE.get('notes_editor').setProgressState(1);
				//set them here on the click
				var save_n_id = $(this).attr("note_id");
				var save_table = $(this).attr("table");
				var save_type = $(this).attr("note_type");
				var targ = "./views/ajax_notes_single.php?type=" + save_type + "&table=" + save_table + "&save=true&note_id=" + save_n_id + "&data=" + escape(tinyMCE.get('notes_editor').getContent());
				
				$.ajax({
					url: targ,
					success: function(data) {
						if(data == 'true') {
							//tinyMCE.get('notes_editor').setProgressState(0);
							tinyMCE.get('notes_editor').setContent('');
							location.reload();
						} else {
							tinyMCE.get('notes_editor').setContent(data);
						}
					}
				});
			}

			if($(this).attr("mode") == "add") {
				//set them here on the click
				var save_n_id = $(this).attr("note_id");
				var targ = "./views/ajax_notes_single.php?cond=add&table=notes_added&save=true&note_id=" + save_n_id + "&data=" + escape(tinyMCE.get('notes_editor').getContent());
				$.ajax({
					url: targ,
					success: function(data) {
						if(data == 'true') {
							tinyMCE.get('notes_editor').setContent('');
							location.reload();
						} else {
							tinyMCE.get('notes_editor').setContent(data);
						}
					}
				});					
			}
		});
	});
}

function print_note(note_num) {
	window.open('./views/ui_notes_print.php?note_id=' + note_num);
}

function prompt_delete_note(note_id) {
	return confirm('This will delete any invoice, payments, and other notes associated with note #' + note_id + '.\n\nThis operation cannot be undone.');
}

function mce_detect_keypress(e) {
	var dropdown_html = '<div id="dropdown_close"><a href="#" onclick="close_dropdown(); return false;">X</a></div>';

	dropdown_html += '<form id="service_form" onsubmit="return false;">';
		dropdown_html += '<input name="service" type="text" size="55" />';
		//dropdown_html += '<input type="submit" value="Add">';
	dropdown_html += '</form>';
	dropdown_html += '<div id="service_search"></div>';

	if(e.type == "keydown") {
		//alert(e.keyCode);

		//187 for +
		//50 for @
		//40 for down arrow
		//38 for up arrow
		//27 for escape
		//13 for enter
		if(e.keyCode == "50" && e.shiftKey) {
			$('#dropdown2').html(dropdown_html);

			//onkeydown for the service finder
			$('input[name=service]').keydown(function(event_a) {
				if(event_a.keyCode == "27") {
					$('#dropdown2').slideUp('fast');
					tinyMCE.execInstanceCommand('textarea_notes', "mceInsertContent", false, '');
				}
			});

			$('input[name=service]').keyup(function(event_b) {
				if(event_b.keyCode != "40" && event_b.keyCode != "38") {
					if($(this).val() != '') {		
						var targ = "./views/ajax_serviceSearch.php?query=" + $(this).val();

						$.ajax({
							url: targ,
							success: function(data) {
								$("#service_search").html(data);
								$("#service_search").slideDown('fast');

								//hover over service entry logic
								$(".service_entry").hover(function() {
									$('.service_entry').removeClass('service_selected');
									$(this).addClass('service_selected');
								});
							}
						});
					}
				}

				if(event_b.keyCode == "13") {
					if($('#service_search').is(":visible")) {
						$('.service_selected').click();
					}
				}

				//down arrow logic
				if(event_b.keyCode == "40") {
					if(!($('.service_selected').is('.service_last'))) {
						$(".service_entry.service_selected").nextAll(".service_entry:first").andSelf().toggleClass("service_selected");
					}
				}

				//up arrow logic
				if(event_b.keyCode == "38") {
					if(!($('.service_selected').is('.service_first'))) {
						$(".service_entry.service_selected").prevAll(".service_entry:first").andSelf().toggleClass("service_selected");
					}
				}

				if($('input[name=service]').val() == '') {
					$('#service_search').slideUp('fast');
				}
			});

			/*$('input[name=service]').blur(function() {
				$('#dropdown2').slideUp('fast');
			})*/

			$('#dropdown2').slideDown('fast');
			$('input[name=service]').focus();

			//insert_service('Spyware/Virus Removal', '2395728375235');
		}
	}

	return true;
}

function insert_service(title, upc, price) {
	if(tinyMCE.activeEditor.getContent().length == 0) {
		nbsp = '&nbsp;';
	} else {
		nbsp = '';
	}

	var html = nbsp + '<input type="button" title="' + upc + '" value="' + title + ' - ' + price + '" disabled="disabled" />';

	tinyMCE.execInstanceCommand('notes_editor', "mceInsertContent", false, html);

	$('#dropdown2').slideUp('fast');
}

function close_dropdown() {
	$('#dropdown2').slideUp('fast');
}