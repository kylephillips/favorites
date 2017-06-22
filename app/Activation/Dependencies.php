<?php 
namespace Favorites\Activation;

use Favorites\Helpers;
use Favorites\Config\SettingsRepository;

/**
* Plugin Dependencies
*/
class Dependencies 
{
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
		global $favorites_version;
		$this->plugin_version = $favorites_version;
	}

	/**
	* Admin Styles
	*/
	public function adminStyles()
	{
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style(
			'simple-favorites-admin', 
			$this->plugin_dir . '/assets/css/favorites-admin.css', 
			array(), 
			$this->plugin_version
		);
	}

	/**
	* Admin Scripts
	*/
	public function adminScripts()
	{
		$screen = get_current_screen();
		$settings_page = ( strpos($screen->id, 'simple-favorites') ) ? true : false;
		if ( !$settings_page ) return;
		wp_enqueue_script(
			'simple-favorites-admin', 
			$this->plugin_dir . '/assets/js/favorites-admin.min.js', 
			array('jquery', 'wp-color-picker'), 
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
			$this->plugin_dir . '/assets/css/favorites.css', 
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
		$file = ( $this->settings_repo->devMode() ) ? 'favorites.js' : 'favorites.min.js';
		wp_enqueue_script(
			'favorites', 
			$this->plugin_dir . '/assets/js/' . $file, 
			array('jquery'), 
			$this->plugin_version
		);
		$localized_data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('simple_favorites_nonce'),
			'favorite' => $this->settings_repo->buttonText(),
			'favorited' => $this->settings_repo->buttonTextFavorited(),
			'includecount' => $this->settings_repo->includeCountInButton(),
			'indicate_loading' => $this->settings_repo->includeLoadingIndicator(),
			'loading_text' => $this->settings_repo->loadingText(),
			'loading_image' => $this->settings_repo->loadingImage(),
			'loading_image_active' => $this->settings_repo->loadingImage('active'),
			'loading_image_preload' => $this->settings_repo->includeLoadingIndicatorPreload(),
			'cache_enabled' => $this->settings_repo->cacheEnabled(),
			'button_options' => $this->settings_repo->formattedButtonOptions(),
			'authentication_modal_content' => _favorites_content($this->settings_repo->authenticationModalContent()),
			'dev_mode' => $this->settings_repo->devMode()
		);
		if ( !$this->settings_repo->cacheEnabled() ) $localized_data['nonce'] = wp_create_nonce('simple_favorites_nonce');
		wp_localize_script(
			'favorites',
			'favorites_data',
			$localized_data
		);
	}
}