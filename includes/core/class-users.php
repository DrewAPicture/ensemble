<?php
/**
 * Sets up Users functionality
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Core\Interfaces\Loader;
use function Ensemble\Components\Contests\get_contest;
use function Ensemble\Components\Venues\get_venue;

/**
 * Sets up the ability to manipulate various components as users.
 *
 * @since 1.0.0
 */
class Users implements Loader {

	/**
	 * Registers any hook callbacks needed to integration with the WordPress Users API.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_filter( 'map_meta_cap', array( $this, 'map_meta_cap' ), 10, 4 );
	}

	/**
	 * Maps meta capabilities to primitive ones.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $caps    The user's actual capabilities.
	 * @param string $cap     Capability name.
	 * @param int    $user_id The user ID.
	 * @param array  $args    Adds the context to the cap. Typically the object ID.
	 * @return array (Maybe) modified capabilities.
	 */
	public function map_meta_caps( $caps, $cap, $user_id, $args ) {
		switch( $cap ) {
			case 'add_contest':
				$caps[] = 'manage_contests';
				break;

			case 'add_venue':
				$caps[] = 'manage_venues';
				break;

			case 'edit_contest':
			case 'delete_contest':
				$contest = get_contest( $args[0] );

				$caps[] = is_wp_error( $contest ) ? 'do_not_allow' : 'manage_contests';
				break;

			case 'edit_venue':
			case 'delete_venue':
				$venue = get_venue( $args[0] );

				$caps[] = is_wp_error( $venue ) ? 'do_not_allow' : 'manage_venues';
				break;
		}

		return $caps;
	}

}
