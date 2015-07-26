<?php

namespace SimpleFavorites\Entities\Favorite;

use SimpleFavorites\Entities\Post\FavoriteCount;

/**
* Format the user's favorite array to include additional post data
*/
class FavoritesArrayFormatter
{
	/**
	* Formatted favorites array
	*/
	private $formatted_favorites;

	/**
	* Total Favorites Counter
	*/
	private $counter;

	public function __construct()
	{
		$this->counter = new FavoriteCount;
	}

	public function format($favorites)
	{
		$this->formatted_favorites = $favorites;
		$this->resetIndexes();
		$this->addPostData();
		return $this->formatted_favorites;
	}

	/**
	* Reset the favorite indexes
	*/
	private function resetIndexes()
	{
		foreach ( $this->formatted_favorites as $site => $site_favorites ){
			// Make older posts compatible with new name
			if ( !isset($site_favorites['posts']) ) {
				$site_favorites['posts'] = $site_favorites['site_favorites'];
				unset($this->formatted_favorites[$site]['site_favorites']);
			}
			foreach ( $site_favorites['posts'] as $key => $favorite ){
				unset($this->formatted_favorites[$site]['posts'][$key]);
				$this->formatted_favorites[$site]['posts'][$favorite]['post_id'] = $favorite;
			}
		}
	}

	/**
	* Add the post type to each favorite
	*/
	private function addPostData()
	{
		foreach ( $this->formatted_favorites as $site => $site_favorites ){
			foreach ( $site_favorites['posts'] as $key => $favorite ){
				$this->formatted_favorites[$site]['posts'][$key]['post_type'] = get_post_type($key);
				$this->formatted_favorites[$site]['posts'][$key]['title'] = get_the_title($key);
				$this->formatted_favorites[$site]['posts'][$key]['permalink'] = get_the_permalink($key);
				$this->formatted_favorites[$site]['posts'][$key]['total'] = $this->counter->getCount($key, $site['site_id']);
			}
		}
	}

}