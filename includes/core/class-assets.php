<?php
/**
 * Registers Ensemble scripts and styles
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

/**
 * Core class used to register scripts and styles for use in Ensemble admin screens.
 *
 * @since 1.0.0
 *
 * @see Interfaces\Loader
 */
class Assets implements Interfaces\Loader {

	/**
	 * Sets up hook callbacks to register scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'wp_enqueue_scripts',    array( $this, 'assets'       ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
	}

	/**
	 * Registers scripts and styles for general use.
	 *
	 * @since 1.0.0
	 */
	public function assets() {

	}

	/**
	 * Registers scripts and styles for admin use.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function admin_assets( $hook_suffix ) {
		wp_register_script( 'esbl-parsley', ENSEMBLE_PLUGIN_URL . '/assets/js/parsley.js', array( 'jquery' ), '2.8.1' );
	}

}
