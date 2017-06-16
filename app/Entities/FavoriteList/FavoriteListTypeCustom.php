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

	public function getMarkup()
	{
		$out = 'testing';
		if ( is_multisite() ) switch_to_blog($this->list_options->site_id);
		if ( is_multisite() ) restore_current_blog();
		return $out;
	}
}