<?php
namespace Favorites\Entities\FavoriteList;

use Favorites\Config\SettingsRepository;
use Favorites\Entities\FavoriteList\FavoriteListDefault;
use Favorites\Entities\FavoriteList\FavoriteListCustom;

abstract class FavoriteListBase
{
	/**
	* List Options
	* @var object
	*/
	protected $list_options;

	/**
	* Settings Repository
	*/
	protected $settings_repo;

	public function __construct($list_options)
	{
		$this->list_options = $list_options;
		$this->settings_repo = new SettingsRepository;
		$this->setOptions();
	}

	/**
	* Set the list default options
	*/
	protected function setOptions()
	{
		$options = $this->list_options;
		$this->list_options = new \stdClass;
		$this->list_options->user_id = ( isset($options['user_id']) ) ? $options['user_id'] : null;
		if ( ($this->list_options->user_id && isset($options['include_button'])) && ( get_current_user_id() !== $this->list_options->user_id ) ) unset($options['include_button']);
		$this->list_options->site_id = ( isset($options['site_id']) ) ? $options['site_id'] : null;
		$this->list_options->filters = ( isset($options['filters']) ) ? $options['filters'] : null;
		$this->list_options->include_button = ( isset($options['include_button']) ) ? $options['include_button'] : false;
		$this->list_options->include_thumbnails = ( isset($options['include_thumbnails']) ) ? $options['include_thumbnails'] : false;
		$this->list_options->thumbnail_size = ( isset($options['thumbnail_size']) ) ? $options['thumbnail_size'] : 'thumbnail';
		$this->list_options->include_excerpt = ( isset($options['include_excerpt']) ) ? $options['include_excerpt'] : false;
		$this->list_options->include_links = ( isset($options['include_links']) ) ? $options['include_links'] : false;
		$this->list_options->customized = $this->settings_repo->listCustomization('customize');
		$this->list_options->wrapper_type = 'ul';
		$this->list_options->wrapper_css = '';
		$this->list_options->listing_type = 'li';
		$this->list_options->listing_css = '';
		$this->list_options->no_favorites = ( isset($options['no_favorites']) ) ? $options['no_favorites'] : '';
		$this->setCustomOptions();
		if ( !property_exists($this->list_options, 'customize_markup') ) $this->list_options->customize_markup = false;
		if ( !property_exists($this->list_options, 'custom_markup_html') ) $this->list_options->custom_markup_html = false;
	}

	/**
	* Set the list customized options
	*/
	protected function setCustomOptions()
	{
		$options = $this->settings_repo->listCustomization('all');
		if ( !$options ) return;
		foreach ( $options as $option => $val ){
			if ( $option == 'customize' ) continue;
			if ( $val == '' ) continue;
			$this->list_options->$option = ( $val == 'true' ) ? true : sanitize_text_field($val);
			if ( $option == 'custom_markup_html' ) $this->list_options->custom_markup_html = $val;
		}
	}
}