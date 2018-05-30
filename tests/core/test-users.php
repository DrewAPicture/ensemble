<?php
namespace Ensemble\Core;

use Ensemble\Tests\UnitTestCase;

/**
 * Core users bootstrap tests.
 *
 * @since 1.0.0
 */
class Users_Tests extends UnitTestCase {

	/**
	 * Users fixture.
	 *
	 * @var \Ensemble\Core\Users
	 */
	protected static $users;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$users = new Users;
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_manage_ensemble_cap_should_require_manage_options() {
		$result = self::$users->map_meta_caps( array(), 'manage_ensemble', 0, array() );

		$this->assertContains( 'manage_options', $result );
	}

}

