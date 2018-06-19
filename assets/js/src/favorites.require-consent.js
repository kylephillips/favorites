/**
* Favorites Require Consent Modal Agreement
*/
var Favorites = Favorites || {};

Favorites.RequireConsent = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.consentData;
	plugin.postData;
	plugin.activeButton;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-require-consent', function(event, consent_data, post_data, active_button){
			plugin.consentData = consent_data;
			plugin.postData = post_data;
			plugin.activeButton = active_button;
			plugin.openModal();
		});
		$(document).on('favorites-user-consent-approved', function(e, button){
			if ( typeof button !== 'undefined' ){
				$(plugin.activeButton).attr('data-user-consent-accepted', 'true');
				$(plugin.activeButton).click();
				plugin.closeModal();
				return;
			}
			plugin.setConsent(true);
		});
		$(document).on('favorites-user-consent-denied', function(){
			plugin.setConsent(false);
		});
		$(document).on('click', '.simplefavorites-modal-backdrop', function(e){
			plugin.closeModal();
		});
		$(document).on('click', '[data-favorites-consent-deny]', function(e){
			e.preventDefault();
			plugin.closeModal();
			$(document).trigger('favorites-user-consent-denied');
		});
		$(document).on('click', '[data-favorites-consent-accept]', function(e){
			e.preventDefault();
			$(document).trigger('favorites-user-consent-approved', [$(this)]);
		});
	}

	/**
	* Open the Modal
	*/
	plugin.openModal = function()
	{
		plugin.buildModal();
		setTimeout(function(){
			$('[' + Favorites.selectors.consentModal + ']').addClass('active');
		}, 10);
	}

	/**
	* Build the Modal
	*/
	plugin.buildModal = function()
	{
		var modal = $('[' + Favorites.selectors.consentModal + ']');
		if ( modal.length > 0 ) return;
		var html = '<div class="simplefavorites-modal-backdrop" ' + Favorites.selectors.consentModal + '></div>';
		html += '<div class="simplefavorites-modal-content" ' + Favorites.selectors.consentModal + '>';
		html += '<div class="simplefavorites-modal-content-body no-padding">';
		html += '<div class="simplefavorites-modal-content-interior">';
		html += plugin.consentData.message;
		html += '</div>';
		html += '<div class="simplefavorites-modal-content-footer">'
		html += '<button class="simplefavorites-button-consent-deny" data-favorites-consent-deny>' + plugin.consentData.deny_text + '</button>';
		html += '<button class="simplefavorites-button-consent-accept" data-favorites-consent-accept>' + plugin.consentData.accept_text + '</button>';
		html += '</div><!-- .simplefavorites-modal-footer -->';
		html += '</div><!-- .simplefavorites-modal-content-body -->';
		html += '</div><!-- .simplefavorites-modal-content -->';
		$('body').prepend(html);
	}

	/**
	* Close the Modal
	*/
	plugin.closeModal = function()
	{
		$('[' + Favorites.selectors.consentModal + ']').removeClass('active');
	}

	/**
	* Submit a manual deny/consent
	*/
	plugin.setConsent = function(consent)
	{
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			dataType: 'json',
			data: {
				action : Favorites.formActions.cookieConsent,
				consent : consent
			}
		});
	}

	return plugin.bindEvents();
}