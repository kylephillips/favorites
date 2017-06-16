<?php
namespace Favorites\Entities\FavoriteList;

use Favorites\Entities\Favorite\FavoriteButton;

/**
* Create a favorites list using the default markup
*/
class FavoriteListTypeDefault extends FavoriteListTypeBase
{
	public function __construct($list_options)
	{
		parent::__construct($list_options);
	}

	private function setTemplate()
	{
		$template = '';
		if ( $this->list_options->include_thumbnails ) $template .= '[post_thumbnail_' . $this->list_options->thumbnail_size . ']';
		$template .= "\n\n";
		if ( $this->list_options->include_links )  $template .= '<a href="[post_permalink]">';
		$template .= '[post_title]';
		if ( $this->list_options->include_links ) $template .= '</a>';
		$template .= "\n\n";
		if ( $this->list_options->include_excerpt ) $template .= "[post_excerpt]\n\n";
		if ( $this->list_options->include_button )$template .= "[favorites_button]";
		return $template;
	}

	/**
	* Generate a single listing
	* @param int favorite post id
	*/
	public function listing($favorite)
	{
		return $this->listing_presenter->present($this->list_options, $this->setTemplate(), $favorite);
	}
}