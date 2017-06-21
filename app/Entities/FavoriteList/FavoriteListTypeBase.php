<?php
namespace Favorites\Entities\FavoriteList;

use Favorites\Entities\User\UserFavorites;
use Favorites\Config\SettingsRepository;
use Favorites\Entities\FavoriteList\FavoriteListingPresenter;
use Favorites\Entities\PostType\PostTypeRepository;

/**
* Base class for favorite lists
*/
abstract class FavoriteListTypeBase
{
	/**
	* List options
	* @var object
	*/
	protected $list_options;

	/**
	* User favorites object
	*/
	protected $user_favorites;

	/**
	* Settings Repo
	*/
	protected $settings_repo;

	/**
	* Post Type Repo
	*/
	protected $post_type_repo;

	/**
	* User's Favorites
	*/
	protected $favorites;

	/**
	* Listing Presenter
	*/
	protected $listing_presenter;

	public function __construct($list_options)
	{
		$this->settings_repo = new SettingsRepository;
		$this->post_type_repo = new PostTypeRepository;
		$this->user_favorites = new UserFavorites;
		$this->listing_presenter = new FavoriteListingPresenter;
		$this->list_options = $list_options;
		$this->setApiOptions();
		$this->setFavorites();
		$this->setNoFavoritesText();
		$this->setPostTypes();
	}

	/**
	* Set the API options (defined in shortcode and api functions)
	*/
	protected function setApiOptions()
	{
		$this->list_options->site_id = ( is_null($this->list_options->site_id) || $this->list_options->site_id == '' ) 
			? get_current_blog_id() : $this->list_options->site_id;
		$this->list_options->user_id = ( is_null($this->list_options->user_id) || $this->list_options->user_id == '' ) 
			? null : $this->list_options->user_id;
		$this->list_options->filters = ( is_null($this->list_options->filters) || $this->list_options->filters == '' ) 
			? null : $this->list_options->filters;
	}

	/**
	* Set the favorites
	*/
	protected function setFavorites()
	{
		$favorites = $this->user_favorites->getFavoritesArray($this->list_options->user_id, $this->list_options->site_id, $this->list_options->filters);
		$this->favorites = ( isset($favorites[0]['site_id']) ) ? $favorites[0]['posts'] : $favorites;
	}

	/**
	* Set the no favorites text
	*/
	protected function setNoFavoritesText()
	{
		if ( $this->list_options->no_favorites == '' ) 
			$this->list_options->no_favorites = $this->settings_repo->noFavoritesText();
	}

	/**
	* Set the post types
	*/
	protected function setPostTypes()
	{
		$this->list_options->post_types = implode(',', $this->post_type_repo->getAllPostTypes('names', true));
		if ( isset($this->list_options->filters['post_type']) )	
			$this->list_options->post_types = implode(',', $this->list_options->filters['post_type']);
	}

	/**
	* Generate the list opening
	*/
	protected function listOpening()
	{
		$css = apply_filters('favorites/list/wrapper/css', $this->list_options->wrapper_css, $this->list_options);
		$out = '<' . $this->list_options->wrapper_type;
		$out .= ' class="favorites-list ' . $css . '" data-userid="' . $this->list_options->user_id . '" data-siteid="' . $this->list_options->site_id . '" ';
		$out .= ( $this->list_options->include_button ) ? 'data-includebuttons="true"' : 'data-includebuttons="false"';
		$out .= ( $this->list_options->include_links ) ? ' data-includelinks="true"' : ' data-includelinks="false"';
		$out .= ( $this->list_options->include_thumbnails ) ? ' data-includethumbnails="true"' : ' data-includethumbnails="false"';
		$out .= ( $this->list_options->include_excerpt ) ? ' data-includeexcerpts="true"' : ' data-includeexcerpts="false"';
		$out .= ' data-thumbnailsize="' . $this->list_options->thumbnail_size . '"';
		$out .= ' data-nofavoritestext="' . $this->list_options->no_favorites . '"';
		$out .= ' data-posttypes="' . $this->list_options->post_types . '"';
		$out .= '>';
		return $out;
	}

	/**
	* Generate the list closing
	*/
	protected function listClosing()
	{
		return '</' . $this->list_options->wrapper_type . '>';
	}

	/**
	* Generates the no favorites item
	*/
	protected function noFavorites()
	{
		if ( !empty($this->favorites) ) return;
		$out = $this->listOpening();
		$out .= '<' . $this->list_options->wrapper_type;
		$out .= ' data-postid="0" data-nofavorites class="no-favorites">' . $this->list_options->no_favorites;
		$out .= '</' . $this->list_options->wrapper_type . '>';
		$out .= $this->listClosing();
		return $out;
	}

	/**
	* Get the markup for a full list
	*/
	public function getListMarkup()
	{
		if ( is_multisite() ) switch_to_blog($this->list_options->site_id);
		if ( empty($this->favorites) ) return $this->noFavorites();

		$out = $this->listOpening();	
		foreach ( $this->favorites as $key => $favorite ){
			$out .= $this->listing($favorite);
		}
		$out .= $this->listClosing();
		if ( is_multisite() ) restore_current_blog();
		return $out;
	}
}