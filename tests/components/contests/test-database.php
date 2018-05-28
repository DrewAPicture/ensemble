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

}

