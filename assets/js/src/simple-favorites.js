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
	} else {
		var status = 'active';
		$(button).addClass('active');
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
		},
		success: function(data){
			console.log(data);
		}
	});
}


});