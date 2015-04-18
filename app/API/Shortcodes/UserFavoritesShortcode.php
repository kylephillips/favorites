<?php namespace SimpleFavorites\API\Shortcodes;

use SimpleFavorites\Entities\User\UserFavorites;

class UserFavoritesShortcode {

	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	public function __construct()
	{
		add_shortcode('user_favorites', array($this, 'renderView'));
	}

	/**
	* Shortcode Options
	*/
	private function setOptions($options)
	{
		$this->options = shortcode_atts(array(
			'user_id' => '',
			'site_id' => '',
			'include_links' => 'true'
		), $options);
	}

	/**
	* Render the HTML list
	* @param $options, array of shortcode options
	*/
	public function renderView($options)
	{
		$this->setOptions($options);
		
		$this->options['include_links'] = ( $this->options['include_links'] == 'true' ) ? true : false;
		if ( $this->options['user_id'] == "" ) $this->options['user_id'] = null;
		if ( $this->options['site_id'] == "" ) $this->options['site_id'] = null;

		$favorites = new UserFavorites($this->options['user_id'], $this->options['site_id'], $this->options['include_links']);
		return $favorites->getFavoritesList();
	}

}