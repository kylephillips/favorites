jQuery(document).ready(function(){
	new Favorites;
});


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
		clearall : 'simplefavorites_clear'
	}

	// DOM Selectors
	plugin.buttons = '.simplefavorite-button';
	plugin.lists = '.favorites-list';
	plugin.clear_buttons = '.simplefavorites-clear';

	// Localized Data
	plugin.ajaxurl = simple_favorites.ajaxurl;
	plugin.favorite = simple_favorites.favorite;
	plugin.favorited = simple_favorites.favorited;
	plugin.include_count = simple_favorites.includecount;
	plugin.initial_load = true;
	plugin.indicate_loading = simple_favorites.indicate_loading;
	plugin.loading_text = simple_favorites.loading_text;
	plugin.loading_image_active = simple_favorites.loading_image_active;
	plugin.loading_image = simple_favorites.loading_image;

	// JS Data
	plugin.nonce = ''; // The nonce, generated dynamically
	plugin.userfavorites; // Object – User Favorites


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
		plugin.setUserFavorites(plugin.updateAllButtons);
	}


	// Generate a nonce 
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
			}
		});
	}


	// Set the user favorites
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
				if ( callback ) callback();
			}
		});
	}


	// Update the Favorite Buttons to match the user favorites
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

		// if ( plugin.initial_load ) plugin.getFavoriteLists();
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
				console.log(data);
				$(button).removeClass('loading');
				$(button).html(original_html);
				$(button).attr('disabled', false);
				plugin.userfavorites = data.favorites;
				plugin.syncButtons(button);
			}
		});
	}


	// Add loading indication to button
	plugin.addButtonLoading = function(html, status){
		if ( plugin.indicate_loading !== '1' ) return html;
		if ( status === 'active' ){
			return plugin.loading_text + plugin.loading_image_active;
		} else {
			return plugin.loading_text + plugin.loading_image;
		}
	}


	// Sync all buttons of the same post and site id in the DOM to a single button
	plugin.syncButtons = function(button){

		var postid = $(button).attr('data-postid');
		var siteid = $(button).attr('data-siteid');
		var count = $(button).attr('data-favoritecount');
		var status = ( $(button).hasClass('active') ) ? 'active' : 'inactive';
		var html = "";

		for ( var i = 0; i < $(plugin.buttons).length; i++ ){
			var button = $(plugin.buttons)[i];
			
			if ( ( $(button).attr('data-postid') !== postid ) || ( $(button).attr('data-siteid') !== siteid ) ) continue;
			
			$(button).attr('data-favoritecount', count);
			if ( status === 'active' ){
				html = plugin.addFavoriteCount(plugin.favorited, count);
				$(button).html(html).addClass('active');
				continue;
			} 
			html = plugin.addFavoriteCount(plugin.favorite, count);
			$(button).html(html).removeClass('active');
		}

		 plugin.updateClearButtons();
		 plugin.updateAllLists();
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
				var list = $(lists)[c];
				plugin.updateSingleList($(list), plugin.userfavorites[i].posts);
			}
		}
	}


	// Update a single list html
	plugin.updateSingleList = function(list, favorites){

		plugin.removeInvalidListItems(list, favorites);

		// Update the no favorites item
		if ( plugin.objectLength(favorites) > 0 ){
			$(list).find('[data-nofavorites]').remove();
		} else {
			html = '<li data-nofavorites>' + $(list).attr('data-nofavoritestext') + '</li>';
			$(list).empty().append(html);
		}

		var include_buttons = ( $(list).attr('data-includebuttons') === 'true' ) ? true : false;
		var include_links = ( $(list).attr('data-includelinks') === 'true' ) ? true : false;
		
		// Add favorites that arent in the list
		$.each(favorites, function(i, v){
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


	// Remove invalid list items
	plugin.removeInvalidListItems = function(list, favorites){
		var listitems = $(list).find('li[data-postid]');
		$.each(listitems, function(i, v){
			var postid = $(this).attr('data-postid');
			if ( !plugin.isFavorite(postid, favorites) ) $(this).remove();
		});
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


	// Remove an item from an array
	plugin.removeFromArray = function(value, array){
		for ( var i = 0; i < array.length; i++ ){
			if ( array[i] === value ){
				array.splice(i, 1);
			}
		}
		return array;
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