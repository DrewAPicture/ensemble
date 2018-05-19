<?php
/**
 * Sets up the Contests admin menu item
 *
 * @package   Ensemble\Components\Contests\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Components\Contests\Database;
use Ensemble\Core\Interfaces\Menu_Router;
use function Ensemble\{load_view, get_current_view};

/**
 * Sets up the Contests menu.
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
		add_action( 'admin_menu', array( $this, 'register_submenu' ), 20 );
	}

	/**
	 * Registers the Contests submenu.
	 *
	 * @since 1.0.0
	 */
	public function register_submenu() {
		add_submenu_page(
			'ensemble-admin',
			__( 'Contests Overview', 'ensemble' ),
			__( 'Contests', 'ensemble' ),
			'manage_contests',
			'ensemble-admin-contests',
			array( $this, 'route_request' )
		);
	}

	/**
	 * Routes the request based on the current ensbl-view value.
	 *
	 * @since 1.0.0
	 */
	public function route_request() {
		$view = get_current_view();

		// If 'overview' is requested and there are no contests, take the user to the Add Contest screen instead.
		if ( 'overview' === $view ) {
			if ( 0 === ( new Database )->count() ) {
				$view = 'add';
			}
		}

		load_view( new Actions, $view );
	}

}
