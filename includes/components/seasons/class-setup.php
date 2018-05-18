<?php
/**
 * Sets up the Seasons component
 *
 * @package   Ensemble\Components\Seasons
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Seasons;

use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\Taxonomy_Component;
use function Ensemble\{load};

/**
 * Implements Class component functionality in Ensemble core.
 *
 * @since 1.0.0
 *
 * @see Loader
 * @see Taxonomy_Component
 */
class Setup implements Loader {

	use Taxonomy_Component;

	/**
	 * Initializes the component.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		require_once __DIR__ . '/functions.php';

		if ( is_admin() ) {
			load( new Admin\Menu );
			load( new Admin\Actions );
		}

		$this->register_taxonomy_callbacks();
	}

	/**
	 * Retrieves the taxonomy slug for Seasons.
	 *
	 * @since 1.0.0
	 *
	 * @return string Taxonomy slug.
	 */
	public function get_taxonomy_slug() {
		return 'ensemble_season';
	}

	/**
	 * Registers taxonomies used in Ensemble core.
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomy() {
		// Competing Seasons taxonomy.
		register_taxonomy( $this->get_taxonomy_slug(), array(), array(
			'hierarchical'           => false,
			'public'                 => true,
			'show_in_nav_menus'      => true,
			'show_ui'                => true,
			'show_admin_column'      => false,
			'query_var'              => true,
			'rewrite'                => true,
			'show_in_rest'           => true,
			'rest_base'              => $this->get_taxonomy_slug(),
			'rest_controller_season' => 'WP_REST_Terms_Controller',
			'labels'            => array(
				'name'                       => __( 'Seasons', 'ensemble' ),
				'singular_name'              => _x( 'Season', 'taxonomy general name', 'ensemble' ),
				'search_items'               => __( 'Search Seasons', 'ensemble' ),
				'popular_items'              => __( 'Popular Seasons', 'ensemble' ),
				'all_items'                  => __( 'All Seasons', 'ensemble' ),
				'parent_item'                => __( 'Parent Season', 'ensemble' ),
				'parent_item_colon'          => __( 'Parent Season:', 'ensemble' ),
				'edit_item'                  => __( 'Edit Season', 'ensemble' ),
				'update_item'                => __( 'Update Season', 'ensemble' ),
				'view_item'                  => __( 'View Season', 'ensemble' ),
				'add_new_item'               => __( 'Add a New Season', 'ensemble' ),
				'new_item_name'              => __( 'New Season', 'ensemble' ),
				'separate_items_with_commas' => __( 'Separate Seasons with commas', 'ensemble' ),
				'add_or_remove_items'        => __( 'Add or remove Seasons', 'ensemble' ),
				'choose_from_most_used'      => __( 'Choose from the most used Seasons', 'ensemble' ),
				'not_found'                  => __( 'No Seasons found.', 'ensemble' ),
				'no_terms'                   => __( 'No Seasons', 'ensemble' ),
				'menu_name'                  => __( 'Seasons', 'ensemble' ),
				'items_list_navigation'      => __( 'Seasons list navigation', 'ensemble' ),
				'items_list'                 => __( 'Seasons list', 'ensemble' ),
				'most_used'                  => _x( 'Most Used', 'ensemble_season', 'ensemble' ),
				'back_to_items'              => __( '&larr; Back to Seasons', 'ensemble' ),			),
		) );
	}

}
