<?php
namespace Ensemble\Tests;

use Ensemble\Components\Contests\Database as Contests_Database;
use Ensemble\Components\Venues\Database as Venues_Database;

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

	/**
	 * Intercepts calls to the factory property and injects the custom middleware.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Property name.
	 * @return \Ensemble\Tests\Factory Custom factory middleware.
	 */
	function __get( $name ) {
		if ( 'factory' === $name ) {
			return self::ensemble();
		}
	}

	/**
	 * Retrieves a factory instance for ensemble tests.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @static
	 */
	public static function tearDownAfterClass() {
		self::_delete_all_data();

		return parent::tearDownAfterClass();
	}

	/**
	 * Helper to strip seconds from a given date in case the test is slow
	 * and the seconds don't line up.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date Date string.
	 * @return string Modified date string in Y-m-d H:i format (no seconds).
	 */
	public function strip_seconds_from_date( $date ) {
		return date( 'Y-m-d H:i', strtotime( $date ) );
	}

	/**
	 * Deletes all data from defined tables.
	 *
	 * @since 1.0.0
	 *
	 * @global \wpdb $wpdb WordPress database abstraction layer.
	 */
	protected static function _delete_all_data() {
		global $wpdb;

		$tables = [
			( new Contests_Database )->get_table_name(),
			( new Venues_Database )->get_table_name()
		];

		foreach ( $tables as $table ) {
			$wpdb->query( "DELETE FROM {$table}" );
		}
	}

	/**
	 * Helper to flush the $wp_roles global.
	 *
	 * @since 1.0.0
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
