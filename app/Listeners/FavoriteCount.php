<?php
namespace Favorites\Listeners;

use Favorites\Entities\Post\FavoriteCount as FavoriteCounter;

/**
* Return the total number of favorites for a specified post
*/
class FavoriteCount extends AJAXListenerBase
{
	/**
	* Favorite Counter
	*/
	private $favorite_counter;

	public function __construct()
	{
		parent::__construct();
		$this->favorite_counter = new FavoriteCounter;
		$this->setData();
		$this->sendCount();
	}

	private function setData()
	{
		$this->data['postid'] = ( isset($_POST['postid']) ) ? intval($_POST['postid']) : null;
		$this->data['siteid'] = ( isset($_POST['siteid']) ) ? intval($_POST['siteid']) : null;
	}

	private function sendCount()
	{
		$this->response([
			'status' => 'success',
			'count' => $this->favorite_counter->getCount($this->data['postid'], $this->data['siteid'])
		]);
	}
}