<?php 
namespace Favorites\API\Shortcodes;

class PostFavoritesShortcode 
{
	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	public function __construct()
	{
		add_shortcode('post_favorites', [$this, 'renderView']);
	}

	/**
	* Shortcode Options
	*/
	private function setOptions($options)
	{
		$this->options = shortcode_atts([
			'post_id' => '',
			'site_id' => '',
			'separator' => 'list',
			'include_anonymous' => 'true',
			'anonymous_label' => __('Anonymous Users', 'favorites'),
			'anonymous_label_single' => __('Anonymous User', 'favorites')
		], $options);
	}

	/**
	* Render the HTML list
	* @param $options, array of shortcode options
	*/
	public function renderView($options)
	{
		$this->setOptions($options);

		$this->options['include_anonymous'] = ( $this->options['include_anonymous'] == 'true' ) ? true : false;
		$this->options['post_id'] = ( $this->options['post_id'] == "" ) ? '' : intval($this->options['post_id']);
		$this->options['site_id'] = ( $this->options['site_id'] == "" ) ? null : intval($this->options['site_id']);
		
		return get_users_list_who_favorited_post(
			$post_id = $this->options['post_id'], 
			$site_id = $this->options['site_id'], 
			$separator = sanitize_text_field($this->options['separator']), 
			$include_anonymous = $this->options['include_anonymous'], 
			$anonymous_label = sanitize_text_field($this->options['anonymous_label']), 
			$anonymous_label_single =  sanitize_text_field($this->options['anonymous_label_single'])
		);
	}
}