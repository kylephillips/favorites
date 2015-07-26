<?php 

namespace SimpleFavorites\Listeners;

use SimpleFavorites\Entities\User\UserRepository;

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
		$favorites = $this->user->getAllFavorites();

		// Update the returned arrays to follow numeric index
		foreach ($favorites as $key => $site){
			$c = 0;
			foreach ( $site['site_favorites'] as $fkey => $favorite ){
				unset($favorites[$key]['site_favorites'][$fkey]);
				$favorites[$key]['site_favorites'][$c] = $favorite;
				$c++;
			}
		}

		$this->favorites = $favorites;
	}
}