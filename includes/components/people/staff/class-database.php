<?php
/**
 * Sets up querying for circuit staff
 *
 * @package   Ensemble\Components\People
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Staff;

use Ensemble\Core;

/**
 * Staff member database class.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Core\User_Database
 *
 * @method Meta_Database meta()
 */
class User_Database extends Core\User_Database {

	/**
	 * Facilitates magic method calls.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name      Method name.
	 * @param array  $arguments Method arguments (if any)
	 * @return mixed Results of the method call (if any).
	 */
	public function __call( $name, $arguments ) {
		switch ( $name ) {
			case 'meta':
				return ( new Meta_Database );
				break;
		}
	}

}
