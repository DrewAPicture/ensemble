<?php
/**
 * Sets up the Units component
 *
 * @package   Ensemble\Components\Units
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Units;

use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\Taxonomy_Component;
use function Ensemble\{load};

/**
 * Implements Units component functionality in Ensemble core.
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
	 * Retrieves the taxonomy slug for Units.
	 *
	 * @since 1.0.0
	 *
	 * @return string Taxonomy slug.
	 */
	public function get_taxonomy_slug() {
		return 'ensemble_unit';
	}

	/**
	 * Registers taxonomies used in Ensemble core.
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomy() {
		// Competing Units taxonomy.
		register_taxonomy( $this->get_taxonomy_slug(), array(), array(
			'hierarchical'          => false,
			'public'                => true,
			'show_in_nav_menus'     => true,
			'show_ui'               => true,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => true,
			'show_in_rest'          => true,
			'rest_base'             => $this->get_taxonomy_slug(),
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'labels'                => array(
				'name'                       => __( 'Competing Units', 'ensemble' ),
				'singular_name'              => _x( 'Competing Unit', 'taxonomy general name', 'ensemble' ),
				'search_items'               => __( 'Search Competing Units', 'ensemble' ),
				'popular_items'              => __( 'Popular Competing Units', 'ensemble' ),
				'all_items'                  => __( 'All Competing Units', 'ensemble' ),
				'edit_item'                  => __( 'Edit Competing Unit', 'ensemble' ),
				'update_item'                => __( 'Update Unit', 'ensemble' ),
				'view_item'                  => __( 'View Competing Unit', 'ensemble' ),
				'add_new_item'               => __( 'Add a Competing Unit', 'ensemble' ),
				'new_item_name'              => __( 'New Competing Unit', 'ensemble' ),
				'separate_items_with_commas' => __( 'Separate Units with commas', 'ensemble' ),
				'add_or_remove_items'        => __( 'Add or remove Competing Units', 'ensemble' ),
				'choose_from_most_used'      => __( 'Choose Competing Units', 'ensemble' ),
				'not_found'                  => __( 'No Competing Units found.', 'ensemble' ),
				'no_terms'                   => __( 'No Competing Units', 'ensemble' ),
				'menu_name'                  => __( 'Units', 'ensemble' ),
				'items_list_navigation'      => __( 'Competing Units list navigation', 'ensemble' ),
				'items_list'                 => __( 'Competing Units list', 'ensemble' ),
				'most_used'                  => _x( 'Most Used', 'ensemble_unit', 'ensemble' ),
				'back_to_items'              => __( '&larr; Back to Competing Units', 'ensemble' ),
			),
		) );
	}

}
