<?php 

namespace SimpleFavorites\Listeners;

use SimpleFavorites\Entities\Favorite\Favorite;
use SimpleFavorites\Entities\User\UserRepository;
use SimpleFavorites\Entities\Favorite\SyncAllFavorites;
use SimpleFavorites\Entities\Post\SyncFavoriteCount;

class ClearFavorites extends AJAXListenerBase
{
	/**
	* User Repository
	*/
	private $user_repo;

	/**
	* Favorites Sync
	*/
	private $favorites_sync;

	public function __construct()
	{
		parent::__construct();
		$this->user_repo = new UserRepository;
		$this->favorites_sync = new SyncAllFavorites;
		$this->setFormData();
		$this->clearFavorites();
		$this->sendResponse();
	}

	/**
	* Set Form Data
	*/
	private function setFormData()
	{
		$this->data['siteid'] = intval(sanitize_text_field($_POST['siteid']));
	}

	/**
	* Remove all user's favorites from the specified site
	*/
	private function clearFavorites()
	{
		$user = ( is_user_logged_in() ) ? get_current_user_id() : null;
		do_action('favorites_before_clear', $this->data['siteid'], $user);
		
		$favorites = $this->user_repo->getAllFavorites();
		foreach($favorites as $key => $site_favorites){
			if ( $site_favorites['site_id'] == $this->data['siteid'] ) {
				$this->updateFavoriteCounts($site_favorites);
				unset($favorites[$key]);
			}
		}
		$this->favorites_sync->sync($favorites);
		
		do_action('favorites_after_clear', $this->data['siteid'], $user);
	}

	/**
	* Update all the cleared post favorite counts
	*/
	private function updateFavoriteCounts($site_favorites)
	{
		foreach($site_favorites['posts'] as $favorite){
			$count_sync = new SyncFavoriteCount($favorite, 'inactive', $this->data['siteid']);
			$count_sync->sync();
		}
	}

	/**
	* Set and send the response
	*/
	private function sendResponse()
	{
		$favorites = $this->user_repo->formattedFavorites();
		$this->response(array(
			'status' => 'success',
			'favorites' => $favorites
		));
	}

}