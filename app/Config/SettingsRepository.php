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
	* @param string option key
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
			return __('Favorite', 'simplefavorites') . ' <i class="simplefavorite-icon-star"></i>';
		return esc_html($option['buttontext']);
	}

}