<?php
namespace Favorites\Listeners;

use Favorites\Entities\User\UserRepository;

class CookieConsent
{
	/**
	* Consented?
	* @var bool
	*/
	private $consent;

	/**
	* User Repo
	*/
	private $user_repo;

	public function __construct()
	{
		$this->user_repo = new UserRepository;
		$this->setConsent();
		$this->respond();
	}

	private function setConsent()
	{
		$this->consent = ( isset($_POST['consent']) && $_POST['consent'] == 'true' ) ? true : false;
		if ( $this->consent ){
			$this->setApprove();
			return;
		}
		$this->setDeny();
	}

	private function setApprove()
	{
		$cookie = [];
		if ( isset($_COOKIE['simplefavorites']) ) {
			$cookie = json_decode(stripslashes($_COOKIE['simplefavorites']), true);
		} else {
			$cookie = $this->user_repo->getAllFavorites();
		}
		$cookie[0]['consent_provided'] = time();
		setcookie( 'simplefavorites', json_encode( $cookie ), time() + apply_filters( 'simplefavorites_cookie_expiration_interval', 31556926 ), '/' );
	}

	private function setDeny()
	{
		$cookie = [];
		$cookie[0]['consent_denied'] = time();
		setcookie( 'simplefavorites', json_encode( $cookie ), time() + apply_filters( 'simplefavorites_cookie_expiration_interval', 31556926 ), '/' );
	}

	private function respond()
	{
		wp_send_json(array(
			'status' => 'success',
			'consent' => $this->consent
		));
	}
}