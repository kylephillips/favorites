<?php namespace SimpleFavorites\Forms;

use SimpleFavorites\Entities\User\UserRepository;

/**
* Return an array of user's favorited posts
*/
class FavoritesArrayHandler {

	/**
	* User Repository
	*/
	private $user;

	/**
	* User Favorites
	* @var array
	*/
	private $favorites;


	public function __construct()
	{
		$this->user = new UserRepository;
		$this->setFavorites();
		$this->sendResponse();
	}

	/**
	* Get the Favorites
	*/
	private function setFavorites()
	{
		$this->favorites = $this->user->getAllFavorites();
	}

	/**
	* Send the Response
	*/
	private function sendResponse()
	{
		return wp_send_json(array('status'=>'success', 'favorites'=>$this->favorites));
	}

}