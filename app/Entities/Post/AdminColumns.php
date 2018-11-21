<?php
namespace Favorites\Entities\Post;

use Favorites\Config\SettingsRepository;
use Favorites\Entities\PostType\PostTypeRepository;

/**
* Add Post Favorite Counts to Admin Columns
*/
class AdminColumns
{
	/**
	* Settings Repository
	*/
	private $settings_repo;

	/**
	* Post Type Repository
	*/
	private $post_type_repo;

	/**
	* Post Type
	*/
	private $post_types;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->post_type_repo = new PostTypeRepository;
		$this->setPostTypes();
		$this->addHooks();
	}

	/**
	* Set the Post Types
	*/
	private function setPostTypes()
	{
		$this->post_types = [];
		foreach ( $this->post_type_repo->getAllPostTypes() as $key => $posttype ) : 
			$display = $this->settings_repo->displayInPostType($posttype);
			if ( isset($display['admincolumns']) ) array_push($this->post_types, $key);
		endforeach;
	}

	/**
	* Add Hooks
	*/
	private function addHooks()
	{
		foreach($this->post_types as $type){
			add_filter('manage_' . $type . '_posts_columns', [$this, 'addColumn']);
			add_action('manage_' . $type . '_posts_custom_column', [$this, 'addColumnData'], 10, 2);
		}
	}

	/**
	* Add the column
	*/
	public function addColumn($columns)
	{
		$new_column = ['favorites' => __('Favorites', 'favorites')];
		return array_merge($columns, $new_column);
	}

	/**
	* Add the column data
	*/
	public function addColumnData($column, $post_id)
	{
		if ( $column !== 'favorites' ) return;
		echo get_favorites_count($post_id);
	}
}