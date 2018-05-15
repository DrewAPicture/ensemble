<?php
/**
 * Contest Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Contests;

/**
 * Retrieves the label for a given contest status.
 *
 * @since 1.0.0
 *
 * @param string $status Status.
 * @return string Status label.
 */
function get_status_label( $status ) {
	switch( $status ) {
		case 'draft':
			return __( 'Draft', 'ensemble' );
			break;

		case 'published':
			return __( 'Published', 'ensemble' );
			break;
	}
}

/**
 * Retrieves the label for a given contest type.
 *
 * @since 1.0.0
 *
 * @param string $type Contest type.
 * @return string Type label.
 */
function get_type_label( $type ) {
	switch ( $type ) {
		case 'regular':
			return __( 'Regular', 'ensemble' );
			break;

		default:
			/**
			 * Filters the contest type label for non-core types.
			 *
			 * @since 1.0.0
			 *
			 * @param string $label Type label.
			 * @param string $type  Contest type.
			 */
			return apply_filters( 'ensemble_contests_type_label', '', $type );
			break;
	}
}
