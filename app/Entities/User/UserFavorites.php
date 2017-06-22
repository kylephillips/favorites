<?php 
namespace Favorites\Entities\User;

use Favorites\Entities\User\UserRepository;
use Favorites\Entities\Favorite\FavoriteFilter;
use Favorites\Entities\FavoriteList\FavoriteList;

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

	public function __construct($user_id = null, $site_id = null, $links = false, $filters = null)
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
	public function getFavoritesArray($user_id = null, $site_id = null, $filters = null)
	{
		$user_id = ( isset($user_id) ) ? $user_id : $this->user_id;
		$site_id = ( isset($site_id) ) ? $site_id : $this->site_id;
		$favorites = $this->user_repo->getFavorites($user_id, $site_id);
		if ( isset($filters) ) $this->filters = $filters;
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
	* @param $include_thumbnails boolean - whether to include post thumbnails
	* @param $thumbnail_size string - thumbnail size to display
	* @param $include_excerpt boolean - whether to include the post excerpt
	*/
	public function getFavoritesList($include_button = false, $include_thumbnails = false, $thumbnail_size = 'thumbnail', $include_excerpt = false, $no_favorites = '')
	{
		$list_args = array(
			'include_button' => $include_button,
			'include_thumbnails' => $include_thumbnails,
			'thumbnail_size' => $thumbnail_size,
			'include_excerpt' => $include_excerpt,
			'include_links' => $this->links,
			'site_id' => $this->site_id,
			'user_id' => $this->user_id,
			'no_favorites' => $no_favorites,
			'filters' => $this->filters,
		);
		$list = new FavoriteList($list_args);
		return $list->getList();
	}

	/**
	* Check if post exists and is published
	*/
	private function postExists($id)
	{
		$allowed_statuses = ( isset($this->filters['status']) && is_array($this->filters['status']) ) ? $this->filters['status'] : array('publish');
		$status = get_post_status($id);
		if ( !$status ) return false;
		if ( !in_array($status, $allowed_statuses) ) return false;
		return true;
	}
}