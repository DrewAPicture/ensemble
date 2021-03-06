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

	public function __construct( $factory, array $default_generation_definitions = array() ) {
		parent::__construct( $factory, $default_generation_definitions );

		$this->default_generation_definitions = array(
			'name' => new \WP_UnitTest_Generator_Sequence( 'Test Contest: %s' ),
		);
	}

	/**
	 * Wraps the parent method for IDE type hinting purposes.
	 *
	 * @param array $args
	 * @param null  $generation_definitions
	 * @return \Ensemble\Components\Contests\Model|\WP_Error
	 */
	function create_and_get( $args = array(), $generation_definitions = null ) {
		return parent::create_and_get( $args, $generation_definitions );
	}

	/**
	 * Creates a contest fixture.
	 *
	 * @param $args
	 * @return int|\WP_Error Contest ID or WP_Error if there was a problem.
	 */
	function create_object( $args ) {
		if ( empty( $args['venues'] ) ) {
			$args['venues'] = array( 1, 2 );
		}

		return ( new Database )->insert( $args );
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
	 * @return \Ensemble\Components\Contests\Model|false
	 */
	function get_object_by_id( $contest_id ) {
		return get_contest( $contest_id );
	}
}
