<?php
/**
 * Venues Overview
 *
 * @package   Ensemble\Components\Venues\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues\Admin;

use Ensemble\Core\Interfaces\View_Loader;

/**
 * Handles displaying and managing the venues overview.
 *
 * @since 1.0.0
 *
 * @see View_Loader
 */
class Overview implements View_Loader {

	/**
	 * Registers hook callbacks for listing venues.
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
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Venues Overview', 'ensemble' ); ?></h1>
			<a href="<?php echo esc_url( add_query_arg( array( 'ensbl-view' => 'add' ) ) ); ?>" class="page-title-action" role="button">
				<?php esc_html_e( 'Add New', 'ensemble' ); ?>
			</a>
		</div>
		<?php
	}

}
