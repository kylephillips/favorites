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
}