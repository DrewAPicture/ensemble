<?php
namespace Ensemble\Core;

use Ensemble\Tests\UnitTestCase;

/**
 * Core requests bootstrap tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Core\Requests
 *
 * @group core
 * @group requests
 */
class Requests_Tests extends UnitTestCase {

	/**
	 * Requests fixture.
	 *
	 * @var \Ensemble\Core\Requests
	 */
	protected static $requests;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$requests = new Requests;
	}

	/**
	 * @dataProvider _test_whitelist_query_vars_dp
	 * @covers ::whitelist_query_vars()
	 */
	public function test_whitelist_query_vars( $query_var ) {
		$result = self::$requests->whitelist_query_vars( array() );

		$this->assertContains( $query_var, $result );
	}

	/**
	 * Data provider for test_whitelist_query_vars.
	 */
	public function _test_whitelist_query_vars_dp() {
		return array(
			array( 'contest_id' ),
			array( 'venue_id' ),
		);
	}
}

