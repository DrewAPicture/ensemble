<?php
/**
 * Defines the database abstraction for working with the Contests component
 *
 * @package   Ensemble\Components\Contests
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests;

use Ensemble\Core;
use function Ensemble\get_wp_timezone;

/**
 * Contests table database class.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Core\Database
 *
 * @method Meta_Database              meta()
 * @method Ensemble\Contest|\WP_Error get_core_object( int|Ensemble\Contest $contest )
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
		return 'contests';
	}

	/**
	 * Retrieves the query object type to map results against.
	 *
	 * @since 1.0.0
	 *
	 * @return string Query object type.
	 */
	public function get_query_object_type() {
		return 'Ensemble\\Contest';
	}

	/**
	 * Retrieves the table name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Table name
	 */
	public function get_table_suffix() {
		return 'ensemble_contests';
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
			'id'         => '%d',
			'name'       => '%s',
			'venues'     => '%s',
			'type'       => '%s',
			'external'   => '%s',
			'status'     => '%s',
			'timezone'   => '%s',
			'start_date' => '%s',
			'end_date'   => '%s',
		);
	}

	/**
	 * Retrieves any column defaults.
	 *
	 * @since 1.0.0
	 */
	public function get_column_defaults() {
		$today = new \DateTime( 'now', new DateTimeZone( get_wp_timezone() ) );

		return array(
			'type'       => 'regular',
			'status'     => 'published',
			'timezone'   => get_wp_timezone(),
			'start_date' => $today->format( 'Y-m-d 00:00:00' ),
			'end_date'   => $today->format( 'Y-m-d 23:59:59' ),
		);
	}

	/**
	 * Queries for contests.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args {
	 *     Optional. Arguments for querying contests. Default empty array.
	 *
	 *     @type int|array    $id      Contest ID or array of contest IDs to retrieve.
	 *     @type int          $number  Number of contests to query for. Default 20.
	 *     @type int          $offset  Number of contests to offset the query for. Default 0.
	 *     @type int|array    $exclude Contest ID or array of IDs to explicitly exclude.
	 *     @type string       $status  Contest status. Default empty.
	 *     @type string       $order   How to order returned contest results. Accepts 'ASC' or 'DESC'.
	 *                                 Default 'DESC'.
	 *     @type string       $orderby Contests table column to order results by. Default 'id'.
	 *     @type string|array $fields  Specific fields to retrieve. Accepts 'ids', a single contest field, or an
	 *                                 array of fields. Default '*' (all).
	 * }
	 * @param bool  $count Optional. Whether to return only the total number of results found. Default false.
	 * @return array|int Array of contest objects (if found), integer if `$count` is true.
	 */
	public function query( $query_args = array(), $count = false ) {
		$defaults = array(
			'id'      => 0,
			'number'  => 20,
			'offset'  => 0,
			'exclude' => array(),
			'status'  => '',
			'order'   => 'DESC',
			'orderby' => 'id',
			'fields'  => '',
		);

		$args = wp_parse_args( $args, $defaults );

		if( $args['number'] < 1 ) {
			$args['number'] = 999999999999;
		}

		$claws = claws();

		// ID.
		if ( ! empty( $args['id'] ) ) {
			if ( ! is_array( $args['id'] ) ) {
				$args['id'] = array( $args['id'] );
			}

			$claws->where( 'id' )->in( $args['id'], 'int' );
		}

		// Exclude.
		if ( ! empty( $args['exclude'] ) ) {
			if ( ! is_array( $args['exclude'] ) ) {
				$args['exclude'] = array( $args['exclude'] );
			}

			$claws->where( 'id' )->not_in( $exclude, 'int' );
		}

		// Venues.
		if ( ! empty( $args['venues'] ) ) {
			if ( ! is_array( $args['venues'] ) ) {
				$args['venues'] = array( $args['venues'] );
			}

			$claws->where( 'venues' )->in( $venues, 'int' );
		}

		// Type.
		if ( ! empty( $args['type'] ) ) {
			$claws->where( 'type' )->equals( $args['type'] );
		}

		// Status.
		if ( ! empty( $args['status'] ) ) {
			$claws->where( 'status' )->equals( $args['status'] );
		}

		// (is) External.
		if ( ! empty( $args['external'] ) ) {
			$claws->where( 'external' )->exists( $args['external'] );
		}

		$where = $claws->get_sql();

		if ( 'DESC' === strtoupper( $args['order'] ) ) {
			$order = 'DESC';
		} else {
			$order = 'ASC';
		}

		$join = '';

		// Check against the columns whitelist. If no match, default to $primary_key.
		$orderby = array_key_exists( $args['orderby'], $this->get_columns() ) ? $args['orderby'] : $this->get_primary_key();

		// Overload args values for the benefit of the cache.
		$args['orderby'] = $orderby;
		$args['order']   = $order;

		$callback = '';

		if ( 'ids' === $args['fields'] ) {
			$fields   = (string) $this->get_primary_key();
			$callback = 'intval';
		} else {
			$fields = $this->parse_fields( $args['fields'] );

			if ( '*' === $fields ) {
				$callback = 'Ensemble\\get_contest';
			}
		}

		$key          = $this->build_cache_key( $count, $args );
		$last_changed = $this->get_last_changed();

		$cache_key = "{$key}:{$last_changed}";

		$results = wp_cache_get( $cache_key, $this->cache_group );

		if ( false === $results ) {

			$clauses = compact( 'fields', 'join', 'where', 'orderby', 'order', 'count' );

			$results = $this->get_results( $clauses, $args, $callback );
		}

		wp_cache_add( $cache_key, $results, $this->get_cache_group(), HOUR_IN_SECONDS );

		return $results;
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
			name mediumtext NOT NULL,
			venues mediumtext NOT NULL,
			type varchar(30) NOT NULL,
			external varchar(255) NOT NULL,
			status tinytext NOT NULL,
			timezone varchar(30) NOT NULL,
			start_date datetime NOT NULL,
			end_date datetime NOT NULL,
			PRIMARY KEY  (id),
			) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );

		update_option( $table_name . '_db_version', $instance->get_version() );
	}

}
