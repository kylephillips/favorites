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

}