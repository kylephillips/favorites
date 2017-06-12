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