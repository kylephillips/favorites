/**
* Gets the user favorites
*/
var Favorites = Favorites || {};

Favorites.UserFavorites = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.initialLoad = false;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-nonce-generated', function(){
			plugin.initalLoad = true;
			plugin.getFavorites();
		});
	}

	/**
	* Get the user favorites
	*/
	plugin.getFavorites = function()
	{
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : Favorites.formActions.favoritesarray
			},
			success: function(data){
				Favorites.userFavorites = data.favorites;
				$(document).trigger('favorites-user-favorites-loaded', [plugin.initalLoad]);

				// Deprecated Callback
				if ( plugin.initalLoad ) favorites_after_initial_load(Favorites.userFavorites);
			}
		});
	}

	return plugin.bindEvents();
}