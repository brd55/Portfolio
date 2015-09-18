//Change 4/27/15 by Brendan: added email bar function
//Change 5/3/15 by Brendan: Replaced text box function with placeholder attribute
function contentSize() {
	var win = $(window).height();
	var topNav = $('#topNav').height();
	var bottomNav = $('#bottomNav').height();
	var header = $('#headerDiv').height();
	$('#bodyDiv').height(win - topNav - bottomNav - header);
}
var searching = false;
var email = false;
window.onload = function(){
	contentSize();
	$('.contentThumb').mouseover(function() {
		$(this).css('width', $(this)[0].naturalWidth);
		$(this).css('height', $(this)[0].naturalHeight);
		$(this).parent().css('height', $(this)[0].naturalHeight);
		$(this).parent().css('margin-bottom', "20px");
	});
	$('.contentThumb').mouseout(function() {
		$(this).css('width', 100);
		$(this).css('height', 100);
		$(this).parent().css('height', '');
		$(this).parent().css('margin-bottom', "0px");
	});
}
$(window).resize(function(){
	contentSize();
});
//Filtering
var request;
$(document).ready( function() {
	request = null;

	$("#sub").change(function(){
		filter();
		$('#scidDefault').text('Show all categories');
	});
	$("#season").change(function(){
		filter();
		$('#season_idDefault').text('Show all seasons');
	});
	$("#gender").change(function(){
		filter();
		$('#gidDefault').text('Show all genders');
	});
	$("#searchBar").keyup(filter);
});

function filter(){
	//abandon active requests
	if (request) {request.abort();}	

	//pull values from filtering form
	var subFilter = $("#sub").val();
	var seasonFilter = $("#season").val();
	var genderFilter = $("#gender").val();
	var keyword = $("#searchBar").val();

	//prepare data to send as JSON
	var dataToSend = {key: keyword};
	if (subFilter !== ""){
		dataToSend.scid = subFilter;
	}
	if (seasonFilter !== ""){
		dataToSend.season_id = seasonFilter;
	}
	if (genderFilter !== ""){
		dataToSend.gid = genderFilter;
	}
	//AJAX call
	request = $.ajax( {
		url:"includes/filter.php",
		type: "POST",
		data: dataToSend,
		dataType: "json",
		error: function(jqXHR, textStatus, errorThrown) {
  		console.log(textStatus, errorThrown);
		}
	});

	request.done(displayFilter);
}

function displayFilter(response){
	//clear search results
	$(".searchDiv").remove();
	$("#noResults").remove();
	if(response.length == 0 && $('#emptySearchPrompt').length == 0){
		$("#bodyDiv").append("<div id='noResults' class='centeredDiv contentDiv'>Sorry, no search results were found.</div>");
	}
	$.each(response, function(d, e){
		$("#bodyDiv").append("<div class='searchDiv contentDiv'>" + 
			"<a href='" + e.URL + "' class='contentLink'><span class='contentThumbSpan'>" + 
				"<img src='" + e.img_URL + "' class='contentThumb' alt='" + e.description + "'/><br /><br /></span>" + 
				"<h4 class='contentH4'>" + e.name + " </h4>" + e.description + " <br /><br />" + "<text class='contentTags'>" + e.scid + " &nbsp;&nbsp;&nbsp; " + e.gid + " &nbsp;&nbsp;&nbsp; " + e.season_id + "</text>" + 
			"</a>&nbsp;&nbsp;&nbsp;" + 
			(e.alreadyLiked ? "" : "<span class='love' onclick='loveItem(" + e.item_id + ", $(this))'><img src='pictures/love_arrow.gif' alt='Love this!'/>Love this!</span>") +
			"<text class='likeNum'>&nbsp;&nbsp;&nbsp;" + e.like_num + (e.like_num == 1 ? " person":" people") + " love" + (e.like_num == 1 ? "s":"") + " this!</text>" + 
		"</div>");
	});
	$('.contentThumb').mouseover(function() {
		$(this).css('width', $(this)[0].naturalWidth);
		$(this).css('height', $(this)[0].naturalHeight);
		$(this).parent().css('height', $(this)[0].naturalHeight);
	});
	$('.contentThumb').mouseout(function() {
		$(this).css('width', 100);
		$(this).css('height', 100);
		$(this).parent().css('height', '');
	});
}

function loveItem(itemId, element) {
		if (request) {
			request.abort();
		}	
		var dataToSend= { 'item_id' : itemId };
		request = $.ajax({
			url:"includes/like.php",
			type: "post",
			data:dataToSend,
			dataType: "json"
			});
		request.done(function(d){
			if(d.error) {
				$('#bodyDiv').prepend("There was an error");
			}
			else if(d.alreadyLoved) {
				console.log('test');
				$('#bodyDiv').prepend("You already love this post");
			}
			else {
				element.hide();
				if(d.like_num == 1) {
					element.next().html("&nbsp;&nbsp;" + "1 person loves this!");
				}
				else {
					element.next().html("&nbsp;&nbsp;" + d.like_num + " people love this!");
				}
			}
		});
	}
