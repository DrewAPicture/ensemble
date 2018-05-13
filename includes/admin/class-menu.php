<?php
/**
 * Sets up the Ensemble admin menu
 *
 * @package   Ensemble\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Admin;

/**
 * Sets up the Ensemble Admin.
 *
 * @since 1.0.0
 */
class Menu {

	/**
	 * Initializes menu registrations.
	 *
	 * @since 1.0.0
	 * @static
	 */
	public static function init() {
		$instance = new self();

		add_action( 'admin_menu', array( $instance, 'register_menus' ) );
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
