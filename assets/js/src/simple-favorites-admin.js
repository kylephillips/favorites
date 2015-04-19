jQuery(function($){

/**
* --------------------------------------------------------------------
* Toggle dependency content under general settings
* --------------------------------------------------------------------
*/

$(document).ready(function(){
	var items = $('.simplefavorites-dependency');
	$.each(items, function(i, v){
		toggle_dependency_content($(this));
	});
});

$('.simplefavorites-dependency-cb').on('change', function(){
	var item = $(this).parents('.simplefavorites-dependency');
	toggle_dependency_content(item);
});

function toggle_dependency_content(item)
{
	if ( $(item).find('.simplefavorites-dependency-cb').is(':checked') ){
		$(item).find('.simplefavorites-dependency-content').hide();
	} else {
		$(item).find('.simplefavorites-dependency-content').show();
	}
}

/**
* --------------------------------------------------------------------
* Settings Fields Toggling
* --------------------------------------------------------------------
*/
$(document).on('change', '.simplefavorites-display-anonymous', function(){
	toggle_anonymous_save();
});
$(document).ready(function(){
	toggle_anonymous_save();
});

function toggle_anonymous_save()
{
	if ( $('.simplefavorites-display-anonymous').is(':checked') ){
		$('.simplefavorites-save-anonymous').show();
	} else {
		$('.simplefavorites-save-anonymous').hide().find('input[type="checkbox"]').attr('checked', false);	
	}
}

// Post Type Display
$(document).on('change', '*[data-sf-posttype]', function(){
	toggle_posttype_display();
});
$(document).ready(function(){
	toggle_posttype_display();
});

function toggle_posttype_display()
{
	var posttypes = $('*[data-sf-posttype]');
	$.each(posttypes, function(i, v){
		var selections = $(this).parents('.simple-favorites-posttype').find('.simple-favorites-posttype-locations');
		if ( $(this).is(':checked') ){
			$(selections).show();
		} else {
			$(selections).hide();
		}
	});
}

// Loading Indicators
$(document).ready(function(){
	toggle_loading_indicators();
});
$(document).on('change', '.simplefavorites-display-loading', function(){
	toggle_loading_indicators();
});
function toggle_loading_indicators()
{
	if ( $('.simplefavorites-display-loading').is(':checked') ){
		$('.simplefavorites-loading-fields').show();
	} else {
		$('.simplefavorites-loading-fields').hide();
	}
}


});