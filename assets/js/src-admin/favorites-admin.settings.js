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
			plugin.toggleAnonymousSave();
			plugin.togglePostTypeOptions();
			plugin.toggleLoadingIndicators();
			$.each($(FavoritesAdmin.selectors.dependencyItem), function(){
				plugin.toggleDependencyContent($(this));
			});
		});
		$(document).on('change', FavoritesAdmin.selectors.dependencyCheckbox, function(){
			var item = $(this).parents(FavoritesAdmin.selectors.dependencyItem);
			plugin.toggleDependencyContent(item);
		});
		$(document).on('change', FavoritesAdmin.selectors.anonymousCheckbox, function(){
			plugin.toggleAnonymousSave();
		});
		$(document).on('change', '*[data-sf-posttype]', function(){
			plugin.togglePostTypeOptions();
		});
		$(document).on('change', '.simplefavorites-display-loading', function(){
			plugin.toggleLoadingIndicators();
		});
	}

	/**
	* Toggle Dependency Content Depending on whether the setting is checked or not
	*/
	plugin.toggleDependencyContent = function(item)
	{
		if ( $(item).find(FavoritesAdmin.selectors.dependencyCheckbox).is(':checked') ){
			$(item).find(FavoritesAdmin.selectors.dependencyContent).hide();
			return;
		}
		$(item).find(FavoritesAdmin.selectors.dependencyContent).show();
	}

	/**
	* Toggle the "Include in count" checkbox with anonymous enabling
	*/
	plugin.toggleAnonymousSave = function()
	{
		if ( $(FavoritesAdmin.selectors.anonymousCheckbox).is(':checked') ){
			$(FavoritesAdmin.selectors.anonymousCount).show();
			return;
		}
		$(FavoritesAdmin.selectors.anonymousCount).hide().find('input[type="checkbox"]').attr('checked', false);
	}

	/**
	* Toggle Post Type Options under Display
	*/
	plugin.togglePostTypeOptions = function()
	{
		var posttypes = $('*[data-sf-posttype]');
		$.each(posttypes, function(i, v){
			var selections = $(this).parents('.simple-favorites-posttype').find('.simple-favorites-posttype-locations');
			if ( $(this).is(':checked') ){
				$(selections).show();
			} else {
				$(selections).hide();
			}
		});
	}

	/**
	* Toggle Loading Indicators
	*/
	plugin.toggleLoadingIndicators = function()
	{
		if ( $('.simplefavorites-display-loading').is(':checked') ){
			$('.simplefavorites-loading-fields').show();
			return;
		}
		$('.simplefavorites-loading-fields').hide();
	}

	return plugin.bindEvents();
}