<?php
/**
 * Sets up the base Database class to be extended by components
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Core\Interfaces;
use function Ensemble\clean_item_cache;
use function Ensemble\get_wp_timezone;

/**
 * Core database abstraction layer.
 *
 * @since 1.0.0
 * @abstract
 *
 * @see Interfaces\Database
 */
abstract class Database implements Interfaces\Database {

	/**
	 * Primary key.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $primary_key = 'id';

	/**
	 * Represents the table name, which can change depending on network-wide settings.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $table_name;

	/**
	 * Represents the table version, used for upgrade routines related to schema changes.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $version;

	/**
	 * Sets up the database class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->set_table_name();

		$this->version = $this->get_version();
	}

	/**
	 * Retrieves the value of the primary key.
	 *
	 * @since 1.0.0
	 *
	 * @return string Value of the primary_key member.
	 */
	public function get_primary_key() {
		return $this->primary_key;
	}

	/**
	 * Retrieves the table version (used for schema changes).
	 *
	 * @since 1.0.0
	 *
	 * @return string Version number as a string.
	 */
	abstract public function get_version();

	/**
	 * Retrieves a list of column keys and their corresponding data types.
	 *
	 * @since 1.0.0
	 *
	 * @return array Columns and data type pairs.
	 */
	abstract public function get_columns();

	/**
	 * Retrieves a list of column keys and their corresponding defaults.
	 *
	 * @since 1.0.0
	 *
	 * @return array Column and default pairs.
	 */
	abstract public function get_column_defaults();

	/**
	 * Retrieves the table suffix as defined by the extending class.
	 *
	 * @since 1.0.0
	 *
	 * @return string Table name.
	 */
	abstract public function get_table_suffix();

	/**
	 * Retrieves the table name based on network settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string Table name.
	 */
	public function get_table_name() {
		return $this->table_name;
	}

	/**
	 * Sets the table name.
	 *
	 * @since 1.0.0
	 */
	private function set_table_name() {
		$suffix = $this->get_table_suffix();

		if ( defined( 'ENSEMBLE_NETWORK_WIDE' ) && ENSEMBLE_NETWORK_WIDE ) {
			$this->table_name = $suffix;
		} else {
			$this->table_name = $GLOBALS['wpdb']->prefix . $suffix;
		}

	}

	/**
	 * Inserts a new record into the database.
	 *
	 * Please note: successfully inserting a record invalidates the item and related query caches.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Column data. See get_column_defaults().
	 * @return int|\WP_Error ID for the newly inserted record, otherwise a WP_Error object.
	 */
	public function insert( $data ) {
		$errors = new \WP_Error();

		// Set any default values.
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		// Snag columns and their formats.
		$column_formats = $this->get_columns();

		// Force fields to lowercase
		$data = array_change_key_case( $data );

		// Validate data keys against columns defined by the component.
		$data = array_intersect_key( $data, $column_formats );

		// Unslash data.
		$data = wp_unslash( $data );

		// Reorder $column_formats to match the order of columns given in $data.
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$inserted = $GLOBALS['wpdb']->insert( $this->get_table_name(), $data, $column_formats );

		if ( ! $inserted ) {
			$errors->add( 'insert_failure', 'An Ensemble record could not be inserted.', $data );

			return $errors;
		} else {
			$object = $this->get_core_object( $GLOBALS['wpdb']->insert_id );

			if ( is_wp_error( $object ) ) {
				return $object;
			} else {
				// Prime the item cache, and invalidate related query caches.
				clean_item_cache( $object );

				return $object->{$this->get_primary_key()};
			}
		}
	}

	/**
	 * Updates an existing record in the database.
	 *
	 * Note: successfully updating a record invalidates the item and related query caches.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $object_id Object ID for the record being updated.
	 * @param array  $data      Optional. Array of columns and associated data to update. Default empty array.
	 * @param string $where     Optional. Column to match against in the WHERE clause. If empty, $primary_key
	 *                          will be used. Default empty.
	 * @return true|\WP_Error True if the record was successfully updated, otherwise a WP_Error object.
	 */
	public function update( $object_id, $data = array(), $where = '' ) {
		$errors = new \WP_Error();

		// Object ID must be positive integer
		$object_id = absint( $object_id );

		$object = $this->get_core_object( $object_id );

		if ( is_wp_error( $object ) ) {
			return $object;
		}

		if ( empty( $where ) ) {
			$where = $this->get_primary_key();
		}

		// Initialise column format array
		$column_formats = $this->get_columns();

		// Force fields to lowercase.
		$data = array_change_key_case( $data );

		// Validate data keys against columns defined by the component.
		$data = array_intersect_key( $data, $column_formats );

		// Unslash data.
		$data = wp_unslash( $data );

		// Ensure primary key is not included in the $data array
		if ( isset( $data[ $this->get_primary_key ] ) ) {
			unset( $data[ $this->get_primary_key ] );
		}

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$updated = $GLOBALS['wpdb']->update( $this->get_table_name(), $data, array( $where => $object->{$this->primary_key} ), $column_formats );

		if ( false === $updated ) {
			$message = sprintf( 'The %1$s object update failed for the %2$s query.', $object_id, $this->get_cache_group() );

			$errors->add( 'update_failure', $message, array(
				'id'   => $object_id,
				'data' => $data,
			) );
		}

		$error_codes = $errors->get_error_codes();

		if ( ! empty( $error_codes ) ) {
			return $errors;
		} else {
			// Invalidate and prime the item cache, and invalidate related query caches.
			clean_item_cache( $object );

			return true;
		}
	}

