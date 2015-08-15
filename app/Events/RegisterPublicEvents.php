<?php 

namespace SimpleFavorites\Events;

use SimpleFavorites\Listeners\NonceHandler;
use SimpleFavorites\Listeners\FavoriteButton;
use SimpleFavorites\Listeners\FavoritesArray;
use SimpleFavorites\Listeners\ClearFavorites;
use SimpleFavorites\Listeners\FavoriteCount;
use SimpleFavorites\Listeners\FavoriteList;

class RegisterPublicEvents 
{

	public function __construct()
	{
		// Generate a Nonce
		add_action( 'wp_ajax_nopriv_simplefavorites_nonce', array($this, 'nonce' ));
		add_action( 'wp_ajax_simplefavorites_nonce', array($this, 'nonce' ));

		// Front End Favorite Button
		add_action( 'wp_ajax_nopriv_simplefavorites_favorite', array($this, 'favoriteButton' ));
		add_action( 'wp_ajax_simplefavorites_favorite', array($this, 'favoriteButton' ));

		// User's Favorited Posts (array of IDs)
		add_action( 'wp_ajax_nopriv_simplefavorites_array', array($this, 'favoritesArray' ));
		add_action( 'wp_ajax_simplefavorites_array', array($this, 'favoritesArray' ));

		// Clear Favorites
		add_action( 'wp_ajax_nopriv_simplefavorites_clear', array($this, 'clearFavorites' ));
		add_action( 'wp_ajax_simplefavorites_clear', array($this, 'clearFavorites' ));

		// Total Favorite Count
		add_action( 'wp_ajax_nopriv_simplefavorites_totalcount', array($this, 'favoriteCount' ));
		add_action( 'wp_ajax_simplefavorites_totalcount', array($this, 'favoriteCount' ));

		// Single Favorite List
		add_action( 'wp_ajax_nopriv_simplefavorites_list', array($this, 'favoriteList' ));
		add_action( 'wp_ajax_simplefavorites_list', array($this, 'favoriteList' ));

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

}