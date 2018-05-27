<?php
namespace Ensemble\Components\Contests;

use Ensemble\Tests\UnitTestCase;

/**
 * Contests functions tests.
 *
 * @since 1.0.0
 */
class Functions_Tests extends UnitTestCase {

	/**
	 * @covers \Ensemble\Components\Contests\get_contest
	 */
	public function test_get_contest_with_invalid_id_should_return_WP_Error() {
		$contest = get_contest( null );

		$this->assertWPError( $contest );
	}

	/**
	 * @covers \Ensemble\Components\Contests\get_contest
	 */
	public function test_get_contest_with_invalid_id_should_return_WP_Error_including_code_get_instance_invalid_id() {
		$contest = get_contest( null );

		$this->assertContains( 'get_instance_invalid_id', $contest->get_error_codes() );
	}

}

