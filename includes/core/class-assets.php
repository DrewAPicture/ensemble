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
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.min' : '';

		// Stylesheets.
		wp_register_style( 'ensbl-bootstrap-css', ENSEMBLE_PLUGIN_URL . '/assets/css/bootstrap' . $suffix . '.css', array(), '4.0.0' );
		wp_register_style( 'ensbl-admin-css', ENSEMBLE_PLUGIN_URL . '/assets/css/ensemble-admin.css', array( 'ensbl-bootstrap-css' ), ENSEMBLE_VERSION );

		// Scripts.
		wp_register_script( 'ensbl-bootstrap', ENSEMBLE_PLUGIN_URL . '/assets/js/bootstrap' . $suffix . '.js', array( 'jquery' ), '4.0.0' );
		wp_register_script( 'ensbl-parsley', ENSEMBLE_PLUGIN_URL . '/assets/js/parsley.js', array( 'jquery', 'ensbl-bootstrap' ), '2.8.1' );
		wp_register_script( 'ensbl-admin', ENSEMBLE_PLUGIN_URL . '/assets/js/ensemble-admin.js', array( 'ensbl-parsley' ), ENSEMBLE_VERSION );

		if ( false !== strpos( $hook_suffix, 'page_ensemble-admin' ) ) {
			wp_enqueue_style( 'ensbl-admin-css' );
			wp_enqueue_script( 'ensbl-admin' );
		}
	}

}
