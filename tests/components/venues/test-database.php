<?php
namespace Ensemble\Components\Venues;

use Ensemble\Tests\UnitTestCase;
use Ensemble\Util\Date;

/**
 * Venues database tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Components\Venues\Database
 *
 * @group components
 * @group venues
 * @group database
 */
class Database_Tests extends UnitTestCase {

	/**
	 * Venue database fixture.
	 *
	 * @var \Ensemble\Components\Venues\Database
	 */
	protected static $db;

	/**
	 * Venue models fixture.
	 *
	 * @var int[]
	 */
	protected static $venues;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$db = ( new Database );

		self::$venues = self::ensemble()->venue->create_many( 2 );
	}

	/**
	 * @covers ::get_cache_group()
	 */
	public function test_get_cache_group_should_return_venues() {
		$this->assertSame( 'venues', self::$db->get_cache_group() );
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
	public function test_get_table_suffix_should_return_ensemble_venues() {
		$this->assertSame( 'ensemble_venues', self::$db->get_table_suffix() );
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
			'id'         => '%d',
			'name'       => '%s',
			'address'    => '%s',
			'type'       => '%s',
			'status'     => '%s',
			'date_added' => '%s',
		);

		$this->assertEqualSetsWithIndex( $expected, self::$db->get_columns() );
	}

	/**
	 * @covers ::get_column_defaults()
	 */
	public function test_get_column_defaults_should_return_column_defaults() {
		$expected = array(
			'type'       => 'school',
			'status'     => 'active',
			'date_added' =>  Date::UTC( 'Y-m-d H:i' ),
		);

		$actual = self::$db->get_column_defaults();

		$actual['date_added'] = $this->strip_seconds_from_date( $actual['date_added'] );

		$this->assertEqualSetsWithIndex( $expected, $actual );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_count_true_should_return_only_a_count() {
		$result = self::$db->query( array(), true );

		$this->assertTrue( is_numeric( $result ) );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_defaults_should_return_no_greater_than_20_results() {
		$results = self::$db->query();
		$count   = count( $results );


		$this->assertTrue( $count > 0 && $count <= 20 );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_single_invalid_id_should_not_affect_results() {
		$results = self::$db->query( array(
			'fields' => 'ids',
			'id'     => 9999
		) );

		$this->assertEqualSets( self::$venues, $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_single_valid_id_should_only_return_that_venue() {
		$results = self::$db->query( array(
			'fields' => 'ids',
			'id'     => self::$venues[0],
		) );

		$this->assertEqualSets( array( self::$venues[0] ), $results );
	}

	/**
	 * @covers ::query()
	 * @group query
	 */
	public function test_query_with_multiple_valid_ids_should_only_return_those_venues() {
		$venues = $this->factory->venue->create_many( 2 );

		$results = self::$db->query( array(
			'fields' => 'ids',
			'id'     => $venues,
		) );

		$this->assertEqualSets( $venues, $results );
	}

}

