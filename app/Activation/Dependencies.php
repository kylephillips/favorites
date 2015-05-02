<?php namespace SimpleFavorites\Activation;

use SimpleFavorites\Helpers;
use SimpleFavorites\Config\SettingsRepository;

/**
* Plugin Dependencies
*/
class Dependencies {

	/**
	* Plugin Directory
	*/
	private $plugin_dir;

	/**
	* Plugin Version
	*/
	private $plugin_version;

	/**
	* Settings Repository
	*/
	private $settings_repo;


	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->setPluginVersion();
		$this->plugin_dir = Helpers::plugin_url();
		add_action( 'admin_enqueue_scripts', array($this, 'adminStyles') );
		add_action( 'admin_enqueue_scripts', array($this, 'adminScripts') );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontendStyles' ));
		add_action( 'wp_enqueue_scripts', array( $this, 'frontendScripts' ));
	}

	/**
	* Set the Plugin Version
	*/
	private function setPluginVersion()
	{
		global $simple_favorites_version;
		$this->plugin_version = $simple_favorites_version;
	}

	/**
	* Admin Styles
	*/
	public function adminStyles()
	{
		wp_enqueue_style(
			'simple-favorites-admin', 
			$this->plugin_dir . '/assets/css/simple-favorites-admin.css', 
			array(), 
			$this->plugin_version
		);
	}

	/**
	* Admin Scripts
	*/
	public function adminScripts()
	{
		wp_enqueue_script(
			'simple-favorites-admin', 
			$this->plugin_dir . '/assets/js/simple-favorites-admin.min.js', 
			array('jquery'), 
			$this->plugin_version
		);
	}

	/**
	* Front End Styles
	*/
	public function frontendStyles()
	{
		if ( !$this->settings_repo->outputDependency('css') ) return;
		wp_enqueue_style(
			'simple-favorites', 
			$this->plugin_dir . '/assets/css/simple-favorites.css', 
			array(), 
			$this->plugin_version
		);
	}

	/**
	* Front End Scripts
	*/
	public function frontendScripts()
	{
		if ( !$this->settings_repo->outputDependency('js') ) return;
		wp_enqueue_script(
			'simple-favorites', 
			$this->plugin_dir . '/assets/js/simple-favorites.min.js', 
			array('jquery'), 
			$this->plugin_version
		);
		wp_localize_script(
			'simple-favorites',
			'simple_favorites',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'favorite' => $this->settings_repo->buttonText(),
				'favorited' => $this->settings_repo->buttonTextFavorited(),
				'includecount' => $this->settings_repo->includeCountInButton(),
				'indicate_loading' => $this->settings_repo->includeLoadingIndicator(),
				'loading_text' => $this->settings_repo->loadingText(),
				'loading_image' => $this->settings_repo->loadingImage(),
				'loading_image_active' => $this->settings_repo->loadingImage('active'),
				'loading_image_preload' => $this->settings_repo->includeLoadingIndicatorPreload()
			)
		);
	}

}