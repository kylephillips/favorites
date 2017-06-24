<?php 
namespace Favorites\Entities\Favorite;

use Favorites\Config\SettingsRepository;
use Favorites\Entities\Favorite\SyncSingleFavorite;
use Favorites\Entities\Post\SyncFavoriteCount;

class Favorite 
{
	/**
	* Settings Repository
	*/
	private $settings_repo;

	/**
	* Save Type
	*/
	private $save_type;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
	}

	/**
	* Save the Favorite
	*/
	public function update($post_id, $status, $site_id, $group_id = 1)
	{
		$this->save_type = $this->settings_repo->saveType();
		$usersync = new SyncSingleFavorite($post_id, $site_id, $group_id);
		$saveType = $this->save_type;
		$usersync->$saveType();
		
		$postsync = new SyncFavoriteCount($post_id, $status, $site_id);
		$postsync->sync();
	}

	/**
	* Get the Save Type
	*/
	public function saveType()
	{
		return $this->save_type;
	}
}