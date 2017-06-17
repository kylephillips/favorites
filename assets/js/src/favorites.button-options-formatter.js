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