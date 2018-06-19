/**
* Utility Methods
*/
var Favorites = Favorites || {};

Favorites.Utilities = function()
{
	var plugin = this;
	var $ = jQuery;

	/*
	* Check if an item is favorited
	* @param int post_id
	* @param object favorites for a specific site
	*/
	plugin.isFavorite = function(post_id, site_favorites)
	{
		var status = false;
		$.each(site_favorites, function(i, v){
			if ( v.post_id === parseInt(post_id) ) status = true;
			if ( parseInt(v.post_id) === post_id ) status = true;
		});
		return status;
	}

	/**
	* Get the length of an
	*/
	plugin.objectLength = function(object)
	{
		var size = 0, key;
		for (key in object) {
			if (object.hasOwnProperty(key)) size++;
		}
		return size;
	}

	/*
	* Get Site index from All Favorites
	*/
	plugin.siteIndex = function(siteid)
	{
		for ( var i = 0; i < Favorites.userFavorites.length; i++ ){
			if ( Favorites.userFavorites[i].site_id !== parseInt(siteid) ) continue;
			return i;
		}
	}

	/*
	* Get a specific thumbnail size
	*/
	plugin.getThumbnail = function(favorite, size)
	{
		var thumbnails = favorite.thumbnails;
		if ( typeof thumbnails === 'undefined' || thumbnails.length == 0 ) return false;
		var thumbnail_url = thumbnails[size];
		if ( typeof thumbnail_url === 'undefined' ) return false;
		if ( !thumbnail_url ) return false;
		return thumbnail_url;
	}
}
/**
* Formatting functionality
*/
var Favorites = Favorites || {};

Favorites.Formatter = function()
{
	var plugin = this;
	var $ = jQuery;

	/*
	*  Add Favorite Count to a button
	*/
	plugin.addFavoriteCount = function(html, count)
	{
		if ( !Favorites.jsData.button_options.include_count ) return html;
		if ( count <= 0 ) count = 0;
		html += ' <span class="simplefavorite-button-count">' + count + '</span>';
		return html;
	}

	/**
	* Decrement all counts by one
	*/
	plugin.decrementAllCounts = function(){
		var buttons = $('.simplefavorite-button.active.has-count');
		for ( var i = 0; i < buttons.length; i++ ){
			var button = $(buttons)[i];
			var count_display = $(button).find('.simplefavorite-button-count');
			var new_count = $(count_display).text() - 1;
			$(button).attr('data-favoritecount', new_count);
		}
	}
}
/**
* Builds the favorite button html
*/
var Favorites = Favorites || {};

