var Favorites = Favorites || {};

/**
* Favorites Plugin
*/
Favorites.FrontEnd = function()
{
	var plugin = this;
	var $ = jQuery;

	// Bind events, called in initialization
	plugin.bindEvents = function(){
		$(document).on('favorites-nonce-generated', function(){
			plugin.setUserFavorites(plugin.updateAllButtons);
		});
		$(document).on('favorites-cleared', function(){
			plugin.resetCounts();
		});
		
		$(document).on('click', Favorites.selectors.button, function(e){
			e.preventDefault();
			plugin.submitFavorite($(this));
		});
		
	}

	// Set the initial user favorites (called on page load)
	plugin.setUserFavorites = function(callback){
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : Favorites.formActions.favoritesarray
			},
			success: function(data){
				Favorites.userFavorites = data.favorites;
				plugin.updateAllLists();
				plugin.updateAllButtons();
				plugin.updateClearButtons();
				plugin.updateTotalFavorites();
				if ( callback ) callback();
				favorites_after_initial_load(Favorites.userFavorites);
			}
		});
	}


	// Update all favorites buttons to match the user favorites
	plugin.updateAllButtons = function(callback){
		for ( var i = 0; i < $(Favorites.selectors.button).length; i++ ){
			var button = $(Favorites.selectors.button)[i];
			var postid = $(button).attr('data-postid');
			var siteid = $(button).attr('data-siteid');
			var favorite_count = $(button).attr('data-favoritecount');
			var html = "";
			var site_index = plugin.siteIndex(siteid);
			var site_favorites = Favorites.userFavorites[site_index].posts;

			if ( plugin.isFavorite( postid, site_favorites ) ){
				favorite_count = Favorites.userFavorites[site_index].posts[postid].total;
				html = plugin.addFavoriteCount(Favorites.jsData.favorited, favorite_count);
				$(button).addClass('active').html(html).removeClass('loading');
				continue;
			}

			html = plugin.addFavoriteCount(Favorites.jsData.favorite, favorite_count);
			$(button).removeClass('active').html(html).removeClass('loading');
		}

		if ( callback ) callback();
	}


	// Get Site Favorites index from All Favorites
	plugin.siteIndex = function(siteid){
		for ( var i = 0; i < Favorites.userFavorites.length; i++ ){
			if ( Favorites.userFavorites[i].site_id !== parseInt(siteid) ) continue;
			return i;
		}
	}


	// Add Favorite Count to a button
	plugin.addFavoriteCount = function(html, count){
		if ( Favorites.jsData.include_count === '1' ){
			html += ' <span class="simplefavorite-button-count">' + count + '</span>';
		}
		return html;
	}


	// Submit a Favorite
	plugin.submitFavorite = function(button)
	{
		$(button).attr('disabled', 'disabled');
		$(button).addClass('loading');

		var post_id = $(button).attr('data-postid');
		var site_id = $(button).attr('data-siteid');
		var favorite_count = parseInt($(button).attr('data-favoritecount'));

		var status = 'inactive';
		var html = "";
		var original_html = "";

		if ( $(button).hasClass('active') ) {
			$(button).removeClass('active');
			if ( favorite_count - 1 < 0 ) favorite_count = 1;
			$(button).attr('data-favoritecount', favorite_count - 1);
			original_html = plugin.addFavoriteCount(Favorites.jsData.favorite, favorite_count - 1);
		} else {
			status = 'active';
			$(button).addClass('active');
			$(button).attr('data-favoritecount', favorite_count + 1);
			original_html = plugin.addFavoriteCount(Favorites.jsData.favorited, favorite_count + 1);
		}

		html = plugin.addButtonLoading(original_html, status);
		$(button).html(html);

		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : Favorites.formActions.favorite,
				nonce : Favorites.jsData.nonce,
				postid : post_id,
				siteid : site_id,
				status : status
			},
			success: function(data){
				$(button).removeClass('loading');
				$(button).html(original_html);
				$(button).attr('disabled', false);
				Favorites.userFavorites = data.favorites;
				plugin.updateAllLists();
				plugin.updateAllButtons();
				plugin.updateClearButtons();
				plugin.updateTotalFavorites();
				favorites_after_button_submit(data.favorites, post_id, site_id, status);
			}
		});
	}


	// Add loading indication to button
	plugin.addButtonLoading = function(html, status){
		if ( Favorites.jsData.indicate_loading !== '1' ) return html;
		if ( status === 'active' ) return Favorites.jsData.loading_text + Favorites.jsData.loading_image_active;
		return Favorites.jsData.loading_text + Favorites.jsData.loading_image;
	}


	// Update disabled status for clear buttons
	plugin.updateClearButtons = function(){
		for ( var i = 0; i < $(Favorites.selectors.clear_button).length; i++ ){
			var button = $(Favorites.selectors.clear_button)[i];
			var siteid = $(button).attr('data-siteid');
			for ( var c = 0; c < Favorites.userFavorites.length; c++ ){
				if ( Favorites.userFavorites[c].site_id !== parseInt(siteid) ) continue;
				if ( plugin.objectLength(Favorites.userFavorites[c].posts) > 0 ) {
					$(button).attr('disabled', false);
					continue;
				}
				$(button).attr('disabled', 'disabled');
			}
		}
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

		plugin.setUserFavorites(plugin.updateAllButtons);
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
		if ( plugin.objectLength(favorites) > 0 ){
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
			if ( !plugin.isFavorite(postid, favorites) ) $(this).remove();
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


	// ------------------------------------------------------------------------------
	// Utilities
	// ------------------------------------------------------------------------------


	// Check if an item is in an array
	plugin.isFavorite = function(search, object){
		var status = false;
		$.each(object, function(i, v){
			if ( v.post_id === parseInt(search) ) status = true;
			if ( parseInt(v.post_id) === search ) status = true;
		});
		return status;
	}


	// Get the length of an object (for IE < 9)
	plugin.objectLength = function(object){
		var size = 0, key;
		for (key in object) {
			if (object.hasOwnProperty(key)) size++;
		}
		return size;
	}


	return plugin.bindEvents();
}