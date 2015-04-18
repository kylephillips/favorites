<?php namespace SimpleFavorites\Entities\User;

use SimpleFavorites\Config\SettingsRepository;
use SimpleFavorites\Helpers;

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
	public function getsButton()
	{
		if ( is_user_logged_in() ) return true;
		if ( $this->settings_repo->anonymous('display') ) return true;
		return false;
	}

	/**
	* Get All of current user's favorites
	* @return array
	*/
	public function getAllFavorites()
	{
		if ( is_user_logged_in() ) return $this->getLoggedInFavorites();
		$saveType = $this->settings_repo->saveType();
		$favorites = ( $saveType == 'cookie' ) ? $this->getCookieFavorites() : $this->getSessionFavorites();
		return $this->favoritesWithSiteID($favorites);
	}

	/**
	* Get User's Favorites by Site ID
	* @return array
	*/
	public function getFavorites($user_id = null, $site_id = null)
	{
		if ( is_user_logged_in() ) return $this->getLoggedInFavorites($user_id, $site_id);
		$saveType = $this->settings_repo->saveType();
		$favorites = ( $saveType == 'cookie' ) ? $this->getCookieFavorites($site_id) : $this->getSessionFavorites($site_id);
		return $favorites;
	}

	/**
	* Check for Site ID in user's favorites
	* Multisite Compatibility for >1.1
	* @since 1.1
	*/
	private function favoritesWithSiteID($favorites)
	{
		if ( Helpers::keyExists('site_id', $favorites) ) return $favorites;
		$new_favorites = array(
			array(
				'site_id' => 1,
				'site_favorites' => $favorites
			)
		);
		return $new_favorites;
	}

	/**
	* Get Logged In User Favorites
	*/
	private function getLoggedInFavorites($user_id = null, $site_id = null)
	{
		$user_id = ( isset($user_id) ) ? $user_id : get_current_user_id();
		$favorites = get_user_meta($user_id, 'simplefavorites');
		
		if ( empty($favorites) ) return array(array('site_id'=>1, 'site_favorites' => array()));
		
		$favorites = $this->favoritesWithSiteID($favorites[0]);

		return ( !is_null($site_id) ) ? Helpers::pluckSiteFavorites($site_id, $favorites) : $favorites;
	}

	/**
	* Get Session Favorites
	*/
	private function getSessionFavorites($site_id = null)
	{
		if ( !isset($_SESSION['simplefavorites']) ) $_SESSION['simplefavorites'] = array();
		$favorites = $_SESSION['simplefavorites'];
		$favorites = $this->favoritesWithSiteID($favorites);
		return ( !is_null($site_id) ) ? Helpers::pluckSiteFavorites($site_id, $favorites) : $favorites;
	}


	/**
	* Get Cookie Favorites
	*/
	private function getCookieFavorites($site_id = null)
	{
		if ( !isset($_COOKIE['simplefavorites']) ) $_COOKIE['simplefavorites'] = json_encode(array());
		$favorites = json_decode(stripslashes($_COOKIE['simplefavorites']), true);
		$favorites = $this->favoritesWithSiteID($favorites);
		return ( !is_null($site_id) ) ? Helpers::pluckSiteFavorites($site_id, $favorites) : $favorites;
	}


	/**
	* Has the user favorited a specified post?
	* @param int $post_id
	* @param int $site_id
	*/
	public function isFavorite($post_id, $site_id = 1)
	{
		$favorites = $this->getFavorites(null, $site_id);
		return ( isset($favorites) && (!empty($favorites)) && in_array($post_id, $favorites) ) ? true : false;
	}


	/**
	* Does the user count in total favorites?
	* @return boolean
	*/
	public function countsInTotal()
	{
		if ( is_user_logged_in() ) return true;
		return $this->settings_repo->anonymous('save');
	}

}