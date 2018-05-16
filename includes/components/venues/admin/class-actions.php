<?php
/**
 * Venues CRUD Actions
 *
 * @package   Ensemble\Components\Venues\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues\Admin;

use Ensemble\Components\Contests\Setup;
use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\View_Loader;

/**
 * Sets up logic for performing CRUD actions on contests.
 *
 * @since 1.0.0
 *
 * @see Loader
 * @see View_Loader
 */
class Actions implements Loader {

	use View_Loader;

	/**
	 * Registers hook callbacks for contest actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'init', array( $this, 'add_venue'    ) );
		add_action( 'init', array( $this, 'update_venue' ) );
		add_action( 'init', array( $this, 'delete_venue' ) );
	}

	/**
	 * Processes adding a new venue.
	 *
	 * @since 1.0.0
	 */
	public function add_venue() {
		$valid_request = $_REQUEST['ensemble-add-venue'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}
	}

	/**
	 * Processes updating a venue.
	 *
	 * @since 1.0.0
	 */
	public function update_venue() {
		$valid_request = $_REQUEST['ensemble-update-venue'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}
	}

	/**
	 * Processes deleting a venue.
	 *
	 * @since 1.0.0
	 */
	public function delete_venue() {
		$valid_request = $_REQUEST['ensemble-delete-venue'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}
	}

	/**
	 * Retrieves registered contest views.
	 *
	 * @since 1.0.0
	 *
	 * @return array Registered contest views.
	 */
	public function get_views() {
		return array( 'overview', 'add', 'edit', 'delete' );
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


}