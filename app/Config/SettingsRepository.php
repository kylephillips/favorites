<?php namespace SimpleFavorites\Config;

class SettingsRepository {

	/**
	* Output Dependency
	* @return array
	* @since 1.0
	*/
	public function outputDependency($dependency = 'css')
	{
		$option = get_option('simplefavorites_dependencies');
		return ( isset($option[$dependency]) && $option[$dependency] == 'true' ) ? true : false;
	}

	/**
	* Anonymous Display Options
	* @param string option (array key)
	* @since 1.0
	* @return boolean
	*/
	public function anonymous($option = 'display')
	{
		$anon_option = get_option('simplefavorites_users');

		if ( isset($anon_option['anonymous'][$option]) 
			&& $anon_option['anonymous'][$option] == 'true') {
			return true;
		}
		return false;
	}

	/**
	* Method of saving favorites for anonymous users
	*/
	public function saveType()
	{
		$option = get_option('simplefavorites_users');
		if ( !isset($option['anonymous']['saveas']) ) return 'cookie';
		return $option['anonymous']['saveas'];
	}


	/**
	* Display in a given Post Type?
	* @param string post type name
	*/
	public function displayInPostType($posttype)
	{
		$types = get_option('simplefavorites_display');
		if ( $types && $types !== "" ){
			foreach ( $types['posttypes'] as $key => $type ){
				if ( $key == $posttype && isset($type['display']) && $type['display'] == 'true' ) return $type;
			}
		}
		return false;
	}

	/**
	* Favorite Button Text
	*/
	public function buttonText()
	{
		$option = get_option('simplefavorites_display');
		if ( !isset($option['buttontext']) || $option['buttontext'] == "" ) 
			return __('Favorite', 'simplefavorites');
		return esc_html($option['buttontext']);
	}

	/**
	* Favorite Button Text (Active state)
	*/
	public function buttonTextFavorited()
	{
		$option = get_option('simplefavorites_display');
		if ( !isset($option['buttontextfavorited']) || $option['buttontextfavorited'] == "" ) 
			return __('Favorited', 'simplefavorites');
		return esc_html($option['buttontextfavorited']);
	}

	/**
	* Post Types to show meta box on
	*/
	public function metaEnabled()
	{
		$posttypes = array();
		$types = get_option('simplefavorites_display');
		if ( !isset($types['posttypes']) || $types['posttypes'] == "" ) return $posttypes;
		foreach ( $types['posttypes'] as $key => $type ){
			if ( isset($type['postmeta']) && $type['postmeta'] == 'true' ) array_push($posttypes, $key);
		}
		return $posttypes;
	}

}