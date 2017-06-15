<?php
namespace Favorites\Entities\FavoriteList;

use Favorites\Entities\User\UserFavorites;
use Favorites\Config\SettingsRepository;
use Favorites\Entities\Favorite\FavoriteButton;

/**
* Create a favorites list using the default markup
*/
class FavoriteListDefault
{
	/**
	* List options
	* @var object
	*/
	private $list_options;

	/**
	* User favorites object
	*/
	private $user_favorites;

	/**
	* Settings Repo
	*/
	private $settings_repo;

	public function __construct($list_options)
	{
		$this->settings_repo = new SettingsRepository;
		$this->user_favorites = new UserFavorites;
		$this->list_options = $list_options;
	}

	/**
	* Generate the default markup
	*/
	public function getMarkup()
	{
		$site_id = ( is_null($this->list_options->site_id) || $this->list_options->site_id == '' ) 
			? get_current_blog_id() : $this->list_options->site_id;
		$user_id = ( is_null($this->list_options->user_id) || $this->list_options->user_id == '' ) 
			? null : $this->list_options->user_id;
		$filters = ( is_null($this->list_options->filters) || $this->list_options->filters == '' ) 
			? null : $this->list_options->filters;
		
		$favorites = $this->user_favorites->getFavoritesArray($user_id, $site_id, $filters);
		$no_favorites = $this->settings_repo->noFavoritesText();
		$favorites = ( isset($favorites[0]['site_id']) ) ? $favorites[0]['posts'] : $favorites;

		// // Post Type filters for data attr
		$post_types = '';
		if ( isset($this->list_options->filters['post_type']) ){
			$post_types = implode(',', $this->list_options->filters['post_type']);
		}
		
		if ( is_multisite() ) switch_to_blog($this->list_options->site_id);
		
		$out = '<' . $this->list_options->wrapper_type;
		$out .= ' class="favorites-list ' . $this->list_options->wrapper_css . '" data-userid="' . $user_id . '" data-links="true" data-siteid="' . $site_id . '" ';
		$out .= ( $this->list_options->include_button ) ? 'data-includebuttons="true"' : 'data-includebuttons="false"';
		$out .= ( $this->list_options->include_links ) ? ' data-includelinks="true"' : ' data-includelinks="false"';
		$out .= ( $this->list_options->include_thumbnails ) ? ' data-includethumbnails="true"' : ' data-includethumbnails="false"';
		$out .= ( $this->list_options->include_excerpt ) ? ' data-includeexcerpts="true"' : ' data-includeexcerpts="false"';
		$out .= ' data-thumbnailsize="' . $this->list_options->thumbnail_size . '"';
		$out .= ' data-nofavoritestext="' . $no_favorites . '"';
		$out .= ' data-posttype="' . $post_types . '"';
		$out .= '>';

		if ( empty($favorites) ) $out .= '<li data-postid="0" data-nofavorites>' . $no_favorites . '</li>';
		if ( !empty($favorites) ) :
			foreach ( $favorites as $key => $favorite ){
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
					$button = new FavoriteButton($favorite, $site_id);
					$out .= '<p>' . $button->display(false) . '</p>';
				}
				$out .= '</' . $this->list_options->listing_type . '>';
			}
		endif;
		$out .= '</' . $this->list_options->wrapper_type . '>';
		if ( is_multisite() ) restore_current_blog();
		return $out;
	}
}