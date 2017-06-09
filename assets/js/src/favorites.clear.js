/**
* Clears all favorites for the user
*
* Events:
* favorites-cleared: The user's favorites have been cleared. Params: clear button
*/
var Favorites = Favorites || {};

Favorites.Clear = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.activeButton; // The active "clear favorites" button

	plugin.bindEvents = function()
	{
		$(document).on('click', Favorites.selectors.clear_button, function(e)
		{
			e.preventDefault();
			plugin.activeButton = $(this);
			plugin.clearFavorites();
		});
	}

	/*
	* Submit an AJAX request to clear all of the user's favorites
	*/
	plugin.clearFavorites = function(button)
	{
		plugin.loading(true);
		var site_id = $(plugin.activeButton).attr('data-siteid');
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : Favorites.formActions.clearall,
				nonce : Favorites.jsData.nonce,
				siteid : site_id,
			},
			success : function(data){
				Favorites.userFavorites = data.favorites;
				plugin.loading(false);
				$(document).trigger('favorites-cleared', [plugin.activeButton]);
			}
		});
	}

	/**
	* Toggle the button loading state
	*/
	plugin.loading = function(loading)
	{
		if ( loading ){
			$(plugin.activeButton).addClass(Favorites.cssClasses.loading);
			$(plugin.activeButton).attr('disabled', 'disabled');
			return;
		}
		$(plugin.activeButton).removeClass(Favorites.cssClasses.loading);
		$(plugin.activeButton).attr('disabled', false);
	}

	return plugin.bindEvents();
}