<?php
/**
 * Sets up the Venues admin menu item
 *
 * @package   Ensemble\Components\Venues\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues\Admin;

use Ensemble\Components\Venues\Database;
use Ensemble\Core\Interfaces\Menu_Router;
use function Ensemble\{load_view};

/**
 * Sets up the Venues menu.
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
		add_action( 'admin_menu', array( $this, 'register_submenu' ), 15 );
	}

	/**
	 * Registers the Venues submenu.
	 *
	 * @since 1.0.0
	 */
	public function register_submenu() {
		add_submenu_page(
			'ensemble-admin',
			__( 'Venues Overview', 'ensemble' ),
			__( 'Venues', 'ensemble' ),
			'manage_options',
			'ensemble-admin-venues',
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

		// If 'overview' is requested and there are no venues, take the user to the Add Venue screen instead.
		if ( 'overview' === $view ) {
			if ( 0 === ( new Database )->count() ) {
				$view = 'add';
			}
		}

		load_view( new Actions, $view );
	}

}
