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