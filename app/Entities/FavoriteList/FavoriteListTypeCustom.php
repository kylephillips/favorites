<?php
namespace Favorites\Entities\FavoriteList;

use Favorites\Entities\User\UserFavorites;
use Favorites\Config\SettingsRepository;

/**
* Generate the list for custom markup list
*/
class FavoriteListTypeCustom extends FavoriteListTypeBase
{

	public function __construct($list_options)
	{
		parent::__construct($list_options);
	}

	public function listing($favorite)
	{
		return $this->listing_presenter->present($this->list_options, $this->list_options->custom_markup_html, $favorite);
	}
}