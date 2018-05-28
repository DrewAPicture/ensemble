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
use Ensemble\Util\Date;

/**
 * Contests table database class.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Core\Database
 *
 * @method Meta_Database    meta()
 * @method Model|\WP_Error get_core_object( int|Model $contest )
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
				return parent::get_core_object( $arguments[0] );
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
		return __NAMESPACE__ . '\\Model';
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
		return array(
			'type'       => 'standard',
			'status'     => 'published',
			'timezone'   => Date::get_wp_timezone(),
			'start_date' => Date::UTC( 'Y-m-d H:i:s' ),
		);
	}

	/**
	 * Queries for contests.
	 *
	 * @since 1.0.0
	 * @since 1.0.2 Added array support for querying by 'name', 'type', and 'status'.
	 *
	 * @param array $query_args {
	 *     Optional. Arguments for querying contests. See parse_global_args() for available
	 *     global custom query arguments. Default empty array.
	 *
	 *     @type int|int[]       $id       ID or array of contest IDs to retrieve. Default 0.
	 *     @type string|string[] $name     Name or array of contest names to query by. Default empty.
	 *     @type int|int[]       $venues   Venue ID or array of venue IDs to query contests by (based on
	 *                                     the contest:venue relationship). Default empty array.
	 *     @type string|string[] $type     Type or array of types to query contests by. Default empty.
	 *     @type string|string[] $status   Status or array of statuses to query contests by. Default empty.
	 *     @type int|array       $exclude  ID or array of contest IDs to explicitly exclude.
	 *     @type bool            $external Whether the contest contains an external URL or not.
	 *                                     Accepts true, false, or empty (ignored) Default empty.
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
			'external'   => '',
		);

		$args = wp_parse_args( $query_args, $defaults );

		$claws = claws();

		// ID.
		if ( ! empty( $args['id'] ) ) {
			$claws->where( 'id' )->in( $args['id'], 'int' );
		}

		// Name.
		if ( ! empty( $args['name'] ) ) {
			$claws->where( 'name' )->in( $args['name'] );
		}

		// Venues.
		if ( ! empty( $args['venues'] ) ) {
			$claws->where( 'venues' )->in( $args['venues'], 'int' );
		}

		// Type.
		if ( ! empty( $args['type'] ) && array_key_exists( $args['type'], get_allowed_types() ) ) {
			$claws->where( 'type' )->in( $args['type'] );
		}

		// Status.
		if ( ! empty( $args['status'] ) && array_key_exists( $args['status'], get_allowed_statuses() ) ) {
			$claws->where( 'status' )->in( $args['status'] );
		}

		// Exclude.
		if ( ! empty( $args['exclude'] ) ) {
			$claws->where( 'id' )->not_in( $args['exclude'], 'int' );
		}

		// (is) External.
		if ( '' !== $args['external'] ) {
			if ( true === $args['external'] ) {
				$claws->where( 'external' )->doesnt_equal( '' );
			} elseif ( false === $args['external'] ) {
				$claws->where( 'external' )->not_exists();
			}
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

		if ( ! empty( $data['start_date'] ) ) {
			$data['start_date'] = Date::WP_to_UTC( $data['start_date'] );
		}

		if ( ! empty( $data['end_date'] ) ) {
			$data['end_date'] = Date::WP_to_UTC( $data['end_date'] );
		}

		$error_codes = $errors->get_error_codes();

		if ( ! empty( $error_codes ) ) {
			return $errors;
		} else {
			return parent::insert( $data );
		}
	}

	/**
	 * Updatea a contest record in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $object_id Contest ID.
	 * @param array  $data      Data array.
	 * @param string $where     Optional. Column to match against in the WHERE clause. If empty, $primary_key
	 *                          will be used. Default empty.
	 * @return true|\WP_Error True if the contest was successfully updated, otherwise a WP_Error object.
	 */
	public function update( $object_id, $data = array(), $where = '' ) {
		$contest = get_contest( $object_id );

		if ( is_wp_error( $contest ) ) {
			return $contest;
		}

		if ( ! empty( $data['venues'] ) && is_array( $data['venues'] ) ) {
			$data['venues'] = implode( ',', $data['venues'] );
		}

		if ( ! empty( $data['start_date'] ) ) {
			$data['start_date'] = Date::WP_to_UTC( $data['start_date'] );
		}

		if ( ! empty( $data['end_date'] ) ) {
			$data['end_date'] = Date::WP_to_UTC( $data['end_date'] );
		}

		return parent::update( $object_id, $data, $where );
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
