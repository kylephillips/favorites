<?php 
/**
* Static Wrapper for Bootstrap Class
* Prevents T_STRING error when checking for 5.3.2
*/
class Favorites 
{
	public static function init()
	{
		add_action( 'init', [__CLASS__, 'init_translated']);

		// dev/live
		global $favorites_env;
		$favorites_env = 'live';

		global $favorites_version;
		$favorites_version = '2.3.4';

		$app = new Favorites\Bootstrap;
	}

	public static function init_translated() {
		global $favorites_name;
		$favorites_name = __('Favorites', 'favorites');
	}
}