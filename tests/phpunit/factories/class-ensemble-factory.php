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
	 * @var \AffWP\Tests\Factory\Venue
	 */
	public $venue;

	/**
	 * Sets up the factory.
	 */
	function __construct() {
		parent::__construct();

		$this->load_factories();
		$this->setup_handlers();
	}

	/**
	 * Loads custom factory files.
	 */
	public function load_factories() {
		require_once __DIR__ . '/class-ensemble-factory-for-contests.php';
		require_once __DIR__ . '/class-ensemble-factory-for-venues.php';

	}

	/**
	 * Sets up the custom factory instances.
	 */
	public function setup_handlers() {
		$this->contest = new Factory\Contest( $this );
		$this->venue   = new Factory\Venue( $this );
	}

}
