<?php
namespace Ensemble\Tests\Factory;

class Staff extends \WP_UnitTest_Factory_For_Thing {

	function __construct( $factory = null ) {
		parent::__construct( $factory );
	}

	function create_many( $count, $args = array(), $generation_definitions = null ) {
		return parent::create_many( $count, $args, $generation_definitions );
	}

	function create_object( $args ) {
		return ensemble()->staff->add( $args );
	}

	function update_object( $staff_member_id, $fields ) {
		return ensemble()->staff->update( $staff_member_id, $fields );
	}

	function get_object_by_id( $staff_member_id ) {
		return ensemble_get_staff( $staff_member_id );
	}
}
