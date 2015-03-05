<?php namespace SimpleFavorites\API\Shortcodes;

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
			'user_id' => null,
			'include_links' => 'true'
		), $options);
	}

	/**
	* Call the function
	*/
	public function renderView($options)
	{
		$this->setOptions($options);

		$favorites = get_user_favorites($this->options['user_id']);
		$out = '<ul>';
		foreach($favorites as $favorite){
			$out .= '<li>';
			if ( $this->options['include_links'] == 'true' ) $out .= '<a href="' . get_permalink($favorite) . '">';
			$out .= get_the_title($favorite);
			if ( $this->options['include_links'] == 'true' ) $out .= '</a>';
			$out .= '</li>';
		}
		$out .= '</ul>';
		return $out;
	}

}