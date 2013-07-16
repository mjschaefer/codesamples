function validate_settings() {
	var valid = true;

	if($('input[name=employee_password_old]').val() != '') {
		if($('input[name=employee_password_new]').val() != '' && $('input[name=employee_password_new_confirm]').val() != '') {
			if($('input[name=employee_password_new]').val() === $('input[name=employee_password_new_confirm]').val()) {
				valid = true;
			} else {
				valid = false;
				$('#pass_new_error').html('These must match');
				$('input[name=employee_password_new]').addClass('error');
				$('input[name=employee_password_new_confirm]').addClass('error');
			}
		} else {
			valid = false;

			$('#pass_new_error').html('New Password?');
			$('input[name=employee_password_new]').addClass('error');
			$('input[name=employee_password_new_confirm]').addClass('error');
		}
	}

	return valid;
}

function change_view(target, fresh) {
	$('#settings_left > a').removeClass('selected');
	$('a[targ=' + target + ']').addClass('selected');

	$('.setting_wrap:visible').fadeToggle('fast', function() {	
		$('#' + target).fadeToggle('fast');
	});

	if(fresh) {
		$('#' + target).fadeToggle('fast');			
	}

	$('input[name=current_view]').val(target);

}