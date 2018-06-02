<?php
namespace Ensemble\Core;

use Ensemble\Core\Traits\Testable_Abstract;
use Ensemble\Tests\UnitTestCase;
use Ensemble\Components\Contests;

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

		// Use an anonymous class instead of a phpunit mock, because PHP 7.
		self::$db = self::get_db();
	}

	/**
	 * Runs after each test method.
	 */
	public function tearDown() {
		// Restore wpdb->suppress_errors.
		$GLOBALS['wpdb']->suppress_errors = false;

		parent::tearDown();
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
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_success_should_return_an_id_of_a_newly_created_object() {
		$result = ( new Contests\Database )->insert( array(
			'name'   => 'Foo',
			'venues' => array( 1 ),
		) );

		$this->assertTrue( is_numeric( $result ) );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_success_should_return_valid_id_of_the_newly_created_object() {
		$id = ( new Contests\Database )->insert( array(
			'name'   => 'Foo',
			'venues' => array( 1 ),
		) );

		$result = Contests\get_contest( $id );

		$this->assertInstanceOf( 'Ensemble\\Components\\Contests\\Model', $result );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_success_should_return_true() {
		$object_id = $this->factory->contest->create();

		$result = ( new Contests\Database )->update( $object_id, array( 'name' => 'Foo' ) );

		$this->assertTrue( $result );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_no_data_to_update_should_return_true() {
		$object_id = $this->factory->contest->create();

		$result = ( new Contests\Database )->update( $object_id, array() );

		$this->assertTrue( $result );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_invalid_query_object_type_should_return_WP_Error() {
		$result = self::$db->update( 9999 );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_invalid_query_object_type_should_return_WP_Error_including_code_get_core_object_class() {
		$result = self::$db->update( 9999 );

		$this->assertContains( 'get_core_object_class', $result->get_error_codes() );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_invalid_id_type_should_return_WP_Error() {
		$db = self::get_db( array(
			'object_type' => 'Ensemble\\Components\\Contests\\Model',
		) );

		$result = $db->update( 'foo' );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_invalid_id_type_should_return_WP_Error_including_code_get_instance_invalid_id() {
		$db = self::get_db( array(
			'object_type' => 'Ensemble\\Components\\Contests\\Model',
		) );

		$result = $db->update( 'foo' );

		$this->assertContains( 'get_instance_invalid_id', $result->get_error_codes() );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_invalid_id_should_return_WP_Error() {
		$db = self::get_db( array(
			'object_type' => 'Ensemble\\Components\\Contests\\Model',
		) );

		$result = $db->update( 9999 );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_invalid_id_should_return_WP_Error_including_code_invalid_object() {
		$db = self::get_db( array(
			'object_type' => 'Ensemble\\Components\\Contests\\Model',
		) );

		$result = $db->update( 9999 );

		$this->assertContains( 'invalid_object', $result->get_error_codes() );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_fail_should_return_WP_Error() {
		$contests = new Contests\Database;

		/*
		 * Instantiate a mocked version of Contests\Database with a fake table name
		 * to trigger the update failure.
		 */
		$db = self::get_db( array(
			'cache_group'     => 'contests',
			'object_type'     => $contests->get_query_object_type(),
			'table_suffix'    => 'fake',
			'columns'         => $contests->get_columns(),
			'column_defaults' => $contests->get_column_defaults(),
		) );

		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		$object_id = $this->factory->contest->create();
		$result    = $db->update( $object_id, array( 'name' => 'Foo' ) );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_fail_should_return_WP_Error_including_code_update_failure() {
		$contests = new Contests\Database;

		/*
		 * Instantiate a mocked version of Contests\Database with a fake table name
		 * to trigger the update failure.
		 */
		$db = self::get_db( array(
			'cache_group'     => 'contests',
			'object_type'     => $contests->get_query_object_type(),
			'table_suffix'    => 'fake',
			'columns'         => $contests->get_columns(),
			'column_defaults' => $contests->get_column_defaults(),
		) );

		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		$object_id = $this->factory->contest->create();
		$result    = $db->update( $object_id, array( 'name' => 'Foo' ) );

		$this->assertContains( 'update_failure', $result->get_error_codes() );
	}

	/**
	 * @covers ::delete()
	 */
	public function test_delete_success_should_return_true() {
		$object_id = $this->factory->contest->create();

		$result = ( new Contests\Database )->delete( $object_id );

		$this->assertTrue( $result );
	}

	/**
	 * @covers ::delete()
	 */
	public function test_delete_with_invalid_query_object_type_should_return_WP_Error() {
		$result = self::$db->delete( 9999 );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::delete()
	 */
	public function test_delete_with_invalid_query_object_type_should_return_WP_Error_including_code_get_core_object_class() {
		$result = self::$db->delete( 9999 );

		$this->assertContains( 'get_core_object_class', $result->get_error_codes() );
	}

	/**
	 * @covers ::delete()
	 */
	public function test_delete_with_invalid_id_type_should_return_WP_Error() {
		$db = self::get_db( array(
			'object_type' => 'Ensemble\\Components\\Contests\\Model',
		) );

		$result = $db->delete( 'foo' );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::delete()
	 */
	public function test_delete_with_invalid_id_type_should_return_WP_Error_including_code_get_instance_invalid_id() {
		$db = self::get_db( array(
			'object_type' => 'Ensemble\\Components\\Contests\\Model',
		) );

		$result = $db->delete( 'foo' );

		$this->assertContains( 'get_instance_invalid_id', $result->get_error_codes() );
	}

	/**
	 * @covers ::delete()
	 */
	public function test_delete_with_invalid_id_should_return_WP_Error() {
		$db = self::get_db( array(
			'object_type' => 'Ensemble\\Components\\Contests\\Model',
		) );

		$result = $db->delete( 9999 );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::delete()
	 */
	public function test_delete_with_invalid_id_should_return_WP_Error_including_code_invalid_object() {
		$db = self::get_db( array(
			'object_type' => 'Ensemble\\Components\\Contests\\Model',
		) );

		$result = $db->delete( 9999 );

		$this->assertContains( 'invalid_object', $result->get_error_codes() );
	}

	/**
	 * @covers ::delete()
	 */
	public function test_delete_fail_should_return_WP_Error() {
		$contests = new Contests\Database;

		/*
		 * Instantiate a mocked version of Contests\Database with a fake table name
		 * to trigger the delete failure.
		 */
		$db = self::get_db( array(
			'cache_group'     => 'contests',
			'object_type'     => $contests->get_query_object_type(),
			'table_suffix'    => 'fake',
			'columns'         => $contests->get_columns(),
			'column_defaults' => $contests->get_column_defaults(),
		) );

		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		$object_id = $this->factory->contest->create();
		$result    = $db->delete( $object_id );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::delete()
	 */
	public function test_delete_fail_should_return_WP_Error_including_code_update_failure() {
		$contests = new Contests\Database;

		/*
		 * Instantiate a mocked version of Contests\Database with a fake table name
		 * to trigger the delete failure.
		 */
		$db = self::get_db( array(
			'cache_group'     => 'contests',
			'object_type'     => $contests->get_query_object_type(),
			'table_suffix'    => 'fake',
			'columns'         => $contests->get_columns(),
			'column_defaults' => $contests->get_column_defaults(),
		) );

		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		$object_id = $this->factory->contest->create();
		$result    = $db->delete( $object_id );

		$this->assertContains( 'delete_failure', $result->get_error_codes() );
	}

	/**
	 * @covers ::get()
	 */
	public function test_get_success_should_return_an_stdClass_object() {
		$object_id = $this->factory->contest->create();

		$result = ( new Contests\Database )->get( $object_id );

		$this->assertInstanceOf( 'stdClass', $result );
	}

	/**
	 * @covers ::get()
	 */
	public function test_get_with_invalid_object_id_type_should_return_a_WP_Error() {
		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		$result = self::$db->get( 'foo' );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::get()
	 */
	public function test_get_with_invalid_object_id_type_should_return_a_WP_Error_including_code_invalid_object() {
		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		$result = self::$db->get( 'foo' );

		$this->assertContains( 'invalid_object', $result->get_error_codes() );
	}

	/**
	 * @covers ::get()
	 */
	public function test_get_fail_should_return_a_WP_Error() {
		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		$result = self::$db->get( 9999 );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::get()
	 */
	public function test_get_fail_should_return_a_WP_Error_including_code_invalid_object() {
		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		$result = self::$db->get( 9999 );

		$this->assertContains( 'invalid_object', $result->get_error_codes() );
	}

	/**
	 * @covers ::count()
	 */
	public function test_count_should_return_an_integer() {
		$result = self::$db->count();

		$this->assertTrue( is_numeric( $result ) );
	}

	/**
	 * @covers ::exists()
	 */
	public function test_exists_success_should_return_true() {
		$object_id = $this->factory->contest->create();

		$result = ( new Contests\Database )->exists( $object_id );

		$this->assertTrue( $result );
	}

	/**
	 * @covers ::exists()
	 */
	public function test_exists_with_invalid_id_type_should_return_false() {
		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		$result = self::$db->exists( 'foo' );

		$this->assertFalse( $result );
	}

	/**
	 * @covers ::exists()
	 */
	public function test_exists_with_nonexistent_id_should_return_false() {
		// Attempting to query an invalid table will throw a wpdb warning. Suppress it.
		$GLOBALS['wpdb']->suppress_errors = true;

		$result = self::$db->exists( 999 );

		$this->assertFalse( $result );
	}

	/**
	 * @covers ::get_by()
	 */
	public function test_get_by_with_invalid_column_should_return_WP_Error() {
		$this->assertWPError( self::$db->get_by( 'foo', 1 ) );
	}

	/**
	 * @covers ::get_by()
	 */
	public function test_get_by_with_invalid_column_should_return_WP_Error_including_code_invalid_column() {
		$result = self::$db->get_by( 'foo', 1 );

		$this->assertContains( 'invalid_column', $result->get_error_codes() );
	}

	/**
	 * @covers ::get_by()
	 */
	public function test_get_by_success_should_return_an_stdClass_object() {
		$contest_id = $this->factory->contest->create( array(
			'name'   => 'Foo Name',
		) );

		$contests = new Contests\Database;

		/*
		 * Instantiate a mocked version of Contests\Database with a fake table name
		 * to trigger the delete failure.
		 */
		$db = self::get_db( array(
			'cache_group'     => 'contests',
			'object_type'     => $contests->get_query_object_type(),
			'table_suffix'    => $contests->get_table_suffix(),
			'columns'         => $contests->get_columns(),
			'column_defaults' => $contests->get_column_defaults(),
		) );

		$result = $db->get_by( 'name', 'Foo Name' );

		$this->assertInstanceOf( 'stdClass', $result );
	}

	/**
	 * @covers ::get_column()
	 */
	public function test_get_column_with_invalid_column_should_return_a_WP_Error() {
		$this->assertWPError( self::$db->get_column( 'foo', 1 ) );
	}

	/**
	 * @covers ::get_column()
	 */
	public function test_get_column_with_invalid_column_should_return_a_WP_Error_including_code_invalid_column() {
		$result = self::$db->get_column( 'foo', 1 );

		$this->assertContains( 'invalid_column', $result->get_error_codes() );
	}

	/**
	 * @covers ::get_column()
	 */
	public function test_get_column_with_an_invalid_object_id_type_should_return_a_WP_Error() {
		$db = self::get_db( array(
			'columns' => array( 'foo' => '%s' ),
		) );

		$this->assertWPError( $db->get_column( 'foo', 'bar' ) );
	}

	/**
	 * @covers ::get_column()
	 */
	public function test_get_column_with_an_invalid_object_id_type_should_return_a_WP_Error_including_code_missing_object_id() {
		$db = self::get_db( array(
			'columns' => array( 'foo' => '%s' ),
		) );

		$result = $db->get_column( 'foo', 'bar' );

		$this->assertContains( 'missing_object_id', $result->get_error_codes() );
	}

	/**
	 * @covers ::get_column()
	 */
	public function test_get_column_with_an_invalid_object_id_should_return_a_WP_Error() {
		$db = self::get_db( array(
			'columns' => array( 'foo' => '%s' ),
		) );

		$this->assertWPError( $db->get_column( 'foo', 0 ) );
	}

	/**
	 * @covers ::get_column()
	 */
	public function test_get_column_with_an_invalid_object_id_should_return_a_WP_Error_including_code_missing_object_id() {
		$db = self::get_db( array(
			'columns' => array( 'foo' => '%s' ),
		) );

		$result = $db->get_column( 'foo', 0 );

		$this->assertContains( 'missing_object_id', $result->get_error_codes() );
	}

	/**
	 * Builds a "mock" abstract Core\Database object.
	 *
	 * If needed, setting up abstract methods with return values from an actual
	 * subclass allows the CRUD methods to be tested with real fixture data. Boom.
	 *
	 * @since 1.0.2
	 *
	 * @see Testable_Abstract
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

		// $overrides passed through the constructor to Testable_Abstract::set_overrides().
		$db_object = new class( $overrides ) extends Database {

			public function get_cache_group() { return $this->get_override( 'cache_group' ); }

			public function get_query_object_type() { return $this->get_override( 'object_type' ); }

			public function get_table_suffix() { return $this->get_override( 'table_suffix' ); }

			public function get_version() { return $this->get_override( 'version' ); }

			public function get_columns() { return $this->get_override( 'columns' ); }

			public function get_column_defaults() { return $this->get_override( 'column_defaults' ); }

			public function query( $query_args = array(), $count = false ) { return true === $count ? $this->get_override( 'query_count' ) : $this->get_override( 'query_results' ); }
		};

		return $db_object;
	}
}

