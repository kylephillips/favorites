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
	plugin.utilities = new Favorites.Utilities;
	plugin.formatter = new Favorites.Formatter;

	plugin.bindEvents = function()
	{
		$(document).on('click', Favorites.selectors.clear_button, function(e){
			e.preventDefault();
			plugin.activeButton = $(this);
			plugin.clearFavorites();
		});
		$(document).on('favorites-updated-single', function(){
			plugin.updateClearButtons();
		});
		$(document).on('favorites-user-favorites-loaded', function(){
			plugin.updateClearButtons();
		});
	}

	/*
	* Submit an AJAX request to clear all of the user's favorites
	*/
	plugin.clearFavorites = function()
	{
		plugin.loading(true);
		var site_id = $(plugin.activeButton).attr('data-siteid');
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : Favorites.formActions.clearall,
				siteid : site_id
			},
			success : function(data){
				if ( Favorites.jsData.dev_mode ){
					console.log('Favorites list successfully cleared.');
					console.log(data);
				}
				Favorites.userFavorites = data.favorites;
				plugin.formatter.decrementAllCounts();
				plugin.loading(false);
				plugin.clearSiteFavorites(site_id);
				$(document).trigger('favorites-cleared', [plugin.activeButton, data.old_favorites]);
				$(document).trigger('favorites-update-all-buttons');
			},
			error : function(data){
				if ( !Favorites.jsData.dev_mode ) return;
				console.log('There was an error clearing the favorites list.');
				console.log(data);
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
	}

	/*
	* Update disabled status for clear buttons
	*/
	plugin.updateClearButtons = function()
	{
		var button;
		var siteid; 
		for ( var i = 0; i < $(Favorites.selectors.clear_button).length; i++ ){
			button = $(Favorites.selectors.clear_button)[i];
			siteid = $(button).attr('data-siteid');
			for ( var c = 0; c < Favorites.userFavorites.length; c++ ){
				if ( Favorites.userFavorites[c].site_id !== parseInt(siteid) ) continue;
				if ( plugin.utilities.objectLength(Favorites.userFavorites[c].posts) > 0 ) {
					$(button).attr('disabled', false);
					continue;
				}
				$(button).attr('disabled', 'disabled');
			}
		}
	}

	/**
	* Clear out favorites for this site id (fix for cookie-enabled sites)
	*/
	plugin.clearSiteFavorites = function(site_id)
	{
		$.each(Favorites.userFavorites, function(i, v){
			if ( this.site_id !== parseInt(site_id) ) return;
			Favorites.userFavorites[i].posts = {};
		});
	}

	return plugin.bindEvents();
}