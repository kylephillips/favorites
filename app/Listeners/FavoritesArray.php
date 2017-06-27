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
		parent::__construct();
		$this->user = new UserRepository;
		$this->setFavorites();
		$this->response(array('status'=>'success', 'favorites' => $this->favorites));
	}

	public function getApiResponse()
	{
		return $this->response(array('status'=>'success', 'favorites' => $this->favorites ));
	}

	/**
	* Get the Favorites
	*/
	private function setFavorites()
	{
		$user_id = ( isset($_POST['user_id']) ) ? intval($_POST['user_id']) : null;
		$favorites = $this->user->formattedFavorites(null, null, null, $user_id);
		$this->favorites = $favorites;
	}
}