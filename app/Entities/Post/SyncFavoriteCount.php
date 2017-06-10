<?php 
namespace Favorites\Entities\Post;

use Favorites\Entities\Post\FavoriteCount;
use Favorites\Entities\User\UserRepository;

/**
* Updates the favorite count for a given post
*/
class SyncFavoriteCount 
{
	/**
	* Post ID
	* @var int
	*/
	private $post_id;

	/**
	* Site ID
	* @var int
	*/
	private $site_id;

	/**
	* Status
	* @var string
	*/
	private $status;

	/**
	* Favorite Count
	* @var object
	*/
	private $favorite_count;

	/**
	* User Repository
	*/
	private $user;

	public function __construct($post_id, $status, $site_id)
	{
		$this->post_id = $post_id;
		$this->status = $status;
		$this->site_id = $site_id;
		$this->favorite_count = new FavoriteCount;
		$this->user = new UserRepository;
	}

	/**
	* Sync the Post Total Favorites
	*/
	public function sync()
	{
		if ( !$this->user->countsInTotal() ) return false;
		$count = $this->favorite_count->getCount($this->post_id, $this->site_id);
		$count = ( $this->status == 'active' ) ? $count + 1 : max(0, $count - 1);
		return update_post_meta($this->post_id, 'simplefavorites_count', $count);
	}
}