<?php
/**
 * Sets up the Venues component
 *
 * @package   Ensemble\Components\Venues
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues;

use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Interfaces\Views_Manifest;
use function Ensemble\load;

/**
 * Implements Contests component functionality in Ensemble core.
 *
 * @since 1.0.0
 *
 * @see Ensemble\Core\Interfaces\Loader
 */
class Setup implements Loader, Views_Manifest {

	/**
	 * Initializes the component.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		require_once ENSEMBLE_PLUGIN_DIR . 'includes/functions/venue-functions.php';

		if ( is_admin() ) {
			load( new Admin\Menu );
			load( new Admin\Save );
			load( new Admin\Delete );
			load( new Admin\Overview );
		}
	}

	/**
	 * Retrieves a list of all venue admin views.
	 *
	 * @since 1.0.0
	 *
	 * @return array Venue admin views.
	 */
	public function get_views() {
		return array( 'overview', 'add', 'edit', 'delete' );
	}

}
