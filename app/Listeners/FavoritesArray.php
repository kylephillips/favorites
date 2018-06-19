<?php 
namespace Favorites\Listeners;

/**
* Return an array of user's favorited posts
*/
class FavoritesArray extends AJAXListenerBase
{
	/**
	* User Favorites
	* @var array
	*/
	private $favorites;

	public function __construct()
	{
		parent::__construct(false);
		$this->setFavorites();
		$this->response(array('status'=>'success', 'favorites' => $this->favorites));
	}

	/**
	* Get the Favorites
	*/
	private function setFavorites()
	{
		$favorites = $this->user_repo->formattedFavorites();
		$this->favorites = $favorites;
	}
}