/**
* Admin Settings
*/
var FavoritesAdmin = FavoritesAdmin || {};

FavoritesAdmin.ListingCustomizer = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).ready(function(){
			plugin.toggleListingCustomizer();
		});
		$(document).on('change', '[data-favorites-listing-customizer-checkbox]', function(){
			plugin.toggleListingCustomizer();
		});
	}

	/**
	* Toggle the listing customizer
	*/
	plugin.toggleListingCustomizer = function()
	{
		if ( $('[data-favorites-listing-customizer-checkbox]').is(':checked') ){
			$('[data-favorites-listing-customizer]').show();
			return;
		}
		$('[data-favorites-listing-customizer]').hide();
	}

	return plugin.bindEvents();
}