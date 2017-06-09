/**
* Primary Favorites Initialization
* @package Favorites
* @author Kyle Phillips - https://github.com/kylephillips/favorites
*
* Events:
* favorites-nonce-generated: The nonce has been generated
*/

jQuery(document).ready(function(){
	new Favorites.Factory;
});

var Favorites = Favorites || {};

/**
* DOM Selectors Used by the Plugin
*/
Favorites.selectors = {

}

/**
* Localized JS Data Used by the Plugin
*/
Favorites.jsData = {
	ajaxurl : simple_favorites.ajaxurl, // The WP AJAX URL
	nonce : null, // The Dynamically-Generated Nonce
}

/**
* WP Form Actions Used by the Plugin
*/
Favorites.formActions = {
	nonce : 'simplefavorites_nonce',
	favoritesarray : 'simplefavorites_array',
	favorite : 'simplefavorites_favorite',
	clearall : 'simplefavorites_clear',
	favoritelist : 'simplefavorites_list'
}

Favorites.Factory = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.build = function()
	{
		new Favorites.NonceGenerator;
		new Favorites.FrontEnd;
	}

	return plugin.build();
}