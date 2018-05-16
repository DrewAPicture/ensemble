<?php
/**
 * Sets up request handlers
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Core\Interfaces\Loader;

/**
 * Core class used to capture and process requests.
 *
 * @since 1.0.0
 *
 * @see Loader
 */
class Requests implements Loader {

	/**
	 * Registers hook callbacks for capturing requests.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'query_vars', array( $this, 'whitelist_query_vars' ) );
	}

	/**
	 * Registers query variables needed by the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_vars Existing list of query vars.
	 * @return array Modified list of query vars.
	 */
	public function whitelist_query_vars( $query_vars ) {
		return array_merge( $query_vars, array(
			'contest_id',
			'venue_id',
		) );
	}
}
