<?php
/**
 * Sets up the base Database class to be extended by components
 *
 * @package   Ensemble\Core\Database
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Core\Interfaces;

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
	private $primary = 'id';

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
	 * Please note: inserting a record flushes the cache.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Column data. See get_column_defaults().
	 * @return int|\WP_Error ID for the newly inserted record, otherwise a WP_Error object.
	 */
	public function insert( $args ) {

		$defaults = array();

		$args = wp_parse_args( $args, $defaults );

		$current_date = current_time( 'mysql' );

		// Insert the record.
		$add = false;

		return $add;
	}

	/**
	 * Retrieves a single object directly from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param int $object_id Object ID.
	 * @return \Ensemble\Model|\WP_Error Core object or WP_Error if there was a problem.
	 */
	public function get( $object_id ) {
		$GLOBALS['wpdb']->get_row(
			$GLOBALS['wpdb']->prepare(
				"SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $object_id
			)
		);
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
			$wpdb->prepare(
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
					"SELECT $column FROM $this->table_name WHERE $this->primary_key_key = '%s' LIMIT 1;", $object_id
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
	 * @param array    $clauses  Compacted array of query clauses.
	 * @param array    $args     Query arguments.
	 * @param callable $callback Optional. Callback to run against results in the generic results case.
	 *                           Default empty.
	 * @return array|int|null|object Query results.
	 */
	public function get_results( $clauses, $args, $callback = '' ) {

		if ( true === $clauses['count'] ) {

			$results = $GLOBALS['wpdb']->get_var(
				$GLOBALS['wpdb']->prepare(
					"SELECT COUNT({$this->primary_key}) FROM {$this->table_name} {$clauses['where']};"
				)
			);

			$results = absint( $results );

		} else {

			$fields = $clauses['fields'];

			// Run the query.
			$results = $GLOBALS['wpdb']->get_results(
				$GLOBALS['wpdb']->prepare(
					"SELECT {$fields} FROM {$this->table_name} {$clauses['join']} {$clauses['where']} ORDER BY {$clauses['orderby']} {$clauses['order']} LIMIT %d, %d;",
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
	 * @param object|int $instance Instance or object ID.
	 * @return object|\WP_Error Object instance, otherwise WP_Error object if there was a problem.
	 */
	public function get_core_object( $instance ) {
		$object_class = $this->get_query_object_type();

		if ( ! class_exists( $object_class ) ) {
			return new \WP_Error( 'get_core_object_class' );
		}

		if ( $instance instanceof $object_class ) {
			$_object = $instance;
		} elseif ( is_object( $instance ) ) {
			if ( isset( $instance->ID ) ) {
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

}
