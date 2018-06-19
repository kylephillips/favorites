<?php 
namespace Favorites\Events;

use Favorites\Listeners\NonceHandler;
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
		// Generate a Nonce
		add_action( 'wp_ajax_nopriv_favorites_nonce', array($this, 'nonce' ));
		add_action( 'wp_ajax_favorites_nonce', array($this, 'nonce' ));

		// Front End Favorite Button
		add_action( 'wp_ajax_nopriv_favorites_favorite', array($this, 'favoriteButton' ));
		add_action( 'wp_ajax_favorites_favorite', array($this, 'favoriteButton' ));

		// User's Favorited Posts (array of IDs)
		add_action( 'wp_ajax_nopriv_favorites_array', array($this, 'favoritesArray' ));
		add_action( 'wp_ajax_favorites_array', array($this, 'favoritesArray' ));

		// Clear Favorites
		add_action( 'wp_ajax_nopriv_favorites_clear', array($this, 'clearFavorites' ));
		add_action( 'wp_ajax_favorites_clear', array($this, 'clearFavorites' ));

		// Total Favorite Count
		add_action( 'wp_ajax_nopriv_favorites_totalcount', array($this, 'favoriteCount' ));
		add_action( 'wp_ajax_favorites_totalcount', array($this, 'favoriteCount' ));

		// Single Favorite List
		add_action( 'wp_ajax_nopriv_favorites_list', array($this, 'favoriteList' ));
		add_action( 'wp_ajax_favorites_list', array($this, 'favoriteList' ));

		// Accept/Deny Cookies
		add_action( 'wp_ajax_nopriv_favorites_cookie_consent', array($this, 'cookiesConsented' ));
		add_action( 'wp_ajax_favorites_cookie_consent', array($this, 'cookiesConsented' ));

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