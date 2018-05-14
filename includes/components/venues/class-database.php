<?php
/**
 * Defines the database abstraction for working with the Venues component
 *
 * @package   Ensemble\Components\Venues
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues;

use Ensemble\Core;

/**
 * Venue table database class.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Core\Database
 *
 * @method Meta_Database    meta()
 * @method Object|\WP_Error get_core_object( int|Object $venue )
 */
class Database extends Core\Database {

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
			case 'get_core_object':
				return $this->get_core_object( $arguments[0] );
				break;

			case 'meta':
				return ( new Meta_Database );
				break;
		}
	}

	/**
	 * Cache group for queries.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public function get_cache_group() {
		return 'venues';
	}

	/**
	 * Retrieves the query object type to map results against.
	 *
	 * @since 1.0.0
	 *
	 * @return string Query object type.
	 */
	public function get_query_object_type() {
		return 'Ensemble\\Components\\Venues\\Object';
	}

	/**
	 * Retrieves the table name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Table name
	 */
	public function get_table_suffix() {
		return 'ensemble_venues';
	}

	/**
	 * Retrieves the table version.
	 *
	 * @since 1.0.0
	 *
	 * @return string Version number as a string.
	 */
	public function get_version() {
		return '1.0';
	}

	/**
	 * Retrieves a list of columns and data types.
	 *
	 * @since 1.0.0
	 */
	public function get_columns() {
		return array(
			'id'     => '%d',
			'status' => '%s',
			'date'   => '%s',
		);
	}

	/**
	 * Retrieves any column defaults.
	 *
	 * @since 1.0.0
	 */
	public function get_column_defaults() {
		return array();
	}

	/**
	 * Queries for venues.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args {
	 *     Optional. Arguments for querying venues. Default empty array.
	 *
	 *     @type int|array    $id      Venue ID or array of venue IDs to retrieve.
	 *     @type int          $number  Number of venues to query for. Default 20.
	 *     @type int          $offset  Number of venues to offset the query for. Default 0.
	 *     @type int|array    $exclude Venue ID or array of IDs to explicitly exclude.
	 *     @type string       $status  Venue status. Default empty.
	 *     @type string       $order   How to order returned venue results. Accepts 'ASC' or 'DESC'.
	 *                                 Default 'DESC'.
	 *     @type string       $orderby Venues table column to order results by. Default 'id'.
	 *     @type string|array $fields  Specific fields to retrieve. Accepts 'ids', a single venue field, or an
	 *                                 array of fields. Default '*' (all).
	 * }
	 * @param bool  $count Optional. Whether to return only the total number of results found. Default false.
	 * @return array|int Array of venue objects (if found), integer if `$count` is true.
	 */
	public function query( $query_args = array(), $count = false ) {
		$defaults = array(
			'id'      => 0,
			'exclude' => array(),
			'status'  => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$claws = claws();

		// ID.
		if ( ! empty( $args['id'] ) ) {
			$claws->where( 'id' )->in( $args['id'], 'int' );
		}

		// Exclude.
		if ( ! empty( $args['exclude'] ) ) {
			$claws->where( 'id' )->not_in( $args['exclude'], 'int' );
		}

		// Status.
		if ( ! empty( $args['status'] ) ) {
			$claws->where( 'status' )->equals( $args['status'] );
		}

		// Clauses.
		$join  = '';
		$where = $claws->get_sql();

		// Factor in global arguments.
		$args = $this->parse_global_args( $args );

		$clauses = compact( 'join', 'where', 'count' );

		return $this->get_results( $clauses, $args );
	}

	/**
	 * Creates the database table.
	 *
	 * @since 1.0.0
	 */
	public static function create_table() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$instance   = new self();
		$table_name = $instance->get_table_name();

		$sql = "CREATE TABLE {$table_name} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			status tinytext NOT NULL,
			PRIMARY KEY (id)
			) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );

		update_option( $table_name . '_db_version', $instance->get_version() );
	}

}
