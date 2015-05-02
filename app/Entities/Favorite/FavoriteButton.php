<?php namespace SimpleFavorites\Entities\Favorite;

use SimpleFavorites\Entities\User\UserRepository;
use SimpleFavorites\Entities\Post\FavoriteCount;
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

		$count = new FavoriteCount();
		$count = $count->getCount($this->post_id, $this->site_id);

		$favorited = ( $this->user->isFavorite($this->post_id, $this->site_id) ) ? true : false;
		$text = ( $favorited ) 
			? html_entity_decode($this->settings_repo->buttonTextFavorited()) 
			: html_entity_decode($this->settings_repo->buttonText());

		$out = '<button class="simplefavorite-button';
		
		// Button Classes
		if ( $favorited ) $out .= ' active';
		if ( $this->settings_repo->includeCountInButton() ) $out .= ' has-count';
		
		if ( $this->settings_repo->includeLoadingIndicator() && $this->settings_repo->includeLoadingIndicatorPreload() ) $out .= ' loading';

		$out .= '" data-postid="' . $this->post_id . '" data-siteid="' . $this->site_id . '" data-favoritecount="' . $count . '">';

		if ( $this->settings_repo->includeLoadingIndicator() && $this->settings_repo->includeLoadingIndicatorPreload() ){
			$out .= $this->settings_repo->loadingText();
			$spinner = ($favorited) ? $this->settings_repo->loadingImage('active') : $this->settings_repo->loadingImage();
			if ( $spinner ) $out .= $spinner;
		} else {
			$out .= $text;
			if ( $this->settings_repo->includeCountInButton() ) $out .= '<span class="simplefavorite-button-count">' . $count . '<span>';
		}
		$out .= '</button>';
		return $out;
	}


}