<?php

namespace SimpleFavorites\Listeners;

use SimpleFavorites\Entities\User\UserFavorites;

class FavoriteList extends AJAXListenerBase
{
	/**
	* Form Data
	* @var array
	*/
	protected $data;

	/**
	* List HTML
	*/
	private $list;

	public function __construct()
	{
		parent::__construct();
		$this->setData();
		$this->getList();
		wp_send_json(array('status' => 'success', 'list' => $this->list));
	}

	/**
	* Set the User ID & Site ID
	*/
	private function setData()
	{
		$this->data['user_id'] = ( isset($_POST['userid']) ) ? intval($_POST['userid']) : null;
		$this->data['site_id'] = ( isset($_POST['siteid']) ) ? intval($_POST['siteid']) : null;
		$this->data['includelinks'] = ( isset($_POST['includelinks']) && $_POST['includelinks'] == 'true' ) ? true : false;
		$this->data['includebuttons'] = ( isset($_POST['includebuttons']) && $_POST['includebuttons'] == 'true' ) ? true : false;
		$this->data['posttypes'] = ( isset($_POST['posttype']) ) ? explode(',', $_POST['posttype']) : array();
	}

	/**
	* Get the favorites list
	*/
	private function getList()
	{
		$this->list = get_user_favorites_list(
			$user_id = $this->data['user_id'], 
			$site_id = $this->data['site_id'], 
			$include_links = $this->data['includelinks'], 
			$filters = array('post_type'=> $this->data['posttypes']),
			$include_button = $this->data['includebuttons']
		);
	}
}