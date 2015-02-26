<?php namespace SimpleFavorites\Activation;
/**
* Plugin Activation
*/
class Activate {

	public function __construct()
	{
		$this->setOptions();
	}

	/**
	* Default Plugin Options
	*/
	private function setOptions()
	{
		if ( !get_option('simplefavorites_dependencies') 
			&& get_option('simplefavorites_dependencies') !== "" ){
			update_option('simplefavorites_dependencies', array(
				'css' => 'true',
				'js' => 'true'
			));
		}
		if ( !get_option('simplefavorites_users')
			&& get_option('simplefavorites_users') !== "" ){
			update_option('simplefavorites_users', array(
				'anonymous' => array(
					'display' => 'true',
					'save' => 'true'
				)
			));
		}
	}

}