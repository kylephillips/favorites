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
		if ( Favorites.jsData.ajax_type === 'wp_api' ){
			var url = Favorites.api_endpoints.user_favorites;
			var data = {
				// user_id : Favorites.jsData.user_id
				user_id : 2
			};
		} else {
			var url = Favorites.jsData.ajaxurl;
			var data = {
				action : Favorites.formActions.nonce
			}
		}

		$.ajax({
			url: url,
			type: 'POST',
			datatype: 'json',
			data: data,
			success: function(data){
				console.log(data);
				console.log(url);
				if ( Favorites.jsData.dev_mode ) {
					console.log('The current user favorites were successfully loaded.');
					console.log(data);
				}
				Favorites.userFavorites = data.favorites;
				$(document).trigger('favorites-user-favorites-loaded', [plugin.initalLoad]);
				$(document).trigger('favorites-update-all-buttons');

				// Deprecated Callback
				if ( plugin.initalLoad ) favorites_after_initial_load(Favorites.userFavorites);
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