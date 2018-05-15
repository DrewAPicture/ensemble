<?php
/**
 * Venue Add and Edit
 *
 * @package   Ensemble\Components\Venues\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues\Admin;

use Ensemble\Core\Interfaces\View_Loader;

/**
 * Handles adding and editing venues in the admin.
 *
 * @since 1.0.0
 *
 * @see View_Loader
 */
class Save implements View_Loader {

	/**
	 * Registers hook callbacks for adding and editing venues.
	 *
	 * @since 1.0.0
	 */
	public function load() {

	}

	/**
	 * Loads a view based on the 'ensbl-view' $_REQUEST arg.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Optional. Passed-thru display arguments (if any). Default empty array.
	 */
	public function load_view( $args = array() ) {
		if ( ! empty( $args['view'] ) && ! empty( $args['id'] ) ) {

			switch( $args['view'] ) {
				case 'edit':
					$this->display_edit_venue( $args['id'] );
					break;

				case 'add':
					$this->display_add_venue( $args['id'] );
					break;
			}

		}
	}

	/**
	 * Displays the Edit Venue screen markup.
	 *
	 * @since 1.0.0
	 *
	 * @param int $venue Venue ID.
	 */
	public function display_edit_venue( $venue ) {
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Venue', 'ensemble' ); ?></h1>
		</div>
		<?php
	}

	/**
	 * Displays the Add Venue screen markup.
	 *
	 * @since 1.0.0
	 *
	 * @param int $venue Venue ID.
	 */
	public function display_add_venue( $venue ) {
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Add Venue', 'ensemble' ); ?></h1>
		</div>
		<?php
	}
}
