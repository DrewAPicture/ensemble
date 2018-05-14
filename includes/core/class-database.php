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
			$this->table_name  = $GLOBALS['wpdb']->prefix . $suffix;
		}

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
		$GLOBALS['wpdb']->get_row( $wpdb->prepare(
			"SELECT * FROM $this->table_name WHERE $this->primary = %s LIMIT 1;", $object_id )
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
