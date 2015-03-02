<?php
/**
* Primary plugin API functions
*/

use SimpleFavorites\Entities\Favorite\FavoriteButton;
use SimpleFavorites\Entities\Post\FavoriteCount;

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

/**
* Get the Favorite Count
*/
function get_simple_favorites_count($post_id = null)
{
	if ( !$post_id ) $post_id = get_the_id();
	$count = new FavoriteCount();
	return $count->getCount($post_id);
}

/**
* Echo the Favorite Count
*/
function simple_favorites_count($post_id = null)
{
	echo get_simple_favorites_count($post_id);
}