Favorites.ButtonOptionsFormatter = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.options = Favorites.jsData.button_options;
	plugin.formatter = new Favorites.Formatter;

	/**
	* Format the button according to plugin options
	*/
	plugin.format = function(button, isFavorite)
	{
		if ( plugin.options.custom_colors ) plugin.colors(button, isFavorite);
		plugin.html(button, isFavorite);
	}

	/**
	* Set the HTML content for the button
	*/
	plugin.html = function(button, isFavorite)
	{
		var count = $(button).attr('data-favoritecount');
		var options = plugin.options.button_type;
		var html = '';
		if ( plugin.options.button_type === 'custom' ){
			if ( isFavorite ) $(button).html(plugin.formatter.addFavoriteCount(Favorites.jsData.favorited, count));
			if ( !isFavorite ) $(button).html(plugin.formatter.addFavoriteCount(Favorites.jsData.favorite, count));
			plugin.applyIconColor(button, isFavorite);
			plugin.applyCountColor(button, isFavorite);
			return;
		}
		if ( isFavorite ){
			html += '<i class="' + options.icon_class + '"></i> ';
			html += options.state_active;
			$(button).html(plugin.formatter.addFavoriteCount(html, count));
			return;
		}
		html += '<i class="' + options.icon_class + '"></i> ';
		html += options.state_default;
		$(button).html(plugin.formatter.addFavoriteCount(html, count));
		plugin.applyIconColor(button, isFavorite);
		plugin.applyCountColor(button, isFavorite);
	}

	/**
	* Apply custom colors to the button if the option is selected
	*/
	plugin.colors = function(button, isFavorite)
	{
		if ( !plugin.options.custom_colors ) return;
		if ( isFavorite ){
			var options = plugin.options.active;
			if ( options.background_active ) $(button).css('background-color', options.background_active);
			if ( options.border_active ) $(button).css('border-color', options.border_active);
			if ( options.text_active ) $(button).css('color', options.text_active);
			return;
		}
		var options = plugin.options.default;
		if ( options.background_default ) $(button).css('background-color', options.background_default);
		if ( options.border_default ) $(button).css('border-color', options.border_default);
		if ( options.text_default ) $(button).css('color', options.text_default);
		plugin.boxShadow(button);
	}

	/**
	* Remove the box shadow from the button if the option is selected
	*/
	plugin.boxShadow = function(button)
	{
		if ( plugin.options.box_shadow ) return;
		$(button).css('box-shadow', 'none');
		$(button).css('-webkit-box-shadow', 'none');
		$(button).css('-moz-box-shadow', 'none');
	}

	/**
	* Apply custom colors to the icon if the option is selected
	*/
	plugin.applyIconColor = function(button, isFavorite)
	{
		if ( !plugin.options.custom_colors ) return;
		if ( isFavorite && plugin.options.active.icon_active ) {
			$(button).find('i').css('color', plugin.options.active.icon_active);
		}
		if ( !isFavorite && plugin.options.default.icon_default ) {
			$(button).find('i').css('color', plugin.options.default.icon_default);
		}
	}

	/**
	* Apply custom colors to the favorite count if the option is selected
	*/
	plugin.applyCountColor = function(button, isFavorite)
	{
		if ( !plugin.options.custom_colors ) return;
		if ( isFavorite && plugin.options.active.count_active ) {
			$(button).find(Favorites.selectors.count).css('color', plugin.options.active.count_active);
			return;
		}
		if ( !isFavorite && plugin.options.default.count_default ) {
			$(button).find(Favorites.selectors.count).css('color', plugin.options.default.count_default);
		}
	}
}
/**
* Generates a new nonce on page load via AJAX
* Solves problem of cached pages and expired nonces
*
* Events:
* favorites-nonce-generated: The nonce has been generated
*/
var Favorites = Favorites || {};

Favorites.NonceGenerator = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).ready(function(){
			if ( Favorites.jsData.dev_mode ){
				console.log('Favorites Localized Data');
				console.log(Favorites.jsData);
			}
			plugin.getNonce();
		});
	}

	/**
	* Make the AJAX call to get the nonce
	*/
	plugin.getNonce = function()
	{
		if ( Favorites.jsData.cache_enabled === '' ){
			Favorites.jsData.nonce = favorites_data.nonce;
			return;
		}
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'POST',
			datatype: 'json',
			data: {
				action : Favorites.formActions.nonce,
				logged_in : Favorites.jsData.logged_in,
				user_id : Favorites.jsData.user_id
			},
			success: function(data){
				Favorites.jsData.nonce = data.nonce;
				if ( Favorites.jsData.dev_mode ){
					console.log('Nonce successfully generated: ' + data.nonce);
				}
				$(document).trigger('favorites-nonce-generated', [data.nonce]);
			}
		});
	}

	return plugin.bindEvents();
}
/**
* Gets the user favorites
*/
var Favorites = Favorites || {};

