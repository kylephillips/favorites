<?php namespace SimpleFavorites\Forms;

use SimpleFavorites\Forms\NonceHandler;
use SimpleFavorites\Forms\FavoriteButtonHandler;
use SimpleFavorites\Forms\FavoritesArrayHandler;
use SimpleFavorites\Forms\FavoritesListHandler;

class Handlers {

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

	}

	/**
	* Favorite Button
	*/
	public function favoriteButton()
	{
		new FavoriteButtonHandler;
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
		new FavoritesArrayHandler;
	}

	/**
	* Get an HTML formatted list of given user's favorites
	*/
	public function favoritesList()
	{
		new FavoritesListHandler;
	}

}