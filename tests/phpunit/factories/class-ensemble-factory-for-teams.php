<?php
namespace Ensemble\Tests\Factory;

class Team extends \WP_UnitTest_Factory_For_Thing {

	function __construct( $factory = null ) {
		parent::__construct( $factory );
	}

	function create_object( $args ) {
		return ensemble()->teams->add( $args );
	}

	/**
	 * Stub out copy of parent method for IDE type hinting purposes.
	 *
	 * @param array $args
	 * @param null  $generation_definitions
	 *
	 * @return \Ensemble\Team|int
	 */
	function create_and_get( $args = array(), $generation_definitions = null ) {
		return parent::create_and_get( $args, $generation_definitions );
	}

	function update_object( $team_id, $fields ) {
		return ensemble()->teams->update( $team_id, $fields );
	}

	/**
	 * Stub out copy of parent method for IDE type hinting purposes.
	 *
	 * @param int $team_id Team ID.
	 * @return \Ensemble\Team|false
	 */
	function get_object_by_id( $team_id ) {
		return affwp_get_team( $team_id );
	}
}
