<?php 
namespace Favorites\Listeners;

use Favorites\Entities\User\UserRepository;

/**
* Return an array of user's favorited posts
*/
class FavoritesArray extends AJAXListenerBase
{
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
		$this->response(array('status'=>'success', 'favorites' => $this->favorites));
	}

	/**
	* Get the Favorites
	*/
	private function setFavorites()
	{
		$favorites = $this->user->formattedFavorites();
		$this->favorites = $favorites;
	}
}