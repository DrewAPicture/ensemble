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

use Ensemble\Core\Admin\Notices_Registry;
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
		add_filter( 'removable_query_args', array( $this, 'clear_admin_query_args' ) );

		add_action( 'load-toplevel_page_ensemble-unit-admin', array( $this, 'redirect_units_menu' ) );
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

	/**
	 * Handles clearing (dynamically removing) notice and other query args in Ensemble admin screens.
	 *
	 * In the case of notices, this serves to prevent notices unnecessarily persisting across screens.
	 *
	 * @since 1.0.2
	 *
	 * @param string $query_args Current query args.
	 * @return string (Maybe) modified query args list.
	 */
	public function clear_admin_query_args( $query_args ) {
		$registry = Notices_Registry::instance();

		$potential_notices = preg_grep( '/^notice\-/', array_keys( $_REQUEST ) );

		foreach ( $potential_notices as $notice_id ) {
			if ( $registry->offsetExists( $notice_id ) ) {
				$query_args[] = $notice_id;
			}
		}

		return $query_args;
	}

	/**
	 * Redirects the top-level Competing Units menu page to the Units page.
	 *
	 * @since 1.1.0
	 */
	public function redirect_units_menu() {
		wp_redirect( add_query_arg( 'taxonomy', 'ensemble_unit', admin_url( 'edit-tags.php' ) ) );
		exit;
	}

}
