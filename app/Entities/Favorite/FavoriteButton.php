<?php
namespace Favorites\Entities\Favorite;

use Favorites\Entities\User\UserRepository;
use Favorites\Entities\Post\FavoriteCount;
use Favorites\Config\SettingsRepository;

class FavoriteButton
{
	/**
	* The Post ID
	*/
	private $post_id;

	/**
	* Site ID
	*/
	private $site_id;

	/**
	* Group ID
	*/
	private $group_id;

	/**
	* User Respository
	*/
	private $user;

	/**
	* Favorite Count Object
	*/
	private $count;

	/**
	* Button Options
	* @var array
	*/
	private $button_options;

	/**
	* Favorited?
	* @var bool
	*/
	private $favorited;

	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct($post_id, $site_id, $group_id = 1)
	{
		$this->user = new UserRepository;
		$this->settings_repo = new SettingsRepository;
		$this->count = new FavoriteCount;
		$this->post_id = $post_id;
		$this->site_id = $site_id;
		$this->group_id = $group_id;
	}

	/**
	* Diplay the Button
	* @param boolean loading - whether to include loading class
	* @return html
	*/
	public function display($loading = true)
	{
		// $cookie = json_decode(stripslashes($_COOKIE['simplefavorites']), true);
		// var_dump($cookie[0]);
		if ( !$this->settings_repo->cacheEnabled() ) $loading = false;
		if ( !$this->user->getsButton() ) return false;

		$this->button_options = $this->settings_repo->formattedButtonOptions();
		$this->favorited = ( $this->user->isFavorite($this->post_id, $this->site_id, null, $this->group_id) ) ? true : false;
		$count = $this->count->getCount($this->post_id, $this->site_id);
		$button_html_type = apply_filters('favorites/button/element_type', $this->settings_repo->getButtonHtmlType(), $this->post_id, $this->site_id);
		$html = $this->html();

		$out = '<' . $button_html_type . ' class="' . $this->cssClasses($loading) . '"';
		
		$out .= ' data-postid="' . $this->post_id . '" data-siteid="' . $this->site_id . '" data-groupid="' . $this->group_id . '" data-favoritecount="' . $count . '" style="' . $this->styleAttributes() . '">';

		if ( $this->settings_repo->includeLoadingIndicator() && $this->settings_repo->includeLoadingIndicatorPreload() && $loading){
			$out .= $this->settings_repo->loadingText();
			$spinner = ( $this->favorited ) ? $this->settings_repo->loadingImage('active') : $this->settings_repo->loadingImage();
			if ( $spinner ) $out .= $spinner;
		} else {
			$out .= $html;
			if ( $this->button_options['include_count'] ) $out .= $this->addCount();
		}
		$out .= '</' . $button_html_type . '>';
		return $out;
	}

	/**
	* Add CSS Classes
	*/
	private function cssClasses($loading)
	{
		$classes = 'simplefavorite-button';
		if ( $this->favorited ) $classes .= ' active';
		if ( $this->button_options['include_count'] ) $classes .= ' has-count';
		if ( $this->settings_repo->includeLoadingIndicator() && $this->settings_repo->includeLoadingIndicatorPreload() && $loading ) $classes .= ' loading';
		if ( is_array($this->button_options['button_type']) ) $classes .= ' preset';
		return apply_filters('favorites/button/css_classes', $classes, $this->post_id, $this->site_id);
	}

	/**
	* Add Style Attributes
	*/
	private function styleAttributes($icon = false)
	{
		if ( !$this->button_options['custom_colors'] ) return null;
		$html = '';
		$default_colors = $this->button_options['default'];
		$active_colors = $this->button_options['active'];
		if ( $icon ){
			if ( $this->favorited ){
				if ( $active_colors['icon_active'] ) $html .= 'color:' . $active_colors['icon_active'] . ';';
			} else {
				if ( $default_colors['icon_default'] ) $html .= 'color:' . $default_colors['icon_default'] . ';';
			}
			return $html;
		}

		if ( !$this->button_options['box_shadow'] ) $html .= 'box-shadow:none;-webkit-box-shadow:none;-moz-box-shadow:none;';

		if ( $this->favorited ) {
			if ( $active_colors['background_active'] ) $html .= 'background-color:' . $active_colors['background_active'] . ';';
			if ( $active_colors['border_active'] ) $html .= 'border-color:' . $active_colors['border_active'] . ';';
			if ( $active_colors['text_active'] ) $html .= 'color:' . $active_colors['text_active'] . ';';
			return $html;
		}
		if ( $default_colors['background_default'] ) $html .= 'background-color:' . $default_colors['background_default'] . ';';
		if ( $default_colors['border_default'] ) $html .= 'border-color:' . $default_colors['border_default'] . ';';
		if ( $default_colors['text_default'] ) $html .= 'color:' . $default_colors['text_default'] . ';';
		return $html;
	}

	/**
	* Build the HTML for the button
	*/
	private function html()
	{
		$html = '';
		if ( is_array($this->button_options['button_type']) ) {
			$html .= '<i class="' . $this->button_options['button_type']['icon_class'] . '" style="' . $this->styleAttributes(true) . '"></i>';
			$html .= ( $this->favorited ) ? $this->button_options['button_type']['state_active'] : $this->button_options['button_type']['state_default'];
		} else {
			$html .= ( $this->favorited ) ? html_entity_decode($this->settings_repo->buttonTextFavorited()) : html_entity_decode($this->settings_repo->buttonText());
		}
		return apply_filters('favorites/button/html', $html, $this->post_id, $this->favorited, $this->site_id);
	}

	/**
	* Add the favorite count
	*/
	private function addCount()
	{
		$default_colors = $this->button_options['default'];
		$active_colors = $this->button_options['active'];

		$html = '<span class="simplefavorite-button-count" style="';
		if ( $this->favorited ){
			if ( $active_colors['count_active'] ) $html .= 'color:' . $active_colors['count_active'] . ';';
		} else {
			if ( $default_colors['count_default'] ) $html .= 'color:' . $default_colors['count_default'] . ';';
		}
		$html .= '">' . $this->count->getCount($this->post_id, $this->site_id) . '</span>';
		return apply_filters('favorites/button/count', $html);
	}
}