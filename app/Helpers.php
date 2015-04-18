<?php namespace SimpleFavorites;
/**
* Static Helper Methods
*/
class Helpers {

	/**
	* Plugin Root Directory
	*/
	public static function plugin_url()
	{
		return plugins_url() . '/' . dirname( plugin_basename( FAVORITES_PLUGIN_FILE ) );
	}

	/**
	* Views
	*/
	public static function view($file)
	{
		return dirname(__FILE__) . '/Views/' . $file . '.php';
	}

	/**
	* Plugin Version
	*/
	public static function version()
	{
		global $simple_favorites_version;
		return $simple_favorites_version;
	}

	/**
	* Get File Contents
	*/
	public static function getFileContents($file)
	{
		return file_get_contents( dirname( dirname(__FILE__) ) . '/' . $file);
	}

	/**
	* Multidemensional array key search
	* @since 1.1
	* @return boolean
	*/
	public static function keyExists($needle, $haystack)
	{
		if ( array_key_exists($needle, $haystack) || in_array($needle, $haystack) ){
			return true;
		} else {
			$return = false;
			foreach ( array_values($haystack) as $value ){
				if ( is_array($value) && !$return ) $return = self::keyExists($needle, $value);
			}
			return $return;
		}
	}

	/**
	* Site ID Exists
	* checks if site id is in favorites array yet
	* @since 1.1
	* @return boolean
	*/
	public static function siteExists($site_id, $meta)
	{
		foreach ( $meta as $key => $site ){
			if ( $site['site_id'] == $site_id ) return true;
		}
		return false;
	}

	/**
	* Pluck the site favorites from saved meta array
	* @since 1.1
	* @param int $site_id
	* @param array $favorites (user meta)
	* @return array
	*/
	public static function pluckSiteFavorites($site_id, $all_favorites)
	{
		foreach($all_favorites as $site_favorites){
			if ( $site_favorites['site_id'] == $site_id ) return $site_favorites['site_favorites'];
		}
		return array();
	}


}