Favorites.UserFavorites = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.initialLoad = false;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-nonce-generated', function(){
			plugin.initialLoad = true;
			plugin.getFavorites();
		});
	}

	/**
	* Get the user favorites
	*/
	plugin.getFavorites = function()
	{
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'POST',
			datatype: 'json',
			data: {
				action : Favorites.formActions.favoritesarray,
				logged_in : Favorites.jsData.logged_in,
				user_id : Favorites.jsData.user_id
			},
			success: function(data){
				if ( Favorites.jsData.dev_mode ) {
					console.log('The current user favorites were successfully loaded.');
					console.log(data);
				}
				Favorites.userFavorites = data.favorites;
				$(document).trigger('favorites-user-favorites-loaded', [plugin.initialLoad]);
				$(document).trigger('favorites-update-all-buttons');

				// Deprecated Callback
				if ( plugin.initialLoad ) favorites_after_initial_load(Favorites.userFavorites);
			},
			error: function(data){
				if ( !Favorites.jsData.dev_mode ) return;
				console.log('The was an error loading the user favorites.');
				console.log(data);
			}
		});
	}

	return plugin.bindEvents();
}
/**
* Clears all favorites for the user
*
* Events:
* favorites-cleared: The user's favorites have been cleared. Params: clear button
*/
var Favorites = Favorites || {};

Favorites.Clear = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.activeButton; // The active "clear favorites" button
	plugin.utilities = new Favorites.Utilities;
	plugin.formatter = new Favorites.Formatter;

	plugin.bindEvents = function()
	{
		$(document).on('click', Favorites.selectors.clear_button, function(e){
			e.preventDefault();
			plugin.activeButton = $(this);
			plugin.clearFavorites();
		});
		$(document).on('favorites-updated-single', function(){
			plugin.updateClearButtons();
		});
		$(document).on('favorites-user-favorites-loaded', function(){
			plugin.updateClearButtons();
		});
	}

	/*
	* Submit an AJAX request to clear all of the user's favorites
	*/
	plugin.clearFavorites = function()
	{
		plugin.loading(true);
		var site_id = $(plugin.activeButton).attr('data-siteid');
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : Favorites.formActions.clearall,
				nonce : Favorites.jsData.nonce,
				siteid : site_id,
				logged_in : Favorites.jsData.logged_in,
				user_id : Favorites.jsData.user_id
			},
			success : function(data){
				if ( Favorites.jsData.dev_mode ){
					console.log('Favorites list successfully cleared.');
					console.log(data);
				}
				Favorites.userFavorites = data.favorites;
				plugin.formatter.decrementAllCounts();
				plugin.loading(false);
				plugin.clearSiteFavorites(site_id);
				$(document).trigger('favorites-cleared', [plugin.activeButton, data.old_favorites]);
				$(document).trigger('favorites-update-all-buttons');
			},
			error : function(data){
				if ( !Favorites.jsData.dev_mode ) return;
				console.log('There was an error clearing the favorites list.');
				console.log(data);
			}
		});
	}

	/**
	* Toggle the button loading state
	*/
	plugin.loading = function(loading)
	{
		if ( loading ){
			$(plugin.activeButton).addClass(Favorites.cssClasses.loading);
			$(plugin.activeButton).attr('disabled', 'disabled');
			return;
		}
		$(plugin.activeButton).removeClass(Favorites.cssClasses.loading);
	}

	/*
	* Update disabled status for clear buttons
	*/
	plugin.updateClearButtons = function()
	{
		var button;
		var siteid; 
		for ( var i = 0; i < $(Favorites.selectors.clear_button).length; i++ ){
			button = $(Favorites.selectors.clear_button)[i];
			siteid = $(button).attr('data-siteid');
			for ( var c = 0; c < Favorites.userFavorites.length; c++ ){
				if ( Favorites.userFavorites[c].site_id !== parseInt(siteid) ) continue;
				if ( plugin.utilities.objectLength(Favorites.userFavorites[c].posts) > 0 ) {
					$(button).attr('disabled', false);
					continue;
				}
				$(button).attr('disabled', 'disabled');
			}
		}
	}

	/**
	* Clear out favorites for this site id (fix for cookie-enabled sites)
	*/
	plugin.clearSiteFavorites = function(site_id)
	{
		$.each(Favorites.userFavorites, function(i, v){
			if ( this.site_id !== parseInt(site_id) ) return;
			Favorites.userFavorites[i].posts = {};
		});
	}

	return plugin.bindEvents();
}
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
				nonce : Favorites.jsData.nonce,
				userid : user_id,
				siteid : site_id,
				include_links : include_links,
				include_buttons : include_buttons,
				include_thumbnails : include_thumbnails,
				thumbnail_size : thumbnail_size,
				include_excerpt : include_excerpt,
				no_favorites : no_favorites,
				post_types : post_types,
				user_id_current : Favorites.jsData.user_id,
				logged_in : Favorites.jsData.logged_in
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
/**
* Favorite Buttons
* Favorites/Unfavorites a specific post
*
* Events:
* favorites-updated-single: A user's favorite has been updated. Params: favorites, post_id, site_id, status
*/
var Favorites = Favorites || {};

