<?php
/**
 * Defines the contract under which Database classes exist
 *
 * @package Ensemble\Core\Interfaces
 *
 * @since 1.0.0
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
	function get( $object_id );

	/**
	 * Queries for component objects.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @param bool  $count      Optional. Whether this is a count query. Default false.
	 * @return array|int Array of results, or int if `$count` is true.
	 */
	function query( $query_args, $count = false );

	/**
	 * Retrieves a count of core objects based on the given query arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @return int Number of results for the given query arguments.
	 */
	function query_count( $query_args );

}
