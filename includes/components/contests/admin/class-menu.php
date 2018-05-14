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

use Ensemble\Core\Interfaces\Loader;

/**
 * Sets up the Contests menu.
 *
 * @since 1.0.0
 *
 * @see Loader
 */
class Menu implements Loader {

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
			array( $this, 'contests_overview' )
		);
	}

	/**
	 * Renders the secondary Contests Overview admin screen.
	 *
	 * @since 1.0.0
	 */
	public function contests_overview() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Contests Overview', 'ensemble' ); ?></h1>
		</div>
		<?php
	}

}
