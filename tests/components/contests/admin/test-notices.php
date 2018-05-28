<?php
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Tests\UnitTestCase;

/**
 * Contests admin notices tests.
 *
 * @since 1.0.0
 *
 * @coversDefaultClass \Ensemble\Components\Contests\Admin\Notices
 *
 * @group contests
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
			array( 'notice-contest-added', 'The contest was successfully created.', 'success' ),
			array( 'notice-contest-added-error', 'There was an error adding the contest. Please try again.', 'warning' ),
			array( 'notice-contest-forbidden', 'Sorry, you are not allowed to do that.', 'info' ),
			array( 'notice-contest-updated', 'The contest was successfully updated.', 'success' ),
			array( 'notice-contest-updated-error', 'The contest could not be updated. Please try again.', 'warning' ),
			array( 'notice-contest-deleted', 'The contest was successfully deleted.', 'success' ),
			array( 'notice-contest-deleted-error', 'The contest could not be deleted. Please try again.', 'warning' ),
			array( 'notice-contest-deleted-no-change', 'No changes were made to the contest.', 'success' ),
		);
	}

	/**
	 * @covers \Ensemble\Components\Contests\Admin\Notices
	 */
	public function test_Notices_should_use_the_Admin_Notices_trait() {
		$traits = class_uses( new Notices );

		$this->assertArrayHasKey( 'Ensemble\\Core\\Traits\\Admin_Notices', $traits );
	}

}

