<?php
/**
 * Defines the contract under which Database classes exist
 *
 * @package   Ensemble\Core\Interfaces
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Interfaces;

/**
 * Database interface.
 *
 * @since 1.0.0
 */
interface Database {

	/**
	 * Retrieves a single core object.
	 *
	 * @since 1.0.0
	 *
	 * @param int $object_id Object ID.
	 * @return \Ensemble\Model|\WP_Error Core object or WP_Error if there was a problem.
	 */
	public function get( $object_id );

	/**
	 * Retrieves a single core object with caching.
	 *
	 * @since 1.0.0
	 *
	 * @param int|Ensemble\Core\Model $object Object or object ID.
	 * @return Ensemble\Core\Model|\WP_Error Core object or WP_Error object.
	 */
	public function get_object( $object );

	/**
	 * Queries for component objects.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @param bool  $count      Optional. Whether this is a count query. Default false.
	 * @return array|int Array of results, or int if `$count` is true.
	 */
	public function query( $query_args, $count = false );

	/**
	 * Retrieves a count of core objects based on the given query arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @return int Number of results for the given query arguments.
	 */
	public function count( $query_args );

}
