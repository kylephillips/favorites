<?php 
namespace Favorites\Entities\Post;

use Favorites\Config\SettingsRepository;
use Favorites\Entities\Post\FavoriteCount;

class PostMeta 
{
	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		add_action( 'add_meta_boxes', [$this, 'favoriteCountBox']);
	}

	/**
	* Add the Favorite Count Meta Box for enabled Types
	*/
	public function favoriteCountBox()
	{
		foreach ( $this->settings_repo->metaEnabled() as $type ){
			add_meta_box(
				'favorites',
				__( 'Favorites', 'favorites' ),
				[$this, 'favoriteCount'],
				$type,
				'side',
				'low'
			);
		}
	}

	/**
	* The favorite count
	*/
	public function favoriteCount()
	{
		global $post;
		$count = new FavoriteCount;
		echo '<strong>' . __('Total Favorites', 'favorites') . ':</strong> ';
		echo $count->getCount($post->ID);
		echo '<input type="hidden" name="simplefavorites_count" value="' . $count->getCount($post->ID) . '">';
	}
}