<?php
namespace Ensemble\Components\Contests;

use Ensemble\Tests\UnitTestCase;

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

		self::$contests[] = self::ensemble()->contest->create_many( 2 );
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
	public function test_get_columns_should_return_colummns_and_types() {
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
	 * @covers ::get_columns()
	 *
	 * @dataProvider _get_columns_formats_dp
	 * @param string $column Column key
	 * @param string $format Format specifier
	 */
	public function test_get_columns_formats( $column, $format ) {
		$columns = self::$db->get_columns();

		$this->assertTrue( $columns[ $column ] === $format );
	}

	/**
	 * Data provider for test_get_columns_formats()
	 *
	 * @return array Data.
	 */
	public function _get_columns_formats_dp() {
		return array(
			array( 'id', '%d' ),
			array( 'name', '%s' ),
			array( 'description', '%s' ),
			array( 'venues', '%s' ),
			array( 'type', '%s' ),
			array( 'external', '%s' ),
			array( 'status', '%s' ),
			array( 'timezone', '%s' ),
			array( 'start_date', '%s' ),
			array( 'end_date', '%s' ),
		);
	}

}

