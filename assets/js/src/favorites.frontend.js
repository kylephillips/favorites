var Favorites = Favorites || {};

/**
* Favorites Plugin
*/
Favorites.FrontEnd = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.utilities = new Favorites.Utilities;
	plugin.formatter = new Favorites.Formatter;

	plugin.bindEvents = function()
	{
		
	}


	

	return plugin.bindEvents();
}