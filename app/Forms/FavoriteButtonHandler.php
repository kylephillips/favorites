<?php namespace SimpleFavorites\Forms;

use SimpleFavorites\Entities\Favorite\Favorite;

class FavoriteButtonHandler {

	/**
	* Form Data
	*/
	private $data;

	public function __construct()
	{
		$this->setFormData();
		$this->validateNonce();
		$this->updateFavorite();
	}

	/**
	* Set Form Data
	*/
	private function setFormData()
	{
		$this->data['nonce'] = sanitize_text_field($_POST['nonce']);
		$this->data['postid'] = intval(sanitize_text_field($_POST['postid']));
		$this->data['siteid'] = intval(sanitize_text_field($_POST['siteid']));
		$this->data['status'] = ( $_POST['status'] == 'active') ? 'active' : 'inactive';
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
	private function updateFavorite()
	{
		$favorite = new Favorite;
		$favorite->update($this->data['postid'], $this->data['status'], $this->data['siteid']);
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