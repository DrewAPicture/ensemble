<?php
/**
 * Defines multi-dimensional logic for taxonomy components
 *
 * @package   Ensemble\Core\Traits
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Traits;

/**
 * Core trait used for taxonomy component setup.
 *
 * @since 1.0.0
 */
trait Taxonomy_Component {

	/**
	 * Retrieves the taxonomy slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string Taxonomy slug.
	 */
	abstract public function get_taxonomy_slug();

	/**
	 * Registers hook callbacks for needed taxonomy functionality.
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomy_callbacks() {
		add_action( 'init',        array( $this, 'register_taxonomy' ) );
		add_filter( 'parent_file', array( $this, 'set_menu_highlight' ) );
	}

	/**
	 * Registers the taxonomy.
	 *
	 * @since 1.0.0
	 */
	abstract public function register_taxonomy();

	/**
	 * Ensures the Ensures the Ensemble > Classes menu is highlighted when viewing the Classes admin screen.
	 *
	 * @since 1.0.0
	 *
	 * @param string $parent_file Menu parent file/slug.
	 * @return string (Maybe) modified parent file.
	 */
	public function set_menu_highlight( $parent_file ) {
		$current_screen = get_current_screen();

		if ( isset( $current_screen->taxonomy ) && $this->get_taxonomy_slug() === $current_screen->taxonomy ) {
			$parent_file = 'ensemble-unit-admin';
		}

		return $parent_file;
	}

}