<?php
namespace Ensemble\Components\Venues;

use Ensemble\Tests\UnitTestCase;

/**
 * Venues functions tests.
 *
 * @since 1.0.0
 */
class Functions_Tests extends UnitTestCase {

	/**
	 * @covers \Ensemble\Components\Venues\get_venue
	 */
	public function test_get_venue_with_invalid_id_should_return_a_WP_Error() {
		$this->assertWPError( get_venue( null ) );
	}

	/**
	 * @covers \Ensemble\Components\Venues\get_venue
	 */
	public function test_get_venue_with_invalid_id_should_return_a_WP_Error_including_code_get_instance_invalid_id() {
		$venue = get_venue( null );

		$this->assertContains( 'get_instance_invalid_id', $venue->get_error_codes() );
	}

	/**
	 * @covers \Ensemble\Components\Venues\get_venue
	 */
	public function test_get_venue_with_valid_venue_id_should_return_Model() {
		$venue_id = $this->factory->venue->create();

		$this->assertInstanceOf( '\\Ensemble\\Components\\Venues\\Model', get_venue( $venue_id ) );
	}


}

