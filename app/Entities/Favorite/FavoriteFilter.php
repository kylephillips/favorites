<?php 
namespace Favorites\Entities\Favorite;

/**
* Filters an array of favorites using provided array of filters
*/
class FavoriteFilter 
{
	/**
	* Favorites
	* @var array of post IDs
	*/
	private $favorites;

	/**
	* Filters
	* @var array
	*
	* Example: 
	*
	* array(
	* 	'post_type' => array('post', 'posttypetwo'),
	*	'terms' => array(
	*		'category' => array(
	*			'termone', 'termtwo', 'termthree'
	*		),
	*		'other-taxonomy' => array(
	*			'termone', 'termtwo', 'termthree'
	*		)
	*	)
	* );
	*
	*/
	private $filters;

	public function __construct($favorites, $filters)
	{
		$this->favorites = $favorites;
		$this->filters = $filters;
	}

	public function filter()
	{
		if ( isset($this->filters['post_type']) && is_array($this->filters['post_type']) ) $this->filterByPostType();
		if ( isset($this->filters['terms']) && is_array($this->filters['terms']) ) $this->filterByTerm();
		if ( isset($this->filters['status']) && is_array($this->filters['status']) ) $this->filterByStatus();
		return $this->favorites;
	}

	/**
	* Filter favorites by post type
	* @since 1.1.1
	* @param array $favorites
	*/
	private function filterByPostType()
	{
		foreach($this->favorites as $key => $favorite){
			$post_type = get_post_type($favorite);
			if ( !in_array($post_type, $this->filters['post_type']) ) unset($this->favorites[$key]);
		}
	}

	/**
	* Filter favorites by status
	* @since 2.1.4
	*/
	private function filterByStatus()
	{
		foreach($this->favorites as $key => $favorite){
			$status = get_post_status($favorite);
			if ( !in_array($status, $this->filters['status']) ) unset($this->favorites[$key]);
		}
	}

	/**
	* Filter favorites by terms
	* @since 1.1.1
	* @param array $favorites
	*/
	private function filterByTerm()
	{
		$taxonomies = $this->filters['terms'];
		$favorites = $this->favorites;
		
		foreach ( $favorites as $key => $favorite ) :

			$all_terms = [];
			$all_filters = [];

			foreach ( $taxonomies as $taxonomy => $terms ){
				if ( !isset($terms) || !is_array($terms) ) continue;
				$post_terms = wp_get_post_terms($favorite, $taxonomy, array("fields" => "slugs"));
				if ( !empty($post_terms) ) $all_terms = array_merge($all_terms, $post_terms);
				$all_filters = array_merge($all_filters, $terms);
			}

			$dif = array_diff($all_filters, $all_terms);
			if ( !empty($dif) ) unset($favorites[$key]);		

		endforeach;

		$this->favorites = $favorites;
	}
}