<?php 
namespace Favorites\Entities\Post;

/**
* Returns the total number of favorites for a post
*/
class FavoriteCount
{
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

	/**
	* Get the favorite count for all posts in a site
	*/
	public function getAllCount($site_id = null)
	{
		if ( (is_multisite()) && (isset($site_id)) && ($site_id !== "") ) switch_to_blog(intval($site_id));
		global $wpdb;
		$query = "SELECT SUM(meta_value) AS favorites_count FROM {$wpdb->prefix}postmeta WHERE meta_key = 'simplefavorites_count'";
		$count = $wpdb->get_var( $query );
		if ( (is_multisite()) && (isset($site_id) && ($site_id !== "")) ) restore_current_blog();
		return intval($count);
	}

    /**
     * Get count how many time posts for $user_id has been added to favorites
     *
     * @param $user_id
     * @param null $site_id
     * @return int
     */
    public function getCountByUsers($user_id, $site_id = null) {
        if ( (is_multisite()) && (isset($site_id)) && ($site_id !== "") ) switch_to_blog(intval($site_id));

        if (!intval($user_id))
            return 0;

        $types = get_option('simplefavorites_display');

        //Get all posts ids for user
        $args = array(
            'author'         => $user_id,
            'post_type'      => array_keys($types['posttypes']),
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids'
        );

        $user_posts = new \WP_Query($args);

        $post_ids = implode(",", $user_posts->posts);

        global $wpdb;
        $query = "SELECT SUM(meta_value) AS favorites_count FROM {$wpdb->prefix}postmeta WHERE meta_key = 'simplefavorites_count' AND post_id = '$post_ids'";
        $count = $wpdb->get_var( $query );
        if ( (is_multisite()) && (isset($site_id) && ($site_id !== "")) ) restore_current_blog();
        return intval($count);
    }
}