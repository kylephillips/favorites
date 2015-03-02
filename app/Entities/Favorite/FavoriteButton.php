<?php namespace SimpleFavorites\Entities\Favorite;

class FavoriteButton {

	/**
	* The Post ID
	*/
	private $post_id;


	public function __construct($post_id)
	{
		$this->post_id = $post_id;
	}

	/**
	* Diplay the Button
	*/
	public function display()
	{
		return 'Favorite Button for post ' . $this->post_id;
	}

}