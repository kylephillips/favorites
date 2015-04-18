<?php namespace SimpleFavorites\Entities\Favorite;

use SimpleFavorites\Entities\User\UserRepository;
use SimpleFavorites\Config\SettingsRepository;

class FavoriteButton {

	/**
	* The Post ID
	*/
	private $post_id;

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


	public function __construct($post_id, $site_id)
	{
		$this->user = new UserRepository;
		$this->settings_repo = new SettingsRepository;
		$this->post_id = $post_id;
		$this->site_id = $site_id;
	}

	/**
	* Diplay the Button
	* @return html
	*/
	public function display()
	{
		if ( !$this->user->getsButton() ) return false;

		$favorited = ( $this->user->isFavorite($this->post_id, $this->site_id) ) ? true : false;
		$text = ( $favorited ) 
			? html_entity_decode($this->settings_repo->buttonTextFavorited()) 
			: html_entity_decode($this->settings_repo->buttonText());

		$out = '<button class="simplefavorite-button';
		if ( $favorited ) $out .= ' active';
		$out .= '" data-postid="' . $this->post_id . '" data-siteid="' . $this->site_id . '">' . $text . '</button>';
		return $out;
	}


}