<?php namespace SimpleFavorites;
/**
* Plugin Bootstrap
*/
class Bootstrap {

	public function __construct()
	{
		$this->init();
		$this->setFormActions();
		add_action( 'init', array($this, 'startSession') );
		add_filter( 'plugin_action_links_' . 'simple-favorites/simple-favorites.php', array($this, 'settingsLink' ) );
		add_action( 'plugins_loaded', array($this, 'addLocalization') );
	}

	/**
	* Initialize
	*/
	public function init()
	{
		new Config\Settings;
		new Activation\Activate;
		new Activation\Dependencies;
		new Entities\Post\PostHooks;
	}

	/**
	* Set Form Actions & Handlers
	*/
	public function setFormActions()
	{
		
	}


	/**
	* Add a link to the settings on the plugin page
	*/
	public function settingsLink($links)
	{ 
		$settings_link = '<a href="options-general.php?page=simple-favorites">' . __('Settings','simplefavorites') . '</a>'; 
		$help_link = '<a href="http://favoriteposts.com">' . __('FAQ','simplefavorites') . '</a>'; 
		array_unshift($links, $help_link); 
		array_unshift($links, $settings_link);
		return $links; 
	}


	/**
	* Localization Domain
	*/
	public function addLocalization()
	{
		load_plugin_textdomain(
			'simplefavorites', 
			false, 
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages' );
	}

	/**
	* Initialize a Session
	*/
	public function startSession()
	{
		if ( !session_id() ) session_start();
	}



}