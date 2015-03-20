<?php namespace SimpleFavorites\Entities\User;

use SimpleFavorites\Entities\User\UserRepository;

class UserFavorites {

	/**
	* User ID
	* @var int
	*/
	private $user_id;

	/**
	* Settings Repository
	*/
	private $user_repo;


	public function __construct($user_id)
	{
		$this->user_id = $user_id;
		$this->user_repo = new UserRepository;
	}

	/**
	* Get an array of favorites for specified user
	*/
	public function getFavoritesArray()
	{
		if ( $this->user_id ) return get_user_meta($this->user_id, 'simplefavorites', true);
		if ( is_user_logged_in() ) return get_user_meta(get_current_user_id(), 'simplefavorites', true);
		return $this->user_repo->getFavorites();
	}

	/**
	* Return an HTML list of favorites for specified user
	*/
	public function getFavoritesList()
	{
		$favorites = $this->getFavoritesArray();
		if ( $favorites ){
			$out = '<ul>';
			foreach ( $favorites as $key => $favorite ){
				$out .= '<li><a href="' . get_permalink($favorite) . '">' . get_the_title($favorite) . '</a></li>';
			}
			$out .= '</ul>';
			return $out;
		}
		return false;
	}

}