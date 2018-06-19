<?php 
namespace Favorites\Entities\Favorite;

use Favorites\Entities\User\UserRepository;
use Favorites\Helpers;

/**
* Sync a single favorite to a given save type
*/
class SyncSingleFavorite 
{
	/**
	* The Post ID
	*/
	private $post_id;

	/**
	* The Site ID
	*/
	private $site_id;

	/**
	* The Group ID
	*/
	private $group_id;

	/**
	* User Repository
	*/
	private $user;

	public function __construct($post_id, $site_id, $group_id = 1)
	{
		$this->user = new UserRepository;
		$this->post_id = $post_id;
		$this->site_id = $site_id;
		$this->group_id = $group_id;
	}

	/**
	* Sync a Session Favorite
	*/
	public function session()
	{
		if ( $this->user->isFavorite($this->post_id, $this->site_id) ) return $_SESSION['simplefavorites'] = $this->removeFavorite();
		return $_SESSION['simplefavorites'] = $this->addFavorite();
	}

	/**
	* Sync a Cookie Favorite
	*/
	public function cookie()
	{
		if ( $this->user->isFavorite($this->post_id, $this->site_id) ){
			$favorites = $this->removeFavorite();
			setcookie( 'simplefavorites', json_encode( $favorites ), time() + apply_filters( 'simplefavorites_cookie_expiration_interval', 31556926 ), '/' );
			return;
		}
		$favorites = $this->addFavorite();
		setcookie( 'simplefavorites', json_encode( $favorites ), time() + apply_filters( 'simplefavorites_cookie_expiration_interval', 31556926 ), '/' );
		return;
	}

	/**
	* Update User Meta (logged in only)
	*/
	public function updateUserMeta($favorites)
	{
		if ( !isset($_POST['logged_in']) || intval($_POST['logged_in']) !== 1 ) return false;
		if ( !isset($_POST['user_id']) ) return false;
		update_user_meta( intval($_POST['user_id']), 'simplefavorites', $favorites );
	}

	/**
	* Remove a Favorite
	*/
	private function removeFavorite()
	{
		$favorites = $this->user->getAllFavorites($this->site_id);

		foreach($favorites as $key => $site_favorites){
			if ( $site_favorites['site_id'] !== $this->site_id ) continue;
			foreach($site_favorites['posts'] as $k => $fav){
				if ( $fav == $this->post_id ) unset($favorites[$key]['posts'][$k]);
			}
			if ( !Helpers::groupsExist($site_favorites) ) return;
			foreach( $site_favorites['groups'] as $group_key => $group){
				if ( $group['group_id'] !== $this->group_id ) continue;
				foreach ( $group['posts'] as $k => $g_post_id ){
					if ( $g_post_id == $this->post_id ) unset($favorites[$key]['groups'][$group_key]['posts'][$k]);
				}
			}
		}
		$this->updateUserMeta($favorites);
		return $favorites;
	}

	/**
	* Add a Favorite
	*/
	private function addFavorite()
	{
		$favorites = $this->user->getAllFavorites($this->site_id);
		if ( !Helpers::siteExists($this->site_id, $favorites) ){
			$favorites[] = array(
				'site_id' => $this->site_id,
				'posts' => array()
			);
		}
		// Loop through each site's favorites, continue if not the correct site id
		foreach($favorites as $key => $site_favorites){
			if ( $site_favorites['site_id'] !== $this->site_id ) continue;
			$favorites[$key]['posts'][] = $this->post_id;

			// Add the default group if it doesn't exist yet
			if ( !Helpers::groupsExist($site_favorites) ){
				$favorites[$key]['groups'] = array(
					array(
						'group_id' => 1,
						'site_id' => $this->site_id,
						'group_name' => __('Default List', 'favorites'),
						'posts' => array()
					)
				);
			}
			foreach( $favorites[$key]['groups'] as $group_key => $group){
				if ( $group['group_id'] == $this->group_id ) 
					$favorites[$key]['groups'][$group_key]['posts'][] = $this->post_id;
			}
		}
		$this->updateUserMeta($favorites);
		return $favorites;
	}
}