Favorites.Button = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.activeButton; // The clicked button
	plugin.allButtons; // All favorite buttons for the current post
	plugin.authenticated = true;

	plugin.formatter = new Favorites.Formatter;
	plugin.data = {};

	plugin.bindEvents = function()
	{
		$(document).on('click', Favorites.selectors.button, function(e){
			e.preventDefault();
			plugin.activeButton = $(this);
			plugin.setAllButtons();
			plugin.submitFavorite();
		});
	}

	/**
	* Set all buttons
	*/
	plugin.setAllButtons = function()
	{
		var post_id = $(plugin.activeButton).attr('data-postid');
		plugin.allButtons = $('button[data-postid="' + post_id + '"]');
	}

	/**
	* Set the Post Data
	*/
	plugin.setData = function()
	{
		plugin.data.post_id = $(plugin.activeButton).attr('data-postid');
		plugin.data.site_id = $(plugin.activeButton).attr('data-siteid');
		plugin.data.status = ( $(plugin.activeButton).hasClass('active') ) ? 'inactive' : 'active';
		var consentProvided = $(plugin.activeButton).attr('data-user-consent-accepted');
		plugin.data.user_consent_accepted = ( typeof consentProvided !== 'undefined' && consentProvided !== '' ) ? true : false;
	}

	/**
	* Submit the button
	*/
	plugin.submitFavorite = function()
	{
		plugin.loading(true);
		plugin.setData();
		var formData = {
			action : Favorites.formActions.favorite,
			nonce : Favorites.jsData.nonce,
			postid : plugin.data.post_id,
			siteid : plugin.data.site_id,
			status : plugin.data.status,
			logged_in : Favorites.jsData.logged_in,
			user_id : Favorites.jsData.user_id,
			user_consent_accepted : plugin.data.user_consent_accepted
		}
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			dataType: 'json',
			data: formData,
			success: function(data){
				if ( Favorites.jsData.dev_mode ) {
					console.log('The favorite was successfully saved.');
					console.log(data);
				}
				if ( data.status === 'unauthenticated' ){
					Favorites.authenticated = false;
					plugin.loading(false);
					plugin.data.status = 'inactive';
					$(document).trigger('favorites-update-all-buttons');
					$(document).trigger('favorites-require-authentication', [plugin.data]);
					return;
				}
				if ( data.status === 'consent_required' ){
					plugin.loading(false);
					$(document).trigger('favorites-require-consent', [data, plugin.data, plugin.activeButton]);
					return;
				}
				Favorites.userFavorites = data.favorites;
				plugin.loading(false);
				plugin.resetButtons();
				$(document).trigger('favorites-updated-single', [data.favorites, plugin.data.post_id, plugin.data.site_id, plugin.data.status]);
				$(document).trigger('favorites-update-all-buttons');

				// Deprecated callback
				favorites_after_button_submit(data.favorites, plugin.data.post_id, plugin.data.site_id, plugin.data.status);
			},
			error: function(data){
				if ( !Favorites.jsData.dev_mode ) return;
				console.log('There was an error saving the favorite.');
				console.log(data);
			}
		});
	}

	/*
	* Set the output html
	*/
	plugin.resetButtons = function()
	{
		var favorite_count = parseInt($(plugin.activeButton).attr('data-favoritecount'));

		$.each(plugin.allButtons, function(){
			if ( plugin.data.status === 'inactive' ) {
				if ( favorite_count <= 0 ) favorite_count = 1;
				$(this).removeClass(Favorites.cssClasses.active);
				$(this).attr('data-favoritecount', favorite_count - 1);
				$(this).find(Favorites.selectors.count).text(favorite_count - 1);
				return;
			} 
			$(this).addClass(Favorites.cssClasses.active);
			$(this).attr('data-favoritecount', favorite_count + 1);
			$(this).find(Favorites.selectors.count).text(favorite_count + 1);
		});
	}

	/*
	* Toggle loading on the button
	*/
	plugin.loading = function(loading)
	{
		if ( loading ){
			$.each(plugin.allButtons, function(){
				$(this).attr('disabled', 'disabled');
				$(this).addClass(Favorites.cssClasses.loading);
				$(this).html(plugin.addLoadingIndication());
			});
			return;
		}
		$.each(plugin.allButtons, function(){
			$(this).attr('disabled', false);
			$(this).removeClass(Favorites.cssClasses.loading);
		});
	}

	/*
	* Add loading indication to button
	*/
	plugin.addLoadingIndication = function(html)
	{
		if ( Favorites.jsData.indicate_loading !== '1' ) return html;
		if ( plugin.data.status === 'active' ) return Favorites.jsData.loading_text + Favorites.jsData.loading_image_active;
		return Favorites.jsData.loading_text + Favorites.jsData.loading_image;
	}

	return plugin.bindEvents();
}
/**
* Updates Favorite Buttons as Needed
*/
var Favorites = Favorites || {};

