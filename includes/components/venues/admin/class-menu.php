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

use Ensemble\Core\Interfaces\Loader;

/**
 * Sets up the Venues menu.
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
			array( $this, 'venues_overview' )
		);
	}

	/**
	 * Renders the secondary Venues Overview admin screen.
	 *
	 * @since 1.0.0
	 */
	public function venues_overview() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Venues Overview', 'ensemble' ); ?></h1>
		</div>
		<?php
	}

}
