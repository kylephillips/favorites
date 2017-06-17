/**
* Favorites Require Authentication
*/
var Favorites = Favorites || {};

Favorites.RequireAuthentication = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-require-authentication', function(){
			plugin.openModal();
		});
		$(document).on('click', '.simplefavorites-modal-backdrop', function(e){
			plugin.closeModal();
		});
		$(document).on('click', '[' + Favorites.selectors.close_modals + ']', function(e){
			e.preventDefault();
			plugin.closeModal();
		});
	}

	/**
	* Open the Moda
	*/
	plugin.openModal = function()
	{
		plugin.buildModal();
		setTimeout(function(){
			$('[' + Favorites.selectors.modals + ']').addClass('active');
		}, 10);
	}

	/**
	* Build the Modal
	*/
	plugin.buildModal = function()
	{
		var modal = $('[' + Favorites.selectors.modals + ']');
		if ( modal.length > 0 ) return;
		var html = '<div class="simplefavorites-modal-backdrop" ' + Favorites.selectors.modals + '></div>';
		html += '<div class="simplefavorites-modal-content" ' + Favorites.selectors.modals + '>';
		html += '<div class="simplefavorites-modal-content-body">';
		html += Favorites.jsData.authentication_modal_content;
		html += '</div><!-- .simplefavorites-modal-content-body -->';
		html += '</div><!-- .simplefavorites-modal-content -->';
		$('body').prepend(html);
	}

	/**
	* Close the Moda
	*/
	plugin.closeModal = function()
	{
		$('[' + Favorites.selectors.modals + ']').removeClass('active');
		$(document).trigger('favorites-modal-closed');
	}

	return plugin.bindEvents();
}