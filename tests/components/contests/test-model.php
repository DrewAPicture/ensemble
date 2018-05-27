<?php
namespace Ensemble\Components\Contests;

use Ensemble\Tests\UnitTestCase;
use Ensemble\Util\Date;

/**
 * Contests model tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Components\Contests\Model
 *
 * @group components
 * @group contests
 * @group models
 */
class Model_Tests extends UnitTestCase {

	/**
	 * Contest model fixture.
	 *
	 * @var \Ensemble\Components\Contests\Model
	 */
	protected static $contest;

	/**
	 * Contest model fixture with end_date set.
	 *
	 * @var \Ensemble\Components\Contests\Model
	 */
	protected static $contest_with_end_date;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$contest = self::ensemble()->contest->create_and_get();

		self::$contest_with_end_date = self::ensemble()->contest->create_and_get( array(
			'end_date' => date( 'Y-m-d H:i:s', time() + MONTH_IN_SECONDS ),
		) );
	}

	/**
	 * @covers \Ensemble\Components\Contests\Model
	 */
	public function test_contest_model_object_vars() {
		$expected = array(
			'id', 'name', 'description', 'venues', 'type',
			'external', 'status', 'timezone', 'start_date',
			'end_date'
		);

		$this->assertEqualSets( $expected, array_keys( get_object_vars( self::$contest ) ) );
	}

	/**
	 * @covers ::db()
	 */
	public function test_db_should_return_a_Database_instance() {
		$this->assertInstanceOf( '\\Ensemble\\Components\\Contests\\Database', self::$contest->db() );
	}

	/**
	 * @covers ::get_start_date()
	 */
	public function test_get_start_date_should_return_localized_time_string() {
		$expected = Date::UTC_to_WP( self::$contest->start_date, 'm/d/Y' );

		$this->assertSame( $expected, self::$contest->get_start_date() );
	}

	/**
	 * @covers ::get_start_date()
	 */
	public function test_get_start_date_with_non_default_format_should_return_localized_time_string_in_that_format() {
		$expected = Date::UTC_to_WP( self::$contest->start_date, 'Y-m-d H:i:s' );

		$this->assertSame( $expected, self::$contest->get_start_date( 'Y-m-d H:i:s' ) );
	}

	/**
	 * @covers ::get_end_date()
	 */
	public function test_get_end_date_should_return_empty_string_if_not_set() {
		$this->assertSame( '', self::$contest->get_end_date() );
	}

	/**
	 * @covers ::get_end_date()
	 */
	public function test_get_end_date_should_return_localized_time_string_if_set() {
		$expected = Date::UTC_to_WP( self::$contest_with_end_date->start_date, 'm/d/Y' );

		$this->assertSame( $expected, self::$contest_with_end_date->get_start_date() );

	}

	/**
	 * @covers ::get_end_date()
	 */
	public function test_get_end_date_with_non_default_format_should_return_localized_time_string_in_that_format() {
		$expected = Date::UTC_to_WP( self::$contest_with_end_date->end_date, 'Y-m-d H:i:s' );

		$this->assertSame( $expected, self::$contest_with_end_date->get_end_date( 'Y-m-d H:i:s' ) );
	}

}

