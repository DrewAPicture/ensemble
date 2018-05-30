<?php
namespace Ensemble\Core\Admin;

use Ensemble\Tests\UnitTestCase;

/**
 * Core admin notices registry tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Core\Admin\Notices_Registry
 *
 * @group core
 * @group notices
 * @group registry
 */
class Notices_Registry_Tests extends UnitTestCase {

	/**
	 * Notices registry fixture.
	 *
	 * @var \Ensemble\Core\Admin\Notices_Registry
	 */
	protected static $registry;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$registry = Notices_Registry::instance();
	}

	/**
	 * Runs after each test to reset the items array.
	 *
	 * @access public
	 */
	public function tearDown() {
		self::$registry->exchangeArray( array() );

		parent::tearDown();
	}

	/**
	 * @covers ::init()
	 */
	public function test_init_should_fire_the_ensemble_notices_registry_init_hook() {
		self::$registry->init();

		$this->assertTrue( did_action( 'ensemble_notices_registry_init' ) > 0 );
	}

	/**
	 * @covers ::register_notice()
	 */
	public function test_register_notice_success_should_register_notice() {
		$expected = array(
			'message' => 'Foo',
			'type'    => 'warning',
		);

		self::$registry->register_notice( 'test_notice_1', $expected );

		$notice = self::$registry->get( 'test_notice_1' );

		$this->assertEqualSetsWithIndex( $expected, $notice );
	}

	/**
	 * @covers ::register_notice()
	 */
	public function test_register_notice_with_defaults_should_register_notice_with_empty_message() {
		self::$registry->register_notice( 'test_notice_1', array() );

		$notice = self::$registry->get( 'test_notice_1' );

		$this->assertSame( '', $notice['message'] );
	}

	/**
	 * @covers ::register_notice()
	 */
	public function test_register_notice_with_defaults_should_register_notice_with_success_type() {
		self::$registry->register_notice( 'test_notice_1', array() );

		$notice = self::$registry->get( 'test_notice_1' );

		$this->assertSame( 'success', $notice['type'] );
	}

	/**
	 * @covers ::register_notice()
	 */
	public function test_register_notice_with_invalid_type_should_default_to_success() {
		self::$registry->register_notice( 'test_notice_1', array(
			'type' => 'fake',
		) );

		$notice = self::$registry->get( 'test_notice_1' );

		$this->assertSame( 'success', $notice['type'] );
	}

	/**
	 * @dataProvider _test_register_notice_types_dp
	 * @covers ::register_notice()
	 */
	public function test_register_notice_should_accept_allowed_types( $type ) {
		$expected = array(
			'message' => 'Foo',
			'type'    => $type,
		);

		self::$registry->register_notice( 'test_notice_1', $expected );

		$notice = self::$registry->get( 'test_notice_1' );

		$this->assertEqualSetsWithIndex( $expected, $notice );
	}

	/**
	 * Data provider for test_register_notice_should_accept_allowed_types.
	 */
	public function _test_register_notice_types_dp() {
		return array(
			array( 'success' ),
			array( 'error' ),
			array( 'info' ),
			array( 'warning' ),
		);
	}

}

