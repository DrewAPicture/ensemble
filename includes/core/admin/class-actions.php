<?php
/**
 * Core Actions
 *
 * @package   Ensemble\Core\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Admin;

use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\View_Loader;

/**
 * Sets up logic for performing core admin actions.
 *
 * @since 1.0.0
 *
 * @see Loader
 * @see View_Loader
 */
class Actions implements Loader {

	use View_Loader;

	/**
	 * Registers hook callbacks for core actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {

	}

	/**
	 * Retrieves views registered to core.
	 *
	 * @since 1.0.0
	 *
	 * @return array Views registered to core.
	 */
	public function get_views() {
		return array( 'overview' );
	}

	/**
	 * Retrieves the path to the view templates directory for core.
	 *
	 * @since 1.0.0
	 *
	 * @return string Path to the view templates directory.
	 */
	public function get_views_dir() {
		return __DIR__ . '/views/';
	}

}