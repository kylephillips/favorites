jQuery(document).ready(function(){
	new Favorites;
});


/**
* Callback Functions for use in themes
*/
function favorites_after_button_submit(favorites){}
function favorites_after_initial_load(favorites){}


/**
* Favorites Plugin
*/
var Favorites = function()
{

	var plugin = this;
	var $ = jQuery;

	// Form Actions for AJAX calls
	plugin.formactions = {
		nonce : 'simplefavorites_nonce',
		favoritesarray : 'simplefavorites_array',
		favorite : 'simplefavorites_favorite',
		clearall : 'simplefavorites_clear',
		favoritelist : 'simplefavorites_list'
	}

	// DOM Selectors
	plugin.buttons = '.simplefavorite-button'; // Favorites Button Selector
	plugin.lists = '.favorites-list'; // Favorites List Selector
	plugin.clear_buttons = '.simplefavorites-clear'; // Clear Button Selector
	plugin.total_favorites = '.simplefavorites-user-count'; // Total Favorites (from the_user_favorites_count)

	// Localized Data
	plugin.ajaxurl = simple_favorites.ajaxurl; // The WP AJAX URL
	plugin.favorite = simple_favorites.favorite; // Active Button Text
	plugin.favorited = simple_favorites.favorited; // Inactive Button Text
	plugin.include_count = simple_favorites.includecount; // Whether to include the count in buttons
	plugin.indicate_loading = simple_favorites.indicate_loading; // Whether to include loading indication in buttons
	plugin.loading_text = simple_favorites.loading_text; // Loading indication text
	plugin.loading_image_active = simple_favorites.loading_image_active; // Loading spinner url in active button
	plugin.loading_image = simple_favorites.loading_image; // Loading spinner url in inactive button

	// JS Data
	plugin.nonce = ''; // The nonce, generated dynamically
	plugin.userfavorites; // Object – User Favorites, each site is an array of post objects


	// Bind events, called in initialization
	plugin.bindEvents = function(){
		$(document).on('click', plugin.buttons, function(e){
			e.preventDefault();
			plugin.submitFavorite($(this));
		});
		$(document).on('click', plugin.clear_buttons, function(e){
			e.preventDefault();
			plugin.clearFavorites($(this));
		});
	}


	// Initialization
	plugin.init = function(){
		plugin.bindEvents();
		plugin.generateNonce();
	}


	// Generate a nonce (workaround for cached pages/nonces)
	plugin.generateNonce = function(){
		$.ajax({
			url: plugin.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : plugin.formactions.nonce
			},
			success: function(data){
				plugin.nonce = data.nonce;
				plugin.setUserFavorites(plugin.updateAllButtons);
			}
		});
	}


	// Set the initial user favorites (called on page load)
	plugin.setUserFavorites = function(callback){
		$.ajax({
			url: plugin.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : plugin.formactions.favoritesarray
			},
			success: function(data){
				plugin.userfavorites = data.favorites;
				plugin.updateAllLists();
				plugin.updateAllButtons();
				plugin.updateClearButtons();
				plugin.updateTotalFavorites();
				if ( callback ) callback();
				favorites_after_initial_load(plugin.userfavorites);
			}
		});
	}


	// Update all favorites buttons to match the user favorites
	plugin.updateAllButtons = function(callback){
		for ( var i = 0; i < $(plugin.buttons).length; i++ ){
			var button = $(plugin.buttons)[i];
			var postid = $(button).attr('data-postid');
			var siteid = $(button).attr('data-siteid');
			var favorite_count = $(button).attr('data-favoritecount');
			var html = "";
			var site_index = plugin.siteIndex(siteid);
			var site_favorites = plugin.userfavorites[site_index].posts;

			if ( plugin.isFavorite( postid, site_favorites ) ){
				favorite_count = plugin.userfavorites[site_index].posts[postid].total;
				html = plugin.addFavoriteCount(plugin.favorited, favorite_count);
				$(button).addClass('active').html(html).removeClass('loading');
				continue;
			}

			html = plugin.addFavoriteCount(plugin.favorite, favorite_count);
			$(button).removeClass('active').html(html).removeClass('loading');
		}

		if ( callback ) callback();
	}


	// Get Site Favorites index from All Favorites
	plugin.siteIndex = function(siteid){
		for ( var i = 0; i < plugin.userfavorites.length; i++ ){
			if ( plugin.userfavorites[i].site_id !== parseInt(siteid) ) continue;
			return i;
		}
	}


	// Add Favorite Count to a button
	plugin.addFavoriteCount = function(html, count){
		if ( plugin.include_count === '1' ){
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
			original_html = plugin.addFavoriteCount(plugin.favorite, favorite_count - 1);
		} else {
			status = 'active';
			$(button).addClass('active');
			$(button).attr('data-favoritecount', favorite_count + 1);
			original_html = plugin.addFavoriteCount(plugin.favorited, favorite_count + 1);
		}

		html = plugin.addButtonLoading(original_html, status);
		$(button).html(html);

		$.ajax({
			url: plugin.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : plugin.formactions.favorite,
				nonce : plugin.nonce,
				postid : post_id,
				siteid : site_id,
				status : status
			},
			success: function(data){
				$(button).removeClass('loading');
				$(button).html(original_html);
				$(button).attr('disabled', false);
				plugin.userfavorites = data.favorites;
				plugin.updateAllLists();
				plugin.updateAllButtons();
				plugin.updateClearButtons();
				plugin.updateTotalFavorites();
				favorites_after_button_submit(data.favorites);
			}
		});
	}


	// Add loading indication to button
	plugin.addButtonLoading = function(html, status){
		if ( plugin.indicate_loading !== '1' ) return html;
		if ( status === 'active' ) return plugin.loading_text + plugin.loading_image_active;
		return plugin.loading_text + plugin.loading_image;
	}


	// Update disabled status for clear buttons
	plugin.updateClearButtons = function(){
		for ( var i = 0; i < $(plugin.clear_buttons).length; i++ ){
			var button = $(plugin.clear_buttons)[i];
			var siteid = $(button).attr('data-siteid');
			for ( var c = 0; c < plugin.userfavorites.length; c++ ){
				if ( plugin.userfavorites[c].site_id !== parseInt(siteid) ) continue;
				if ( plugin.objectLength(plugin.userfavorites[c].posts) > 0 ) {
					$(button).attr('disabled', false);
					continue;
				}
				$(button).attr('disabled', 'disabled');
			}
		}
	}


	// Clear all favorites
	plugin.clearFavorites = function(button){
		$(button).addClass('loading');
		$(button).attr('disabled', 'disabled');
		var site_id = $(button).attr('data-siteid');
		$.ajax({
			url: plugin.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : plugin.formactions.clearall,
				nonce : plugin.nonce,
				siteid : site_id,
			},
			success : function(data){
				plugin.userfavorites = data.favorites;
				$(button).removeClass('loading');
				plugin.resetCounts();
			}
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

		plugin.setUserFavorites(plugin.updateAllButtons);
	}


	// Update all lists
	plugin.updateAllLists = function(){
		for ( var i = 0; i < plugin.userfavorites.length; i++ ){
			var lists = $(plugin.lists + '[data-siteid="' + plugin.userfavorites[i].site_id + '"]');
			for ( var c = 0; c < $(lists).length; c++ ){
				if ( $(lists[c]).attr('data-userid') === "" ){
					var list = $(lists)[c];
					plugin.updateSingleList($(list), plugin.userfavorites[i].posts);
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
				action : plugin.formactions.favoritelist,
				nonce : plugin.nonce,
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
		for ( var i = 0; i < $(plugin.total_favorites).length; i++ ){
			var item = $(plugin.total_favorites)[i];
			var siteid = parseInt($(item).attr('data-siteid'));
			var posttypes = $(item).attr('data-posttypes');
			var posttypes_array = posttypes.split(','); // Multiple Post Type Support
			var count = 0;

			// Loop through all sites in favorites
			for ( var c = 0; c < plugin.userfavorites.length; c++ ){
				var site_favorites = plugin.userfavorites[c];
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


	return plugin.init();
}