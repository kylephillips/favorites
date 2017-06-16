<?php 
namespace Favorites\Entities\FavoriteList;

/**
* Get a full list of user favorites
* @param array of options from shortcode/api function
*/
class FavoriteList extends FavoriteListBase
{
	public function __construct($options)
	{
		parent::__construct($options);
	}

	/**
	* Get the list
	*/
	public function getList()
	{
		$list = ( !$this->list_options->customize_markup || !$this->list_options->custom_markup_html ) 
			? new FavoriteListTypeDefault($this->list_options)
			: new FavoriteListTypeCustom($this->list_options);
		return $list->getListMarkup();
	}

	/**
	* Get a single listing
	*/
	public function getListing($post_id)
	{
		$list = ( !$this->list_options->customize_markup || !$this->list_options->custom_markup_html ) 
			? new FavoriteListTypeDefault($this->list_options)
			: new FavoriteListTypeCustom($this->list_options);
		return $list->listing($post_id);
	}
}