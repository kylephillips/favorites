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
			$.each($('[data-favorites-dependency-checkbox]'), function(){
				var item = $(this).parents('.field');
				plugin.toggleDependencyContent(item);
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
				return;
			}
			$(this).html(icon + ' ' + defaultText);
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

	return plugin.bindEvents();
}