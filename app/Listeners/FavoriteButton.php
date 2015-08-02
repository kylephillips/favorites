<?php 

namespace SimpleFavorites\Listeners;

use SimpleFavorites\Entities\Favorite\Favorite;
use SimpleFavorites\Entities\User\UserRepository;

class FavoriteButton extends AJAXListenerBase
{
	/**
	* User Repository
	* @var SimpleFavorites\Entities\User\UserRepository
	*/
	private $user_repo;

	public function __construct()
	{
		parent::__construct();
		$this->user_repo = new UserRepository;
		$this->setFormData();
		$this->updateFavorite();
	}

	/**
	* Set Form Data
	*/
	private function setFormData()
	{
		$this->data['postid'] = intval(sanitize_text_field($_POST['postid']));
		$this->data['siteid'] = intval(sanitize_text_field($_POST['siteid']));
		$this->data['status'] = ( $_POST['status'] == 'active') ? 'active' : 'inactive';
	}

	/**
	* Update the Favorite
	*/
	private function updateFavorite()
	{
		$this->beforeUpdateAction();
		$favorite = new Favorite;
		$favorite->update($this->data['postid'], $this->data['status'], $this->data['siteid']);
		$this->afterUpdateAction();

		$this->response(array(
			'status' => 'success', 
			'favorite_data' => array('id' => $this->data['postid'], 'siteid' => $this->data['siteid'], 'status' => $this->data['status']),
			'favorites' => $this->user_repo->formattedFavorites($this->data['postid'], $this->data['siteid'], $this->data['status'])
		));
	}

	/**
	* Before Update Action
	* Provides hook for performing actions before a favorite
	*/
	private function beforeUpdateAction()
	{
		$user = ( is_user_logged_in() ) ? get_current_user_id() : null;
		do_action('favorites_before_favorite', $this->data['postid'], $this->data['status'], $this->data['siteid'], $user);
	}

	/**
	* After Update Action
	* Provides hook for performing actions after a favorite
	*/
	private function afterUpdateAction()
	{
		$user = ( is_user_logged_in() ) ? get_current_user_id() : null;
		do_action('favorites_after_favorite', $this->data['postid'], $this->data['status'], $this->data['siteid'], $user);
	}

}