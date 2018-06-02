<?php
namespace Ensemble\Core;

use Ensemble\Tests\UnitTestCase;

/**
 * Core assets tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Core\Assets
 *
 * @group assets
 * @group core
 */
class Assets_Tests extends UnitTestCase {

	/**
	 * Assets fixture.
	 *
	 * @var \Ensemble\Core\Assets
	 */
	protected static $assets;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$assets = new Assets;
		self::$assets->admin_assets( 'page_ensemble-admin' );
	}

	/**
	 * @covers ::admin_assets()
	 *
	 * @dataProvider _test_admin_scripts_dp
	 */
	public function test_admin_scripts( $script, $script_is ) {
		$this->assertTrue( wp_script_is( $script, $script_is ) );
	}

	/**
	 * Data provider for test_admin_scripts().
	 */
	public function _test_admin_scripts_dp() {
		return array(
			array( 'ensbl-bootstrap', 'registered' ),
			array( 'ensbl-parsley', 'registered' ),
			array( 'ensbl-selectWoo', 'registered' ),
			array( 'ensbl-admin', 'registered' ),
		);
	}

	/**
	 * @covers ::admin_assets()
	 *
	 * @dataProvider _test_admin_styles_dp
	 */
	public function test_admin_styles( $style, $style_is ) {
		$this->assertTrue( wp_style_is( $style, $style_is ) );
	}

	/**
	 * Data provider for test_admin_styles().
	 */
	public function _test_admin_styles_dp() {
		return array(
			array( 'ensbl-jquery-ui-css', 'registered' ),
			array( 'ensbl-selectWoo-css', 'registered' ),
			array( 'ensbl-select2-bootstrap-css', 'registered' ),
			array( 'ensbl-bootstrap-css', 'registered' ),
			array( 'ensbl-admin-css', 'registered' ),
		);
	}

	/**
	 * @covers ::admin_assets()
	 *
	 * @dataProvider _test_special_admin_scripts
	 */
	public function test_special_admin_scripts( $script, $script_is ) {
		$this->assertTrue( wp_script_is( $script, $script_is ) );
	}

	/**
	 * Data provider for test_special_admin_scripts()
	 */
	public function _test_special_admin_scripts() {
		return array(
			array( 'ensbl-admin', 'enqueued' )
		);
	}

	/**
	 * @covers ::admin_assets()
	 *
	 * @dataProvider _test_special_admin_styles
	 */
	public function test_special_admin_styles( $style, $style_is ) {
		$this->assertTrue( wp_style_is( $style, $style_is ) );
	}

	/**
	 * Data provider for test_special_admin_styles()
	 */
	public function _test_special_admin_styles() {
		return array(
			array( 'ensbl-admin-css', 'enqueued' )
		);
	}

	/**
	 * @covers ::get_asset_version()
	 */
	public function test_get_asset_version_should_return_filemtime_for_the_file_path() {
		$expected = filemtime( ENSEMBLE_PLUGIN_DIR . 'ensemble.php' );
		$actual   = self::$assets->get_asset_version( 'ensemble.php' );

		$this->assertSame( $expected, $actual );
	}

}

