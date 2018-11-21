/**
* Favorites List functionality
*/
var Favorites = Favorites || {};

Favorites.Lists = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.utilities = new Favorites.Utilities;
	plugin.buttonFormatter = new Favorites.ButtonOptionsFormatter;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-update-all-lists', function(){
			plugin.updateAllLists();
		});
		$(document).on('favorites-updated-single', function(){
			plugin.updateAllLists();
		});
		$(document).on('favorites-cleared', function(){
			plugin.updateAllLists();
		});
		$(document).on('favorites-user-favorites-loaded', function(){
			plugin.updateAllLists();
		});
	}

	/**
	* Loop through all the favorites lists
	*/
	plugin.updateAllLists = function()
	{
		if ( typeof Favorites.userFavorites === 'undefined' ) return;
		for ( var i = 0; i < Favorites.userFavorites.length; i++ ){
			var lists = $(Favorites.selectors.list + '[data-siteid="' + Favorites.userFavorites[i].site_id + '"]');
			for ( var c = 0; c < $(lists).length; c++ ){
				var list = $(lists)[c];
				plugin.updateSingleList(list)
			}
		}
	}

	/**
	* Update a specific user list
	*/
	plugin.updateSingleList = function(list)
	{
		var user_id = $(list).attr('data-userid');
		var site_id = $(list).attr('data-siteid');
		var include_links = $(list).attr('data-includelinks');
		var include_buttons = $(list).attr('data-includebuttons');
		var include_thumbnails = $(list).attr('data-includethumbnails');
		var thumbnail_size = $(list).attr('data-thumbnailsize');
		var include_excerpt = $(list).attr('data-includeexcerpts');
		var post_types = $(list).attr('data-posttypes');
		var no_favorites = $(list).attr('data-nofavoritestext');

		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			dataType: 'json',
			data: {
				action : Favorites.formActions.favoritelist,
				userid : user_id,
				siteid : site_id,
				include_links : include_links,
				include_buttons : include_buttons,
				include_thumbnails : include_thumbnails,
				thumbnail_size : thumbnail_size,
				include_excerpt : include_excerpt,
				no_favorites : no_favorites,
				post_types : post_types
			},
			success : function(data){
				if ( Favorites.jsData.dev_mode ){
					console.log('Favorites list successfully retrieved.');
					console.log($(list));
					console.log(data);
				}
				var newlist = $(data.list);
				$(list).replaceWith(newlist);
				plugin.removeButtonLoading(newlist);
				$(document).trigger('favorites-list-updated', [newlist]);
			},
			error : function(data){
				if ( !Favorites.jsData.dev_mode ) return;
				console.log('There was an error updating the list.');
				console.log(list);
				console.log(data);
			}
		});
	}

	/**
	* Remove loading state from buttons in the list
	*/
	plugin.removeButtonLoading = function(list)
	{
		var buttons = $(list).find(Favorites.selectors.button);
		$.each(buttons, function(){
			plugin.buttonFormatter.format($(this), false);
			$(this).removeClass(Favorites.cssClasses.active);
			$(this).removeClass(Favorites.cssClasses.loading);
		});
	}

	/**
	* Remove unfavorited items from the list
	*/
	plugin.removeInvalidListItems = function(list, favorites)
	{
		var listitems = $(list).find('li[data-postid]');
		$.each(listitems, function(i, v){
			var postid = $(this).attr('data-postid');
			if ( !plugin.utilities.isFavorite(postid, favorites) ) $(this).remove();
		});
	}

	return plugin.bindEvents();
}