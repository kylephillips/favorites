<?php
/**
* Primary plugin API functions
*/

use SimpleFavorites\Entities\Favorite\FavoriteButton;

function get_simple_favorites_button($post_id = null)
{
	if ( !$post_id ) $post_id = get_the_id();
	$button = new FavoriteButton($post_id);
	return $button->display();
}

function simple_favorites_button($post_id = null)
{	
	echo get_simple_favorites_button($post_id);
}