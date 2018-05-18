<?php
/**
 * Sets up the Ensemble admin menu
 *
 * @package   Ensemble\Core\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Admin;

use Ensemble\Core\Interfaces\Menu_Router;
use Ensemble\Core\Traits\View_Loader;
use function Ensemble\{load_view, get_view_var};

/**
 * Sets up the Ensemble Admin.
 *
 * @since 1.0.0
 *
 * @see Menu_Router
 * @see View_Loader
 */
class Menu implements Menu_Router {

	/**
	 * Initializes menu registrations.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'admin_menu', array( $this, 'register_menu'          )     );
		add_action( 'admin_menu', array( $this, 'register_settings_menu' ), 45 );
	}

	/**
	 * Registers the top-level Ensemble admin menu.
	 *
	 * @since 1.0.0
	 */
	public function register_menu() {
		add_menu_page(
			__( 'Ensemble', 'ensemble' ),
			__( 'Ensemble', 'ensemble' ),
			'manage_options',
			'ensemble-admin',
			array( $this, 'route_request' ),
			'dashicons-universal-access-alt'
		);
	}

	/**
	 * Registers the Settings submenu.
	 *
	 * @since 1.0.0
	 */
	public function register_settings_menu() {
		add_submenu_page(
			'ensemble-admin',
			__( 'Settings', 'ensemble' ),
			__( 'Settings', 'ensemble' ),
			'manage_options',
			'ensemble-admin-settings',
			array( $this, 'route_request' )
		);
	}

	/**
	 * Routes core admin requests.
	 *
	 * @since 1.0.0
	 */
	public function route_request() {
		load_view( new Actions, get_view_var() );
	}
}
