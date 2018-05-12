<?php
namespace Ensemble\Tests;

/**
 * A factory for making WordPress data with a cross-object type API.
 *
 * Tests should use this factory to generate test fixtures.
 */
class Factory extends \WP_UnitTest_Factory {

	/**
	 * @var \AffWP\Tests\Factory\Contest
	 */
	public $contest;

	/**
	 * @var \AffWP\Tests\Factory\Staff
	 */
	public $staff;

	/**
	 * @var \AffWP\Tests\Factory\Team
	 */
	public $team;

	/**
	 * @var \AffWP\Tests\Factory\Venue
	 */
	public $venue;

	function __construct() {
		parent::__construct();

		$this->contest = new Factory\Contest( $this );
		$this->staff   = new Factory\Staff( $this );
		$this->team    = new Factory\Team( $this );
		$this->venue   = new Factory\Venue( $this );
	}
}
