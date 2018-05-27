<?php
namespace Ensemble\Components\Contests;

use Ensemble\Tests\UnitTestCase;

/**
 * General contests component tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Components\Contests\Setup
 *
 * @group components
 * @group contests
 * @group setup
 */
class Tests extends UnitTestCase {

	/**
	 * Contest component setup fixture.
	 *
	 * @var \Ensemble\Components\Contests\Setup
	 */
	protected static $setUp;

	/**
	 * User fixture.
	 *
	 * @var int User ID
	 */
	protected static $user_id;

	/**
	 * Contest fixtures.
	 *
	 * @var Model[] $contests
	 */
	protected static $contests;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$setUp   = new Setup();
		self::$user_id = self::ensemble()->user->create( array(
			'role' => 'administrator'
		) );

		// Create 2 contests.
		for ( $i = 0; $i <= 1; $i++ ) {
			self::$contests[] = self::ensemble()->contest->create();
		}
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_add_contest_cap_should_require_manage_contests() {
		$this->assertContains( 'manage_contests', self::$setUp->map_meta_caps( array(), 'add_contest', 0, array() ) );
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_edit_contest_cap_and_invalid_contest_should_trigger_do_not_allow() {
		$mapped = self::$setUp->map_meta_caps( array(), 'edit_contest', self::$user_id, array( 999 ) );

		$this->assertContains( 'do_not_allow', $mapped );
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_edit_contest_and_valid_contest_should_require_manage_contests() {
		$mapped = self::$setUp->map_meta_caps( array(), 'edit_contest', self::$user_id, array( self::$contests[0] ) );

		$this->assertContains( 'manage_contests', $mapped );
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_delete_contest_cap_and_invalid_contest_should_trigger_do_not_allow() {
		$mapped = self::$setUp->map_meta_caps( array(), 'delete_contest', self::$user_id, array( 999 ) );

		$this->assertContains( 'do_not_allow', $mapped );
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_delete_contest_and_valid_contest_should_require_manage_contests() {
		$mapped = self::$setUp->map_meta_caps( array(), 'delete_contest', self::$user_id, array( self::$contests[0] ) );

		$this->assertContains( 'manage_contests', $mapped );
	}

}

