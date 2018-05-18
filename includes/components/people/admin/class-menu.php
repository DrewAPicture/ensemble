<?php
/**
 * Sets up the People admin menu item
 *
 * @package   Ensemble\Components\People\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Admin;

use Ensemble\Core\Interfaces\Menu_Router;
use function Ensemble\{load_view};

/**
 * Sets up the People menu.
 *
 * @since 1.0.0
 *
 * @see Menu_Router
 */
class Menu implements Menu_Router {

	/**
	 * Initializes menu registrations.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'admin_menu', array( $this, 'register_submenu' ), 25 );
	}

	/**
	 * Registers the Contests submenu.
	 *
	 * @since 1.0.0
	 */
	public function register_submenu() {
		add_submenu_page(
			'ensemble-admin',
			__( 'People', 'ensemble' ),
			__( 'People', 'ensemble' ),
			'manage_options',
			'ensemble-admin-people',
			array( $this, 'route_request' )
		);
	}

	/**
	 * Routes the request based on the current ensbl-view value.
	 *
	 * @since 1.0.0
	 */
	public function route_request() {
		$view = isset( $_REQUEST['ensbl-view'] ) ? sanitize_key( $_REQUEST['ensbl-view' ] ) : 'overview';

		load_view( new Actions, $view );
	}

}
