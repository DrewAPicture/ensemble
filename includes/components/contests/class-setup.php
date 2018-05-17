<?php
/**
 * Sets up the Contests component
 *
 * @package   Ensemble\Components\Contests
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests;

use Ensemble\Core\Interfaces\Loader;
use function Ensemble\{load};

/**
 * Implements Contests component functionality in Ensemble core.
 *
 * @since 1.0.0
 *
 * @see Ensemble\Core\Interfaces\Loader
 */
class Setup implements Loader {

	/**
	 * Initializes the component.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_filter( 'map_meta_cap', array( $this, 'map_meta_caps' ), 10, 4 );

		require_once __DIR__ . '/functions.php';

		if ( is_admin() ) {
			load( new Admin\Menu );
			load( new Admin\Actions );
		}
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

			case 'edit_contest':
			case 'delete_contest':
				$contest = get_contest( $args[0] );

				$caps[] = is_wp_error( $contest ) ? 'do_not_allow' : 'manage_contests';
				break;
		}

		return $caps;
	}

}
