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
use Ensemble\Util\Date;

/**
 * Venue table database class.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Core\Database
 *
 * @method Meta_Database    meta()
 * @method Model|\WP_Error get_core_object( int|Model $venue )
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
			'id'         => '%d',
			'name'       => '%s',
			'address'    => '%s',
			'type'       => '%s',
			'status'     => '%s',
			'date_added' => '%s',
		);
	}

	/**
	 * Retrieves any column defaults.
	 *
	 * @since 1.0.0
	 */
	public function get_column_defaults() {
		return array(
			'type'       => 'school',
			'status'     => 'active',
			'date_added' =>  Date::UTC( 'Y-m-d H:i:s' ),
		);
	}

	/**
	 * Queries for venues.
	 *
	 * @since 1.0.0
	 * @since 1.1.0 Added array support for querying by 'name', 'type', and 'status'.
	 *
	 * @param array $query_args {
	 *     Optional. Arguments for querying venues. See parse_global_args() for available
	 *     global custom query arguments. Default empty array.
	 *
	 *     @type int|int[]       $id      Venue ID or array of venue IDs to retrieve.
	 *     @type string|string[] $name    Name or array of names to query venues by. Default empty.
	 *     @type string|string[] $address Address or array of addresses to query venues by. Default empty.
	 *     @type int|int[]       $exclude ID or array of venue IDs to exclude from the query.
	 *                                    Default empty array.
	 *     @type string|string[] $type    Venue type or array of types to query by. Default empty (all).
	 *     @type string|string[] $status  Status or array of statuses to query by. Default empty (all).
	 * }
	 * @param bool  $count Optional. Whether to return only the total number of results found. Default false.
	 * @return array|int Array of venue objects (if found), integer if `$count` is true.
	 */
	public function query( $query_args = array(), $count = false ) {
		$defaults = array(
			'id'      => 0,
			'exclude' => array(),
			'status'  => '',
			'type'    => '',
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

		// Address.
		if ( ! empty( $args['address'] ) ) {
			$claws->where( 'address' )->in( $args['address'] );
		}

		// Exclude.
		if ( ! empty( $args['exclude'] ) ) {
			$claws->where( 'id' )->not_in( $args['exclude'], 'int' );
		}

		// Type.
		if ( ! empty( $args['type'] ) ) {
			$args['type'] = $this->validate_with_whitelist( $args['type'], get_allowed_types() );

			if ( ! empty( $args['type'] ) ) {
				$claws->where( 'type' )->in( $args['type'] );
			}
		}

		// Status.
		if ( ! empty( $args['status'] ) ) {
			$args['status'] = $this->validate_with_whitelist( $args['status'], get_allowed_statuses() );

			if ( ! empty( $args['status'] ) ) {
				$claws->where( 'status' )->in( $args['status'] );
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
	 * Inserts a new venue into the database.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Venue data.
	 * @return int|\WP_Error The newly-minted venue object ID, otherwise a WP_Error object if there was a problem.
	 */
	public function insert( $data ) {
		$errors = new \WP_Error();

		if ( empty( $data['name'] ) ) {
			// Translated for surfacing in the UI.
			$errors->add( 'missing_venue_name', __( 'A name must be specified when adding a new venue.', 'ensemble' ), $data );
		}

		if ( empty( $data['address'] ) ) {
			// Translated for surfacing in the UI.
			$errors->add( 'missing_venue_address', __( 'An address must be specified when adding a new venue.', 'ensemble' ), $data );
		}

		if ( ! empty( $data['date_added'] ) ) {
			$data['date_added'] = Date::WP_to_UTC( $data['date_added'] );
		}

		$error_codes = $errors->get_error_codes();

		if ( ! empty( $error_codes ) ) {
			return $errors;
		} else {
			return parent::insert( $data );
		}
	}

	/**
	 * Updates an existing venue in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $object_id Venue ID.
	 * @param array  $data      Venue data to update.
	 * @param string $where     Optional. Column to match against in the WHERE clause. If empty, $primary_key
	 *                          will be used. Default empty.
	 * @return true|\WP_Error True if the venue was successfully updated, otherwise a WP_Error object.
	 */
	public function update( $object_id, $data = array(), $where = '' ) {
		if ( ! empty( $data['date_added'] ) ) {
			$data['date_added'] = Date::WP_to_UTC( $data['date_added'] );
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
			address mediumtext NOT NULL,
			type tinytext NOT NULL,
			status tinytext NOT NULL,
			date_added datetime NOT NULL,
			PRIMARY KEY (id)
			) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );

		update_option( $table_name . '_db_version', $instance->get_version() );
	}

}
