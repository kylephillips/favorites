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

	/**
	* Generate the default markup
	*/
	public function getMarkup()
	{		
		if ( is_multisite() ) switch_to_blog($this->list_options->site_id);
		$out = $this->listOpening();
		$out .= $this->noFavorites();
		
		if ( !empty($this->favorites) ) :
			foreach ( $this->favorites as $key => $favorite ){
				$out .= '<' . $this->list_options->listing_type;
				$out .= ' data-postid="' . $favorite . '" class="' . $this->list_options->listing_css . '">';
				if ( $this->list_options->include_thumbnails ) {
					$thumb_url = get_the_post_thumbnail_url($favorite, $this->list_options->thumbnail_size);
					if ( $thumb_url ){
						$img = '<img src="' . $thumb_url . '" alt="' . get_the_title($favorite) . '" class="favorites-list-thumbnail" />';
						$out .= apply_filters('favorites/list/thumbnail', $img, $favorite, $this->list_options->thumbnail_size);
					};
				}
				if ( $this->list_options->include_links ) $out .= '<p><a href="' . get_permalink($favorite) . '">';
				$out .= get_the_title($favorite);
				if ( $this->list_options->include_links ) $out .= '</a></p>';
				if ( $this->list_options->include_excerpt ) {
					$excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', $favorite));
					if ( $excerpt ) $out .= '<p class="excerpt">' . apply_filters('favorites/list/excerpt', $excerpt) . '</p>';
				}
				if ( $this->list_options->include_button ){
					$button = new FavoriteButton($favorite, $this->list_options->site_id);
					$out .= '<p>' . $button->display(false) . '</p>';
				}
				$out .= '</' . $this->list_options->listing_type . '>';
			}
		endif;
		$out .= $this->listClosing();
		if ( is_multisite() ) restore_current_blog();
		return $out;
	}
}