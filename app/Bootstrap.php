<?php 
namespace Favorites;

use Favorites\Config\SettingsRepository;

/**
* Plugin Bootstrap
*/
class Bootstrap 
{
	/**
	* Settings Repository
	* @var object
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		add_action( 'init', array($this, 'init') );
		add_action( 'admin_init', array($this, 'adminInit'));
		add_filter( 'plugin_action_links_' . 'favorites/favorites.php', array($this, 'settingsLink' ) );
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
		new Events\RegisterPublicEvents;
		new Entities\Post\PostMeta;
		new API\Shortcodes\ButtonShortcode;
		new API\Shortcodes\FavoriteCountShortcode;
		new API\Shortcodes\UserFavoritesShortcode;
		new API\Shortcodes\UserFavoriteCount;
		new API\Shortcodes\PostFavoritesShortcode;
		new API\Shortcodes\ClearFavoritesShortcode;
		$this->startSession();
	}

	/**
	* Admin Init
	*/
	public function adminInit()
	{
		new Entities\Post\AdminColumns;
	}

	/**
	* Add a link to the settings on the plugin page
	*/
	public function settingsLink($links)
	{ 
		$settings_link = '<a href="options-general.php?page=simple-favorites">' . __('Settings', 'favorites') . '</a>'; 
		$help_link = '<a href="http://favoriteposts.com">' . __('FAQ', 'favorites') . '</a>'; 
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
			'favorites', 
			false, 
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages' );
	}

	/**
	* Initialize a Session
	*/
	public function startSession()
	{
		if ( $this->settings_repo->saveType() !== 'session' ) return;
		if ( !session_id() ) session_start();
	}
}