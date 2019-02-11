<?php
/**
 * Testable_Abstract trait used by core abstract classes
 *
 * @package   Ensemble\Core\Traits
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Traits;

/**
 * Core trait that sets up a registry for supplying externally-defined overrides
 * to tests for abstract class methods
 *
 * @since 1.1.0
 */
trait Testable_Abstract {

	/**
	 * Testing overrides.
	 *
	 * @since 1.1.0
	 * @var   array
	 */
	private $overrides = array();

	/**
	 * Sets up a property with overrides for use in unit testing abstract classes.
	 *
	 * This trait only sets up the registry of overrides. The onus is on test classes
	 * to instantiate anonymous classes extending classes using this trait to use the
	 * overridden values for abstract method overrides.
	 *
	 * Example:
	 *
	 *     $overrides = array(
	 *          'arg_name' => 'foo',
	 *     );
	 *
	 *     // Abstract class fixture.
	 *     $db = new class( $overrides ) extends Abstract_Class {
	 *         public function sample_method_name() { return $this->get_override( 'arg_name' ); }
	 *     }
	 *
	 * @since 1.1.0
	 *
	 * @param array $args Various arguments for overriding stuff in unit testing.
	 */
	public function set_overrides( $args ) {
		$this->overrides = $args;
	}

	/**
	 * Retrieves the value of a given override (if set).
	 * @param $name
	 *
	 * @return mixed
	 */
	public function get_override( $name ) {
		$override = '';

		if ( isset( $this->overrides[ $name ] ) ) {
			$override = $this->overrides[ $name ];
		}

		return $override;
	}

}
