<?php
namespace Ensemble\Tests;

require_once dirname( __FILE__ ) . '/factory.php';

/**
 * Defines a basic fixture to run multiple tests.
 *
 * Resets the state of the WordPress installation before and after every test.
 *
 * Includes utility functions and assertions useful for testing WordPress.
 *
 * All WordPress unit tests should inherit from this class.
 */
class UnitTestCase extends \WP_UnitTestCase {

	function __get( $name ) {
		if ( 'factory' === $name ) {
			return self::ensemble();
		}
	}

	/**
	 * Retrieves a factory instance for ensemble tests.
	 *
	 * @access protected
	 * @since  1.0.0
	 * @static
	 *
	 * @return Factory|null
	 */
	protected static function ensemble() {
		static $factory = null;
		if ( ! $factory ) {
			$factory = new Factory();
		}
		return $factory;
	}

	/**
	 * Defines operations to run after tearing down the test class.
	 *
	 * @access public
	 * @since  1.0.0
	 * @static
	 */
	public static function tearDownAfterClass() {
		self::_delete_all_data();

		return parent::tearDownAfterClass();
	}

	/**
	 * Deletes all data from defined tables.
	 *
	 * @access protected
	 * @since  1.0.0
	 *
	 * @global \wpdb $wpdb WordPress database abstraction layer.
	 */
	protected static function _delete_all_data() {
		global $wpdb;

		foreach ( array(
			ensemble()->contests->table_name,
			ensemble()->staff->table_name,
			ensemble()->teams->table_name,
			ensemble()->venues->table_name
		) as $table ) {
			$wpdb->query( "DELETE FROM {$table}" );
		}
	}

	/**
	 * Helper to flush the $wp_roles global.
	 */
	public static function _flush_roles() {
		/*
		 * We want to make sure we're testing against the db, not just in-memory data
		 * this will flush everything and reload it from the db
		 */
		unset( $GLOBALS['wp_user_roles'] );
		global $wp_roles;
		if ( is_object( $wp_roles ) ) {
			$wp_roles->_init();
		}
	}
}