Favorites.ButtonUpdater = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.utilities = new Favorites.Utilities;
	plugin.formatter = new Favorites.Formatter;
	plugin.buttonFormatter = new Favorites.ButtonOptionsFormatter;

	plugin.activeButton;
	plugin.data = {};

	plugin.bindEvents = function()
	{
		$(document).on('favorites-update-all-buttons', function(){
			plugin.updateAllButtons();
		});
		$(document).on('favorites-list-updated', function(event, list){
			plugin.updateAllButtons(list);
		});
	}

	/*
	* Update all favorites buttons to match the user favorites
	* @param list object (optionally updates button in list)
	*/
	plugin.updateAllButtons = function(list)
	{
		if ( typeof Favorites.userFavorites === 'undefined' ) return;
		var buttons = ( typeof list === undefined && list !== '' ) 
			? $(list).find(Favorites.selectors.button) 
			: $(Favorites.selectors.button);
		
		for ( var i = 0; i < $(buttons).length; i++ ){
			plugin.activeButton = $(buttons)[i];
			if ( Favorites.authenticated ) plugin.setButtonData();

			if ( Favorites.authenticated && plugin.utilities.isFavorite( plugin.data.postid, plugin.data.site_favorites ) ){
				plugin.buttonFormatter.format($(plugin.activeButton), true);
				$(plugin.activeButton).addClass(Favorites.cssClasses.active);
				$(plugin.activeButton).removeClass(Favorites.cssClasses.loading);
				$(plugin.activeButton).find(Favorites.selectors.count).text(plugin.data.favorite_count);
				continue;
			}

			plugin.buttonFormatter.format($(plugin.activeButton), false);
			$(plugin.activeButton).removeClass(Favorites.cssClasses.active);
			$(plugin.activeButton).removeClass(Favorites.cssClasses.loading);
			$(plugin.activeButton).find(Favorites.selectors.count).text(plugin.data.favorite_count);
		}
	}


	/**
	* Set the button data
	*/
	plugin.setButtonData = function()
	{
		plugin.data.postid = $(plugin.activeButton).attr('data-postid');
		plugin.data.siteid = $(plugin.activeButton).attr('data-siteid');
		plugin.data.favorite_count = $(plugin.activeButton).attr('data-favoritecount');
		plugin.data.site_index = plugin.utilities.siteIndex(plugin.data.siteid);
		plugin.data.site_favorites = Favorites.userFavorites[plugin.data.site_index].posts;
		if ( plugin.data.favorite_count <= 0 ) plugin.data.favorite_count = 0;
	}

	return plugin.bindEvents();
}
/**
* Total User Favorites Count Updates
*/
var Favorites = Favorites || {};

