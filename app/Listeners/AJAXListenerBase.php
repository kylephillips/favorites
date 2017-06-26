<?php
namespace Favorites\Listeners;

use Favorites\Config\SettingsRepository;

/**
* Base AJAX class
*/
abstract class AJAXListenerBase
{
	/**
	* Form Data
	*/
	protected $data;

	/**
	* AJAX Type
	*/
	protected $ajax_type;

	/**
	* Settings Repo
	*/
	protected $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->setAjaxType();
		$this->validateNonce();
		$this->checkLogIn();
	}

	/**
	* Set the AJAX type
	*/
	protected function setAjaxType()
	{
		$this->ajax_type = $this->settings_repo->ajaxType();
	}

	/**
	* Validate the Nonce
	*/
	protected function validateNonce()
	{
		if ( $this->ajax_type == 'wp_api' ) return;
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
		$error = ( $error ) ? $error : __('The nonce could not be verified.', 'favorites');
		return wp_send_json(array(
			'status' => 'error', 
			'message' => $error
		));
	}

	/**
	* Check if logged in
	*/
	protected function checkLogIn()
	{
		if ( is_user_logged_in() ) return true;
		if ( $this->settings_repo->anonymous('display') ) return true;
		if ( $this->settings_repo->requireLogin() ) return $this->response(array('status' => 'unauthenticated'));
	}

	/**
	* Send a response
	*/
	protected function response($response)
	{
		$response['ajax_type'] = $this->ajax_type;
		if ( $this->ajax_type == 'wp_api' ){
			$response['using_api'] = true;
			return new \WP_REST_Response($response);
		}
		$response['using_api'] = false;
		return wp_send_json($response);
	}
}