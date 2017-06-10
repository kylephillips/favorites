/**
* Generates a new nonce on page load via AJAX
* Solves problem of cached pages and expired nonces
*
* Events:
* favorites-nonce-generated: The nonce has been generated
*/
var Favorites = Favorites || {};

Favorites.NonceGenerator = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).ready(function(){
			plugin.getNonce();
		});
	}

	/**
	* Make the AJAX call to get the nonce
	*/
	plugin.getNonce = function()
	{
		if ( Favorites.jsData.cache_enabled === '' ){
			Favorites.jsData.nonce = favorites.nonce;
			return;
		}
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : Favorites.formActions.nonce
			},
			success: function(data){
				Favorites.jsData.nonce = data.nonce;
				$(document).trigger('favorites-nonce-generated', [data.nonce]);
			}
		});
	}

	return plugin.bindEvents();
}