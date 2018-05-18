<?php
/**
 * People Actions
 *
 * @package   Ensemble\Components\People\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Admin;

use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\View_Loader;

/**
 * Sets up logic for performing people-related actions.
 *
 * @since 1.0.0
 *
 * @see Loader
 * @see View_Loader
 */
class Actions implements Loader {

	use View_Loader;

	/**
	 * Registers hook callbacks for people actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {

	}

	/**
	 * Retrieves registered people views.
	 *
	 * @since 1.0.0
	 *
	 * @return array Registered people views.
	 */
	public function get_views() {
		return array( 'overview' );
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