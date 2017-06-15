<?php
namespace Favorites\Entities\FavoriteList;

class FavoriteListCustom
{
	/**
	* List Options
	* @var object
	*/
	private $list_options;

	public function __construct($list_options)
	{
		$this->list_options = $list_options;
	}

	public function getMarkup()
	{
		return 'testing';
	}
}