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

	plugin.activeButton;
	plugin.formatter = new Favorites.Formatter;
	plugin.data = {};

	plugin.bindEvents = function()
	{
		$(document).on('click', Favorites.selectors.button, function(e){
			e.preventDefault();
			plugin.activeButton = $(this);
			plugin.submitFavorite();
		});
	}

	/**
	* Set the Post Data
	*/
	plugin.setData = function()
	{
		plugin.data.post_id = $(plugin.activeButton).attr('data-postid');
		plugin.data.site_id = $(plugin.activeButton).attr('data-siteid');
		plugin.data.favorite_count = parseInt($(plugin.activeButton).attr('data-favoritecount'));
		plugin.status = ( $(plugin.activeButton).hasClass('active') ) ? 'inactive' : 'active';
	}

	/**
	* Submit the button
	*/
	plugin.submitFavorite = function()
	{
		plugin.loading(true);
		plugin.setData();

		$.ajax({
			url: Favorites.jsData.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : Favorites.formActions.favorite,
				nonce : Favorites.jsData.nonce,
				postid : plugin.data.post_id,
				siteid : plugin.data.site_id,
				status : plugin.data.status
			},
			success: function(data){
				Favorites.userFavorites = data.favorites;
				$(plugin.activeButton).html(plugin.outputHtml());
				plugin.loading(false);
				$(document).trigger('favorites-updated-single', [data.favorites, plugin.data.post_id, plugin.data.site_id, plugin.data.status]);

				// Deprecated callback
				favorites_after_button_submit(data.favorites, plugin.data.post_id, plugin.data.site_id, plugin.data.status);
			}
		});
	}

	/*
	* Set the output html
	*/
	plugin.outputHtml = function()
	{
		if ( plugin.status === 'inactive' ) {
			$(plugin.activeButton).removeClass(Favorites.cssClasses.active);
			if ( plugin.data.favorite_count - 1 < 0 ) plugin.data.favorite_count = 1;
			$(plugin.activeButton).attr('data-favoritecount', plugin.data.favorite_count - 1);
			return plugin.formatter.addFavoriteCount(Favorites.jsData.favorite, plugin.data.favorite_count - 1);
		} 
		$(plugin.activeButton).addClass(Favorites.cssClasses.active);
		$(plugin.activeButton).attr('data-favoritecount', plugin.data.favorite_count + 1);
		return plugin.formatter.addFavoriteCount(Favorites.jsData.favorited, plugin.data.favorite_count + 1);
	}

	/*
	* Toggle loading on the button
	*/
	plugin.loading = function(loading)
	{
		if ( loading ){
			$(plugin.activeButton).attr('disabled', 'disabled');
			$(plugin.activeButton).addClass(Favorites.cssClasses.loading);
			$(plugin.activeButton).html(plugin.addLoadingIndication());
			return;
		}
		$(plugin.activeButton).attr('disabled', false);
		$(plugin.activeButton).removeClass(Favorites.cssClasses.loading);
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