	/**
	 * Deletes a record from the database.
	 *
	 * Please note: successfully deleting a record invalidates the item and related query caches.
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $object_id Object ID.
	 * @return true|\WP_Error True if the record was successfully deleted, otherwise a WP_Error object.
	 */
	public function delete( $object_id ) {
		$errors = new \WP_Error();

		// Object ID must be positive integer.
		$object_id = absint( $object_id );

		$object = $this->get_core_object( $object_id );

		if ( is_wp_error( $object ) ) {
			return $object;
		}

		$deleted = $GLOBALS['wpdb']->query(
			$GLOBALS['wpdb']->prepare(
				"DELETE FROM $this->table_name WHERE $this->primary_key = %d", $object->{$this->get_primary_key()}
			)
		);

		if ( false === $deleted ) {
			$message = sprintf( 'Deletion of the %1$s %2$s object failed.', $object_id, $this->get_cache_group() );

			$errors->add( 'delete_failure', $message, $object_id );
		}

		$error_codes = $errors->get_error_codes();

		if ( ! empty( $error_codes ) ) {
			return $errors;
		} else {
			// Invalidate the item cache along with related query caches.
			clean_item_cache( $object );

			return true;
		}
	}

	/**
	 * Retrieves a single object directly from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param int $object_id Object ID.
	 * @return Object|\WP_Error Core object or WP_Error if there was a problem.
	 */
	public function get( $object_id ) {
		$object = $GLOBALS['wpdb']->get_row(
			$GLOBALS['wpdb']->prepare(
				"SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $object_id
			)
		);

		if ( null !== $object ) {
			return $object;
		} else {
			/* translators: 1: Query type, 2: object ID */
			$message = sprintf( __( 'The %1$s object with ID %2$d is invalid. Please try again.', 'ensemble' ),
				$this->get_cache_group(),
				$object_id
			);
			return new \WP_Error( 'invalid_object', $message );
		}
	}

	/**
	 * Retrieves a count of core objects based on the given query arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @return int Number of results for the given query arguments.
	 */
	public function count( $query_args ) {
		return $this->query( $query_args, true );
	}

	/**
	 * Runs a query for the current object type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $query_args Optional. Query arguments. See extending class for defaults.
	 * @param bool  $count      Optional. Whether this is a count query. Default false.
	 * @return array|int Array of results, or int if `$count` is true.
	 */
	abstract public function query( $query_args = array(), $count = false );

	/**
	 * Determines whether the given object exists.
	 *
	 * Note: This will bypass caching and run a query.
	 *
	 * @since 1.0.0
	 *
	 * @param int $object_id Object ID.
	 * @return bool True if the object exists, otherwise false.
	 */
	public function exists( $object_id ) {
		$result = $GLOBALS['wpdb']->query(
			$GLOBALS['wpdb']->prepare(
				"SELECT 1 FROM {$this->table_name} WHERE {$this->primary_key} = %d;", $object_id
			)
		);

		return ! empty( $result );
	}

	/**
	 * Retrieves a record based on column and object ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string     $column    Column name. See get_columns().
	 * @param int|string $object_id Object ID.
	 * @return object|\WP_Error Database query result object, otherwise a WP_Error object.
	 */
	public function get_by( $column, $object_id ) {
		$errors = new \WP_Error();

		if ( ! array_key_exists( $column, $this->get_columns() ) ) {
			$message = sprintf( 'The %1$s column is invalid for %2$s queries.', $column, $this->get_table_name() );

			$errors->add( 'invalid_column', $message );
		}

		if ( empty( $object_id ) ) {
			$message = sprintf( 'get_column() requires a valid object ID for %s queries.', $this->get_table_name() );

			$errors->add( 'missing_object_id', $message );
		}

		$error_codes = $errors->get_error_codes();

		if ( ! empty( $error_codes ) ) {
			$result = $errors;
		} else {
			$result = $GLOBALS['wpdb']->get_row(
				$GLOBALS['wpdb']->prepare(
					"SELECT * FROM $this->table_name WHERE $column = '%s' LIMIT 1;", $object_id
				)
			);
		}

		return $result;
	}

