<?php namespace SimpleFavorites\Entities\Favorite;

use SimpleFavorites\Entities\User\UserRepository;
use SimpleFavorites\Config\SettingsRepository;

class FavoriteButton {

	/**
	* The Post ID
	*/
	private $post_id;

	/**
	* User Respository
	*/
	private $user;

	/**
	* Settings Repository
	*/
	private $settings_repo;


	public function __construct($post_id)
	{
		$this->user = new UserRepository;
		$this->settings_repo = new SettingsRepository;
		$this->post_id = $post_id;
	}

	/**
	* Diplay the Button
	*/
	public function display()
	{
		if ( !$this->user->displayButton() ) return false;
		return '<button class="simplefavorite-button">' . html_entity_decode($this->settings_repo->buttonText()) . '</button>';
	}


}