<?php
namespace Ensemble\Core;

/**
 * Core database abstraction layer.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Database implements Database_Interface {

	/**
	 * Retrieves a single core object.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param int $object_id Object ID.
	 * @return \Ensemble\Model|\WP_Error Core object or WP_Error if there was a problem.
	 */
	public function get( $object_id ) {

	}

	/**
	 * Queries for core objects.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @param bool  $count      Optional. Whether this is a count query. Default false.
	 * @return array|int Array of results, or int if `$count` is true.
	 */
	abstract public function query( $query_args, $count = false );

	/**
	 * Retrieves a count of core objects based on the given query arguments.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param array $query_args Query arguments.
	 * @return int Number of results for the given query arguments.
	 */
	public function query_count( $query_args ) {
		return $this->query( $query_args, true );
	}

	/**
	 * Retrieves a core object instance based on the given type.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @param object|int $instance Instance or object ID.
	 * @param string     $class    Object class name.
	 * @return object|\WP_Error Object instance, otherwise WP_Error object if there was a problem.
	 */
	protected function get_core_object( $instance, $object_class ) {
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
