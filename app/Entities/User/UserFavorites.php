<?php namespace SimpleFavorites\Entities\User;

use SimpleFavorites\Entities\User\UserRepository;
use SimpleFavorites\Entities\Favorite\FavoriteFilter;
use SimpleFavorites\Helpers;

class UserFavorites {

	/**
	* User ID
	* @var int
	*/
	private $user_id;

	/**
	* Site ID
	* @var int
	*/
	private $site_id;

	/**
	* Display Links
	* @var boolean
	*/
	private $links;

	/**
	* Filters
	* @var array
	*/
	private $filters;

	/**
	* Settings Repository
	*/
	private $user_repo;


	public function __construct($user_id, $site_id, $links = false, $filters = null)
	{
		$this->user_id = $user_id;
		$this->site_id = $site_id;
		$this->links = $links;
		$this->filters = $filters;
		$this->user_repo = new UserRepository;
	}

	/**
	* Get an array of favorites for specified user
	*/
	public function getFavoritesArray()
	{
		$favorites = $this->user_repo->getFavorites($this->user_id, $this->site_id);
		if ( isset($this->filters) && is_array($this->filters) ) $favorites = $this->filterFavorites($favorites);
		return $favorites;
	}

	/**
	* Filter the favorites
	* @since 1.1.1
	* @param array $favorites
	*/
	private function filterFavorites($favorites)
	{
		$favorites = new FavoriteFilter($favorites, $this->filters);
		return $favorites->filter();
	}	

	/**
	* Return an HTML list of favorites for specified user
	*/
	public function getFavoritesList()
	{
		if ( is_null($this->site_id) || $this->site_id == '' ) $this->site_id = get_current_blog_id();
		$favorites = $this->getFavoritesArray();

		if ( $favorites ){
			if ( is_multisite() ) switch_to_blog($this->site_id);
			$out = '<ul class="favorites-list" data-userid="' . $this->user_id . '" data-links="true" data-siteid="' . $this->site_id . '">';
			foreach ( $favorites as $key => $favorite ){
				$out .= '<li>';
				if ( $this->links ) $out .= '<a href="' . get_permalink($favorite) . '">';
				$out .= get_the_title($favorite);
				if ( $this->links ) $out .= '</a>';
				$out .= '</li>';
			}
			$out .= '</ul>';
			if ( is_multisite() ) restore_current_blog();
			return $out;
		}
		return false;
	}

}