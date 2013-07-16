$(document).ready(function() {
	$('#billing_add').click(function() {
		var url = $(this).attr('href');
		//alert(url);
		
		$.ajax({
			url: url,
			success: function(data) {
				$('#dropdown').html(data);
				$('#dropdown').slideDown('fast', function() {					
					$('#process_button').click(function() {
						$('form[name=form_billing]').submit();
						return false;
					});

					$('#cancel_button').click(function() {
						$('#dropdown').slideUp('fast', function() {
							$('#dropdown').html('');
						});
					});
				});
			}
		});

		return false;
	});
});