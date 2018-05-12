<?php
/**
 *
 */

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
	 * Contests instance.
	 *
	 * @since 1.0.0
	 * @var   \Ensemble\Contests\Core
	 */
	public $contests;

	/**
	 * Staff instance.
	 *
	 * @since 1.0.0
	 * @var   \Ensemble\People\Staff\Init
	 */
	public $staff;

	/**
	 * Teams instance.
	 *
	 * @since 1.0.0
	 * @var   \Ensemble\Teams\Setup
	 */
	public $teams;

	/**
	 * Venues instance.
	 *
	 * @since 1.0.0
	 * @var   \Ensemble\Venues\Setup
	 */
	public $venues;

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

		// Version.
		if ( ! defined( 'ENSEMBLE_VERSION' ) ) {
			define( 'ENSEMBLE_VERSION', $this->version );
		}

		// Plugin directory.
		if ( ! defined( 'ENSEMBLE_PLUGIN_DIR' ) ) {
			define( 'ENSEMBLE_PLUGIN_DIR', plugin_dir_path( ENSEMBLE_PLUGIN_FILE ) );
		}
	}

	/**
	 * Loads core files.
	 *
	 * @since 1.0.0
	 */
	private function load() {
		require_once ENSEMBLE_PLUGIN_DIR . '/includes/lib/autoload.php';
	}

	/**
	 * Sets up sub-instances.
	 *
	 * @since 1.0.0
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
