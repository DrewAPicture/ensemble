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
	public function test_query_with_count_true_should_return_only_a_count() {
		$result = self::$db->query( array(), true );

		$this->assertSame( 2, $result );
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
	public function test_query_with_multiple_valid_ids_should_return_only_those_results_inside_number_constraint() {
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

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_single_invalid_exclude_id_should_not_affect_results() {
		$results = self::$db->query( array(
			'fields'  => 'ids',
			'exclude' => 9999
		) );

		$this->assertEqualSets( self::$contests, $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_single_valid_exclude_id_should_exclude_that_id() {
		$results = self::$db->query( array(
			'fields'  => 'ids',
			'exclude' => self::$contests[1],
		) );

		$this->assertEqualSets( array( self::$contests[0] ), $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_multiple_valid_exclude_ids_should_exclude_those_ids() {
		$contests = $this->factory->contest->create_many( 2 );

		$results = self::$db->query( array(
			'fields' => 'ids',
			'exclude' => $contests,
		) );

		// Does not contain the newly-created and excluded contests.
		$this->assertEqualSets( self::$contests, $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_non_empty_non_bool_external_should_not_affect_results() {
		$results = self::$db->query( array(
			'fields'   => 'ids',
			'external' => 'foo',
		) );

		$this->assertEqualSets( self::$contests, $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_external_true_should_only_retrieve_contests_with_external_urls() {
		$contest = $this->factory->contest->create( array(
			'external' => WP_TESTS_DOMAIN,
		) );

		$results = self::$db->query( array(
			'fields'   => 'ids',
			'external' => true,
		) );

		$this->assertEqualSets( array( $contest ), $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_external_false_should_only_retrieve_contests_with_no_external_urls() {
		$contest = $this->factory->contest->create( array(
			'external' => WP_TESTS_DOMAIN,
		) );

		$results = self::$db->query( array(
			'fields'   => 'ids',
			'external' => false,
		) );

		// Does not contain the newly-created contest with an external URL.
		$this->assertNotContains( $contest, $results );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_success_should_return_newly_inserted_contest_id() {
		$result = self::$db->insert( array(
			'name'   => 'Foo',
			'venues' => array( 1, 2 ),
		) );

		$this->assertTrue( is_numeric( $result ) );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_successful_should_not_return_a_WP_Error() {
		$result = self::$db->insert( array(
			'name'   => 'Foo',
			'venues' => array( 1, 2 ),
		) );

		$this->assertNotWPError( $result );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_with_missing_name_should_return_WP_Error() {
		$result = self::$db->insert( array(
			'venues' => array( 1, 2 ),
		) );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_with_missing_name_should_return_WP_Error_including_code_missing_contest_name() {
		$result = self::$db->insert( array(
			'venues' => array( 1, 2 ),
		) );

		$this->assertContains( 'missing_contest_name', $result->get_error_codes() );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_with_missing_venues_should_return_WP_Error() {
		$result = self::$db->insert( array(
			'name' => 'Foo',
		) );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_with_missing_venues_should_return_WP_Error_including_code_missing_contest_venues() {
		$result = self::$db->insert( array(
			'name' => 'Foo',
		) );

		$this->assertContains( 'missing_contest_venues', $result->get_error_codes() );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_with_start_date_should_convert_date_from_WP_to_UTC() {
		$start_date = date( 'Y-m-d H:i' );

		$expected = $this->strip_seconds_from_date( Date::WP_to_UTC( $start_date ) );

		$result = self::$db->insert( array(
			'name'       => 'Foo',
			'venues'     => array( 1 ),
			'start_date' => $start_date,
		) );

		$stored_date = $this->strip_seconds_from_date( get_contest( $result )->start_date );

		$this->assertSame( $expected, $stored_date );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_with_end_date_should_convert_date_from_WP_to_UTC() {
		$end_date = date( 'Y-m-d H:i' );

		$expected = $this->strip_seconds_from_date( Date::WP_to_UTC( $end_date ) );

		$result = self::$db->insert( array(
			'name'     => 'Foo',
			'venues'   => array( 1 ),
			'end_date' => $end_date,
		) );

		$stored_date = $this->strip_seconds_from_date( get_contest( $result )->end_date );

		$this->assertSame( $expected, $stored_date );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_with_no_end_date_should_store_zeroed_time() {
		$result = self::$db->insert( array(
			'name'   => 'Foo',
			'venues' => array( 1 ),
		) );

		$this->assertSame( '0000-00-00 00:00:00', get_contest( $result )->end_date );
	}

	/**
	 * @covers ::insert()
	 */
	public function test_insert_failure_should_return_a_WP_Error() {
		$result = self::$db->insert( array() );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_invalid_contest_id_should_return_WP_Error() {
		$result = self::$db->update( 9999 );

		$this->assertWPError( $result );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_invalid_contest_id_should_return_WP_Error_including_code_invalid_object() {
		$result = self::$db->update( 9999 );

		$this->assertContains( 'invalid_object', $result->get_error_codes() );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_successful_should_return_true() {
		$this->assertTrue( self::$db->update( self::$contests[0] ) );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_new_venues_should_update_venues() {
		$contest = $this->factory->contest->create();
		$venues  = array( 5, 4, 3 );

		self::$db->update( $contest, array(
			'venues' => $venues,
		) );

		$expected = implode( ',', $venues );
		$stored   = get_contest( $contest )->venues;

		$this->assertSame( $expected, $stored );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_new_start_date_should_convert_it_from_WP_to_UTC() {
		$contest = $this->factory->contest->create();

		$start_date = date( 'Y-m-d H:i:s', time() - WEEK_IN_SECONDS );

		self::$db->update( $contest, array(
			'start_date' => $start_date,
		) );

		$expected    = $this->strip_seconds_from_date( Date::WP_to_UTC( $start_date ) );
		$stored_date = $this->strip_seconds_from_date( get_contest( $contest )->start_date );

		$this->assertSame( $expected, $stored_date );
	}

	/**
	 * @covers ::update()
	 */
	public function test_update_with_new_end_date_should_convert_it_from_WP_to_UTC() {
		$contest = $this->factory->contest->create();

		$end_date = date( 'Y-m-d H:i:s', time() + WEEK_IN_SECONDS );

		self::$db->update( $contest, array(
			'end_date' => $end_date,
		) );

		$expected    = $this->strip_seconds_from_date( Date::WP_to_UTC( $end_date ) );
		$stored_date = $this->strip_seconds_from_date( get_contest( $contest )->end_date );

		$this->assertSame( $expected, $stored_date );
	}

}

