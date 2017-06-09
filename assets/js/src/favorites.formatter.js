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
		if ( Favorites.jsData.include_count !== '1' ) return html;
		html += ' <span class="simplefavorite-button-count">' + count + '</span>';
	}
}