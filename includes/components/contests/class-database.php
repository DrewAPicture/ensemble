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
 * @method Meta_Database    meta()
 * @method Object|\WP_Error get_core_object( int|Object $contest )
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
		return 'Ensemble\\Components\\Contests\\Object';
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
			'id'          => '%d',
			'name'        => '%s',
			'description' => '%s',
			'venues'      => '%s',
			'type'        => '%s',
			'external'    => '%s',
			'status'      => '%s',
			'timezone'    => '%s',
			'start_date'  => '%s',
			'end_date'    => '%s',
		);
	}

	/**
	 * Retrieves any column defaults.
	 *
	 * @since 1.0.0
	 */
	public function get_column_defaults() {
		$today = $this->get_date_object();

		return array(
			'type'       => 'standard',
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
	 *     @type string       $status  Contest status. Accepts 'published' or 'draft'. Default empty (all).
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
			'id'         => 0,
			'name'       => '',
			'type'       => '',
			'exclude'    => array(),
			'status'     => '',
			'start_date' => '',
			'end_date'   => '',
		);

		$args = wp_parse_args( $query_args, $defaults );

		$claws = claws();

		// ID.
		if ( ! empty( $args['id'] ) ) {
			$claws->where( 'id' )->in( $args['id'], 'int' );
		}

		// Name.
		if ( ! empty( $args['name'] ) ) {
			$claws->where( 'name' )->equals( $args['name'] );
		}

		// Venues.
		if ( ! empty( $args['venues'] ) ) {
			$claws->where( 'venues' )->in( $args['venues'], 'int' );
		}

		// Type.
		if ( ! empty( $args['type'] ) ) {
			$claws->where( 'type' )->equals( $args['type'] );
		}

		// Status.
		if ( ! empty( $args['status'] ) ) {
			$claws->where( 'status' )->equals( $args['status'] );
		}

		// Exclude.
		if ( ! empty( $args['exclude'] ) ) {
			$claws->where( 'id' )->not_in( $args['exclude'], 'int' );
		}

		// (is) External.
		if ( ! empty( $args['external'] ) ) {
			$claws->where( 'external' )->exists( $args['external'] );
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
	 * Inserts a new contest record into the database.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Contest data.
	 * @return int|\WP_Error Newly-minted contest object ID if successfully inserted, otherwise a WP_Error object.
	 */
	public function insert( $data ) {
		$errors = new \WP_Error();

		if ( empty( $data['name'] ) ) {
			// Translated for surfacing in the UI.
			$errors->add( 'missing_contest_name', __( 'A name must be specified when adding a new contest.', 'ensemble' ), $data );
		}

		if ( empty( $data['venues'] ) ) {
			// Translated for surfacing in the UI.
			$errors->add( 'missing_contest_venues', __( 'One or more venues must be specified when adding a contest.', 'ensemble' ), $data );
		} elseif ( is_array( $data['venues'] ) ) {
			$data['venues'] = implode( ',', $data['venues'] );
		}

		$error_codes = $errors->get_error_codes();

		if ( ! empty( $error_codes ) ) {
			return $errors;
		} else {
			return parent::insert( $data );
		}
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
			description longtext NOT NULL,
			venues mediumtext NOT NULL,
			type tinytext NOT NULL,
			external mediumtext NOT NULL,
			status tinytext NOT NULL,
			timezone varchar(30) NOT NULL,
			start_date datetime NOT NULL,
			end_date datetime NOT NULL,
			PRIMARY KEY (id)
			) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );

		update_option( $table_name . '_db_version', $instance->get_version() );
	}

}
