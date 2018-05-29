<?php
namespace Ensemble\Components\Classifications;

use Ensemble\Tests\UnitTestCase;

/**
 * General classifications component tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Components\Classifications\Setup
 *
 * @group components
 * @group classifications
 * @group taxonomy
 * @group setup
 */
class Tests extends UnitTestCase {

	/**
	 * Classifications component setup fixture.
	 *
	 * @var \Ensemble\Components\Classifications\Setup
	 */
	protected static $setup;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$setup = new Setup();
		self::$setup->load();
	}

	/**
	 * @covers \Ensemble\Components\Classifications\Setup
	 */
	public function test_Setup_should_use_the_Taxonomy_Component_trait() {
		$traits = class_uses( self::$setup );

		$this->arrayHasKey( 'Ensemble\\Core\\Traits\\Taxonomy_Component', $traits );
	}

	/**
	 * @covers ::get_taxonomy_slug()
	 */
	public function test_get_taxonomy_slug_should_return_taxonomy_slug() {
		$this->assertSame( 'ensemble_class', self::$setup->get_taxonomy_slug() );
	}

	/**
	 * @covers ::register_taxonomy()
	 */
	public function test_taxonomy_should_exist() {
		$this->assertTrue( taxonomy_exists( self::$setup->get_taxonomy_slug() ) );
	}

}

