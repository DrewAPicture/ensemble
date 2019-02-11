<?php
/**
 * Sets up the Instructors database class
 *
 * @package   Ensemble\Components\People
 * @copyright Copyright (c) 2019, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.1.0
 */
namespace Ensemble\Components\People\Instructors;

use Ensemble\Core;

/**
 * Instructors member database class.
 *
 * @since 1.1.0
 *
 * @see \Ensemble\Core\User_Database
 *
 * @method Meta_Database meta()
 */
class Database extends Core\User_Database {

	/**
	 * Facilitates magic method calls.
	 *
	 * @since 1.1.0
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

	/**
	 * Queries for instructors in the Users database.
	 *
	 * @since 1.1.0
	 *
	 * @param array $query_args Query arguments.
	 * @param bool  $count      Optional. Whether this is a count query. Default false.
	 * @return array|int If `$count` is true, an integer, otherwise an array of results.
	 */
	public function query( $query_args, $count = false ) {
		$query_args['role__in'] = array( 'ensemble_instructor' );

		return parent::query( $query_args, $count );
	}

}
