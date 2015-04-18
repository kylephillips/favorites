<?php namespace SimpleFavorites\Entities\Favorite;

use SimpleFavorites\Entities\User\UserRepository;
use SimpleFavorites\Helpers;

/**
* Sync a favorite to a given save type
*/
class SyncUserFavorite {

	/**
	* The Post ID
	*/
	private $post_id;

	/**
	* The Site ID
	*/
	private $site_id;

	/**
	* User Repository
	*/
	private $user;


	public function __construct($post_id, $site_id)
	{
		$this->user = new UserRepository;
		$this->post_id = $post_id;
		$this->site_id = $site_id;
	}

	/**
	* Sync a Session Favorite
	*/
	public function session()
	{
		if ( $this->user->isFavorite($this->post_id, $this->site_id) ) return $_SESSION['simplefavorites'] = $this->removeFavorite();
		return $_SESSION['simplefavorites'] = $this->addFavorite();
	}

	/**
	* Sync a Cookie Favorite
	*/
	public function cookie()
	{
		if ( $this->user->isFavorite($this->post_id, $this->site_id) ){
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
		return update_user_meta( get_current_user_id(), 'simplefavorites', $favorites );
	}

	/**
	* Remove a Favorite
	*/
	private function removeFavorite()
	{
		$favorites = $this->user->getAllFavorites($this->site_id);
		foreach($favorites as $key => $site_favorites){
			if ( $site_favorites['site_id'] !== $this->site_id ) continue;
			foreach($site_favorites['site_favorites'] as $k => $fav){
				if ( $fav == $this->post_id ) unset($favorites[$key]['site_favorites'][$k]);
			}
		}
		$this->updateUserMeta($favorites);
		return $favorites;
	}

	/**
	* Add a Favorite
	*/
	private function addFavorite()
	{
		$favorites = $this->user->getAllFavorites($this->site_id);
		if ( !Helpers::siteExists($this->site_id, $favorites) ){
			$favorites[] = array(
				'site_id' => $this->site_id,
				'site_favorites' => array()
			);
		}
		foreach($favorites as $key => $site_favorites){
			if ( $site_favorites['site_id'] !== $this->site_id ) continue;
			$favorites[$key]['site_favorites'][] = $this->post_id;
		}
		$this->updateUserMeta($favorites);
		return $favorites;
	}

}