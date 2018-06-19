/**
* Updates Favorite Buttons as Needed
*/
var Favorites = Favorites || {};

Favorites.ButtonUpdater = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.utilities = new Favorites.Utilities;
	plugin.formatter = new Favorites.Formatter;
	plugin.buttonFormatter = new Favorites.ButtonOptionsFormatter;

	plugin.activeButton;
	plugin.data = {};

	plugin.bindEvents = function()
	{
		$(document).on('favorites-update-all-buttons', function(){
			plugin.updateAllButtons();
		});
		$(document).on('favorites-list-updated', function(event, list){
			plugin.updateAllButtons(list);
		});
	}

	/*
	* Update all favorites buttons to match the user favorites
	* @param list object (optionally updates button in list)
	*/
	plugin.updateAllButtons = function(list)
	{
		if ( typeof Favorites.userFavorites === 'undefined' ) return;
		var buttons = ( typeof list === undefined && list !== '' ) 
			? $(list).find(Favorites.selectors.button) 
			: $(Favorites.selectors.button);
		
		for ( var i = 0; i < $(buttons).length; i++ ){
			plugin.activeButton = $(buttons)[i];
			if ( Favorites.authenticated ) plugin.setButtonData();

			if ( Favorites.authenticated && plugin.utilities.isFavorite( plugin.data.postid, plugin.data.site_favorites ) ){
				plugin.buttonFormatter.format($(plugin.activeButton), true);
				$(plugin.activeButton).addClass(Favorites.cssClasses.active);
				$(plugin.activeButton).removeClass(Favorites.cssClasses.loading);
				$(plugin.activeButton).find(Favorites.selectors.count).text(plugin.data.favorite_count);
				continue;
			}

			plugin.buttonFormatter.format($(plugin.activeButton), false);
			$(plugin.activeButton).removeClass(Favorites.cssClasses.active);
			$(plugin.activeButton).removeClass(Favorites.cssClasses.loading);
			$(plugin.activeButton).find(Favorites.selectors.count).text(plugin.data.favorite_count);
		}
	}


	/**
	* Set the button data
	*/
	plugin.setButtonData = function()
	{
		plugin.data.postid = $(plugin.activeButton).attr('data-postid');
		plugin.data.siteid = $(plugin.activeButton).attr('data-siteid');
		plugin.data.favorite_count = $(plugin.activeButton).attr('data-favoritecount');
		plugin.data.site_index = plugin.utilities.siteIndex(plugin.data.siteid);
		plugin.data.site_favorites = Favorites.userFavorites[plugin.data.site_index].posts;
		if ( plugin.data.favorite_count <= 0 ) plugin.data.favorite_count = 0;
	}

	return plugin.bindEvents();
}