<?php namespace SimpleFavorites\Entities\Favorite;

use SimpleFavorites\Config\SettingsRepository;
use SimpleFavorites\Entities\Favorite\SyncUserFavorite;
use SimpleFavorites\Entities\Post\SyncFavoriteCount;

class Favorite {

	/**
	* Settings Repository
	*/
	private $settings_repo;


	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
	}

	/**
	* Save the Favorite
	*/
	public function update($post_id, $status, $site_id)
	{
		$saveType = $this->settings_repo->saveType();
		$usersync = new SyncUserFavorite($post_id, $site_id);
		$usersync->$saveType();
		
		$postsync = new SyncFavoriteCount($post_id, $status, $site_id);
		$postsync->sync();
	}

}