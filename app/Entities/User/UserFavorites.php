<?php 

namespace SimpleFavorites\Entities\User;

use SimpleFavorites\Entities\User\UserRepository;
use SimpleFavorites\Entities\Favorite\FavoriteFilter;
use SimpleFavorites\Helpers;
use SimpleFavorites\Entities\Favorite\FavoriteButton;
use SimpleFavorites\Config\SettingsRepository;

class UserFavorites 
{

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
	* User Repository
	*/
	private $user_repo;

	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct($user_id = null, $site_id = null, $links = false, $filters = null)
	{
		$this->user_id = $user_id;
		$this->site_id = $site_id;
		$this->links = $links;
		$this->filters = $filters;
		$this->user_repo = new UserRepository;
		$this->settings_repo = new SettingsRepository;
	}

	/**
	* Get an array of favorites for specified user
	*/
	public function getFavoritesArray()
	{
		$favorites = $this->user_repo->getFavorites($this->user_id, $this->site_id);
		if ( isset($this->filters) && is_array($this->filters) ) $favorites = $this->filterFavorites($favorites);
		return $this->removeInvalidFavorites($favorites);
	}

	/**
	* Remove non-existent or non-published favorites
	* @param array $favorites
	*/
	private function removeInvalidFavorites($favorites)
	{
		foreach($favorites as $key => $favorite){
			if ( !$this->postExists($favorite) ) unset($favorites[$key]);
		}
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
	* @param $include_button boolean - whether to include the favorite button
	*/
	public function getFavoritesList($include_button = false)
	{
		if ( is_null($this->site_id) || $this->site_id == '' ) $this->site_id = get_current_blog_id();
		
		$favorites = $this->getFavoritesArray();
		$no_favorites = $this->settings_repo->noFavoritesText();

		// Post Type filters for data attr
		$post_types = '';
		if ( isset($this->filters['post_type']) ){
			$post_types = implode(',', $this->filters['post_type']);
		}
		
		if ( is_multisite() ) switch_to_blog($this->site_id);
		
		$out = '<ul class="favorites-list" data-userid="' . $this->user_id . '" data-links="true" data-siteid="' . $this->site_id . '" ';
		$out .= ( $include_button ) ? 'data-includebuttons="true"' : 'data-includebuttons="false"';
		$out .= ( $this->links ) ? ' data-includelinks="true"' : ' data-includelinks="false"';
		$out .= ' data-nofavoritestext="' . $no_favorites . '"';
		$out .= ' data-posttype="' . $post_types . '"';
		$out .= '>';
		foreach ( $favorites as $key => $favorite ){
			$out .= '<li data-postid="' . $favorite . '">';
			if ( $include_button ) $out .= '<p>';
			if ( $this->links ) $out .= '<a href="' . get_permalink($favorite) . '">';
			$out .= get_the_title($favorite);
			if ( $this->links ) $out .= '</a>';
			if ( $include_button ){
				$button = new FavoriteButton($favorite, $this->site_id);
				$out .= '</p><p>';
				$out .= $button->display(false) . '</p>';
			}
			$out .= '</li>';
		}
		if ( empty($favorites) ) $out .= '<li data-postid="0" data-nofavorites>' . $no_favorites . '</li>';
		$out .= '</ul>';
		if ( is_multisite() ) restore_current_blog();
		return $out;
	}

	/**
	* Check if post exists and is published
	*/
	private function postExists($id)
	{
		$status = get_post_status($id);
		return( !$status || $status !== 'publish') ? false : true;
	}

}