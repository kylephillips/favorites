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
		$(document).on('click', '[data-favorites-listing-customizer-variable-button]', function(e){
			e.preventDefault();
			plugin.addFieldToEditor($(this));
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

	/**
	* Add a field to the editor
	*/
	plugin.addFieldToEditor = function(button)
	{
		var field = $(button).siblings('select').val();
		tinymce.activeEditor.execCommand('mceInsertContent', false, field);
	}

	return plugin.bindEvents();
}