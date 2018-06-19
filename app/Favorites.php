<?php 
/**
* Static Wrapper for Bootstrap Class
* Prevents T_STRING error when checking for 5.3.2
*/
class Favorites 
{
	public static function init()
	{
		// dev/live
		global $favorites_env;
		$favorites_env = 'live';

		global $favorites_version;
		$favorites_version = '2.2.0';

		global $favorites_name;
		$favorites_name = __('Favorites', 'favorites');

		$app = new Favorites\Bootstrap;
	}
}