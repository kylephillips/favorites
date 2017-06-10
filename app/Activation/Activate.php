<?php 
namespace Favorites\Activation;

/**
* Plugin Activation
*/
class Activate 
{
	public function __construct()
	{
		$this->setOptions();
	}

	/**
	* Default Plugin Options
	*/
	private function setOptions()
	{
		if ( !get_option('simplefavorites_dependencies') 
			&& get_option('simplefavorites_dependencies') !== "" ){
			update_option('simplefavorites_dependencies', array(
				'css' => 'true',
				'js' => 'true'
			));
		}
		if ( !get_option('simplefavorites_users')
			&& get_option('simplefavorites_users') !== "" ){
			update_option('simplefavorites_users', array(
				'anonymous' => array(
					'display' => 'true',
					'save' => 'true'
				),
				'saveas' => 'cookie'
			));
		}
		if ( !get_option('simplefavorites_display')
			&& get_option('simplefavorites_display') !== "" ){
			update_option('simplefavorites_display', array(
				'buttontext' => __('Favorite <i class="sf-icon-star-empty"></i>', 'favorites'),
				'buttontextfavorited' => __('Favorited <i class="sf-icon-star-full"></i>', 'favorites'),
				'posttypes' => array(
					'post' => array(
						'display' => true,
						'after_content' => true,
						'postmeta' => true
					)
				)
			));
		}
		if ( !get_option('simplefavorites_cache_enabled')
			&& get_option('simplefavorites_cache_enabled') !== "" ){
			update_option('simplefavorites_cache_enabled', 'true');
		}
	}
}