<?php
namespace Ensemble\Tests\Factory;

use Ensemble\Components\Venues\Database;
use function Ensemble\Components\Venues\get_venue;

/**
 * Factory for creating, updating, and retrieving venue test fixtures.
 *
 * @see \WP_UnitTest_Factory_For_Thing
 */
class Venue extends \WP_UnitTest_Factory_For_Thing {

	public function __construct( $factory, array $default_generation_definitions = array() ) {
		parent::__construct( $factory, $default_generation_definitions );

		$this->default_generation_definitions = array(
			'name'    => new \WP_UnitTest_Generator_Sequence( 'Test Venue: %s' ),
			'address' => new \WP_UnitTest_Generator_Sequence( '%s Somewhere, CO 80131' ),
		);
	}

	/**
	 * Wraps the parent method for IDE type hinting purposes.
	 *
	 * @param array $args
	 * @param null  $generation_definitions
	 * @return \Ensemble\Components\Venues\Model|\WP_Error
	 */
	function create_and_get( $args = array(), $generation_definitions = null ) {
		return parent::create_and_get( $args, $generation_definitions );
	}

	/**
	 * Creates a venue fixture.
	 *
	 * @param $args
	 * @return int|\WP_Error Venue ID or WP_Error if there was a problem.
	 */
	function create_object( $args ) {
		return ( new Database )->insert( $args );
	}

	/**
	 * Updates a venue fixture.
	 *
	 * @param $venue_id
	 * @param $fields
	 *
	 * @return true|\WP_Error
	 */
	function update_object( $venue_id, $fields ) {
		return ( new Database )->update( $venue_id, $fields );
	}

	/**
	 * Retrieve a venue fixture by ID.
	 *
	 * @param int $venue_id Venue ID.
	 * @return \Ensemble\Components\Venues\Model|false
	 */
	function get_object_by_id( $venue_id ) {
		return get_venue( $venue_id );
	}
}
