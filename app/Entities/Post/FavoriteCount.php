<?php namespace SimpleFavorites\Entities\Post;
/**
* Returns the total number of favorites for a post
*/
class FavoriteCount {

	/**
	* Get the favorite count for a post
	*/
	public function getCount($post_id)
	{
		$count = get_post_meta($post_id, 'simplefavorites_count', true);
		if ( $count == '' ) $count = 0;
		return intval($count);
	}

}