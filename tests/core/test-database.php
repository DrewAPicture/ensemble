<?php
namespace Ensemble\Core;

use Ensemble\Tests\UnitTestCase;
use \Ensemble\Components\Contests;

/**
 * Core Database superclass tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Core\Database
 *
 * @group database
 * @group core
 */
class Database_Tests extends UnitTestCase {

	/**
	 * Abstract Core\Database fixture.
	 *
	 * @var \Ensemble\Core\Database
	 */
	protected static $db;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {

		/*
		 * Use an anonymous class instead of a phpunit mock, because PHP 7.
		 *
		 * Setting up abstract methods with return values from an actual subclass
		 * allows the CRUD methods to be tested with real fixture data. Boom.
		 */
		self::$db = self::get_db();
	}


	/**
	 * @covers ::get_primary_key()
	 */
	public function test_get_primary_key_should_get_the_primary_key() {
		$this->assertSame( 'id', self::$db->get_primary_key() );
	}

	/**
	 * @covers ::get_table_name()
	 */
	public function test_get_table_name_should_return_table_name_set_from_table_suffix() {
		// Mocked table name.
		if ( defined( 'ENSEMBLE_NETWORK_WIDE' ) && ENSEMBLE_NETWORK_WIDE ) {
			$expected = 'ensemble_mocks';
		} else {
			$expected = $GLOBALS['wpdb']->prefix . 'ensemble_mocks';
		}

		$this->assertSame( $expected, self::$db->get_table_name() );

	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_failure_should_return_WP_Error() {
		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		// Silence the _doing_it_wrong().
		$this->setExpectedIncorrectUsage( 'wpdb::prepare' );

		// Deliberately query an invalid table to trigger the WP_Error condition.
		$result = self::get_db()->insert( array() );

		// New instance with invalid defaults.
		$this->assertWPError( $result );

		// Cleanup.
		$GLOBALS['wpdb']->suppress_errors = false;
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_failure_should_return_WP_Error_including_code_insert_failure() {
		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		// Silence the _doing_it_wrong().
		$this->setExpectedIncorrectUsage( 'wpdb::prepare' );

		// Deliberately query an invalid table to trigger the WP_Error condition.
		$result = self::get_db()->insert( array() );

		$this->assertContains( 'insert_failure', $result->get_error_codes() );

		// Cleanup.
		$GLOBALS['wpdb']->suppress_errors = false;
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_success_should_return_an_id_of_a_newly_created_object() {
		$result = ( new Contests\Database )->_insert( array(
			'name'   => 'Foo',
			'venues' => array( 1 ),
		) );

		$this->assertTrue( is_numeric( $result ) );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_success_should_return_valid_id_of_the_newly_created_object() {
		$id = ( new Contests\Database )->_insert( array(
			'name'   => 'Foo',
			'venues' => array( 1 ),
		) );

		$result = Contests\get_contest( $id );

		$this->assertInstanceOf( 'Ensemble\\Components\\Contests\\Model', $result );
	}

	/**
	 * Builds a "mock" abstract Core\Database object.
	 *
	 * @since 1.0.2
	 *
	 * @param array $args {
	 *     Optional. Arguments for overriding default abstract method returns.
	 *
	 *     @type string $cache_group     Cache group. Default 'mocks'.
	 *     @type string $object_type     Fully-qualified database model name. Default 'Model'.
	 *     @type string $table_suffix    Table suffix. Used for building the table_name property.
	 *                                   Default ensemble_mocks.
	 *     @type string $version         Table version. Default 1.0.
	 *     @type array  $columns         Column/format pairs to return from get_columns(). Default
	 *                                   empty array.
	 *     @type array  $column_defaults Column/value pairs to return from get_default_columns().
	 *                                   Default empty array.
	 *     @type array  $query_results   Results to return from query() if `$count` is false. Default
	 *                                   empty array.
	 *     @type int    $query_count     Count to return from query() if `$count` is true. Default 1.
	 *
	 * }
	 * @return Database "Mocked" Core\Database instance, except it's a fully-qualified object.
	 */
	protected static function get_db( $args = array() ) {
		$defaults = array(
			'cache_group'     => 'mocks',
			'object_type'     => 'Model',
			'table_suffix'    => 'ensemble_mocks',
			'version'         => '1.0',
			'columns'         => array(),
			'column_defaults' => array(),
			'query_results'   => array(),
			'query_count'     => 1,
		);

		$overrides = wp_parse_args( $args, $defaults );

		// $test_args passed through the constructor to Testable_Abstract::set_overrides().
		$db_object = new class( $overrides ) extends Database {

			public function get_cache_group() { return $this->test_args['cache_group']; }

			public function get_query_object_type() { return $this->test_args['object_type']; }

			public function get_table_suffix() { return $this->test_args['table_suffix']; }

			public function get_version() { return $this->test_args['version']; }

			public function get_columns() { return $this->test_args['columns']; }

			public function get_column_defaults() { return $this->test_args['column_defaults']; }

			public function query( $query_args = array(), $count = false ) { return true === $count ? $this->test_args['query_count'] : $this->test_args['query_results']; }
		};

		return $db_object;
	}
}

