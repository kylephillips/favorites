<?php namespace SimpleFavorites\Entities\Post;
/**
* Returns the total number of favorites for a post
*/
class FavoriteCount {

	/**
	* Get the favorite count for a post
	*/
	public function getCount($post_id, $site_id = null)
	{
		if ( (is_multisite()) && (isset($site_id)) && ($site_id !== "") ) switch_to_blog(intval($site_id));
		$count = get_post_meta($post_id, 'simplefavorites_count', true);
		if ( $count == '' ) $count = 0;
		if ( (is_multisite()) && (isset($site_id) && ($site_id !== "")) ) restore_current_blog();
		return intval($count);
	}

}