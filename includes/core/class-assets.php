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
		wp_register_style( 'ensbl-jquery-ui-css', ENSEMBLE_PLUGIN_URL . '/assets/css/vendor/jquery-ui-fresh.min.css', array(), $this->get_asset_version( '/assets/css/vendor/jquery-ui-fresh.min.css' ) );
		wp_register_style( 'ensbl-selectWoo-css', ENSEMBLE_PLUGIN_URL . '/assets/css/vendor/selectWoo.min.css', array(), $this->get_asset_version( '/assets/css/vendor/selectWoo.min.css' ) );
		wp_register_style( 'ensbl-select2-bootstrap-css', ENSEMBLE_PLUGIN_URL . '/assets/css/vendor/select2-bootstrap.min.css', array(), $this->get_asset_version( '/assets/css/vendor/select2-bootstrap.min.css' ) );
		wp_register_style( 'ensbl-bootstrap-css', ENSEMBLE_PLUGIN_URL . '/assets/css/vendor/bootstrap' . $suffix . '.css', array( 'ensbl-select2-bootstrap-css' ), $this->get_asset_version( '/assets/css/vendor/bootstrap' . $suffix . '.css' ) );
		wp_register_style( 'ensbl-admin-css', ENSEMBLE_PLUGIN_URL . '/assets/css/ensemble-admin.css', array( 'ensbl-bootstrap-css', 'ensbl-selectWoo-css', 'ensbl-jquery-ui-css' ), $this->get_asset_version( '/assets/css/ensemble-admin.css' ) );

		// Scripts.
		wp_register_script( 'ensbl-bootstrap', ENSEMBLE_PLUGIN_URL . '/assets/js/vendor/bootstrap' . $suffix . '.js', array( 'jquery' ), $this->get_asset_version( '/assets/js/vendor/bootstrap' . $suffix . '.js' ) );
		wp_register_script( 'ensbl-parsley', ENSEMBLE_PLUGIN_URL . '/assets/js/vendor/parsley.js', array( 'jquery', 'ensbl-bootstrap' ), $this->get_asset_version( '/assets/js/vendor/parsley.js' ) );
		wp_register_script( 'ensbl-selectWoo', ENSEMBLE_PLUGIN_URL . '/assets/js/vendor/selectWoo.js', array( 'jquery' ), $this->get_asset_version( '/assets/js/vendor/selectWoo.js' ) );
		wp_register_script( 'ensbl-admin', ENSEMBLE_PLUGIN_URL . '/assets/js/ensemble-admin.js', array( 'ensbl-parsley', 'ensbl-selectWoo', 'jquery-ui-datepicker' ), $this->get_asset_version( '/assets/js/ensemble-admin.js' ) );

		$special_screens = array( 'edit-tags.php', 'term.php' );

		if ( false !== strpos( $hook_suffix, 'page_ensemble-admin' ) || in_array( $hook_suffix, $special_screens, true ) ) {
			wp_enqueue_style( 'ensbl-admin-css' );
			wp_enqueue_script( 'ensbl-admin' );
		}
	}

	/**
	 * Retrieves an asset "version" based on the filemtime of the file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path File path relative to the base plugin directory.
	 *
	 * @return Filetime integer.
	 */
	public function get_asset_version( $path ) {
		return filemtime( ENSEMBLE_PLUGIN_DIR . $path );
	}
}
