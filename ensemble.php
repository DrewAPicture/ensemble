<?php
/**
 * Plugin Name: Ensemble
 * Plugin URI: http://werdswords.com
 * Description:
 * Version: 1.0.0
 * Author: Drew Jaynes (DrewAPicture)
 * Author URI: http://werdswords.com
 * License: GPLv2
 */

// Bail if called directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ensemble' ) ) {

/**
 * Base Ensemble setup class.
 *
 * @since 1.0.0
 * @final
 */
final class Ensemble {

	/**
	 * Instance.
	 *
	 * @access private
	 * @since  1.0.0
	 * @var    \Ensemble
	 */
	private static $instance;

	/**
	 * Version.
	 *
	 * @access private
	 * @since  1.0.0
	 * @var    string
	 */
	private $version = '1.0.0';

	/**
	 * Contests instance.
	 *
	 * @access public
	 * @since  1.0.0
	 * @var    \Ensemble\Contests\Core
	 */
	public $contests;

	/**
	 * Staff instance.
	 *
	 * @access public
	 * @since  1.0.0
	 * @var    \Ensemble\People\Staff\Init
	 */
	public $staff;

	/**
	 * Teams instance.
	 *
	 * @access public
	 * @since  1.0.0
	 * @var    \Ensemble\Teams\Setup
	 */
	public $teams;

	/**
	 * Venues instance.
	 *
	 * @access public
	 * @since  1.0.0
	 * @var    \Ensemble\Venues\Setup
	 */
	public $venues;

	/**
	 * Creates an Ensemble instance.
	 *
	 * @access public
	 * @since  1.0.0
	 * @static
	 *
	 * @return \Ensemble Plugin instance.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Ensemble ) ) {
			self::$instance = new Ensemble();

			self::$instance->constants();
			self::$instance->load();
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * Defines core constants.
	 *
	 * @access private
	 * @since  1.0.0
	 */
	private function constants() {
		// Version.
		if ( ! defined( 'ENSEMBLE_VERSION' ) ) {
			define( 'ENSEMBLE_VERSION', $this->version );
		}

		// Plugin directory.
		if ( ! defined( 'ENSEMBLE_PLUGIN_DIR' ) ) {
			define( 'ENSEMBLE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}
	}

	/**
	 * Loads core files.
	 *
	 * @access private
	 * @since  1.0.0
	 */
	private function load() {
		require_once ENSEMBLE_PLUGIN_DIR . '/includes/lib/autoload.php';
	}

	/**
	 * Sets up sub-instances.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function setup() {
		Ensemble\Admin\Menu::init();

//		$this->contests = new Ensemble\Contests\Setup;
		$this->staff    = new Ensemble\People\Staff\Init();
//		$this->teams    = new Ensemble\Teams\Setup;
//		$this->venues   = new Ensemble\Venues\Setup;
	}

	/**
	 * Prevents cloning.
	 *
	 * @access private
	 * @since  1.0.0
	 */
	private function __clone() {}

	/**
	 * Prevents overloading members.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function __set( $a, $b ) {}
}

} // exists

/**
 * Instantiates Ensemble and initializes the object without the need for an object
 * in the global space.
 *
 * h/t Pippin Williamson for the pattern inspiration.
 *
 * @since 1.0.0
 *
 * @return Ensemble Global Ensemble get_instance.
 */
function ensemble() {
	return Ensemble::get_instance();
}
ensemble();
