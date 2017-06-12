/**
* Favorites List functionality
*/
var Favorites = Favorites || {};

Favorites.Lists = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.utilities = new Favorites.Utilities;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-updated-single', function(){
			plugin.updateAllLists();
		});
		$(document).on('favorites-cleared', function(){
			plugin.updateAllLists();
		});
	}

	/**
	* Loop through all the favorites lists
	*/
	plugin.updateAllLists = function()
	{
		for ( var i = 0; i < Favorites.userFavorites.length; i++ ){
			var lists = $(Favorites.selectors.list + '[data-siteid="' + Favorites.userFavorites[i].site_id + '"]');
			for ( var c = 0; c < $(lists).length; c++ ){
				if ( $(lists[c]).attr('data-userid') === "" ){
					var list = $(lists)[c];
					plugin.updateSingleList($(list), Favorites.userFavorites[i].posts);
				} else {
					plugin.updateUserList(lists[c]);
				}
			}
		}
	}


	// Update a single list html
	plugin.updateSingleList = function(list, favorites)
	{
		plugin.removeInvalidListItems(list, favorites);

		var include_buttons = ( $(list).attr('data-includebuttons') === 'true' ) ? true : false;
		var include_links = ( $(list).attr('data-includelinks') === 'true' ) ? true : false;
		var include_thumbnails = ( $(list).attr('data-includethumbnails') === 'true' ) ? true : false;
		var include_excerpts = ( $(list).attr('data-includeexcerpts') === 'true' ) ? true : false;
		var thumbnail_size = $(list).attr('data-thumbnailsize');

		// Remove list items without a data-postid attribute (backwards compatibility plugin v < 1.2)
		var list_items = $(list).find('li');
		$.each(list_items, function(i, v){
			var attr = $(this).attr('data-postid');
			if (typeof attr === typeof undefined || attr === false) $(this).remove();
		});

		// Update the no favorites item
		if ( plugin.utilities.objectLength(favorites) > 0 ){
			$(list).find('[data-nofavorites]').remove();
		} else {
			html = '<li data-nofavorites>' + $(list).attr('data-nofavoritestext') + '</li>';
			$(list).empty().append(html);
		}

		var post_types = $(list).attr('data-posttype');
		post_types = ( typeof post_types === 'undefined' || post_types === '' ) ? 0 : post_types.split(',');
		
		// Add favorites that arent in the list
		$.each(favorites, function(i, v){
			if ( post_types.length > 0 && $.inArray(v.post_type, post_types) === -1 ) return;
			if ( $(list).find('li[data-postid=' + v.post_id + ']').length > 0 ) return;
			html = '<li data-postid="' + v.post_id + '">';
			if ( include_thumbnails ){
				var thumb_url = plugin.utilities.getThumbnail(v, thumbnail_size);
				if ( thumb_url ) html += '<img src="' + thumb_url + '" alt="' + v.title + '" class="favorites-list-image" />';
			}
			html += '<p>';
			if ( include_links ) html += '<a href="' + v.permalink + '">';
			html += v.title;
			if ( include_links ) html += '</a>';
			html += '</p>';
			if ( include_excerpts ) {
				var excerpt = v.excerpt;
				if ( typeof excerpt !== 'undefined' ) html += '<p class="excerpt">' + excerpt + '</p>';
			}
			if ( include_buttons ) html += '<p>' + v.button + '</p>';
			html += '</li>';
			$(list).append(html);
		});
	}

	/**
	* Update a specific user list
	*/
	plugin.updateUserList = function(list)
	{
		var user_id = $(list).attr('data-userid');
		var site_id = $(list).attr('data-siteid');
		var include_links = $(list).attr('data-includelinks');
		var include_buttons = $(list).attr('data-includebuttons');
		var post_type = $(list).attr('data-posttype');
		$.ajax({
			url: plugin.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : Favorites.formActions.favoritelist,
				nonce : Favorites.jsData.nonce,
				userid : user_id,
				siteid : site_id,
				includelinks : include_links,
				includebuttons : include_buttons,
				posttype : post_type
			},
			success : function(data){
				$(list).replaceWith(data.list);
			}
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