<?php namespace SimpleFavorites\Config;

use SimpleFavorites\Config\SettingsRepository;
use SimpleFavorites\Entities\PostType\PostTypeRepository;
use SimpleFavorites\Helpers;

/**
* Plugin Settings
*/
class Settings {

	/**
	* Plugin Name
	*/
	private $plugin_name;

	/**
	* Settings Repository
	*/
	private $settings_repo;

	/**
	* Post Type Repository
	*/
	private $post_type_repo;


	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->post_type_repo = new PostTypeRepository;
		$this->setName();
		add_action( 'admin_init', array( $this, 'registerSettings' ) );
		add_action( 'admin_menu', array( $this, 'registerSettingsPage' ) );
	}


	/**
	* Set the plugin name
	*/
	private function setName()
	{
		global $simple_favorites_name;
		$this->plugin_name = $simple_favorites_name;
	}


	/**
	* Register the settings page
	*/
	public function registerSettingsPage()
	{
		add_options_page( 
			$this->plugin_name . ' ' . __('Settings', 'simplefavorites'),
			$this->plugin_name,
			'manage_options',
			'simple-favorites', 
			array( $this, 'settingsPage' ) 
		);
	}


	/**
	* Display the Settings Page
	*/
	public function settingsPage()
	{
		$tab = ( isset($_GET['tab']) ) ? $_GET['tab'] : 'general';
		include( Helpers::view('settings/settings') );
	}


	/**
	* Register the settings
	*/
	public function registerSettings()
	{
		register_setting( 'simple-favorites-general', 'simplefavorites_dependencies' );
		register_setting( 'simple-favorites-users', 'simplefavorites_users' );
		register_setting( 'simple-favorites-display', 'simplefavorites_display' );
	}


}