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

});