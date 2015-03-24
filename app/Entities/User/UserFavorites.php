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
		return $this->user_repo->getFavorites($this->user_id);
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