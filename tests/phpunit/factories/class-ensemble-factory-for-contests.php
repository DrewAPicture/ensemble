<?php
namespace Ensemble\Tests\Factory;

use Ensemble\Components\Contests\Database;
use function Ensemble\Components\Contests\get_contest;

/**
 * Factory for creating, updating, and retrieving contest test fixtures.
 *
 * @see \WP_UnitTest_Factory_For_Thing
 */
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

	/**
	 * Creates a contest fixture.
	 *
	 * @param $args
	 * @return int|\WP_Error Contest ID.
	 */
	function create_object( $args ) {
		return ( new Database )->insert( array(
			'name'   => 'Test Contest: ' . rand_str( 6 ),
			'venues' => array( 1, 2 ),
		) );
	}

	/**
	 * Updates a contest fixture.
	 *
	 * @param $contest_id
	 * @param $fields
	 *
	 * @return true|\WP_Error
	 */
	function update_object( $contest_id, $fields ) {
		return ( new Database )->update( $contest_id, $fields );
	}

	/**
	 * Retrieve a contest fixture by ID.
	 *
	 * @param int $contest_id Contest ID.
	 * @return \Ensemble\Components\Contests\Object|false
	 */
	function get_object_by_id( $contest_id ) {
		return get_contest( $contest_id );
	}
}
