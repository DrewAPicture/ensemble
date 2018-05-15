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

use Ensemble\Core\Interfaces\Menu_Router;
use function Ensemble\load_view;

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
		add_action( 'admin_menu', array( $this, 'register_submenu' ) );
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
			'manage_options',
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

		$display_args = array(
			'view' => isset( $_REQUEST['ensbl-view'] ) ? sanitize_key( $_REQUEST['ensbl-view' ] ) : '',
			'id'   => isset( $_REQUEST['contest_id'] ) ? absint( $_REQUEST['contest_id'] )        :  0,
		);

		switch ( $display_args['view'] ) {

			case 'add':
			case 'edit':
				load_view( new Save, $display_args );
				break;

			case 'delete':
				load_view( new Delete, $display_args );
				break;

			default:
				load_view( new Overview, $display_args );
				break;
		}
	}

}
