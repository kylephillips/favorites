<?php 
namespace Favorites\Entities\User;

use Favorites\Config\SettingsRepository;
use Favorites\Helpers;
use Favorites\Entities\Favorite\FavoritesArrayFormatter;

class UserRepository 
{
	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
	}

	/**
	* Display button for current user
	* @return boolean
	*/
	public function getsButton()
	{
		if ( is_user_logged_in() ) return true;
		if ( $this->settings_repo->anonymous('display') ) return true;
		if ( $this->settings_repo->requireLogin() ) return true;
		if ( $this->settings_repo->redirectAnonymous() ) return true;
		return false;
	}

	/**
	* Get All of current user's favorites (includes all sites)
	* @return array (multidimensional)
	*/
	public function getAllFavorites()
	{
		if ( isset($_POST['logged_in']) && intval($_POST['logged_in']) == 1 ) {
			$all_favorites = $this->getLoggedInFavorites();
		} else {
			$saveType = $this->settings_repo->saveType();
			$favorites = ( $saveType == 'cookie' ) ? $this->getCookieFavorites() : $this->getSessionFavorites();
			$all_favorites = $this->favoritesWithSiteID($favorites);			
		}
		
		/**
		 * Filter All of current user's favorites.
		 * 
		 * @since	1.3.0
		 * @param	array	The original current user's favorites.
		 */
		$all_favorites = apply_filters('favorites/user/favorites/all', $all_favorites);

		return $all_favorites;
	}

	/**
	* Get User's Favorites by Site ID (includes a single site)
	* @return array (flat)
	*/
	public function getFavorites($user_id = null, $site_id = null, $group_id = null)
	{
		$logged_in = ( isset($_POST['logged_in']) && intval($_POST['logged_in']) == 1 && isset($_POST['user_id']) ) ? true : false;
		if ( $logged_in || is_user_logged_in() || $user_id ) {
			$favorites = $this->getLoggedInFavorites($user_id, $site_id, $group_id);
		} else {
			$saveType = $this->settings_repo->saveType();
			$favorites = ( $saveType == 'cookie' ) 
				? $this->getCookieFavorites($site_id, $group_id) 
				: $this->getSessionFavorites($site_id, $group_id);
		}
		
		/**
		 * Filter a User's Favorites.
		 * 
		 * @since	1.3.0
		 * @param	array	The original User's Favorites.
		 */
		$favorites = apply_filters('favorites/user/favorites', $favorites);

		return $favorites;
	}

	/**
	* Check for Site ID in user's favorites
	* Multisite Compatibility for >1.1
	* 1.2 compatibility with new naming structure
	* @since 1.1
	*/
	private function favoritesWithSiteID($favorites)
	{
		if ( Helpers::keyExists('site_favorites', $favorites) ){
			foreach($favorites as $key => $site_favorites){
				if ( !isset($favorites[$key]['site_favorites']) ) continue;
				$favorites[$key]['posts'] = $favorites[$key]['site_favorites'];
				unset($favorites[$key]['site_favorites']);
				if ( isset($favorites[$key]['total']) ) unset($favorites[$key]['total']);
			}
		}
		if ( Helpers::keyExists('site_id', $favorites) ) return $favorites;
		$new_favorites = array(
			array(
				'site_id' => 1,
				'posts' => $favorites
			)
		);
		return $new_favorites;
	}

	/**
	* Check for Groups array in user's favorites
	* Add all favorites to the default group if it doesn't exist
	* Compatibility for < 2.2
	* @since 2.2
	*/
	private function favoritesWithGroups($favorites)
	{
		if ( Helpers::groupsExist($favorites[0]) ) return $favorites;
		$data = [
			'group_id' => 1,
			'site_id' => $favorites[0]['site_id'],
			'group_name' => __('Default List', 'favorites'),
			'posts' => $favorites[0]['posts']
		];
		$favorites[0]['groups'] = array(
			$data
		);
		return $favorites;
	}

	/**
	* Get Logged In User Favorites
	*/
	private function getLoggedInFavorites($user_id = null, $site_id = null, $group_id = null)
	{
		$user_id_post = ( isset($_POST['user_id']) ) ? intval($_POST['user_id']) : get_current_user_id();
		$user_id = ( !is_null($user_id) ) ? $user_id : $user_id_post;
		$favorites = get_user_meta($user_id, 'simplefavorites');
		if ( empty($favorites) ) return array(array('site_id'=> 1, 'posts' => array(), 'groups' => array() ));
		
		$favorites = $this->favoritesWithSiteID($favorites[0]);
		$favorites = $this->favoritesWithGroups($favorites);

		if ( !is_null($site_id) && is_null($group_id) ) $favorites = Helpers::pluckSiteFavorites($site_id, $favorites);
		if ( !is_null($group_id) ) $favorites = Helpers::pluckGroupFavorites($group_id, $site_id, $favorites);

		return $favorites;
	}

	/**
	* Get Session Favorites
	*/
	private function getSessionFavorites($site_id = null, $group_id = null)
	{
		if ( !isset($_SESSION['simplefavorites']) ) $_SESSION['simplefavorites'] = array();
		$favorites = $_SESSION['simplefavorites'];
		$favorites = $this->favoritesWithSiteID($favorites);
		$favorites = $this->favoritesWithGroups($favorites);
		if ( !is_null($site_id) && is_null($group_id) ) $favorites = Helpers::pluckSiteFavorites($site_id, $favorites);
		if ( !is_null($group_id) ) $favorites = Helpers::pluckGroupFavorites($group_id, $site_id, $favorites);
		return $favorites;
	}

	/**
	* Get Cookie Favorites
	*/
	private function getCookieFavorites($site_id = null, $group_id = null)
	{
		if ( !isset($_COOKIE['simplefavorites']) ) $_COOKIE['simplefavorites'] = json_encode(array());
		$favorites = json_decode(stripslashes($_COOKIE['simplefavorites']), true);
		$favorites = $this->favoritesWithSiteID($favorites);
		$favorites = $this->favoritesWithGroups($favorites);
		if ( isset($_POST['user_consent_accepted']) && $_POST['user_consent_accepted'] == 'true' ) $favorites[0]['consent_provided'] = time();
		if ( !is_null($site_id) && is_null($group_id) ) $favorites = Helpers::pluckSiteFavorites($site_id, $favorites);
		if ( !is_null($group_id) ) $favorites = Helpers::pluckGroupFavorites($group_id, $site_id, $favorites);
		return $favorites;
	}

	/**
	* Has the user favorited a specified post?
	* @param int $post_id
	* @param int $site_id
	* @param int $user_id
	* @param int $group_id
	*/
	public function isFavorite($post_id, $site_id = 1, $user_id = null, $group_id = null)
	{
		$favorites = $this->getFavorites($user_id, $site_id, $group_id);
		if ( in_array($post_id, $favorites) ) return true;
		return false;
	}

	/**
	* Does the user count in total favorites?
	* @return boolean
	*/
	public function countsInTotal()
	{
		if ( is_user_logged_in() ) return true;
		return $this->settings_repo->anonymous('save');
	}

	/**
	* Format an array of favorites
	* @param $post_id - int, post to add to array (for session/cookie favorites)
	* @param $site_id - int, site id for post_id
	*/
	public function formattedFavorites($post_id = null, $site_id = null, $status = null)
	{
		$favorites = $this->getAllFavorites();
		$formatter = new FavoritesArrayFormatter;
		return $formatter->format($favorites, $post_id, $site_id, $status);
	}

	/**
	* Has the user consented to cookies (if applicable)
	*/
	public function consentedToCookies()
	{
		if ( $this->settings_repo->saveType() !== 'cookie' ) return true;
		if ( isset($_POST['user_consent_accepted']) && $_POST['user_consent_accepted'] == 'true' ) return true;
		if ( !$this->settings_repo->consent('require') ) return true;
		if ( isset($_COOKIE['simplefavorites']) ){
			$cookie = json_decode(stripslashes($_COOKIE['simplefavorites']), true);
			if ( isset($cookie[0]['consent_provided']) ) return true;
			if ( isset($cookie[0]['consent_denied']) ) return false;
		}
		return false;
	}

	/**
	* Has the user denied consent to cookies explicitly
	*/
	public function deniedCookies()
	{
		if ( $this->settings_repo->saveType() !== 'cookie' ) return false;
		if ( !$this->settings_repo->consent('require') ) return false;
		if ( isset($_COOKIE['simplefavorites']) ){
			$cookie = json_decode(stripslashes($_COOKIE['simplefavorites']), true);
			if ( isset($cookie[0]['consent_denied']) ) return true;
		}
		return false;
	}
}