<?php 
namespace Favorites\Entities\FavoriteList;

use Favorites\Config\SettingsRepository;
use Favorites\Entities\FavoriteList\FavoriteListDefault;
use Favorites\Entities\FavoriteList\FavoriteListCustom;

/**
* Builds the favorites list options
* @param array of options from shortcode/api function
*/
class FavoriteList
{
	/**
	* List Options
	* @var object
	*/
	private $list_options;

	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct($options)
	{
		$this->settings_repo = new SettingsRepository;
		$this->setOptions($options);
	}

	/**
	* Set the list default options
	*/
	private function setOptions($options)
	{
		$this->list_options = new \stdClass;
		$this->list_options->user_id = ( isset($options['user_id']) ) ? $options['user_id'] : null;
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
		if ( !$this->list_options->customized ) return;
		$this->setCustomOptions();
	}

	/**
	* Set the list customized options
	*/
	private function setCustomOptions()
	{
		$options = $this->settings_repo->listCustomization('all');
		foreach ( $options as $option => $val ){
			if ( $option == 'customize' ) continue;
			if ( $val == '' ) continue;
			$this->list_options->$option = ( $val == 'true' ) ? true : sanitize_text_field($val);
		}
		if ( !property_exists($this->list_options, 'customize_markup') ) $this->list_options->customize_markup = false;
	}

	/**
	* Output the list
	*/
	public function getList()
	{
		$list = ( !$this->list_options->customize_markup ) 
			? new FavoriteListDefault($this->list_options)
			: new FavoriteListCustom($this->list_options);
		return $list->getMarkup();
	}

}