	/**
	 * Retrieves a value based on column name and object ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string     $column    Column name. See get_columns().
	 * @param int|string $object_id Object ID.
	 * @return string|\WP_Error Database query result (as string), otherwise a WP_Error object.
	 */
	public function get_column( $column, $object_id ) {
		$errors = new \WP_Error();

		if ( ! array_key_exists( $column, $this->get_columns() ) ) {
			$message = sprintf( 'The \'%1$s\' column is invalid for \'%2$s\' queries.', $column, $this->get_table_name() );

			$errors->add( 'invalid_column', $message );
		}

		if ( empty( $object_id ) ) {
			$message = sprintf( 'get_column() requires a valid object ID for \'%s\' queries.', $this->get_table_name() );

			$errors->add( 'missing_object_id', $message );
		}

		$error_codes = $errors->get_error_codes();

		if ( ! empty( $error_codes ) ) {
			$result = $errors;
		} else {
			$result = $GLOBALS['wpdb']->get_var(
				$GLOBALS['wpdb']->prepare(
					"SELECT $column FROM $this->table_name WHERE $this->primary_key = '%s' LIMIT 1;", $object_id
				)
			);
		}

		return $result;
	}

	/**
	 * Retrieves one column value based on another given column and matching value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column       Column name. See get_columns().
	 * @param string $column_where Column to match against in the WHERE clause.
	 * @param $column_value        Value to match to the column in the WHERE clause.
	 * @return string|\WP_Error Database query result (as string), otherwise a WP_Error object.
	 */
	public function get_column_by( $column, $column_where, $column_value ) {
		$errors = new \WP_Error();

		if ( empty( $column_where ) ) {
			$message = sprintf( 'Missing column to match against for the WHERE clause in the \'%s\' query.', $this->get_table_name() );

			$errors->add( 'missing_where_column', $message );
		}

		if ( empty( $column_value ) ) {
			$message = sprintf( 'Missing column value for the \'%s\' query.', $this->get_table_name() );

			$errors->add( 'missing_value', $message );
		}

		if ( ! array_key_exists( $column, $this->get_columns() ) ) {
			$message = sprintf( 'The \'%1$s\' column is invalid for \'%2$s\' queries.', $column, $this->get_table_name() );

			$errors->add( 'invalid_column', $message );
		}

		$error_codes = $errors->get_error_codes();

		if ( ! empty( $error_codes ) ) {
			$result = $errors;
		} else {
			$result = $GLOBALS['wpdb']->get_var(
				$GLOBALS['wpdb']->prepare(
					"SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;", $column_value
				)
			);
		}

		return $result;
	}

	/**
	 * Retrieves results for a variety of query types.
	 *
	 * @since 1.0.0
	 *
	 * @param array $clauses Compacted array of query clauses.
	 * @param array $args    Query arguments.
	 * @return array|int|null|object Query results.
	 */
	public function get_results( $clauses, $args ) {

		$key          = $this->build_cache_key( $clauses['count'], $args );
		$last_changed = $this->get_last_changed();

		$cache_key = "{$key}:{$last_changed}";

		$results = wp_cache_get( $cache_key, $this->get_cache_group() );

		// If there are cached results, return them.
		if ( false !== $results ) {
			return $results;
		}

		// Continue with the query.
		if ( true === $clauses['count'] ) {

			$results = $GLOBALS['wpdb']->get_var(
				"SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$clauses['where']};"
			);

			$results = absint( $results );

		} else {

			$fields   = $args['fields'];
			$callback = $args['callback'];

			// Run the query.
			$results = $GLOBALS['wpdb']->get_results(
				$GLOBALS['wpdb']->prepare(
					"SELECT {$fields} FROM {$this->table_name} {$clauses['join']} {$clauses['where']} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d, %d;",
					absint( $args['offset'] ),
					absint( $args['number'] )
				)
			);

			/*
			 * If the query is for a single field, pluck the field into an array.
			 *
			 * Note that only the single field was selected in the query, but wpdb->get_results()
			 * returns an array of objects, thus the pluck.
			 */
			if ( '*' !== $fields && false === strpos( $fields, ',' ) ) {
				$results = wp_list_pluck( $results, $fields );
			}

			// Run the results through the fields-dictated callback.
			if ( ! empty( $callback ) && is_callable( $callback ) ) {
				$results = array_map( $callback, $results );
			}

		}

		wp_cache_add( $cache_key, $results, $this->get_cache_group(), HOUR_IN_SECONDS );

		return $results;
	}

	/**
	 * Retrieves the cache group value as defined by the extending class.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cache group.
	 */
	abstract public function get_cache_group();