Favorites.TotalCount = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-updated-single', function(){
			plugin.updateTotal();
		});
		$(document).on('favorites-cleared', function(){
			plugin.updateTotal();
		});
		$(document).on('favorites-user-favorites-loaded', function(){
			plugin.updateTotal();
		});
	}

	/*
	* Update Total Number of Favorites
	*/
	plugin.updateTotal = function()
	{
		// Loop through all the total favorite elements
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
/**
* Updates the count of favorites for a post
*/
var Favorites = Favorites || {};

Favorites.PostFavoriteCount = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-updated-single', function(event, favorites, post_id, site_id, status){
			if ( status === 'active' ) return plugin.updateCounts();
			plugin.decrementSingle(post_id, site_id);
		});
		$(document).on('favorites-cleared', function(event, button, old_favorites){
			plugin.updateCounts(old_favorites, true);
		});
	}

	/*
	* Update Total Number of Favorites
	*/
	plugin.updateCounts = function(favorites, decrement)
	{
		if ( typeof favorites === 'undefined' || favorites === '' ) favorites = Favorites.userFavorites;
		if ( typeof decrement === 'undefined' || decrement === '' ) decrement = false;

		// Loop through all the total favorite elements
		for ( var i = 0; i < $('[' + Favorites.selectors.post_favorite_count + ']').length; i++ ){

			var item = $('[' + Favorites.selectors.post_favorite_count + ']')[i];
			var postid = parseInt($(item).attr(Favorites.selectors.post_favorite_count));
			var siteid = $(item).attr('data-siteid');
			if ( siteid === '' ) siteid = '1';

			// Loop through all sites in favorites
			for ( var c = 0; c < favorites.length; c++ ){
				var site_favorites = favorites[c];
				if ( site_favorites.site_id !== parseInt(siteid) ) continue; 
				$.each(site_favorites.posts, function(){

					if ( this.post_id === postid ){
						if ( decrement ){
							var count = parseInt(this.total) - 1;
							$(item).text(count);
							return;
						}
						$(item).text(this.total);
					}
				});
			}
		}
	}

	/**
	* Decrement a single post total
	*/
	plugin.decrementSingle = function(post_id, site_id)
	{
		for ( var i = 0; i < $('[' + Favorites.selectors.post_favorite_count + ']').length; i++ ){
			var item = $('[' + Favorites.selectors.post_favorite_count + ']')[i];
			var item_post_id = $(item).attr(Favorites.selectors.post_favorite_count);
			var item_site_id = $(item).attr('data-siteid');
			if ( item_site_id === '' ) item_site_id = '1';
			if ( item_site_id !== site_id ) continue;
			if ( item_post_id !== post_id ) continue;
			var count = parseInt($(item).text()) - 1;
			$(item).text(count);
		}
	}

	return plugin.bindEvents();
}
/**
* Favorites Require Authentication
*/
var Favorites = Favorites || {};

Favorites.RequireAuthentication = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-require-authentication', function(){
			if ( Favorites.jsData.dev_mode ){
				console.log('Unauthenticated user was prevented from favoriting.');
			}
			if ( Favorites.jsData.authentication_redirect ){
				plugin.redirect();
				return;
			}
			plugin.openModal();
		});
		$(document).on('click', '.simplefavorites-modal-backdrop', function(e){
			plugin.closeModal();
		});
		$(document).on('click', '[' + Favorites.selectors.close_modals + ']', function(e){
			e.preventDefault();
			plugin.closeModal();
		});
	}

	/**
	* Redirect to a page
	*/
	plugin.redirect = function()
	{
		window.location = Favorites.jsData.authentication_redirect_url;
	}

	/**
	* Open the Modal
	*/
	plugin.openModal = function()
	{
		plugin.buildModal();
		setTimeout(function(){
			$('[' + Favorites.selectors.modals + ']').addClass('active');
		}, 10);
	}

	/**
	* Build the Modal
	*/
	plugin.buildModal = function()
	{
		var modal = $('[' + Favorites.selectors.modals + ']');
		if ( modal.length > 0 ) return;
		var html = '<div class="simplefavorites-modal-backdrop" ' + Favorites.selectors.modals + '></div>';
		html += '<div class="simplefavorites-modal-content" ' + Favorites.selectors.modals + '>';
		html += '<div class="simplefavorites-modal-content-body">';
		html += Favorites.jsData.authentication_modal_content;
		html += '</div><!-- .simplefavorites-modal-content-body -->';
		html += '</div><!-- .simplefavorites-modal-content -->';
		$('body').prepend(html);
	}

	/**
	* Close the Moda
	*/
	plugin.closeModal = function()
	{
		$('[' + Favorites.selectors.modals + ']').removeClass('active');
		$(document).trigger('favorites-modal-closed');
	}

	return plugin.bindEvents();
}
/**
* Favorites Require Consent Modal Agreement
*/
var Favorites = Favorites || {};

