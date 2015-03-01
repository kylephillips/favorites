<?php namespace SimpleFavorites\Entities\PostType;

class PostTypeRepository {

	/**
	* Get all registered post types
	* @since 1.0
	* @return array
	*/
	public function getAllPostTypes($return = 'names')
	{
		$args = array(
			'public' => true,
			'show_ui' => true
		);
		return get_post_types($args, $return);
	}

}