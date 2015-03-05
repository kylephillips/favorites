<?php namespace SimpleFavorites\API\Shortcodes;

class ButtonShortcode {

	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	public function __construct()
	{
		add_shortcode('favorite_button', array($this, 'renderView'));
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
		return get_favorites_button($this->options['post_id']);
	}

}