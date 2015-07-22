<?php 

namespace SimpleFavorites\Events;

use SimpleFavorites\Listeners\NonceHandler;
use SimpleFavorites\Listeners\FavoriteButton;
use SimpleFavorites\Listeners\FavoritesArray;
use SimpleFavorites\Listeners\FavoritesList;
use SimpleFavorites\Listeners\ClearFavorites;
use SimpleFavorites\Listeners\FavoriteCount;

class RegisterPublicEvents 
{

	public function __construct()
	{
		// Generate a Nonce
		add_action( 'wp_ajax_nopriv_simplefavoritesnonce', array($this, 'nonce' ));
		add_action( 'wp_ajax_simplefavoritesnonce', array($this, 'nonce' ));

		// Front End Favorite Button
		add_action( 'wp_ajax_nopriv_simplefavorites', array($this, 'favoriteButton' ));
		add_action( 'wp_ajax_simplefavorites', array($this, 'favoriteButton' ));

		// User's Favorited Posts (array of IDs)
		add_action( 'wp_ajax_nopriv_simplefavorites_array', array($this, 'favoritesArray' ));
		add_action( 'wp_ajax_simplefavorites_array', array($this, 'favoritesArray' ));

		// HTML formatted list of favorites
		add_action( 'wp_ajax_nopriv_simplefavorites_list', array($this, 'favoritesList' ));
		add_action( 'wp_ajax_simplefavorites_list', array($this, 'favoritesList' ));

		// Clear Favorites
		add_action( 'wp_ajax_nopriv_simplefavorites_clear', array($this, 'clearFavorites' ));
		add_action( 'wp_ajax_simplefavorites_clear', array($this, 'clearFavorites' ));

		// Total Favorite Count
		add_action( 'wp_ajax_nopriv_simplefavorites_totalcount', array($this, 'favoriteCount' ));
		add_action( 'wp_ajax_simplefavorites_totalcount', array($this, 'favoriteCount' ));

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
	* Get an HTML formatted list of given user's favorites
	*/
	public function favoritesList()
	{
		new FavoritesList;
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

}