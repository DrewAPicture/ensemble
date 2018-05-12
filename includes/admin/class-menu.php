<?php
namespace Ensemble\Admin;

/**
 * Sets up the Ensemble Admin.
 *
 * @since 1.0
 */
class Menu {

	/**
	 * Initializes menu registrations.
	 *
	 * @access public
	 * @since  1.0
	 * @static
	 */
	public static function init() {
		add_action( 'admin_menu', array( 'Ensemble\Admin\Menu', 'register_menus' ) );
	}

	/**
	 * Registers top- and sub-level Ensemble menus.
	 *
	 * @access public
	 * @since  1.0
	 */
	public function register_menus() {
		add_menu_page( __( 'Ensemble', 'ensemble' ), __( 'Ensemble', 'ensemble' ), 'manage_options', 'ensemble-admin', array( $this, 'ensemble_admin' ) );
	}

	/**
	 * Renders the primary Ensemble admin.
	 *
	 * @access public
	 * @since  1.0
	 */
	public function ensemble_admin() {
		?>
		<div class="wrap">

			<h2><?php esc_html_e( 'Ensemble', 'ensemble' ); ?></h2>

		</div>
		<?php
	}

}
