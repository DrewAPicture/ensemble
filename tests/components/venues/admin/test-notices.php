<?php
namespace Ensemble\Components\Venues\Admin;

use Ensemble\Tests\UnitTestCase;

/**
 * Venues admin notices tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Components\Venues\Admin\Notices
 *
 * @group venues
 * @group admin
 * @group admin-notices
 */
class Notices_Tests extends UnitTestCase {

	/**
	 * Notices fixture.
	 *
	 * @var \Ensemble\Core\Admin\Notices_Registry
	 */
	protected static $registry;

	/**
	 * Sets up fixtures before the test class has loaded.
	 */
	public static function wpSetUpBeforeClass() {
		$notices = new Notices;
		$notices->load();

		self::$registry = $notices->get_registry();
	}

	/**
	 * @covers ::register_notices()
	 *
	 * @dataProvider _test_register_notices_dp
	 */
	public function test_register_notices( $notice_id, $message, $type ) {
		$notice = self::$registry->get( $notice_id );

		$expected = array(
			'message' => $message,
			'type'    => $type,
		);

		$this->assertEqualSetsWithIndex( $expected, $notice );
	}

	/**
	 * Data provider for test_register_notices().
	 *
	 * @return array
	 */
	public function _test_register_notices_dp() {
		return array(
			array( 'notice-venue-added', 'The venue was successfully created.', 'success' ),
			array( 'notice-venue-added-error', 'There was an error adding the venue. Please try again.', 'warning' ),
			array( 'notice-venue-forbidden', 'Sorry, you are not allowed to do that.', 'info' ),
			array( 'notice-venue-updated', 'The venue was successfully updated.', 'success' ),
			array( 'notice-venue-updated-error', 'The venue could not be updated. Please try again.', 'warning' ),
			array( 'notice-venue-deleted', 'The venue was successfully deleted.', 'success' ),
			array( 'notice-venue-deleted-error', 'The venue could not be deleted. Please try again.', 'warning' ),
		);
	}

	/**
	 * @covers \Ensemble\Components\Venues\Admin\Notices
	 */
	public function test_Notices_should_use_the_Admin_Notices_trait() {
		$traits = class_uses( new Notices );

		$this->assertArrayHasKey( 'Ensemble\\Core\\Traits\\Admin_Notices', $traits );
	}

}

