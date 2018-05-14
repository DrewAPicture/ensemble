<?php
/**
 * Sets up the User_Database abstraction of the Database class
 *
 * @package   Ensemble\Core\Database
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Core\Interfaces;

/**
 * Core database abstraction layer.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class User_Database implements Interfaces\User_Database {

	/**
	 * Retrieves a single core object.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id User ID.
	 * @return \WP_User|\WP_Error User object or WP_Error if there was a problem.
	 */
	public function get( $user_id ) {
		return get_user_by( 'id', $user_id );
	}

	/**
	 * Queries for users.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @param bool  $count      Optional. Whether this is a count query. Default false.
	 * @return array|int Array of user results, or int if `$count` is true.
	 */
	public function query( $query_args, $count = false ) {
		if ( true === $count ) {
			$query_args['number'] = -1;
			$query_args['fields'] = 'ids';
		}

		$query   = new \WP_User_Query( $query_args );
		$results = $query->get_results();

		if ( true === $count ) {
			return count( $results );
		} else {
			return $results;
		}
	}

	/**
	 * Retrieves a count of users based on the given query arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @return int Number of results for the given query arguments.
	 */
	public function count( $query_args ) {
		return $this->query( $query_args, true );
	}

}
