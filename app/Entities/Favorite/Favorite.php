<?php namespace SimpleFavorites\Entities\Favorite;

use SimpleFavorites\Config\SettingsRepository;
use SimpleFavorites\Entities\Favorite\SyncUserFavorite;

class Favorite {

	/**
	* Settings Repository
	*/
	private $settings_repo;

	/**
	* Favorite Sync
	*/
	private $sync_service;


	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
	}

	/**
	* Save the Favorite
	*/
	public function update($post_id)
	{
		$saveType = $this->settings_repo->saveType();
		$usersync = new SyncUserFavorite($post_id);
		$usersync->$saveType();
	}

}