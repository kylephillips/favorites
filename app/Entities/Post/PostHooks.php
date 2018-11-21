<?php 
namespace Favorites\Entities\Post;

use Favorites\Config\SettingsRepository;
use Favorites\Entities\Favorite\FavoriteButton;

/**
* Post Actions and Filters
*/
class PostHooks 
{
	/**
	* Settings Repository
	*/
	private $settings_repo;

	/**
	* The Content
	*/
	private $content;

	/**
	* The Post Object
	*/
	private $post;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		add_filter('the_content', [$this, 'filterContent']);
	}

	/**
	* Filter the Content
	*/
	public function filterContent($content)
	{
		global $post;
		if ( !$post ) return $content;
		$this->post = $post;
		$this->content = $content;

		$display = $this->settings_repo->displayInPostType($post->post_type);
		if ( !$display ) return $content;

		return $this->addFavoriteButton($display);
	}

	/**
	* Add the Favorite Button
	* @todo add favorite button html
	*/
	private function addFavoriteButton($display_in)
	{
		$output = '';
		
		if ( isset($display_in['before_content']) && $display_in['before_content'] == 'true' ){
			$output .= get_favorites_button();
		}
		
		$output .= $this->content;

		if ( isset($display_in['after_content']) && $display_in['after_content'] == 'true' ){
			$output .= get_favorites_button();
		}
		return $output;
	}
}