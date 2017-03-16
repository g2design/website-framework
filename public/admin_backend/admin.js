$(document).ready(function () {
	$('.timepicker').pickatime();
	$('.timeclear').click(function (e) {
		e.preventDefault();
		$(this).parents('.row').find('input').val('');
	});

	tinymce.init({selector: 'textarea'});

	$('a.async-link').click(function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		console.log("Calling " + href);
		$.get(href, function (response) {
			console.log(response);
		});
//		return false;

	});



	$('.chips').each(function () {
		var _this = $(this);
		var target = $(this).data('target');
		console.log('Applying to ' + target);
		var input = $(target);

		//Convert existing values to chips
		var chips = input.val();
		var options = {};
		try {
			if (tags = JSON.parse(chips)) {
				options.data = tags;
			}
		} catch (Exception) {

		}
		
		if($(this).attr('data-availabletags')) {
			data = JSON.parse($(this).attr('data-availabletags'));
			console.log(data);
			
			options.autocompleteData = data;
		}

		$(this).material_chip(options);

		$(this).on('chip.add', function (e, chip) {
			console.log('Apply to input field');
			input.val(JSON.stringify($(this).material_chip('data')));
		});
	});
});