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

	/**
	 * @covers \Ensemble\Components\Venues\get_allowed_statuses
	 */
	public function test_get_allowed_statuses_should_return_the_default_statuses_and_labels() {
		$exepcted = array(
			'active'   => __( 'Active', 'ensemble' ),
			'inactive' => __( 'Inactive', 'ensemble' ),
		);

		$this->assertEqualSetsWithIndex( $exepcted, get_allowed_statuses() );
	}

	/**
	 * @covers \Ensemble\Components\Venues\get_status_label
	 */
	public function test_get_status_label_with_invalid_status_should_return_the_inactive_status_label() {
		$this->assertSame( 'Inactive', get_status_label( 'foo' ) );
	}

	/**
	 * @covers \Ensemble\Components\Venues\get_status_label
	 */
	public function test_get_status_label_with_valid_status_should_return_that_label() {
		$this->assertSame( 'Active', get_status_label( 'active' ) );
	}

	/**
	 * @covers \Ensemble\Components\Venues\get_allowed_types
	 */
	public function test_get_allowed_types_should_return_the_default_types_and_labels() {
		$expected = array(
			'school' => __( 'School', 'ensemble' ),
			'church' => __( 'Church', 'ensemble' ),
			'center' => __( 'Community Center', 'ensemble' ),
			'arena'  => __( 'Arena', 'ensemble' ),
			'other'  => __( 'Other', 'ensemble' ),
		);

		$this->assertEqualSetsWithIndex( $expected, get_allowed_types() );
	}

	/**
	 * @covers \Ensemble\Components\Venues\get_type_label
	 */
	public function test_get_type_label_with_invalid_type_should_return_the_school_label() {
		$this->assertSame( 'School', get_type_label( 'foo' ) );
	}

	/**
	 * @covers \Ensemble\Components\Venues\get_type_label
	 */
	public function test_get_type_label_with_valid_type_should_return_that_label() {
		$this->assertSame( 'Community Center', get_type_label( 'center' ) );
	}

}

