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

}