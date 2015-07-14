<?php

namespace SimpleFavorites\Listeners;

/**
* Base AJAX class
*/
abstract class AJAXListenerBase
{

	/**
	* Form Data
	*/
	protected $data;

	public function __construct()
	{
		$this->validateNonce();
	}

	/**
	* Validate the Nonce
	*/
	protected function validateNonce()
	{
		if ( !isset($_POST['nonce']) ) return $this->sendError();
		$nonce = sanitize_text_field($_POST['nonce']);
		if ( !wp_verify_nonce( $nonce, 'simple_favorites_nonce' ) ) return $this->sendError();
	}

	/**
	* Send an Error Response
	* @param $error string
	*/
	protected function sendError($error = null)
	{
		$error = ( $error ) ? $error : __('Invalid form field', 'simplefavorites');
		return wp_send_json(array(
			'status' => 'error', 
			'message' => $error
		));
	}

	/**
	* Send a response
	*/
	protected function response($response)
	{
		return wp_send_json($response);
	}
}