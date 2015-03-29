<?php namespace SimpleFavorites\Forms;

use SimpleFavorites\Entities\User\UserRepository;

/**
* Return an HTML formatted list of user's favorites
* (For use in replacing cached content with AJAX injected content)
*/
class FavoritesListHandler {

	/**
	* Form Data
	*/
	private $data;

	/**
	* HTML formatted list
	*/
	private $list;

	public function __construct()
	{
		$this->setData();
		$this->setList();
		$this->response();
	}

	/**
	* Set the Form Data
	*/
	private function setData()
	{
		$this->data['user_id'] = ( isset($_POST['user_id']) ) ? intval($_POST['user_id']) : null;
		$this->data['links'] =  ( isset($_POST['links']) && $_POST['links'] == 'true' ) ? true : false;
	}

	/**
	* Set the formatted list
	*/
	private function setList()
	{
		$favorites = get_user_favorites($this->data['user_id']);
		$out = "";
		foreach($favorites as $favorite){
			$out .= '<li>';
			if ( $this->data['links'] ) $out .= '<a href="' . get_permalink($favorite) . '">';
			$out .= get_the_title($favorite);
			if ( $this->data['links'] ) $out .= '</a>';
			$out .= '</li>';
		}
		$this->list = $out;
	}

	/**
	* Send the response
	*/
	private function response()
	{
		return wp_send_json(array('status'=>'success', 'list'=>$this->list));
	}

}