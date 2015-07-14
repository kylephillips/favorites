<?php 

namespace SimpleFavorites\Listeners;

use SimpleFavorites\Entities\Favorite\Favorite;

class FavoriteButton extends AJAXListenerBase
{
	/**
	* Form Data
	*/
	private $data;

	public function __construct()
	{
		parent::__construct();
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
		$favorite = new Favorite;
		$favorite->update($this->data['postid'], $this->data['status'], $this->data['siteid']);
		$this->afterUpdateAction();
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