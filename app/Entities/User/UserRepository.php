<?php namespace SimpleFavorites\Entities\User;

use SimpleFavorites\Config\SettingsRepository;

class UserRepository {

	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
	}

	/**
	* Display button for current user
	* @return boolean
	*/
	public function displayButton()
	{
		if ( is_user_logged_in() ) return true;
		if ( $this->settings_repo->anonymous('display') ) return true;
		return false;
	}

}