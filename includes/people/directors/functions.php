<?php
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