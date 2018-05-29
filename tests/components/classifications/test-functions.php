<?php
namespace Ensemble\Components\Classifications;

use Ensemble\Tests\UnitTestCase;

/**
 * Classifications component functions tests.
 *
 * @since 1.0.0
 *
 * @group classifications
 * @group taxonomy
 * @group functions
 */
class Functions_Tests extends UnitTestCase {

	/**
	 * @covers \Ensemble\Components\Classifications\get_classification
	 */
	public function test_get_classification_with_invalid_id_should_return_WP_Error() {
		$this->assertWPError( get_classification( null ) );
	}

	/**
	 * @covers \Ensemble\Components\Classifications\get_classification
	 */
	public function test_get_classification_with_invalid_id_should_return_WP_Error_including_code_invalid_term() {
		$classification = get_classification( null );

		$this->assertContains( 'invalid_term', $classification->get_error_codes() );
	}

	/**
	 * @covers \Ensemble\Components\Classifications\get_classification
	 */
	public function test_get_classification_with_valid_id_should_return_WP_Term() {
		$classification_id = $this->factory->term->create( array(
			'taxonomy' => ( new Setup )->get_taxonomy_slug(),
		) );

		$this->assertInstanceOf( '\\WP_Term', get_classification( $classification_id ) );
	}

}

