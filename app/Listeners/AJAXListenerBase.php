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
		$this->checkLogIn();
		$this->checkConsent();
	}

	/**
	* Send an Error Response
	* @param $error string
	*/
	protected function sendError($error = null)
	{
		$error = ( $error ) ? $error : __('There was an error processing the request.', 'favorites');
		return wp_send_json([
			'status' => 'error', 
			'message' => $error
		]);
	}

	/**
	* Check if logged in
	*/
	protected function checkLogIn()
	{
		if ( is_user_logged_in() ) return true;
		if ( $this->settings_repo->anonymous('display') ) return true;
		if ( $this->settings_repo->requireLogin() ) return $this->response(['status' => 'unauthenticated']);
		if ( $this->settings_repo->redirectAnonymous() ) return $this->response(['status' => 'unauthenticated']);
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