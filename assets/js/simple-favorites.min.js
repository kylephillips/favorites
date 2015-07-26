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
		list : 'simplefavorites_list',
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
	plugin.favoritecount = []; // Array - Favorite Count by site id


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
				for ( var i = 0; i < data.favorites.length; i++ ){
					plugin.favoritecount[data.favorites[i].site_id] = data.favorites[i].site_favorites.length;
				}
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
			var site_favorites = plugin.userfavorites[site_index].site_favorites;

			if ( plugin.inObject( postid, site_favorites ) ){
				favorite_count = plugin.userfavorites[site_index].total[postid];
				html = plugin.addFavoriteCount(plugin.favorited, favorite_count);
				$(button).addClass('active').html(html).removeClass('loading');
				continue;
			}

			html = plugin.addFavoriteCount(plugin.favorite, favorite_count);
			$(button).removeClass('active').html(html).removeClass('loading');
		}

		if ( plugin.initial_load ) plugin.getFavoriteLists();
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


	// Get all the lists in the DOM
	plugin.getFavoriteLists = function(){
		for ( var i = 0; i < $(plugin.lists).length; i++ ){
			var list = $(plugin.lists)[i];
			plugin.updateList(list);
		}
		if ( plugin.initial_load ) plugin.updateClearButtons();
		plugin.initial_load = false;
	}


	// Update a single list
	plugin.updateList = function(list){
		if ( user_id === '0' ) user_id = null;
		var user_id = $(list).attr('data-userid');
		var site_id = $(list).attr('data-siteid');
		var links = $(list).attr('data-links');
		var include_buttons = $(list).attr('data-includebuttons');

		$.ajax({
			url: plugin.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : plugin.formactions.list,
				userid : user_id,
				siteid : site_id,
				links : links,
				include_buttons : include_buttons
			},
			success: function(data){
				$(list).replaceWith(data.list);
			}
		});
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
				if ( status === 'active' ) $('.simplefavorites-clear[data-siteid=' + site_id + ']').attr('disabled', false);
				if ( !data.has_favorites ) $('.simplefavorites-clear[data-siteid=' + site_id + ']').attr('disabled', true);
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


	// Update the user favorites after a button has been submitted
	plugin.updateUserFavorites = function(button){
		
		var postid = $(button).attr('data-postid');
		var siteid = $(button).attr('data-siteid');
		var status = ( $(button).hasClass('active') ) ? 'active' : 'inactive';
		
		for ( var i = 0; i < plugin.userfavorites.length; i++ ){
			if ( plugin.userfavorites[i].site_id !== parseInt(siteid) ) continue;
			
			if ( status === 'active' ){
				var length = plugin.objectLength(plugin.userfavorites[i].site_favorites);
				plugin.userfavorites[i].site_favorites[length] = parseInt(postid);
				continue;
			}
			
			plugin.userfavorites[i].site_favorites = plugin.removeFromArray(parseInt(postid), plugin.userfavorites[i].site_favorites);
		}
	}


	// Sync all buttons of the same post and site id in the DOM to a single button
	plugin.syncButtons = function(button){	
		
		plugin.updateUserFavorites(button);

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

		 plugin.updateClearButtons()
	}


	// Update disabled status for clear buttons
	plugin.updateClearButtons = function(){
		for ( var i = 0; i < $(plugin.clear_buttons).length; i++ ){
			var button = $(plugin.clear_buttons)[i];
			var siteid = $(button).attr('data-siteid');
			for ( var c = 0; c < plugin.userfavorites.length; c++ ){
				if ( plugin.userfavorites[c].site_id !== parseInt(siteid) ) continue;
				if ( plugin.userfavorites[c].site_favorites.length > 0 ) {
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


	// ------------------------------------------------------------------------------
	// Utilities
	// ------------------------------------------------------------------------------


	// Check if an item is in an array
	plugin.inObject = function(search, object){
		var status = false;
		$.each(object, function(i, v){
			if ( v === parseInt(search) ) status = true;
			if ( parseInt(v) === search ) status = true;
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