/**
* Primary Favorites Admin Initialization
* @package Favorites
* @author Kyle Phillips - https://github.com/kylephillips/favorites
*
*/

jQuery(document).ready(function(){
	new FavoritesAdmin.Factory;
});

var FavoritesAdmin = FavoritesAdmin || {};

/**
* DOM Selectors Used by the Plugin
*/
FavoritesAdmin.selectors = {
	dependencyCheckbox : '.simplefavorites-dependency-cb',
	dependencyItem : '.simplefavorites-dependency',
	dependencyContent : '.simplefavorites-dependency-content',
	anonymousCheckbox : '.simplefavorites-display-anonymous',
	anonymousCount : '.simplefavorites-save-anonymous'
}

/**
* CSS Classes Used by the Plugin
*/
FavoritesAdmin.cssClasses = {
}

/**
* Localized JS Data Used by the Plugin
*/
FavoritesAdmin.jsData = {
}

/**
* WP Form Actions Used by the Plugin
*/
FavoritesAdmin.formActions = {
}

/**
* Primary factory class
*/
FavoritesAdmin.Factory = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.build = function()
	{
		new FavoritesAdmin.Settings;
	}

	return plugin.build();
}