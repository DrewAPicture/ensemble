<?php
/**
 * Core Ensemble Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble {

	/**
	 * Short-hand helper to initialize an aspect of the bootstrap.
	 *
	 * @since 1.0.0
	 *
	 * @param Loader $object Object to initialize.
	 * @return mixed Result of the bootstrap initialization, usually an object.
	 */
	function init( $object ) {
		if ( $object instanceof Interfaces\Loader ) {
			return $object->init();
		}
	}

}

namespace {

	/**
	 * Instantiates Ensemble and initializes the object without the need for an object
	 * in the global space.
	 *
	 * h/t Pippin Williamson for the pattern inspiration.
	 *
	 * @since 1.0.0
	 *
	 * @return Ensemble Global Ensemble get_instance.
	 */
	function ensemble() {
		return Ensemble::get_instance();
	}

}
