<?php
/**
 * Bootstraps Ensemble Components
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Components as Component;
use function Ensemble\{load};

/**
 * Sets up components.
 *
 * @since 1.0.0
 */
class Components implements Interfaces\Loader {

	/**
	 * Initializes the components.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		load( new Component\Contests\Setup );

		load( new Component\People\Directors\Setup );
		load( new Component\People\Judges\Setup );
		load( new Component\People\Staff\Setup );

		load( new Component\Venues\Setup );

		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	/**
	 * Registers taxonomies used in Ensemble core.
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomies() {
		// Competing Units taxonomy.
		register_taxonomy( 'ensemble_unit', array(), array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'rewrite'           => true,
			'show_in_rest'      => false,
			'labels'            => array(
				'name'                       => __( 'Units', 'ensemble' ),
				'singular_name'              => _x( 'Unit', 'taxonomy general name', 'ensemble' ),
				'search_items'               => __( 'Search Units', 'ensemble' ),
				'popular_items'              => __( 'Popular Units', 'ensemble' ),
				'all_items'                  => __( 'All Units', 'ensemble' ),
				'parent_item'                => __( 'Parent Unit', 'ensemble' ),
				'parent_item_colon'          => __( 'Parent Unit:', 'ensemble' ),
				'edit_item'                  => __( 'Edit Unit', 'ensemble' ),
				'update_item'                => __( 'Update Unit', 'ensemble' ),
				'view_item'                  => __( 'View Unit', 'ensemble' ),
				'add_new_item'               => __( 'New Unit', 'ensemble' ),
				'new_item_name'              => __( 'New Unit', 'ensemble' ),
				'separate_items_with_commas' => __( 'Separate Units with commas', 'ensemble' ),
				'add_or_remove_items'        => __( 'Add or remove Units', 'ensemble' ),
				'choose_from_most_used'      => __( 'Choose from the most used Units', 'ensemble' ),
				'not_found'                  => __( 'No Units found.', 'ensemble' ),
				'no_terms'                   => __( 'No Units', 'ensemble' ),
				'menu_name'                  => __( 'Units', 'ensemble' ),
				'items_list_navigation'      => __( 'Units list navigation', 'ensemble' ),
				'items_list'                 => __( 'Units list', 'ensemble' ),
				'most_used'                  => _x( 'Most Used', 'ensemble_unit', 'ensemble' ),
				'back_to_items'              => __( '&larr; Back to Units', 'ensemble' ),
			),
		) );
	}
}
