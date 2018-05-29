<?php
namespace Ensemble\Components\Units;

use Ensemble\Tests\UnitTestCase;

/**
 * Units component functions tests.
 *
 * @since 1.0.0
 *
 * @group units
 * @group taxonomy
 * @group functions
 */
class Functions_Tests extends UnitTestCase {

	/**
	 * @covers \Ensemble\Components\Units\get_unit
	 */
	public function test_get_unit_with_invalid_id_should_return_WP_Error() {
		$this->assertWPError( get_unit( null ) );
	}

	/**
	 * @covers \Ensemble\Components\Units\get_unit
	 */
	public function test_get_unit_with_invalid_id_should_return_WP_Error_including_code_invalid_term() {
		$unit = get_unit( null );

		$this->assertContains( 'invalid_term', $unit->get_error_codes() );
	}

	/**
	 * @covers \Ensemble\Components\Units\get_unit
	 */
	public function test_get_unit_with_valid_id_should_return_WP_Term() {
		$unit_id = $this->factory->term->create( array(
			'taxonomy' => ( new Setup )->get_taxonomy_slug(),
		) );

		$this->assertInstanceOf( '\\WP_Term', get_unit( $unit_id ) );
	}

}

