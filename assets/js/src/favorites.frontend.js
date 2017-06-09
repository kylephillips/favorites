var Favorites = Favorites || {};

/**
* Favorites Plugin
*/
Favorites.FrontEnd = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.utilities = new Favorites.Utilities;
	plugin.formatter = new Favorites.Formatter;
	plugin.setUserFavorites = new Favorites.UserFavorites;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-cleared', function(){
			plugin.resetCounts();
		});
		$(document).on('favorites-updated-single', function(){
			// plugin.updateAllLists();
			// plugin.updateTotalFavorites();
		});
		$(document).on('user-favorites-updated', function(){
			// plugin.updateAllLists();
			// plugin.updateTotalFavorites();
		});
	}

	


	// Update favorite counts after a clear
	plugin.resetCounts = function(){
		var buttons = $('.simplefavorite-button.active.has-count');
		for ( var i = 0; i < buttons.length; i++ ){
			var button = $(buttons)[i];
			var count_display = $(button).find('.simplefavorite-button-count');
			var new_count = $(count_display).text() - 1;
			$(button).attr('data-favoritecount', new_count);
		}
	}


	// Update all lists
	plugin.updateAllLists = function(){
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
	plugin.updateSingleList = function(list, favorites){

		plugin.removeInvalidListItems(list, favorites);

		var include_buttons = ( $(list).attr('data-includebuttons') === 'true' ) ? true : false;
		var include_links = ( $(list).attr('data-includelinks') === 'true' ) ? true : false;

		// Remove list items without a data-postid attribute (backwards compatibility plugin v < 1.2)
		var list_items = $(list).find('li');
		$.each(list_items, function(i, v){
			var attr = $(this).attr('data-postid');
			if (typeof attr === typeof undefined || attr === false) {
				$(this).remove();	
			}
		});

		// Update the no favorites item
		if ( plugin.utilities.objectLength(favorites) > 0 ){
			$(list).find('[data-nofavorites]').remove();
		} else {
			html = '<li data-nofavorites>' + $(list).attr('data-nofavoritestext') + '</li>';
			$(list).empty().append(html);
		}

		var post_types = $(list).attr('data-posttype');
		post_types = post_types.split(',');
		
		// Add favorites that arent in the list
		$.each(favorites, function(i, v){
			if ( post_types.length > 0 && $.inArray(v.post_type, post_types) === -1 ) return;
			if ( $(list).find('li[data-postid=' + v.post_id + ']').length > 0 ) return;
			html = '<li data-postid="' + v.post_id + '">';
			if ( include_buttons ) html += '<p>';
			if ( include_links ) html += '<a href="' + v.permalink + '">';
			html += v.title;
			if ( include_links ) html += '</a>';
			if ( include_buttons ) html += '</p><p>' + v.button + '</p>';
			html += '</li>';
			$(list).append(html);
		});
	}


	// Update a specific user list
	plugin.updateUserList = function(list)
	{
		var user_id = $(list).attr('data-userid');
		var site_id = $(list).attr('data-siteid');
		var include_links = $(list).attr('data-includelinks');
		var include_buttons = $(list).attr('data-includebuttons');
		var post_type = $(list).attr('data-posttype');
		console.log(post_type);

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


	// Remove invalid list items
	plugin.removeInvalidListItems = function(list, favorites){
		var listitems = $(list).find('li[data-postid]');
		$.each(listitems, function(i, v){
			var postid = $(this).attr('data-postid');
			if ( !plugin.utilities.isFavorite(postid, favorites) ) $(this).remove();
		});
	}


	// Update Total Number of Favorites
	plugin.updateTotalFavorites = function()
	{
		// Loop through all the total favorite element
		for ( var i = 0; i < $(Favorites.selectors.total_favorites).length; i++ ){
			var item = $(Favorites.selectors.total_favorites)[i];
			var siteid = parseInt($(item).attr('data-siteid'));
			var posttypes = $(item).attr('data-posttypes');
			var posttypes_array = posttypes.split(','); // Multiple Post Type Support
			var count = 0;

			// Loop through all sites in favorites
			for ( var c = 0; c < Favorites.userFavorites.length; c++ ){
				var site_favorites = Favorites.userFavorites[c];
				if ( site_favorites.site_id !== siteid ) continue; 
				$.each(site_favorites.posts, function(){
					if ( $(item).attr('data-posttypes') === 'all' ){
						count++;
						return;
					}
					if ( $.inArray(this.post_type, posttypes_array) !== -1 ) count++;
				});
			}

			$(item).text(count);
		}
	}

	return plugin.bindEvents();
}