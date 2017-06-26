<?php
namespace Favorites\Events;

use Favorites\Listeners\FavoritesArray;
use Favorites\Listeners\FavoriteButton;

class RegisterAPIEndpoints
{
	public function __construct()
	{
		register_rest_route( 'favorites/v1', '/generate-nonce/', array(
			'methods' => 'GET',
			'callback' => array($this, 'getNonce')
		));
		register_rest_route( 'favorites/v1', '/user-favorites/', array(
			'methods' => 'POST',
			'callback' => array($this, 'getFavorites')
		));
	}

	public function getNonce(\WP_REST_Request $request)
	{
		$response = new \WP_REST_Response( array('status' => 'success', 'nonce' => wp_create_nonce( 'wp_rest' )));
		return $response;
	}

	public function getFavorites()
	{
		$favorites = new FavoritesArray();
		return $favorites->getResponse();
	}
}