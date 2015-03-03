jQuery(function($){

/**
* --------------------------------------------------------------------
* Generate a Nonce and Append to Head (page cache workaround)
* --------------------------------------------------------------------
*/
$(document).ready(function(){
	generate_nonce();
});

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
}


/**
* --------------------------------------------------------------------
* Update Favorite button statuses (page cache workaround)
* --------------------------------------------------------------------
*/
$(document).ready(function(){
	get_favorites();
});

function get_favorites()
{
	$.ajax({
		url: simple_favorites.ajaxurl,
		type: 'post',
		datatype: 'json',
		data: {
			action : 'simplefavorites_list'
		},
		success: function(data){
			var favorites = [];
			$.each(data.favorites, function(i, v){
				favorites[i] = v;
			});
			update_buttons(favorites);
		}
	});
}

function update_buttons(favorites)
{
	var buttons = $('.simplefavorite-button');
	$.each(buttons, function(i, v){
		var postid = $(this).data('postid');
		if ( $.inArray(postid.toString(), favorites) !== -1 ){
			$(this).addClass('active').html(simple_favorites.favorited);
		} else {
			$(this).removeClass('active').html(simple_favorites.favorite);
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
	submit_favorite(button);
});


function submit_favorite(button)
{
	var post_id = $(button).data('postid');
	var status = 'inactive';

	if ( $(button).hasClass('active') ) {
		$(button).removeClass('active');
		$(button).html(simple_favorites.favorite);
	} else {
		var status = 'active';
		$(button).addClass('active');
		$(button).html(simple_favorites.favorited);
	}

	$.ajax({
		url: simple_favorites.ajaxurl,
		type: 'post',
		datatype: 'json',
		data: {
			action : 'simplefavorites',
			nonce : simple_favorites_nonce,
			postid : post_id,
			status : status
		}
	});
}


});