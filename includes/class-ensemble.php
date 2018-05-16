<?php
/**
 * Ensemble bootstrap
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */

use Ensemble\Admin;
use Ensemble\Core;
use Ensemble\Util;
use function Ensemble\load;

/**
 * Sets up the Ensemble plugin.
 *
 * @since 1.0.0
 */
final class Ensemble {

	/**
	 * Instance.
	 *
	 * @since 1.0.0
	 * @var   \Ensemble
	 */
	private static $instance;

	/**
	 * Ensemble loader file path.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $file = '';

	/**
	 * Version.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $version = '1.0.0';

	/**
	 * Creates an Ensemble instance.
	 *
	 * @since 1.0.0
	 * @static
	 *
	 * @param string $file Path to the base plugin file.
	 * @return \Ensemble Plugin instance.
	 */
	public static function get_instance( $file = '' ) {
		if ( ! empty( $file ) && ! isset( self::$instance ) && ! ( self::$instance instanceof Ensemble ) ) {
			self::setup_instance( $file );

			self::$instance->constants();
			self::$instance->load();
			self::$instance->setup();
		}

		return self::$instance;
	}

	/**
	 * Sets up the singleton instance.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file Path to the base plugin file.
	 */
	private static function setup_instance( $file ) {
		self::$instance       = new Ensemble();
		self::$instance->file = $file;
	}

	/**
	 * Defines core constants.
	 *
	 * @since 1.0.0
	 */
	private function constants() {

		// Base plugin file.
		if ( ! defined( 'ENSEMBLE_PLUGIN_FILE' ) ) {
			define( 'ENSEMBLE_PLUGIN_FILE', $this->file );
		}

		// Plugin Folder URL.
		if ( ! defined( 'ENSEMBLE_PLUGIN_URL' ) ) {
			define( 'ENSEMBLE_PLUGIN_URL', plugin_dir_url( $this->file ) );
		}

		// Plugin directory.
		if ( ! defined( 'ENSEMBLE_PLUGIN_DIR' ) ) {
			define( 'ENSEMBLE_PLUGIN_DIR', plugin_dir_path( ENSEMBLE_PLUGIN_FILE ) );
		}

		// Component directory.
		if ( ! defined( 'ENSEMBLE_COMPONENT_DIR' ) ) {
			define( 'ENSEMBLE_COMPONENT_DIR', ENSEMBLE_PLUGIN_DIR . 'includes/components/' );
		}

		// Version.
		if ( ! defined( 'ENSEMBLE_VERSION' ) ) {
			define( 'ENSEMBLE_VERSION', $this->version );
		}
	}

	/**
	 * Loads core files.
	 *
	 * @since 1.0.0
	 */
	private function load() {
		// Autoloader and third-party libraries.
		require_once ENSEMBLE_PLUGIN_DIR . '/includes/lib/autoload.php';
		require_once ENSEMBLE_PLUGIN_DIR . '/includes/lib/claws.php';

		// Core functions.
		require_once ENSEMBLE_PLUGIN_DIR . '/includes/functions.php';
	}

	/**
	 * Sets up sub-instances.
	 *
	 * @since 1.0.0
	 */
	private function setup() {
		register_activation_hook( ENSEMBLE_PLUGIN_FILE, array( 'Ensemble\\Util\\Install', 'run' ) );

		if ( ! get_option( 'ensemble_installed' ) ) {
			load( new Util\Install );
		}

		if ( is_admin() ) {
			load( new Core\Admin\Menu );
			load( new Core\Admin\Settings );
		}

		load( new Core\Ajax_Actions );
		load( new Core\Assets );
		load( new Core\Components );
		load( new Core\Requests );
		load( new Core\Rewrite_Rules );
	}

	/**
	 * Prevents cloning.
	 *
	 * @since 1.0.0
	 */
	private function __clone() {}

	/**
	 * Prevents overloading members.
	 *
	 * @since 1.0.0
	 */
	public function __set( $a, $b ) {}

}
