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
		$this->assertWPError( get_contest( null ) );
	}

	/**
	 * @covers \Ensemble\Components\Contests\get_contest
	 */
	public function test_get_contest_with_invalid_id_should_return_WP_Error_including_code_get_instance_invalid_id() {
		$contest = get_contest( null );

		$this->assertContains( 'get_instance_invalid_id', $contest->get_error_codes() );
	}

	/**
	 * @covers \Ensemble\Components\Contests\get_contest
	 */
	public function test_get_contest_with_valid_id_should_return_Model() {
		$contest_id = $this->factory->contest->create();

		$this->assertInstanceOf( '\\Ensemble\\Components\\Contests\\Model', get_contest( $contest_id ) );
	}

	/**
	 * @covers \Ensemble\Components\Contests\get_allowed_statuses
	 */
	public function test_get_allowed_statuses_should_return_default_statuses_and_labels() {
		$expected = array(
			'private'   => __( 'Private', 'ensemble' ),
			'published' => __( 'Published', 'ensemble' ),
		);

		$this->assertEqualSetsWithIndex( $expected, get_allowed_statuses() );
	}

	/**
	 * @covers \Ensemble\Components\Contests\get_status_label
	 */
	public function test_get_status_label_with_invalid_status_should_return_the_private_label() {
		$this->assertSame( 'Private', get_status_label( 'foo' ) );
	}

	/**
	 * @covers \Ensemble\Components\Contests\get_status_label
	 */
	public function test_get_status_label_with_valid_status_should_return_the_corresponding_label() {
		$this->assertSame( 'Published', get_status_label( 'published' ) );
	}

	/**
	 * @covers \Ensemble\Components\Contests\get_allowed_types
	 */
	public function test_get_allowed_types_should_return_default_types_and_labels() {
		$expected = array(
			'preview'  => __( 'Preview Clinic', 'ensemble' ),
			'standard' => __( 'Standard', 'ensemble' ),
			'special'  => __( 'Special Event', 'ensemble' ),
			'state'    => __( 'State Championships', 'ensemble' ),
			'camp'     => __( 'Summer Camp', 'ensemble' ),
		);

		$this->assertEqualSetsWithIndex( $expected, get_allowed_types() );
	}

	/**
	 * @covers \Ensemble\Components\Contests\get_type_label
	 */
	public function test_get_type_label_with_invalid_type_should_return_the_standard_label() {
		$this->assertSame( 'Standard', get_type_label( 'foo' ) );
	}

	/**
	 * @covers \Ensemble\Components\Contests\get_type_label
	 */
	public function test_get_type_label_with_valid_type_should_return_the_corresponding_label() {
		$this->assertSame( 'Summer Camp', get_type_label( 'camp' ) );
	}
}

