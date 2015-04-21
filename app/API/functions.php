<?php
/**
* Primary plugin API functions
*/

use SimpleFavorites\Entities\Favorite\FavoriteButton;
use SimpleFavorites\Entities\Post\FavoriteCount;
use SimpleFavorites\Entities\User\UserFavorites;


/**
* Get the favorite button
* @param $post_id int, defaults to current post
* @param $site_id int, defaults to current blog/site
* @return html
*/
function get_favorites_button($post_id = null, $site_id = null)
{
	global $blog_id;
	if ( !$post_id ) $post_id = get_the_id();
	$site_id = ( is_multisite() && is_null($site_id) ) ? $blog_id : $site_id;
	if ( !is_multisite() ) $site_id = 1;
	$button = new FavoriteButton($post_id, $site_id);
	return $button->display();
}


/**
* Echos the favorite button
* @param $post_id int, defaults to current post
* @param $site_id int, defaults to current blog/site
* @return html
*/
function the_favorites_button($post_id = null, $site_id = null)
{	
	echo get_favorites_button($post_id, $site_id);
}


/**
* Get the Favorite Total Count for a Post
* @param $post_id int, defaults to current post
* @param $site_id int, defaults to current blog/site
* @return html
*/
function get_favorites_count($post_id = null, $site_id = null)
{
	if ( !$post_id ) $post_id = get_the_id();
	$count = new FavoriteCount();
	return $count->getCount($post_id, $site_id);
}


/**
* Echo the Favorite Count
* @param $post_id int, defaults to current post
* @param $site_id int, defaults to current blog/site
* @return html
*/
function the_favorites_count($post_id = null, $site_id = null)
{
	echo get_favorites_count($post_id, $site_id);
}


/**
* Get an array of User Favorites
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @return array
*/
function get_user_favorites($user_id = null, $site_id = null, $filters = null)
{
	global $blog_id;
	$site_id = ( is_multisite() && is_null($site_id) ) ? $blog_id : $site_id;
	if ( !is_multisite() ) $site_id = 1;
	$favorites = new UserFavorites($user_id, $site_id, $links = false, $filters);
	return $favorites->getFavoritesArray();
}


/**
* HTML List of User Favorites
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @return html
*/
function get_user_favorites_list($user_id = null, $site_id = null, $include_links = false, $filters = null)
{
	global $blog_id;
	$site_id = ( is_multisite() && is_null($site_id) ) ? $blog_id : $site_id;
	if ( !is_multisite() ) $site_id = 1;
	$favorites = new UserFavorites($user_id, $site_id, $include_links, $filters);
	return $favorites->getFavoritesList();
}


/**
* Echo HTML List of User Favorites
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @return html
*/
function the_user_favorites_list($user_id = null, $site_id = null, $include_links = false, $filters = null)
{
	echo get_user_favorites_list($user_id, $site_id, $include_links, $filters);
}


/**
* Get an array of User Favorites
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @return int
*/
function get_user_favorites_count($user_id = null, $site_id = null, $filters = null)
{
	$favorites = get_user_favorites($user_id, $site_id, $filters);
	return count($favorites);
}


/**
* Get an array of User Favorites
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @return html
*/
function the_user_favorites_count($user_id = null, $site_id = null, $filters = null)
{
	echo get_user_favorites_count($user_id, $site_id, $filters);
}
