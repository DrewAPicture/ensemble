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

use Ensemble\Core\Interfaces\Loader;

/**
 * Sets up the Ensemble Admin.
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
		add_action( 'admin_menu', array( $this, 'register_menus' ) );
	}

	/**
	 * Registers top- and sub-level Ensemble menus.
	 *
	 * @since 1.0.0
	 */
	public function register_menus() {
		add_menu_page(
			__( 'Ensemble', 'ensemble' ),
			__( 'Ensemble', 'ensemble' ),
			'manage_options',
			'ensemble-admin',
			array( $this, 'ensemble_admin' ),
			'dashicons-universal-access-alt'
		);
	}

	/**
	 * Renders the primary Ensemble admin.
	 *
	 * @since 1.0.0
	 */
	public function ensemble_admin() {
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Ensemble', 'ensemble' ); ?></h2>
		</div>
		<?php
	}

}
