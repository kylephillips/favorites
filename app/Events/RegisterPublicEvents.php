<?php 
namespace Favorites\Events;

use Favorites\Listeners\FavoriteButton;
use Favorites\Listeners\FavoritesArray;
use Favorites\Listeners\ClearFavorites;
use Favorites\Listeners\FavoriteCount;
use Favorites\Listeners\FavoriteList;
use Favorites\Listeners\CookieConsent;

class RegisterPublicEvents 
{
	public function __construct()
	{
		// Front End Favorite Button
		add_action( 'wp_ajax_nopriv_favorites_favorite', [$this, 'favoriteButton']);
		add_action( 'wp_ajax_favorites_favorite', [$this, 'favoriteButton']);

		// User's Favorited Posts (array of IDs)
		add_action( 'wp_ajax_nopriv_favorites_array', [$this, 'favoritesArray']);
		add_action( 'wp_ajax_favorites_array', [$this, 'favoritesArray']);

		// Clear Favorites
		add_action( 'wp_ajax_nopriv_favorites_clear', [$this, 'clearFavorites']);
		add_action( 'wp_ajax_favorites_clear', [$this, 'clearFavorites']);

		// Total Favorite Count
		add_action( 'wp_ajax_nopriv_favorites_totalcount', [$this, 'favoriteCount']);
		add_action( 'wp_ajax_favorites_totalcount', [$this, 'favoriteCount']);

		// Single Favorite List
		add_action( 'wp_ajax_nopriv_favorites_list', [$this, 'favoriteList']);
		add_action( 'wp_ajax_favorites_list', [$this, 'favoriteList']);

		// Accept/Deny Cookies
		add_action( 'wp_ajax_nopriv_favorites_cookie_consent', [$this, 'cookiesConsented']);
		add_action( 'wp_ajax_favorites_cookie_consent', [$this, 'cookiesConsented']);

	}

	/**
	* Favorite Button
	*/
	public function favoriteButton()
	{
		new FavoriteButton;
	}

	/**
	* Generate a Nonce
	*/
	public function nonce()
	{
		new NonceHandler;
	}

	/**
	* Get an array of current user's favorites
	*/
	public function favoritesArray()
	{
		new FavoritesArray;
	}

	/**
	* Clear all Favorites
	*/
	public function clearFavorites()
	{
		new ClearFavorites;
	}

	/**
	* Favorite Count for a single post
	*/
	public function favoriteCount()
	{
		new FavoriteCount;
	}

	/**
	* Single Favorite List for a Specific User
	*/
	public function favoriteList()
	{
		new FavoriteList;
	}

	/**
	* Cookies were either accepted or denied
	*/
	public function cookiesConsented()
	{
		new CookieConsent;
	}
}