Favorites.RequireConsent = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.consentData;
	plugin.postData;
	plugin.activeButton;

	plugin.bindEvents = function()
	{
		$(document).on('favorites-require-consent', function(event, consent_data, post_data, active_button){
			plugin.consentData = consent_data;
			plugin.postData = post_data;
			plugin.activeButton = active_button;
			plugin.openModal();
		});
		$(document).on('favorites-user-consent-approved', function(e, button){
			if ( typeof button !== 'undefined' ){
				$(plugin.activeButton).attr('data-user-consent-accepted', 'true');
				$(plugin.activeButton).click();
				plugin.closeModal();
				return;
			}
			plugin.setConsent(true);
		});
		$(document).on('favorites-user-consent-denied', function(){
			plugin.setConsent(false);
		});
		$(document).on('click', '.simplefavorites-modal-backdrop', function(e){
			plugin.closeModal();
		});
		$(document).on('click', '[data-favorites-consent-deny]', function(e){
			e.preventDefault();
			plugin.closeModal();
			$(document).trigger('favorites-user-consent-denied');
		});
		$(document).on('click', '[data-favorites-consent-accept]', function(e){
			e.preventDefault();
			$(document).trigger('favorites-user-consent-approved', [$(this)]);
		});
	}

	/**
	* Open the Modal
	*/
	plugin.openModal = function()
	{
		plugin.buildModal();
		setTimeout(function(){
			$('[' + Favorites.selectors.consentModal + ']').addClass('active');
		}, 10);
	}

	/**
	* Build the Modal
	*/
	plugin.buildModal = function()
	{
		var modal = $('[' + Favorites.selectors.consentModal + ']');
		if ( modal.length > 0 ) return;
		var html = '<div class="simplefavorites-modal-backdrop" ' + Favorites.selectors.consentModal + '></div>';
		html += '<div class="simplefavorites-modal-content" ' + Favorites.selectors.consentModal + '>';
		html += '<div class="simplefavorites-modal-content-body no-padding">';
		html += '<div class="simplefavorites-modal-content-interior">';
		html += plugin.consentData.message;
		html += '</div>';
		html += '<div class="simplefavorites-modal-content-footer">'
		html += '<button class="simplefavorites-button-consent-deny" data-favorites-consent-deny>' + plugin.consentData.deny_text + '</button>';
		html += '<button class="simplefavorites-button-consent-accept" data-favorites-consent-accept>' + plugin.consentData.accept_text + '</button>';
		html += '</div><!-- .simplefavorites-modal-footer -->';
		html += '</div><!-- .simplefavorites-modal-content-body -->';
		html += '</div><!-- .simplefavorites-modal-content -->';
		$('body').prepend(html);
	}

	/**
	* Close the Modal
	*/
	plugin.closeModal = function()
	{
		$('[' + Favorites.selectors.consentModal + ']').removeClass('active');
	}

	/**
	* Submit a manual deny/consent
	*/
	plugin.setConsent = function(consent)
	{
		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			dataType: 'json',
			data: {
				action : Favorites.formActions.cookieConsent,
				consent : consent
			}
		});
	}

	return plugin.bindEvents();
}
/**
* Primary Favorites Initialization
* @package Favorites
* @author Kyle Phillips - https://github.com/kylephillips/favorites
*
* Events:
* favorites-nonce-generated: The nonce has been generated
* favorites-updated-single: A user's favorite has been updated. Params: favorites, post_id, site_id, status
* favorites-cleared: The user's favorites have been cleared. Params: clear button
* favorites-user-favorites-loaded: The user's favorites have been loaded. Params: intialLoad (bool)
* favorites-require-authentication: An unauthenticated user has attempted to favorite a post (The Require Login & Show Modal setting is checked)
*/

