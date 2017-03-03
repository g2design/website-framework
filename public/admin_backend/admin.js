$(document).ready(function(){
	$('.timepicker').pickatime();
	$('.timeclear').click(function(e){
		e.preventDefault();	
		$(this).parents('.row').find('input').val('');
	});
});