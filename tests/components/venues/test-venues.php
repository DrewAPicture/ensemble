<?php
namespace Ensemble\Components\Venues;

use Ensemble\Tests\UnitTestCase;

/**
 * General venue component tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Components\Venues\Setup
 *
 * @group components
 * @group venues
 * @group setup
 */
class Tests extends UnitTestCase {

	/**
	 * Venue component setup fixture.
	 *
	 * @var \Ensemble\Components\Venues\Setup
	 */
	protected static $setup;

	/**
	 * User fixture.
	 *
	 * @var int User ID
	 */
	protected static $user_id;

	/**
	 * Venue fixtures.
	 *
	 * @var Model[] $venues
	 */
	protected static $venues;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		self::$setup   = new Setup();
		self::$user_id = self::ensemble()->user->create( array(
			'role' => 'administrator'
		) );

		// Create 2 venues.
		for ( $i = 0; $i <= 1; $i++ ) {
			self::$venues[] = self::ensemble()->venue->create();
		}
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_add_venue_cap_should_require_manage_venues() {
		$this->assertContains( 'manage_venues', self::$setup->map_meta_caps( array(), 'add_venue', 0, array() ) );
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_edit_venue_cap_and_invalid_venue_should_trigger_do_not_allow() {
		$mapped = self::$setup->map_meta_caps( array(), 'edit_venue', self::$user_id, array( 999 ) );

		$this->assertContains( 'do_not_allow', $mapped );
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_edit_venue_and_valid_venue_should_require_manage_venues() {
		$mapped = self::$setup->map_meta_caps( array(), 'edit_venue', self::$user_id, array( self::$venues[0] ) );

		$this->assertContains( 'manage_venues', $mapped );
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_delete_venue_cap_and_invalid_venue_should_trigger_do_not_allow() {
		$mapped = self::$setup->map_meta_caps( array(), 'delete_venue', self::$user_id, array( 999 ) );

		$this->assertContains( 'do_not_allow', $mapped );
	}

	/**
	 * @covers ::map_meta_caps()
	 */
	public function test_map_meta_caps_cb_with_delete_venue_and_valid_venue_should_require_manage_venues() {
		$mapped = self::$setup->map_meta_caps( array(), 'delete_venue', self::$user_id, array( self::$venues[0] ) );

		$this->assertContains( 'manage_venues', $mapped );
	}

}

