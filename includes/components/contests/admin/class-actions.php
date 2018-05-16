<?php
/**
 * Contests CRUD Actions
 *
 * @package   Ensemble\Components\Contests\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

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
		add_action( 'init', array( $this, 'add_contest' ) );
		add_action( 'init', array( $this, 'update_contest' ) );
		add_action( 'init', array( $this, 'delete_contest' ) );
	}

	/**
	 * Processes adding a new contest.
	 *
	 * @since 1.0.0
	 */
	public function add_contest() {

	}

	/**
	 * Processes updating an existing contest.
	 *
	 * @since 1.0.0
	 */
	public function update_contest() {

	}

	/**
	 * Processes deleting a contest.
	 *
	 * @since 1.0.0
	 */
	public function delete_contest() {

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