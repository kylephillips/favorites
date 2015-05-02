<?php 
/**
* Static Wrapper for Bootstrap Class
* Prevents T_STRING error when checking for 5.3.2
*/
class SimpleFavorites {

	public static function init()
	{
		// dev/live
		global $simple_favorites_env;
		$simple_favorites_env = 'live';

		global $simple_favorites_version;
		$simple_favorites_version = '1.1.4';

		global $simple_favorites_name;
		$simple_favorites_name = __('Favorites', 'simplefavorites');

		$app = new SimpleFavorites\Bootstrap;
	}
}