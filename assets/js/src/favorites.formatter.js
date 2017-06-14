/**
* Formatting functionality
*/
var Favorites = Favorites || {};

Favorites.Formatter = function()
{
	var plugin = this;
	var $ = jQuery;

	/*
	*  Add Favorite Count to a button
	*/
	plugin.addFavoriteCount = function(html, count)
	{
		if ( !Favorites.jsData.button_options.include_count ) return html;
		if ( count <= 0 ) count = 0;
		html += ' <span class="simplefavorite-button-count">' + count + '</span>';
		return html;
	}

	/**
	* Decrement all counts by one
	*/
	plugin.decrementAllCounts = function(){
		var buttons = $('.simplefavorite-button.active.has-count');
		for ( var i = 0; i < buttons.length; i++ ){
			var button = $(buttons)[i];
			var count_display = $(button).find('.simplefavorite-button-count');
			var new_count = $(count_display).text() - 1;
			$(button).attr('data-favoritecount', new_count);
		}
	}
}