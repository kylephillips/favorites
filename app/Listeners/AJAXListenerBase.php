<?php
namespace Favorites\Listeners;

use Favorites\Config\SettingsRepository;
use Favorites\Entities\User\UserRepository;

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
	* Settings Repo
	*/
	protected $settings_repo;

	/**
	* User Repo
	*/
	protected $user_repo;

	public function __construct($check_nonce = true)
	{
		$this->settings_repo = new SettingsRepository;
		$this->user_repo = new UserRepository;
		if ( $check_nonce ) $this->validateNonce();
		$this->checkLogIn();
		$this->checkConsent();
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
		if ( isset($_POST['logged_in']) && intval($_POST['logged_in']) == 1 ) return true;
		if ( $this->settings_repo->anonymous('display') ) return true;
		if ( $this->settings_repo->requireLogin() ) return $this->response(array('status' => 'unauthenticated'));
		if ( $this->settings_repo->redirectAnonymous() ) return $this->response(array('status' => 'unauthenticated'));
	}

	/**
	* Check if consent is required and received
	*/
	protected function checkConsent()
	{
		if ( $this->user_repo->consentedToCookies() ) return;
		return $this->response([
			'status' => 'consent_required', 
			'message' => $this->settings_repo->consent('modal'),
			'accept_text' => $this->settings_repo->consent('consent_button_text'),
			'deny_text' => $this->settings_repo->consent('deny_button_text'),
			'post_data' => $_POST
		]);
	}

	/**
	* Send a response
	*/
	protected function response($response)
	{
		return wp_send_json($response);
	}
}