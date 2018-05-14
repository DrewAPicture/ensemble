<?php
namespace Ensemble\Util;

use Ensemble\Components;
use Ensemble\Core\Interfaces\Loader;

/**
 * Installs Ensemble (if necessary).
 *
 * @since 1.0.0
 */
class Install implements Loader {

	/**
	 * Runs the create_table() methods for all component database classes.
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
		self::run();

		update_option( 'ensemble_installed', 1 );
	}

}