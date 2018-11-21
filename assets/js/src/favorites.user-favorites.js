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
		$(window).on('load', function(){
			plugin.initialLoad = true;
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
			type: 'POST',
			datatype: 'json',
			data: {
				action : Favorites.formActions.favoritesarray
			},
			success: function(data){
				if ( Favorites.jsData.dev_mode ) {
					console.log('The current user favorites were successfully loaded.');
					console.log(data);
				}
				Favorites.userFavorites = data.favorites;
				$(document).trigger('favorites-user-favorites-loaded', [data.favorites, plugin.initialLoad]);
				$(document).trigger('favorites-update-all-buttons');

				// Deprecated Callback
				if ( plugin.initialLoad ) favorites_after_initial_load(Favorites.userFavorites);
			},
			error: function(data){
				if ( !Favorites.jsData.dev_mode ) return;
				console.log('The was an error loading the user favorites.');
				console.log(data);
			}
		});
	}

	return plugin.bindEvents();
}