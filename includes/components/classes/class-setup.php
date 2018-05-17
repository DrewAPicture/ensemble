<?php
/**
 * Sets up the Classes component
 *
 * @package   Ensemble\Components\Classes
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Classes;

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
	 * Retrieves the taxonomy slug for Classes.
	 *
	 * @since 1.0.0
	 *
	 * @return string Taxonomy slug.
	 */
	public function get_taxonomy_slug() {
		return 'ensemble_class';
	}

	/**
	 * Registers taxonomies used in Ensemble core.
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomy() {
		// Competing Classes taxonomy.
		register_taxonomy( $this->get_taxonomy_slug(), array(), array(
			'hierarchical'          => false,
			'public'                => true,
			'show_in_nav_menus'     => true,
			'show_ui'               => true,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => true,
			'show_in_rest'          => true,
			'rest_base'             => 'ensemble_class',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'labels'                => array(
				'name'                       => __( 'Classifications', 'ensemble' ),
				'singular_name'              => _x( 'Classification', 'taxonomy general name', 'ensemble' ),
				'search_items'               => __( 'Search Classifications', 'ensemble' ),
				'popular_items'              => __( 'Popular Classifications', 'ensemble' ),
				'all_items'                  => __( 'All Classifications', 'ensemble' ),
				'parent_item'                => __( 'Parent Classification', 'ensemble' ),
				'parent_item_colon'          => __( 'Parent Classification:', 'ensemble' ),
				'edit_item'                  => __( 'Edit Classification', 'ensemble' ),
				'update_item'                => __( 'Update Classification', 'ensemble' ),
				'view_item'                  => __( 'View Classification', 'ensemble' ),
				'add_new_item'               => __( 'New Classification', 'ensemble' ),
				'new_item_name'              => __( 'New Classification', 'ensemble' ),
				'separate_items_with_commas' => __( 'Separate Classifications with commas', 'ensemble' ),
				'add_or_remove_items'        => __( 'Add or remove Classifications', 'ensemble' ),
				'choose_from_most_used'      => __( 'Choose from the most used Classifications', 'ensemble' ),
				'not_found'                  => __( 'No Classifications found.', 'ensemble' ),
				'no_terms'                   => __( 'No Classifications', 'ensemble' ),
				'menu_name'                  => __( 'Classifications', 'ensemble' ),
				'items_list_navigation'      => __( 'Classifications list navigation', 'ensemble' ),
				'items_list'                 => __( 'Classifications list', 'ensemble' ),
				'most_used'                  => _x( 'Most Used', 'ensemble_class', 'ensemble' ),
				'back_to_items'              => __( '&larr; Back to Classifications', 'ensemble' ),
			),
		) );
	}

	/**
	 * Ensures the Ensemble > Classes menu is highlighted when viewing the Classes admin screen.
	 *
	 * @since 1.0.0
	 *
	 * @param string $parent_file Menu parent file/slug.
	 * @return string (Maybe) modified parent file.
	 */
	public function set_menu_highlight( $parent_file ) {
		$current_screen = get_current_screen();

		if ( isset( $current_screen->taxonomy ) && 'ensemble_class' === $current_screen->taxonomy ) {
			$parent_file = 'ensemble-admin';
		}

		return $parent_file;
	}

}
