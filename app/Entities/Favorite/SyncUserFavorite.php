<?php namespace SimpleFavorites\Entities\Favorite;

use SimpleFavorites\Entities\User\UserRepository;

/**
* Sync a favorite to a given save type
*/
class SyncUserFavorite {

	/**
	* The Post ID
	*/
	private $post_id;

	/**
	* User Repository
	*/
	private $user;


	public function __construct($post_id)
	{
		$this->user = new UserRepository;
		$this->post_id = $post_id;
	}

	/**
	* Sync a Session Favorite
	*/
	public function session()
	{
		if ( $this->user->isFavorite($this->post_id) ) return $_SESSION['simplefavorites'] = $this->removeFavorite();
		return $_SESSION['simplefavorites'] = $this->addFavorite();
	}

	/**
	* Sync a Cookie Favorite
	*/
	public function cookie()
	{
		if ( $this->user->isFavorite($this->post_id) ){
			setcookie('simplefavorites', json_encode($this->removeFavorite()), time()+3600, '/' );
			return;
		}
		setcookie('simplefavorites', json_encode($this->addFavorite()), time()+3600, '/' );
		return;
	}

	/**
	* Update User Meta (logged in only)
	*/
	public function updateUserMeta($favorites)
	{
		if ( !is_user_logged_in() ) return false;
		$user_id = get_current_user_id();
		update_user_meta( $user_id, 'simplefavorites', $favorites );
	}

	/**
	* Remove a Favorite
	*/
	private function removeFavorite()
	{
		$favorites = $this->user->getFavorites();
		foreach($favorites as $key => $favorite){
			if ( $favorite == $this->post_id ) unset($favorites[$key]);
		}
		$this->updateUserMeta($favorites);
		return $favorites;
	}

	/**
	* Add a Favorite
	*/
	private function addFavorite()
	{
		$favorites = $this->user->getFavorites();
		array_push($favorites, $this->post_id);
		$this->updateUserMeta($favorites);
		return $favorites;
	}

}