/**
* Callback Functions for use in themes (deprecated in v2 in favor of events)
*/
function favorites_after_button_submit(favorites, post_id, site_id, status){}
function favorites_after_initial_load(favorites){}

jQuery(document).ready(function(){
	new Favorites.Factory;
});

var Favorites = Favorites || {};

/**
* DOM Selectors Used by the Plugin
*/
Favorites.selectors = {
	button : '.simplefavorite-button', // Favorite Buttons
	list : '.favorites-list', // Favorite Lists
	clear_button : '.simplefavorites-clear', // Clear Button
	total_favorites : '.simplefavorites-user-count', // Total Favorites (from the_user_favorites_count)
	modals : 'data-favorites-modal', // Modals
	consentModal : 'data-favorites-consent-modal', // Consent Modal
	close_modals : 'data-favorites-modal-close', // Link/Button to close the modals
	count : '.simplefavorite-button-count', // The count inside the favorites button 
	post_favorite_count : 'data-favorites-post-count-id' // The total number of times a post has been favorited
}

/**
* CSS Classes Used by the Plugin
*/
Favorites.cssClasses = {
	loading : 'loading', // Loading State
	active : 'active', // Active State
}

/**
* Localized JS Data Used by the Plugin
*/
Favorites.jsData = {
	ajaxurl : favorites_data.ajaxurl, // The WP AJAX URL
	nonce : null, // The Dynamically-Generated Nonce
	favorite : favorites_data.favorite, // Active Button Text/HTML
	favorited : favorites_data.favorited, // Inactive Button Text
	include_count : favorites_data.includecount, // Whether to include the count in buttons
	indicate_loading : favorites_data.indicate_loading, // Whether to include loading indication in buttons
	loading_text : favorites_data.loading_text, // Loading indication text
	loading_image_active : favorites_data.loading_image_active, // Loading spinner url in active button
	loading_image : favorites_data.loading_image, // Loading spinner url in inactive button
	cache_enabled : favorites_data.cache_enabled, // Is cache enabled on the site
	authentication_modal_content : favorites_data.authentication_modal_content, // Content to display in authentication gate modal
	authentication_redirect : favorites_data.authentication_redirect, // Whether to redirect unauthenticated users to a page
	authentication_redirect_url : favorites_data.authentication_redirect_url, // URL to redirect to
	button_options : favorites_data.button_options, // Custom button options
	dev_mode : favorites_data.dev_mode, // Is Dev mode enabled
	logged_in : favorites_data.logged_in, // Is the user logged in
	user_id : favorites_data.user_id // The current user ID (0 if logged out)
}

/**
* The user's favorites
* @var object
*/
Favorites.userFavorites = null;

/**
* Is the user authenticated
* @var object
*/
Favorites.authenticated = true;

/**
* WP Form Actions Used by the Plugin
*/
Favorites.formActions = {
	nonce : 'favorites_nonce',
	favoritesarray : 'favorites_array',
	favorite : 'favorites_favorite',
	clearall : 'favorites_clear',
	favoritelist : 'favorites_list',
	cookieConsent : 'favorites_cookie_consent'
}

/**
* Primary factory class
*/
Favorites.Factory = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.build = function()
	{
		new Favorites.NonceGenerator;
		new Favorites.UserFavorites;
		new Favorites.Lists;
		new Favorites.Clear;
		new Favorites.Button;
		new Favorites.ButtonUpdater;
		new Favorites.TotalCount;
		new Favorites.PostFavoriteCount;
		new Favorites.RequireAuthentication;
		new Favorites.RequireConsent;
	}

	return plugin.build();
}