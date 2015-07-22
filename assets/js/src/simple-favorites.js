function after_favorite_submit(post_id, status, site_id){}
function before_clear_favorites(button){}

jQuery(function($){

/**
* --------------------------------------------------------------------
* Generate a Nonce and Append to Head (page cache workaround)
* --------------------------------------------------------------------
*/

$(document).ready(function(){
	generate_nonce();
});

/**
* Generate a nonce to get around cached nonce fields
*/
function generate_nonce()
{
	$.ajax({
		url: simple_favorites.ajaxurl,
		type: 'post',
		datatype: 'json',
		data: {
			action : 'simplefavoritesnonce'
		},
		success: function(data){
			appendNonce(data.nonce);
		}
	});
}

/**
* Append the new nonce
*/
function appendNonce(nonce)
{
	var script = '<script type="text/javascript"> var simple_favorites_nonce = "' + nonce + '" ;</script>';
	$('head').append(script);
	get_favorites();
	get_favorites_lists();
}


/**
* --------------------------------------------------------------------
* Update Favorite button statuses (page cache workaround)
* --------------------------------------------------------------------
*/
function get_favorites()
{
	$.ajax({
		url: simple_favorites.ajaxurl,
		type: 'post',
		datatype: 'json',
		data: {
			action : 'simplefavorites_array'
		},
		success: function(data){
			var favorites = [];
			$.each(data.favorites, function(i, v){
				favorites[i] = v;
			});
			update_buttons(data.favorites);
		}
	});
}

/**
* Loop through all the favorites buttons and update their status
*/
function update_buttons(favorites)
{
	var buttons = $('.simplefavorite-button');
	$.each(buttons, function(i, v){
		
		var postid = $(this).data('postid');
		var siteid = $(this).data('siteid');
		var favorite_count = $(this).attr('data-favoritecount');
		var html = "";
		
		// Find the Site's Favorites Array
		for ( var i = 0; i < favorites.length; i++ ){
			if ( favorites[i].site_id !== siteid ) continue;
			if ( inObject(postid, favorites[i].site_favorites) ){
				favorite_count = favorites[i].total[postid];
				html = add_favorite_count_to_button(simple_favorites.favorited, favorite_count);
				$(this).addClass('active').html(html);
			} else {
				html = add_favorite_count_to_button(simple_favorites.favorite, favorite_count);
				$(this).removeClass('active').html(html);
			}
			$(this).removeClass('loading');
		}
	});
	update_clear_buttons(favorites);
}

/**
* Loop through all sites and update clear button status
*/
function update_clear_buttons(favorites)
{
	for ( var i = 0; i < favorites.length; i++ ){
		var buttons = $('.simplefavorites-clear[data-siteid=' + favorites[i].site_id + ']');
		if ( favorites[i].site_favorites.length > 0 ){
			$(buttons).attr('disabled', false);
		} else {
			$(buttons).attr('disabled', 'disabled;');
		}
	}
	var buttons = $('.simplefavorite-button.active');
}

/**
* Add the favorite count to the button text if enabled
*/
function add_favorite_count_to_button(html, favorite_count)
{
	if ( simple_favorites.includecount === '1' ){
		html += ' <span class="simplefavorite-button-count">' + favorite_count + '<span>';
	}
	return html;
}

/**
* Check if value exists in array
*/
function inObject(search, object)
{
	var status = false;
	$.each(object, function(i, v){
		if ( v === search ) status = true;
		if ( parseInt(v) === search ) status = true;
	});
	return status;
}

/**
* --------------------------------------------------------------------
* Update Favorite Lists
* --------------------------------------------------------------------
*/
function get_favorites_lists()
{
	var lists = $('.favorites-list');
	$.each(lists, function(i, v){
		var user_id = $(this).data('userid');
		var site_id = $(this).data('siteid');
		var links = $(this).data('links');
		var list = $(this);
		get_single_list(list, user_id, site_id, links);
	});
}
function get_single_list(list, user_id, site_id, links)
{
	if ( user_id === '0' ) user_id = null;
	$.ajax({
		url: simple_favorites.ajaxurl,
		type: 'post',
		datatype: 'json',
		data: {
			action : 'simplefavorites_list',
			userid : user_id,
			siteid : site_id,
			links : links
		},
		success: function(data){
			$(list).replaceWith(data.list);
		}
	});
}


/**
* --------------------------------------------------------------------
* Submit Favorite
* --------------------------------------------------------------------
*/
$(document).on('click', '.simplefavorite-button', function(e){
	e.preventDefault();
	var button = $(this);
	$(this).addClass('loading');
	$(this).attr('disabled', 'disabled');
	submit_favorite(button);
});


function submit_favorite(button)
{
	var post_id = $(button).data('postid');
	var site_id = $(button).data('siteid');
	var favorite_count = parseInt($(button).attr('data-favoritecount'));

	var status = 'inactive';
	var html = "";
	var original_html = "";

	if ( $(button).hasClass('active') ) {
		$(button).removeClass('active');
		if ( favorite_count - 1 < 0 ) favorite_count = 1;
		$(button).attr('data-favoritecount', favorite_count - 1);
		original_html = add_favorite_count_to_button(simple_favorites.favorite, favorite_count - 1);
		html = add_loading_indication(original_html, status);
		$(button).html(html);
	} else {
		status = 'active';
		$(button).addClass('active');
		$(button).attr('data-favoritecount', favorite_count + 1);
		original_html = add_favorite_count_to_button(simple_favorites.favorited, favorite_count + 1);
		html = add_loading_indication(original_html, status);
		$(button).html(html);
	}

	$.ajax({
		url: simple_favorites.ajaxurl,
		type: 'post',
		datatype: 'json',
		data: {
			action : 'simplefavorites',
			nonce : simple_favorites_nonce,
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
			update_favorite_button(button, data.count);
			after_favorite_submit(post_id, status, site_id);
		}
	});
}

/**
* Update the favorite button
*/
function update_favorite_button(button, count)
{
	$(button).find('.simplefavorite-button-count').text(count);
}

/**
* Add loading indication
* @return html
*/
function add_loading_indication(html, status)
{
	if ( simple_favorites.indicate_loading !== '1' ) return html;
	if ( status === 'active' ){
		return simple_favorites.loading_text + simple_favorites.loading_image_active;
	} else {
		return simple_favorites.loading_text + simple_favorites.loading_image;
	}
}



/**
* --------------------------------------------------------------------
* Clear All Favorites
* --------------------------------------------------------------------
*/
$(document).on('click', '.simplefavorites-clear', function(e){
	e.preventDefault();
	$(this).addClass('loading');
	$(this).attr('disabled', 'disabled');
	clear_favorites($(this));
});

/**
* Clear all Favorites
*/
function clear_favorites(button)
{
	before_clear_favorites(button);
	var site_id = $(button).attr('data-siteid');
	$.ajax({
		url: simple_favorites.ajaxurl,
		type: 'post',
		datatype: 'json',
		data: {
			action : 'simplefavorites_clear',
			nonce : simple_favorites_nonce,
			siteid : site_id,
		},
		success : function(data){
			$(button).removeClass('loading');
			reset_button_counts_after_clear(data);
		}
	});
}

/**
* Update buttons on the page with new favorite count
*/
function reset_button_counts_after_clear(data)
{
	var buttons = $('.simplefavorite-button.active.has-count');
	$.each(buttons, function(){
		var count_display = $(this).find('.simplefavorite-button-count');
		var new_count = $(count_display).text() - 1;
		$(this).attr('data-favoritecount', new_count);
	});
	update_buttons(data.favorites);
}


});