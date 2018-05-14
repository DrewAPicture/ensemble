<?php
/**
 * Staff member functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble;

function get_staff_member( $member ) {

	if ( is_object( $member ) && isset( $member->member_id ) ) {
		$member_id = $member->member_id;
	} elseif ( is_numeric( $member) ) {
		$member_id = absint( $member );
	} else {
		return new \WP_Error( 'get_staff_member_failure', '', $member );
	}

	return ensemble()->staff->get( $member_id );
}