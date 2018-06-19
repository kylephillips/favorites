/**
* Admin Settings
*/
var FavoritesAdmin = FavoritesAdmin || {};

FavoritesAdmin.Settings = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).ready(function(){
			plugin.toggleButtonTypes();
			plugin.toggleAnonymousSave();
			plugin.toggleLoadingTypeLoad();
			plugin.toggleAnonymousSettings();
			plugin.toggleCustomColorOptions();
			plugin.enableColorPickers();
			plugin.toggleButtonPreviewColors();
			plugin.toggleCountOptions();
			plugin.toggleModalConsentContent();
			$.each($('[data-favorites-dependency-checkbox]'), function(){
				var item = $(this).parents('.field');
				plugin.toggleDependencyContent(item);
			});
			$('.wp-color-result').attrchange({
				callback: function(){
					plugin.toggleButtonPreviewColors();	
				}
			});
		});
		$(document).on('change', '[data-favorites-dependency-checkbox]', function(){
			var item = $(this).parents('.field');
			plugin.toggleDependencyContent(item);
		});

		// User settings
		$(document).on('change', '*[data-favorites-anonymous-checkbox]', function(){
			plugin.toggleAnonymousSave();
			plugin.toggleAnonymousSettings();
		});
		$(document).on('change', '[data-favorites-anonymous-settings]', function(){
			plugin.toggleAnonymousSettings($(this));
		});

		// Post type settings
		$(document).on('change', '*[data-favorites-posttype-checkbox]', function(){
			plugin.togglePostTypeOptionsButtons();
		});
		$(document).on('click', '[data-favorites-toggle-post-type-settings]', function(e){
			e.preventDefault();
			plugin.togglePostTypeOptions($(this));
		});

		// Other Display Settings
		$(document).on('change', '[data-favorites-spinner-type]', function(){
			plugin.toggleLoadingType($(this));
		});

		// Favorite Button Content
		$(document).on('change', '[data-favorites-preset-button-select]', function(){
			plugin.toggleButtonTypes();
		});
		$(document).on('click', '[data-favorites-button-preview]', function(e){
			e.preventDefault();
			plugin.togglePreviewButtonState($(this));
		});
		$(document).on('change', '[data-favorites-include-count-checkbox]', function(){
			plugin.toggleCountOptions();
		});

		// Favorite Button Colors
		$(document).on('change', '[data-favorites-custom-colors-checkbox]', function(){
			plugin.toggleCustomColorOptions();
		});
		$(document).on('change', '[data-favorites-button-shadow]', function(){
			plugin.toggleButtonPreviewColors();
		});
		$(document).on('change', '[data-favorites-color-picker]', function(){
			plugin.toggleButtonPreviewColors();
		});

		// Consent Settings
		$(document).on('change', '[data-favorites-require-consent-checkbox]', function(){
			plugin.toggleModalConsentContent();
		});
	}

	/**
	* Toggle Post Type Options under Display
	*/
	plugin.togglePostTypeOptions = function(button)
	{
		$(button).parents('.post-type-row').find('.post-type-settings').toggle();
		$(button).toggleClass('button-primary');
	}

	/**
	* Toggle the "Options" button under post type rows
	*/
	plugin.togglePostTypeOptionsButtons = function()
	{
		var postTypeCheckboxes = $('[data-favorites-posttype-checkbox]');
		$.each(postTypeCheckboxes, function(){
			var checked = ( $(this).is(':checked') ) ? true : false;
			var row = $(this).parents('.post-type-row');
			var button = $(row).find('[data-favorites-toggle-post-type-settings]');
			if ( checked ){
				$(button).show();
				return;
			}
			$(button).hide();
			$(row).find('.post-type-settings').hide();
		});
	}

	/**
	* Toggle Dependency Content Depending on whether the setting is checked or not
	*/
	plugin.toggleDependencyContent = function(item)
	{
		if ( $(item).find('[data-favorites-dependency-checkbox]').is(':checked') ){
			$(item).find('[data-favorites-dependency-content]').hide();
			return;
		}
		$(item).find('[data-favorites-dependency-content]').show();
	}

	/**
	* Toggle the "Include in count" checkbox with anonymous enabling
	*/
	plugin.toggleAnonymousSave = function()
	{
		if ( $('[data-favorites-anonymous-checkbox]').is(':checked') ){
			$('[data-favorites-anonymous-count]').show();
			$('[data-favorites-require-login]').hide().find('input[type="checkbox"]').attr('checked', false);
			return;
		}
		$('[data-favorites-anonymous-count]').hide().find('input[type="checkbox"]').attr('checked', false);
		$('[data-favorites-require-login]').show();
	}

	/**
	* Toggle Anonymous Users Settings
	*/
	plugin.toggleAnonymousSettings = function(checkbox)
	{
		if ( typeof checkbox === 'undefined' || checkbox === '' ){
			var allCheckboxes = $('[data-favorites-anonymous-settings]');
			$.each(allCheckboxes, function(){
				plugin.toggleAnonymousSettings($(this));
			});
		}
		var attr = $(checkbox).attr('data-favorites-anonymous-settings');
		if ( attr === 'modal' && $(checkbox).is(':checked') ){
			$('[data-favorites-authentication-modal-content]').show();
			$('[data-favorites-anonymous-redirect-content]').hide();
			$('[data-favorites-anonymous-settings="redirect"]').attr('checked', false);
			return;
		}
		if ( attr === 'redirect' && $(checkbox).is(':checked') ){
			$('[data-favorites-anonymous-redirect-content]').show();
			$('[data-favorites-authentication-modal-content]').hide();
			$('[data-favorites-anonymous-settings="modal"]').attr('checked', false);
			return;
		}
		if ( !$('[data-favorites-anonymous-settings="redirect"]').is(':checked') ) $('[data-favorites-anonymous-redirect-content]').hide();
		if ( !$('[data-favorites-anonymous-settings="modal"]').is(':checked') )$('[data-favorites-authentication-modal-content]').hide();
	}

	/**
	* Toggle Loading Html/Image checkboxes (only allow one)
	*/
	plugin.toggleLoadingTypeLoad = function()
	{
		var ImageCheckbox = $('[data-favorites-spinner-type="image"]');
		if ( $(ImageCheckbox).is(':checked') ){
			$('[data-favorites-spinner-type="html"]').attr('checked', false);
			return;
		}
		$('[data-favorites-spinner-type="image"]').attr('checked', false);
	}

	/**
	* Toggle Loading Html/Image checkboxes (only allow one)
	*/
	plugin.toggleLoadingType = function(checkbox)
	{
		var attr = $(checkbox).attr('data-favorites-spinner-type');
		if ( attr === 'image' ){
			$('[data-favorites-spinner-type="html"]').attr('checked', false);
			return;
		}
		$('[data-favorites-spinner-type="image"]').attr('checked', false);
	}

	/**
	* Toggle the active state for the preview button
	*/
	plugin.togglePreviewButtonState = function(button)
	{
		$(button).toggleClass('active');
		var icon = $(button).attr('data-favorites-button-icon');
		var activeText = $(button).attr('data-favorites-button-active-content');
		var defaultText = $(button).attr('data-favorites-button-default-content');
		var text = ( $(button).hasClass('active') ) ? activeText : defaultText;
		var html = icon + ' ' + text;
		if ( $('[data-favorites-include-count-checkbox]').is(':checked') )
			html += ' <span class="simplefavorite-button-count">2</span>';
		$(button).html(html);
		setTimeout(function(){
			plugin.toggleButtonPreviewColors();
		}, 10);
	}

	/**
	* Toggle the favorite button type previews
	*/
	plugin.toggleButtonTypes = function()
	{
		var type = $('[data-favorites-preset-button-select]').val();
		var previewCont = $('[data-favorites-preset-button-previews]');
		var previewButtons = $('[data-favorites-button-preview]');
		var customOptions = $('[data-favorites-custom-button-option]');
		if ( type === 'custom' ){
			$(previewCont).hide();
			$(customOptions).show();
			return;
		}
		$(customOptions).hide();
		$(previewButtons).hide();
		$(previewCont).show();
		$('[data-favorites-button-preview="' + type + '"]').show();
	}

	/**
	* Toggle the custom color options
	*/
	plugin.toggleCustomColorOptions = function()
	{
		var checked = ( $('[data-favorites-custom-colors-checkbox]').is(':checked') ) ? true : false;
		plugin.toggleButtonPreviewColors();
		if ( checked ){
			$('[data-favorites-custom-colors-options]').show();
			return;
		}
		$('[data-favorites-custom-colors-options]').hide();
	}

	/**
	* Enable Color Pickers
	*/
	plugin.enableColorPickers = function()
	{
		$('[data-favorites-color-picker]').wpColorPicker({
			change : function(event, ui){
				setTimeout(function(){
					plugin.toggleButtonPreviewColors();
				}, 10);
			}
		});
	}

	/**
	* Toggle the button preview colors
	*/
	plugin.toggleButtonPreviewColors = function()
	{
		var button = $('[data-favorites-button-preview]');
		var buttonVisible = $('[data-favorites-button-preview]:visible');

		if ( !$('[data-favorites-custom-colors-checkbox]').is(':checked') ) {
			$(button).removeAttr('style');
			plugin.toggleButtonTypes();
			return;
		}

		// Toggle the shadow
		var shadow = ( $('[data-favorites-button-shadow]').is(':checked') ) ? '' : 'none';
		$(button).css('box-shadow', shadow);

		var propertyState = ( $(buttonVisible).hasClass('active') ) ? '_active' : '_default';
		$(button).css('background-color', plugin.getCurrentColor('background' + propertyState));
		$(button).css('border-color', plugin.getCurrentColor('border' + propertyState));
		$(button).css('color', plugin.getCurrentColor('text' + propertyState));
		$(button).find('i').css('color', plugin.getCurrentColor('icon' + propertyState));
		$(button).find('.simplefavorite-button-count').css('color', plugin.getCurrentColor('count' + propertyState));
	} // toggleButtonPreviewColors

	/**
	* Workaround for Iris color picker not triggering change event on clear
	*/
	plugin.getCurrentColor = function(property)
	{
		var input = $('[data-favorites-color-picker="' + property + '"]');
		value = $(input).val();
		return value;
	}

	/**
	* Toggle Count options
	*/
	plugin.toggleCountOptions = function()
	{
		var checked = ( $('[data-favorites-include-count-checkbox]').is(':checked') ) ? true : false;
		if ( checked ){
			$('.simplefavorite-button-count').show();
			$('[data-favorites-color-option="count_default"]').show();
			$('[data-favorites-color-option="count_active"]').show();
			return;
		}
		$('.simplefavorite-button-count').hide();
		$('[data-favorites-color-option="count_default"]').hide();
		$('[data-favorites-color-option="count_active"]').hide();
	}

	/**
	* Toggle the Modal Consent Content
	*/
	plugin.toggleModalConsentContent = function()
	{
		var checked = ( $('[data-favorites-require-consent-checkbox]').is(':checked') ) ? true : false;
		if ( checked ){
			$('[data-favorites-require-consent-modal-content]').show();
			return;
		}
		$('[data-favorites-require-consent-modal-content]').hide();
	}

	return plugin.bindEvents();
}