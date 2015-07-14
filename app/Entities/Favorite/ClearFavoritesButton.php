<?php

namespace SimpleFavorites\Entities\Favorite;

use SimpleFavorites\Entities\User\UserRepository;
use SimpleFavorites\Config\SettingsRepository;

class ClearFavoritesButton
{
	/**
	* Site ID
	*/
	private $site_id;

	/**
	* User Respository
	*/
	private $user;

	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct($site_id = 1)
	{
		$this->user = new UserRepository;
		$this->settings_repo = new SettingsRepository;
		$this->site_id = $site_id;
	}

	/**
	* Display the button
	*/
	public function display()
	{
		if ( !$this->user->getsButton() ) return false;
		$out = '<button class="simplefavorites-clearfavorites" data-siteid="' . $this->site_id . '">' . $this->settings_repo->clearFavoritesText() . '</button>';
		return $out;
	}
}