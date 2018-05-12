<?php
namespace Ensemble\Tests\Factory;

class Contest extends \WP_UnitTest_Factory_For_Thing {

	function __construct( $factory = null ) {
		parent::__construct( $factory );
	}

	/**
	 * Stub out copy of parent method for IDE type hinting purposes.
	 *
	 * @param array $args
	 * @param null  $generation_definitions
	 *
	 * @return \Ensemble\Contest|false
	 */
	function create_and_get( $args = array(), $generation_definitions = null ) {
		return parent::create_and_get( $args, $generation_definitions );
	}

	function create_object( $args ) {
		return ensemble()->contests->add( $args );
	}

	function update_object( $contest_id, $fields ) {
		return ensemble()->contests->update( $contest_id, $fields );
	}

	/**
	 * Stub out copy of parent method for IDE type hinting purposes.
	 *
	 * @param int $contest_id Contest ID.
	 * @return \Ensemble\Contest|false
	 */
	function get_object_by_id( $contest_id ) {
		return ensemble_get_contest( $contest_id );
	}
}
