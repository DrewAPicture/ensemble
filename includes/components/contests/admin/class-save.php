<?php
/**
 * Contest Add and Edit
 *
 * @package   Ensemble\Components\Contests\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Core\Interfaces\View_Loader;

/**
 * Handles adding and editing contests in the admin.
 *
 * @since 1.0.0
 */
class Save implements View_Loader {

	/**
	 * Registers hook callbacks for adding and editing contests.
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
					$this->display_edit_contest( $args['id'] );
					break;

				case 'add':
					$this->display_add_contest( $args['id'] );
					break;
			}

		}
	}

	/**
	 * Displays the Edit Contest screen markup.
	 *
	 * @since 1.0.0
	 *
	 * @param int $contest Contest ID.
	 */
	public function display_edit_contest( $contest ) {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Edit Contest', 'ensemble' ); ?></h1>
		</div>
		<?php
	}

	/**
	 * Displays the Add Contest screen markup.
	 *
	 * @since 1.0.0
	 *
	 * @param int $contest Contest ID.
	 */
	public function display_add_contest( $contest ) {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Add Contest', 'ensemble' ); ?></h1>
		</div>
		<?php
	}

}
