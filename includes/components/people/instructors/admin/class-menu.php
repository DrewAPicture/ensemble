<?php
/**
 * Sets up the Instructors admin menu item
 *
 * @package   Ensemble\Components\People\Instructors\Admin
 * @copyright Copyright (c) 2019, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.1.0
 */
namespace Ensemble\Components\People\Instructors\Admin;

use Ensemble\Core\Interfaces\Menu_Router;
use function Ensemble\{load_view, get_current_view};

/**
 * Sets up the People menu.
 *
 * @since 1.1.0
 *
 * @see Menu_Router
 */
class Menu implements Menu_Router {

	/**
	 * Initializes menu registrations.
	 *
	 * @since 1.1.0
	 */
	public function load() {
		add_action( 'admin_menu', array( $this, 'register_submenu' ), 40 );
	}

	/**
	 * Registers the Contests submenu.
	 *
	 * @since 1.1.0
	 */
	public function register_submenu() {
		add_submenu_page(
			'ensemble-unit-admin',
			__( 'Instructors', 'ensemble' ),
			__( 'Instructors', 'ensemble' ),
			'manage_options',
			'ensemble-admin-people-instructors',
			array( $this, 'route_request' )
		);
	}

	/**
	 * Routes the request based on the current ensbl-view value.
	 *
	 * @since 1.1.0
	 */
	public function route_request() {
		load_view( new Actions, get_current_view() );
	}

}
