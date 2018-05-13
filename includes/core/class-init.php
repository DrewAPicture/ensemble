<?php
/**
 * Defines shared logic for setting up a component
 *
 * @package Ensemble\Core
 *
 * @since 1.0.0
 */
namespace Ensemble\Core;

/**
 * Sets up init logic for the current component.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Init {

	/**
	 * Meta instance.
	 *
	 * @since 1.0.0
	 * @var   Meta_Database_Interface
	 */
	private $meta;

	/**
	 * Database instance.
	 *
	 * @since 1.0.0
	 * @var   Database_Interface
	 */
	private $db;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $database      Database class for this component.
	 * @param string $meta_database Meta database class for this component.
	 */
	public function __construct( $database, $meta_database ) {
		$this->db   = new $database;
		$this->meta = new $meta_database;
	}

	public function __call( $name ) {
		switch( $name ) {

			case 'get':

				break;
		}
	}
	/**
	 * Retrieves a single object instance by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $object_id Object ID.
	 * @return \Ensemble\People\Staff|\WP_Error Staff object object or WP_Error if there was a problem.
	 */
	public function get( $object_id ) {

	}

	/**
	 * Queries for staff.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Staff query arguments.
	 * @param bool  $count      Optional. Whether this is a count query. Default false.
	 * @return array|int Array of results, or int if `$count` is true.
	 */
	public function query( $query_args, $count = false ) {
		return $this->db->query( $query_args, $count );
	}

	/**
	 * Retrieves a count of staff based on the given query arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @return int Number of results for the given query arguments.
	 */
	public function query_count( $query_args ) {
		return $this->db->query( $query_args, true );
	}

	/**
	 * Retrieves user meta for the given staff object.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $staff_id Staff object ID.
	 * @param string $meta_key User meta key.
	 * @param bool   $single   Optional. Whether to retrieve a single user meta value. Default false.
	 *
	 * @return mixed
	 */
	public function get_meta( $staff_id, $meta_key, $single = false ) {
		return $this->meta->get( $staff_id, $meta_key, $single );
	}


}
