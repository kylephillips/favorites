<?php namespace SimpleFavorites\Forms;

use SimpleFavorites\Forms\NonceHandler;
use SimpleFavorites\Forms\FavoriteButtonHandler;
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

		// User's Favorited Lists
		add_action( 'wp_ajax_nopriv_simplefavorites_list', array($this, 'favoriteList' ));
		add_action( 'wp_ajax_simplefavorites_list', array($this, 'favoriteList' ));
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
	* Get a list of current user's favorites
	*/
	public function favoriteList()
	{
		new FavoritesListHandler;
	}

}