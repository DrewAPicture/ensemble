<?php
/**
 * People Actions
 *
 * @package   Ensemble\Components\People\Directors\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Directors\Admin;

use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\View_Loader;

/**
 * Sets up logic for performing director-related actions.
 *
 * @since 1.0.0
 *
 * @see Loader
 * @see View_Loader
 */
class Actions implements Loader {

	use View_Loader;

	/**
	 * Registers hook callbacks for director actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_filter( 'ensemble_people-get_tabs',                             array( $this, 'register_tab'        ),    11 );
		add_action( "ensemble_people-{$this->get_tab_slug()}_tab_contents", array( $this, 'output_tab_contents' ), 10, 2 );
	}

	/**
	 * Retrieves registered director views.
	 *
	 * @since 1.0.0
	 *
	 * @return array Registered director views.
	 */
	public function get_views() {
		return array( 'overview', 'tab' );
	}

	/**
	 * Retrieves the path/to/the/views.
	 *
	 * @since 1.0.0
	 *
	 * @return string Path to the view templates directory.
	 */
	public function get_views_dir() {
		return __DIR__ . '/views/';
	}

	/**
	 * Retrieves the tab slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string Tab slug.
	 */
	public function get_tab_slug() {
		return 'directors';
	}

	public function register_tab( $tabs ) {
		$tabs[ $this->get_tab_slug() ] = __( 'Unit Directors', 'ensemble' );

		return $tabs;
	}

	public function output_tab_contents() {
		$this->load_view( 'tab' );
	}

}