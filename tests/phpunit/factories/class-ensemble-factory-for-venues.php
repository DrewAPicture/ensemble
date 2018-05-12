<?php
namespace Ensemble\Tests\Factory;

class Venue extends \WP_UnitTest_Factory_For_Thing {

	protected static $affiliate_id;

	function __construct( $factory = null ) {
		parent::__construct( $factory );
	}

	function create_object( $args ) {
		return ensemble()->venues->add( $args );
	}

	/**
	 * Stub out copy of parent method for IDE type hinting purposes.
	 *
	 * @param array $args
	 * @param null  $generation_definitions
	 *
	 * @return \Ensemble\Venue|int
	 */
	function create_and_get( $args = array(), $generation_definitions = null ) {
		return parent::create_and_get( $args, $generation_definitions );
	}

	function update_object( $venue_id, $fields ) {
		return ensemble()->venues->update( $venue_id, $fields );
	}

	/**
	 * Stub out copy of parent method for IDE type hinting purposes.
	 *
	 * @param int $venue_id Venue ID.
	 * @return \Ensemble\Venue|false
	 */
	function get_object_by_id( $venue_id ) {
		return ensemble_get_venue( $venue_id );
	}

}
