<?php namespace SimpleFavorites\API\Shortcodes;

class FavoriteCountShortcode {

	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	public function __construct()
	{
		add_shortcode('favorite_count', array($this, 'renderView'));
	}

	/**
	* Shortcode Options
	*/
	private function setOptions($options)
	{
		$this->options = shortcode_atts(array(
			'post_id' => null
		), $options);
	}

	/**
	* Call the function
	*/
	public function renderView($options)
	{
		$this->setOptions($options);
		return get_favorites_count($this->options['post_id']);
	}

}