<?php
namespace Ensemble\Components\Seasons;

use Ensemble\Tests\UnitTestCase;

/**
 * Seasons component functions tests.
 *
 * @since 1.0.0
 *
 * @group seasons
 * @group taxonomy
 * @group functions
 */
class Functions_Tests extends UnitTestCase {

	/**
	 * @covers \Ensemble\Components\Seasons\get_season
	 */
	public function test_get_season_with_invalid_id_should_return_WP_Error() {
		$this->assertWPError( get_season( null ) );
	}

	/**
	 * @covers \Ensemble\Components\Seasons\get_season
	 */
	public function test_get_season_with_invalid_id_should_return_WP_Error_including_code_invalid_term() {
		$season = get_season( null );

		$this->assertContains( 'invalid_term', $season->get_error_codes() );
	}

	/**
	 * @covers \Ensemble\Components\Seasons\get_season
	 */
	public function test_get_season_with_valid_id_should_return_WP_Term() {
		$season_id = $this->factory->term->create( array(
			'taxonomy' => ( new Setup )->get_taxonomy_slug(),
		) );

		$this->assertInstanceOf( '\\WP_Term', get_season( $season_id ) );
	}

}

