<?php 
namespace Favorites\API\Shortcodes;

class UserFavoriteCount 
{
	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	/**
	* List Filters
	* @var array
	*/
	private $filters;

	public function __construct()
	{
		add_shortcode('user_favorite_count', [$this, 'renderView']);
	}

	/**
	* Shortcode Options
	*/
	private function setOptions($options)
	{
		$this->options = shortcode_atts([
			'user_id' => '',
			'site_id' => '',
			'post_types' => ''
		], $options);
	}

	/**
	* Parse Post Types
	*/
	private function parsePostTypes()
	{
		if ( $this->options['post_types'] == "" ) return;
		$post_types = explode(',', $this->options['post_types']);
		$this->filters = ['post_type' => $post_types];
	}

	/**
	* Render the HTML list
	* @param $options, array of shortcode options
	*/
	public function renderView($options)
	{
		$this->setOptions($options);
		$this->parsePostTypes();

		if ( $this->options['user_id'] == '' ) $this->options['user_id'] = null;
		if ( $this->options['site_id'] == '' ) $this->options['site_id'] = get_current_blog_id();
		
		return get_user_favorites_count($this->options['user_id'], $this->options['site_id'], $this->filters, true);
	}
}