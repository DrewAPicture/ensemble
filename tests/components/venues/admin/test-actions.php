<?php
namespace Ensemble\Components\Venues\Admin;

use Ensemble\Tests\UnitTestCase;

/**
 * Venues admin actions tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Components\Venues\Admin\Actions
 *
 * @group venues
 * @group admin
 * @group admin-views
 */
class Actions_Tests extends UnitTestCase {

	/**
	 * Actions fixture.
	 *
	 * @var \Ensemble\Components\Venues\Admin\Actions
	 */
	protected static $actions;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$actions = new Actions;
	}

	/**
	 * @covers ::get_views()
	 */
	public function test_get_views_should_return_registered_views() {
		$expected = array( 'overview', 'add', 'edit', 'delete' );

		$this->assertEqualSets( $expected, self::$actions->get_views() );
	}

	/**
	 * @covers ::get_views_dir()
	 */
	public function test_get_views_dir_should_return_the_absolute_path_to_the_views_dir() {
		$expected = ENSEMBLE_PLUGIN_DIR . 'includes/components/venues/admin/views/';

		$this->assertSame( $expected, self::$actions->get_views_dir() );
	}

	/**
	 * @covers \Ensemble\Components\Venues\Admin\Actions
	 */
	public function test_should_use_the_View_Loader_trait() {
		$traits = class_uses( self::$actions );

		$this->assertArrayHasKey( 'Ensemble\\Core\\Traits\\View_Loader', $traits );
	}

}