	/**
	 * Retrieves the query object type as defined by the extending class.
	 *
	 * @since 1.0.0
	 *
	 * @return string The query object type, e.g. 'Ensemble\{Component}'.
	 */
	abstract public function get_query_object_type();

	/**
	 * Retrieves a core object instance based on the given type.
	 *
	 * @since 1.0.0
	 *
	 * @param Object|int $instance Instance or object ID.
	 * @return Object|\WP_Error Object instance, otherwise WP_Error object if there was a problem.
	 */
	public function get_core_object( $instance ) {
		$object_class = $this->get_query_object_type();

		if ( ! class_exists( $object_class ) ) {
			return new \WP_Error( 'get_core_object_class' );
		}

		if ( $instance instanceof $object_class ) {
			$_object = $instance;
		} elseif ( is_object( $instance ) ) {
			if ( isset( $instance->id ) ) {
				$_object = new $object_class( $instance );
			} else {
				$_object = $object_class::get_instance( $instance );
			}
		} else {
			$_object = $object_class::get_instance( $instance );
		}

		if ( is_wp_error( $_object ) ) {
			return $_object;
		}

		return $_object;
	}

	/**
	 * Parses a string of one or more valid object fields into a SQL-friendly format.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $fields Object fields.
	 * @return string SQL-ready fields list. If empty, default is '*'.
	 */
	public function parse_fields( $fields ) {

		$fields_sql = '';

		if ( ! is_array( $fields ) ) {
			$fields = array( $fields );
		}

		$count     = count( $fields );
		$whitelist = array_keys( $this->get_columns() );

		foreach ( $fields as $index => $field ) {
			if ( ! in_array( $field, $whitelist, true ) ) {
				unset( $fields[ $index ] );
			}
		}

		$fields_sql = implode( ', ', $fields );

		if ( empty ( $fields_sql ) ) {
			$fields_sql = '*';
		}

		return $fields_sql;
	}

	/**
	 * Parses global arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Query arguments.
	 * @return array Parsed arguments
	 */
	public function parse_global_args( $args ) {
		$defaults = array(
			'number'   => 20,
			'offset'   => 0,
			'order'    => 'DESC',
			'orderby'  => 'id',
			'fields'   => '',
			'callback' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		// Number of results.
		if ( $args['number'] < 1 ) {
			$args['number'] = 99999;
		}

		// Number of results to offset.
		if ( $args['offset'] < 0 ) {
			$args['offset'] = 0;
		}

		// Order.
		if ( 'DESC' === strtoupper( $args['order'] ) ) {
			$args['order'] = 'DESC';
		} else {
			$args['order'] = 'ASC';
		}

		// Orderby. Check against the columns whitelist. If no match, default to the primary key.
		if ( ! array_key_exists( $args['orderby'], $this->get_columns() ) ) {
			$args['orderby'] = $this->get_primary_key();
		}

		if ( 'ids' === $args['fields'] ) {
			$args['fields']   = (string) $this->get_primary_key();
			$args['callback'] = 'intval';
		} else {
			$args['fields'] = $this->parse_fields( $args['fields'] );

			if ( '*' === $args['fields'] ) {
				$args['callback'] = array( $this, 'get_core_object' );
			}
		}

		return $args;
	}

	/**
	 * Builds a caching key based on the current query arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $count Whether or not this is a count query.
	 * @param array $args  Current query arguments.
	 * @return string Hash value to use as a cache key for the current query.
	 */
	public function build_cache_key( $count, $args ) {
		if ( true === $count ) {
			$key = md5( 'ensemble_' . $this->get_cache_group() . '_count_' . serialize( $args ) );
		} else {
			$key = md5( 'ensemble_' . $this->get_cache_group() . '_' . serialize( $args ) );
		}

		return $key;
	}

	/**
	 * Retrieves (and sets if not set) the last_changed value used for passive cache invalidation.
	 *
	 * @since 1.0.0
	 *
	 * @see get_cache_group()
	 */
	public function get_last_changed() {
		$last_changed = wp_cache_get( 'last_changed', $this->get_cache_group() );

		if ( false === $last_changed ) {
			$last_changed = microtime();
			wp_cache_set( 'last_changed', $last_changed, $this->get_cache_group() );
		}

		return $last_changed;
	}

	/**
	 * Builds and retrieves a DateTime object based on the WP timezone.
	 *
	 * @since 1.0.0
	 *
	 * @param string $timezone Optional. Timezone to use to generate the date object. Default UTC.
	 * @return \DateTime DateTime object.
	 */
	public function get_date_object( $timezone = null ) {
		if ( null === $timezone ) {
			$timezone = 'UTC';
		}

		return new \DateTime( 'now', new \DateTimeZone( $timezone ) );
	}

}
