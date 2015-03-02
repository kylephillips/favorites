<?php
/**
* Primary plugin API functions
*/

use SimpleFavorites\Entities\Favorite\FavoriteButton;

/**
* Get the favorite button
*/
function get_simple_favorites_button($post_id = null)
{
	if ( !$post_id ) $post_id = get_the_id();
	$button = new FavoriteButton($post_id);
	return $button->display();
}

/**
* Echos the favorite button
*/
function simple_favorites_button($post_id = null)
{	
	echo get_simple_favorites_button($post_id);
}