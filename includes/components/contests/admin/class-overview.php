<?php
/**
 * Contests Overview
 *
 * @package   Ensemble\Components\Contests\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Core\Interfaces\View_Loader;

/**
 * Handles displaying and managing the contests overview.
 *
 * @since 1.0.0
 */
class Overview implements View_Loader {

	/**
	 * Registers hook callbacks for listing contests.
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
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Contests Overview', 'ensemble' ); ?></h1>
		</div>
		<?php
	}

}
