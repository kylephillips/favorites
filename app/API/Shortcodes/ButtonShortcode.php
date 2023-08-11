<?php 
namespace Favorites\API\Shortcodes;

class ButtonShortcode 
{
	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	public function __construct()
	{
		add_shortcode('favorite_button', [$this, 'renderView']);
	}

	/**
	* Shortcode Options
	*/
	private function setOptions($options)
	{
		$this->options = shortcode_atts([
			'post_id' => null,
			'site_id' => null,
			'group_id' => null
		], $options);
	}

	/**
	* Render the Button
	* @param $options, array of shortcode options
	*/
	public function renderView($options)
	{
		$this->setOptions($options);
		$this->options['post_id'] = ( $this->options['post_id'] == "" ) ? null : intval($this->options['post_id']);
		$this->options['site_id'] = ( $this->options['site_id'] == "" ) ? null : intval($this->options['site_id']);
		$this->options['group_id'] = ( $this->options['group_id'] == "" ) ? null : intval($this->options['group_id']);
		return get_favorites_button($this->options['post_id'], $this->options['site_id'], $this->options['group_id']);
	}
}