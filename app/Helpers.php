<?php 
namespace Favorites;

/**
* Static Helper Methods
*/
class Helpers 
{
	/**
	* Plugin Root Directory
	*/
	public static function plugin_url()
	{
		return plugins_url() . '/' . dirname( plugin_basename( FAVORITES_PLUGIN_FILE ) );
	}

	/**
	* Views
	* @param string $file - The view file name
	* @param string $folder - The folder name within Views (default: empty)
	* @return string - The full path to the view file
	*/
	public static function view($file, $folder = '')
	{
		// Sanitize file name - remove any path traversal characters
		$file = sanitize_file_name($file);
		$file = preg_replace('/[^a-zA-Z0-9\-_]/', '', $file);
		
		// Sanitize folder name if provided
		if ( !empty($folder) ) {
			$folder = sanitize_file_name($folder);
			$folder = preg_replace('/[^a-zA-Z0-9\-_]/', '', $folder);
			$folder = $folder . '/';
		}
		
		return dirname(__FILE__) . '/Views/' . $folder . $file . '.php';
	}

	/**
	* Plugin Version
	*/
	public static function version()
	{
		global $favorites_version;
		return $favorites_version;
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
	* Groups Exists
	* checks if groups array is in favorites array yet
	* @since 2.2
	* @return boolean
	*/
	public static function groupsExist($site_favorites)
	{
		if ( isset($site_favorites['groups']) && !empty($site_favorites['groups']) ) return true;
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
			if ( $site_favorites['site_id'] == $site_id && isset($site_favorites['posts']) ) return $site_favorites['posts'];
		}
		return array();
	}

	/**
	* Pluck the site favorites from saved meta array
	* @since 1.1
	* @param int $site_id
	* @param array $favorites (user meta)
	* @return array
	*/
	public static function pluckGroupFavorites($group_id, $site_id = 1, $all_favorites = [])
	{
		foreach($all_favorites as $key => $site_favorites){
			if ( $site_favorites['site_id'] !== $site_id ) continue;
			foreach ( $all_favorites[$key]['groups'] as $group ){
				if ( $group['group_id'] == $group_id ){
					return $group['posts'];
				}
			}
		}
		return array();
	}
}