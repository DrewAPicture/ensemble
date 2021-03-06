<?php
/**
 * Installation utility class
 *
 * @package   Ensemble\Util
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Util;

use Ensemble\Components;
use Ensemble\Core\Users;
use Ensemble\Core\Interfaces\Loader;

/**
 * Installs Ensemble (if necessary).
 *
 * @since 1.0.0
 */
class Install implements Loader {

	/**
	 * Runs the create_table() methods for all component database classes.
	 *
	 * @since 1.0.0
	 */
	public static function run() {
		Components\Contests\Database::create_table();
		Components\Venues\Database::create_table();
	}

	/**
	 * Sets up the install class.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		$users = new Users;

		$users->add_roles();
		$users->add_caps();

		self::run();

		update_option( 'ensemble_installed', 1 );
	}

}