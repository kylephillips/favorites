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