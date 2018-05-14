<?php
/**
 * Defines the contract under which User_Database classes exist
 *
 * @package   Ensemble\Core\Interfaces
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Interfaces;

/**
 * User database interface.
 *
 * @since 1.0.0
 */
interface User_Database extends Database {

	/**
	 * Retrieves a single core object.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id User ID.
	 * @return \WP_User|\WP_Error User object or WP_Error if there was a problem.
	 */
	public function get( $user_id );

	/**
	 * Queries for users.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @param bool  $count      Optional. Whether this is a count query. Default false.
	 * @return array|int Array of user results, or int if `$count` is true.
	 */
	public function query( $query_args, $count = false );

	/**
	 * Retrieves a count of users based on the given query arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @return int Number of results for the given query arguments.
	 */
	public function count( $query_args );

}
