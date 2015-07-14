<?php 

namespace SimpleFavorites\Forms;

use SimpleFavorites\Entities\Favorite\Favorite;

class ClearFavoritesHandler 
{

	/**
	* Form Data
	*/
	private $data;

	public function __construct()
	{
		$this->setFormData();
		$this->validateNonce();
		$this->clearFavorites();
	}

	/**
	* Set Form Data
	*/
	private function setFormData()
	{
		$this->data['nonce'] = sanitize_text_field($_POST['nonce']);
		$this->data['siteid'] = intval(sanitize_text_field($_POST['siteid']));
	}

	/**
	* Validate the Nonce
	*/
	private function validateNonce()
	{
		if ( !wp_verify_nonce( $this->data['nonce'], 'simple_favorites_nonce' ) ) return $this->sendError();
	}

	/**
	* Update the Favorite
	*/
	private function clearFavorites()
	{
		$favorite = new Favorite;
		$favorite->update($this->data['postid'], $this->data['status'], $this->data['siteid']);
		$this->afterClearAction();
	}

	/**
	* After Update Action
	* Provides hook for performing actions after a favorite
	*/
	private function afterClearAction()
	{
		$user = ( is_user_logged_in() ) ? get_current_user_id() : null;
		do_action('favorites_after_favorite', $this->data['postid'], $this->data['status'], $this->data['siteid'], $user);
	}

	/**
	* Send an Error Response
	*/
	private function sendError()
	{
		return wp_send_json(array(
			'status'=>'error', 
			'message'=>__('Invalid form field', 'simplefavorites')
		));
	}

}