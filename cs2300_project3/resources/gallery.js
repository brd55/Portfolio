$(document).ready(function() {imgGal();});
	var current;
	function imgGal(){
	//Initializes gallery elements and adds a bigger version of the image
	$('.photothumb').click(function() {
		current = $(this);
		$('body').append('<div id="big' + current.data('num') + '" class="bigimgdiv"><img src="' + current.attr('src') + '" class="bigimg" /></div>');
		$('body').append('<div id="caption">' + '&nbsp;' + current.data('caption') + '&nbsp;' + '</div>');
		$.fn.setOffset($('.bigimg').width(), $('.bigimg').height());
		$('body').append('<div id="bigbg"></div>');
		$('body').append('<div id="arrowleft"><img src="images/buttons/button_left.gif" id="actualleft"/></div>');
		$('body').append('<div id="arrowright"><img src="images/buttons/button_right.gif" id="actualright"/></div>');
	});
	//Right click event
	$(document).on('click', '#arrowright', function() {
		current = $('.photothumb:eq(' + current.data('num') + ')');
		//Checks if image is the last in the gallery
		if (current.length == 0) {
		current = $('.photothumb:eq(0)');
		}
		$('.bigimgdiv').html('<img src="' + current.attr('src') + '" class="bigimg" />');
		$('.bigimgdiv').attr('id', 'big' + current.data('num'));
		$('#caption').html('&nbsp;' + current.data('caption') + '&nbsp;');
		//Centers image
		$.fn.setOffset($('.bigimg').width(), $('.bigimg').height());
	});
	//Left click event
	$(document).on('click', '#arrowleft', function() {
		current = $('.photothumb:eq(' + (current.data('num') - 2) + ')');
		//Checks if image is the first in the gallery
		if (current.length == 0) {
		current = $('.photothumb:last');
		}
		$('.bigimgdiv').html('<img src="' + current.attr('src') + '" class="bigimg" />');
		$('.bigimgdiv').attr('id', 'big' + current.data('num'));
		$('#caption').html('&nbsp;' + current.data('caption') + '&nbsp;');
		//Centers image
		$.fn.setOffset($('.bigimg').width(), $('.bigimg').height());
	});
	//Exits gallery
	$(document).on('click', '#bigbg', function() {
		$(this).remove();
		$('.bigimgdiv').remove();
		$('#arrowleft').remove();
		$('#arrowright').remove();
		$('#caption').remove();
	});
}
//Centers center of image
$.fn.setOffset = function(width, height) {
	var leftInt = width; 
	var topInt = height;
	var captionInt = Math.floor($('#caption').width() / 2) -5;
	var captionStr = '-' + captionInt + 'px';
	leftInt = leftInt / 2;
	var leftStr = '-' + leftInt + 'px';
	topInt = topInt / 2;
	$('#caption').css('margin-top', ((topInt + 60) + 'px'));
	topInt = topInt - 50;
	var topStr = '-' + topInt + 'px';
	$('.bigimgdiv').css('margin-left', leftStr);
	$('.bigimgdiv').css('margin-top', topStr);
	$('#caption').css('margin-left', captionStr);
	
}