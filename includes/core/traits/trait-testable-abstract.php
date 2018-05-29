<?php
/**
 * Sets up abstract method overrides for testing abstract classes
 *
 * @package   Ensemble\Core\Traits
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Traits;

/**
 * Core trait used for making abstract classes testable.
 *
 * @since 1.0.2
 */
trait Testable_Abstract {

	/**
	 * Testing arguments for overrides.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	public $test_args = array();

	/**
	 * Sets up a property with argument overrides for use in unit testing abstract classes.
	 *
	 * @since 1.0.2
	 *
	 * @param array $args Various arguments for overriding stuff in unit testing.
	 */
	public function set_overrides( $args = array() ) {
		$this->test_args = $args;
	}

}
