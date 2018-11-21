<?php 
namespace Favorites\API\Shortcodes;

class FavoriteCountShortcode 
{
	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	public function __construct()
	{
		add_shortcode('favorite_count', [$this, 'renderView']);
	}

	/**
	* Shortcode Options
	*/
	private function setOptions($options)
	{
		$this->options = shortcode_atts([
			'post_id' => '',
			'site_id' => ''
		], $options);
	}

	/**
	* Render the count
	* @param $options, array of shortcode options
	*/
	public function renderView($options)
	{
		$this->setOptions($options);
		return get_favorites_count($this->options['post_id'], $this->options['site_id']);
	}
}