<?php
namespace Ensemble\Core\Admin;

use Ensemble\Core\Traits\Admin_Notices;
use Ensemble\Tests\UnitTestCase;

/**
 * Core admin notices tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Core\Admin\Notices
 *
 * @group core
 * @group admin
 * @group admin-notices
 */
class Notices_Tests extends UnitTestCase {

	use Admin_Notices;

	/**
	 * Notices fixture.
	 *
	 * @var \Ensemble\Core\Admin\Notices
	 */
	protected static $notices;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$notices = new Notices;
	}

	/**
	 * Registers notice fixtures.
	 */
	public function register_notices() {
		$registry = $this->get_registry();

		$registry->register_notice( 'test_notice_1', array(
			'message' => 'Test Notice 1',
		) );
	}

	/**
	 * @covers ::build_notice()
	 */
	public function test_build_notice_with_invalid_notice_id_should_return_empty_string() {
		$this->assertSame( '', self::$notices->build_notice( 'foo' ) );
	}

	/**
	 * @covers ::build_notice()
	 */
	public function test_build_notice_with_valid_notice_id_should_return_HTML_markup_to_display_a_notice() {
		$this->register_notices();

		$expected = '<div id="test_notice_1-notice" class="notice notice-success is-dismissible"><p>Test Notice 1</p></div>';

		$result = self::$notices->build_notice( 'test_notice_1' );

		$this->assertSame( $expected, $result );
	}

}

