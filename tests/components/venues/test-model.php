<?php
namespace Ensemble\Components\Venues;

use Ensemble\Tests\UnitTestCase;
use Ensemble\Util\Date;

/**
 * Venues model tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Components\Venues\Model
 *
 * @group components
 * @group venues
 * @group models
 */
class Model_Tests extends UnitTestCase {

	/**
	 * Venue fixture
	 *
	 * @var \Ensemble\Components\Venues\Model
	 */
	protected static $venue;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$venue = self::ensemble()->venue->create_and_get();
	}

	/**
	 * @covers \Ensemble\Components\Venues\Model
	 */
	public function test_venue_model_object_vars() {
		$expected = array( 'id', 'name', 'address', 'status', 'type', 'date_added' );

		$this->assertEqualSets( $expected, array_keys( get_object_vars( self::$venue ) ) );
	}

	/**
	 * @covers ::db()
	 */
	public function test_db_should_return_a_Database_instance() {
		$this->assertInstanceOf( '\\Ensemble\\Components\\Venues\\Database', self::$venue->db() );
	}

	/**
	 * @covers ::get_date_added()
	 */
	public function test_get_date_added_with_default_format_should_return_date_in_mdY() {
		$expected = Date::UTC_to_WP( self::$venue->date_added, 'm/d/Y' );

		$this->assertSame( $expected, self::$venue->get_date_added() );
	}

	/**
	 * @covers ::get_date_added()
	 */
	public function test_get_date_added_with_nonstandard_format_should_return_date_in_that_format() {
		$expected = Date::UTC_to_WP( self::$venue->date_added, 'Y-m-d H:i' );

		$this->assertSame( $expected, self::$venue->get_date_added( 'Y-m-d H:i' ) );
	}

}

