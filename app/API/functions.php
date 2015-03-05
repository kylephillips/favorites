<?php
/**
* Primary plugin API functions
*/

use SimpleFavorites\Entities\Favorite\FavoriteButton;
use SimpleFavorites\Entities\Post\FavoriteCount;
use SimpleFavorites\Entities\User\UserFavorites;

/**
* Get the favorite button
* @return html
*/
function get_favorites_button($post_id = null)
{
	if ( !$post_id ) $post_id = get_the_id();
	$button = new FavoriteButton($post_id);
	return $button->display();
}


/**
* Echos the favorite button
* @return html
*/
function the_favorites_button($post_id = null)
{	
	echo get_favorites_button($post_id);
}


/**
* Get the Favorite Total Count for a Post
*/
function get_favorites_count($post_id = null)
{
	if ( !$post_id ) $post_id = get_the_id();
	$count = new FavoriteCount();
	return $count->getCount($post_id);
}


/**
* Echo the Favorite Count
*/
function the_favorites_count($post_id = null)
{
	echo get_favorites_count($post_id);
}


/**
* Get an array of User Favorites
* @return array
*/
function get_user_favorites($user_id = null)
{
	$favorites = new UserFavorites($user_id);
	return $favorites->getFavoritesArray();
}


/**
* HTML List of User Favorites
* @return html
*/
function get_user_favorites_list($user_id = null)
{
	$favorites = new UserFavorites($user_id);
	return $favorites->getFavoritesList();
}


/**
* HTML List of User Favorites
* Echos list
* @return html
*/
function the_user_favorites_list($user_id = null)
{
	echo get_user_favorites_list($user_id);
}
