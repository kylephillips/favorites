<?php 
namespace Favorites\Entities\PostType;

class PostTypeRepository 
{
	/**
	* Get all registered post types
	* @since 1.0
	* @return array
	*/
	public function getAllPostTypes($return = 'names', $flat_array = false)
	{
		$args = [
			'public' => true,
			'show_ui' => true
		];
		$post_types = get_post_types($args, $return);
		if ( !$flat_array ) return $post_types;
		$post_types_flat = [];
		foreach ($post_types as $key => $value) {
			$post_types_flat[] = $value;
		}
		return $post_types_flat;
	}
}