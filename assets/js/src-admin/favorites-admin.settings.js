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
			plugin.toggleAuthModalContentField();
			plugin.toggleCustomColorOptions();
			plugin.enableColorPickers();
			plugin.toggleButtonPreviewColors();
			plugin.toggleCountOptions();
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
			plugin.toggleAuthModalContentField();
		});
		$(document).on('change', '[data-favorites-require-login-checkbox]', function(){
			plugin.toggleAuthModalContentField();
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
			$(this).toggleClass('active');
			var icon = $(this).attr('data-favorites-button-icon');
			var activeText = $(this).attr('data-favorites-button-active-content');
			var defaultText = $(this).attr('data-favorites-button-default-content');
			if ( $(this).hasClass('active') ){
				$(this).html(icon + ' ' + activeText);
			} else {
				$(this).html(icon + ' ' + defaultText);
			}
			setTimeout(function(){
				plugin.toggleButtonPreviewColors();
			}, 10);
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
	* Toggle the authentication modal content field
	*/
	plugin.toggleAuthModalContentField = function()
	{
		var checked = ( $('[data-favorites-require-login-checkbox]').is(':checked') ) ? true : false;
		if ( checked ){
			$('[data-favorites-authentication-modal-content]').show();
			return;
		}
		$('[data-favorites-authentication-modal-content]').hide();
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
		var icon = $(button).find('i');

		if ( !$('[data-favorites-custom-colors-checkbox]').is(':checked') ) {
			$(button).removeAttr('style');
			plugin.toggleButtonTypes();
			return;
		}

		var shadowCheckbox = $('[data-favorites-button-shadow]');

		// Color Values
		var background_default = plugin.getCurrentColor('background_default');
		var border_default = plugin.getCurrentColor('border_default');
		var text_default = plugin.getCurrentColor('text_default');
		var icon_default = plugin.getCurrentColor('icon_default');
		var background_active = plugin.getCurrentColor('background_active');
		var border_active = plugin.getCurrentColor('border_active');
		var text_active = plugin.getCurrentColor('text_active');
		var icon_active = plugin.getCurrentColor('icon_active');

		// Toggle the shadow
		if ( $(shadowCheckbox).is(':checked') ){
			$(button).css('box-shadow', '');
		} else {
			$(button).css('box-shadow', 'none');
		}

		if ( $(buttonVisible).hasClass('active') ){
			if ( background_active !== '' ) {
				$(button).css('background-color', background_active);
			} else {
				$(button).css('background-color', '');
			}
			if ( border_active !== '' ) {
				$(button).css('border-color', border_active);
			} else {
				$(button).css('border-color', '');
			}
			if ( text_active !== '' ) {
				$(button).css('color', text_active);
			} else {
				$(button).css('color', '');
			}
			if ( icon_active !== '' ) {
				$(icon).css('color', icon_active);
			} else {
				$(icon).css('color', '');
			}
			return;
		} //  active

		if ( background_default !== '' ) {
			$(button).css('background-color', background_default);
		} else {
			$(button).css('background-color', '');
		}
		if ( border_default !== '' ) {
			$(button).css('border-color', border_default);
		} else {
			$(button).css('border-color', '');
		}
		if ( text_default !== '' ) {
			$(button).css('color', text_default);
		} else {
			$(button).css('color', '');
		}
		if ( icon_default !== '' ) {
			$(icon).css('color', icon_default);
		} else {
			$(icon).css('color', '');
		}
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

	return plugin.bindEvents();
}