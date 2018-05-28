<?php
namespace Ensemble\Components\Contests;

use Ensemble\Tests\UnitTestCase;
use Ensemble\Util\Date;

/**
 * Contests database tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Components\Contests\Database
 *
 * @group components
 * @group contests
 * @group database
 */
class Database_Tests extends UnitTestCase {

	/**
	 * Contest database fixture.
	 *
	 * @var \Ensemble\Components\Contests\Database
	 */
	protected static $db;

	/**
	 * Contest models fixture.
	 *
	 * @var int[]
	 */
	protected static $contests;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$db = ( new Database );

		self::$contests = self::ensemble()->contest->create_many( 2 );
	}

	/**
	 * @covers ::get_cache_group()
	 */
	public function test_get_cache_group_should_return_contests() {
		$this->assertSame( 'contests', self::$db->get_cache_group() );
	}

	/**
	 * @covers ::get_query_object_type()
	 */
	public function test_get_query_object_type_should_return_the_fully_qualified_model_class() {
		$this->assertSame( __NAMESPACE__ . '\\Model', self::$db->get_query_object_type() );
	}

	/**
	 * @covers ::get_table_suffix()
	 */
	public function test_get_table_suffix_should_return_ensemble_contests() {
		$this->assertSame( 'ensemble_contests', self::$db->get_table_suffix() );
	}

	/**
	 * @covers ::get_version()
	 */
	public function test_get_version_should_return_the_current_db_version() {
		$this->assertSame( '1.0', self::$db->get_version() );
	}

	/**
	 * @covers ::get_columns()
	 */
	public function test_get_columns_should_return_columns_and_types() {
		$expected = array(
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

		$this->assertEqualSetsWithIndex( $expected, self::$db->get_columns() );
	}

	/**
	 * @covers ::get_column_defaults()
	 */
	public function test_get_column_defaults_should_return_column_defaults() {
		$expected = array(
			'type'       => 'standard',
			'status'     => 'published',
			'timezone'   => Date::get_wp_timezone(),
			'start_date' => Date::UTC( 'Y-m-d H:i' ),
		);

		$actual = self::$db->get_column_defaults();

		$actual['start_date'] = $this->strip_seconds_from_date( $actual['start_date'] );

		$this->assertEqualSetsWithIndex( $expected, $actual );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_default_args_should_return_up_to_20_results() {
		$results = self::$db->query();
		$count   = count( $results );

		$this->assertTrue( $count <= 20 && $count >= 1 );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_default_args_should_orderby_id() {
		$results = self::$db->query( array(
			'fields' => 'ids',
		) );

		$this->assertEqualSets( self::$contests, $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_default_args_should_order_descending() {
		$results = self::$db->query( array(
			'fields' => 'ids',
		) );

		$this->assertEqualSets( self::$contests, $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_valid_id_should_return_only_that_record() {
		$results = self::$db->query( array(
			'fields' => 'ids',
			'id'     => self::$contests[0]
		) );

		$this->assertEqualSets( array( self::$contests[0] ), $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_multiple_valid_ds_should_return_only_those_results_inside_number_constraint() {
		$results = self::$db->query( array(
			'fields' => 'ids',
			'id'     => self::$contests
		) );

		$this->assertEqualSets( self::$contests, $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_name_should_return_results_with_exact_matches_only() {
		$contest = $this->factory->contest->create( array(
			'name' => 'FooContest',
		) );

		$results = self::$db->query( array(
			'fields' => 'name',
			'name'   => 'FooContest'
		) );

		$this->assertEqualSets( array( 'FooContest' ), $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_invalid_type_should_not_take_the_type_into_account() {
		$results = self::$db->query( array(
			'fields' => 'type',
			'type'   => 'foo',
		) );

		$this->assertNotContains( 'foo', $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_single_valid_type_should_return_only_results_of_that_type() {
		$results = self::$db->query( array(
			'fields' => 'type',
			'type'   => 'standard',
		) );

		$this->assertEqualSets( array( 'standard' ), array_unique( $results ) );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_multiple_valid_types_should_return_only_results_of_those_types() {
		$contest = $this->factory->contest->create( array(
			'type' => 'preview',
		) );

		$results = self::$db->query( array(
			'fields' => 'type',
			'type'   => array( 'standard', 'preview' ),
		) );

		$this->assertEqualSets( array( 'standard', 'preview' ), array_unique( $results ) );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_invalid_status_should_not_take_the_status_into_account() {
		$results = self::$db->query( array(
			'fields' => 'status',
			'status' => 'foo',
		) );

		$this->assertNotContains( 'foo', $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_single_valid_status_should_return_only_results_with_that_status() {
		$contest = $this->factory->contest->create( array(
			'status' => 'private',
		) );

		$results = self::$db->query( array(
			'fields' => 'status',
			'status' => 'private',
		) );

		$this->assertEqualSets( array( 'private' ), array_unique( $results ) );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_multiple_valid_statuses_should_return_only_resuults_with_those_statuses() {
		$contest = $this->factory->contest->create( array(
			'status' => 'private',
		) );

		$results = self::$db->query( array(
			'fields' => 'status',
			'status' => array( 'published', 'private' ),
		) );

		$this->assertEqualSets( array( 'published', 'private' ), array_unique( $results ) );
	}

}

