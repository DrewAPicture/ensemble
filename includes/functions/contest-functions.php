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
 * Retrieves the list of allowed contest statuses and their corresponding labels.
 *
 * @since 1.0.0
 *
 * @return array Allowed statuses.
 */
function get_allowed_statuses() {
	$statuses = array(
		'draft'     => __( 'Draft', 'ensemble' ),
		'published' => __( 'Published', 'ensemble' ),
	);

	/**
	 * Filters the list of whitelisted statuses for contests.
	 *
	 * @since 1.0.0
	 *
	 * @param array $statuses List of whitelisted contest status/label pairs.
	 */
	return apply_filters( 'ensemble_contests_statuses', $statuses );
}

/**
 * Retrieves the label for a given contest status.
 *
 * @since 1.0.0
 *
 * @param string $status Status.
 * @return string Status label.
 */
function get_status_label( $status ) {
	$statuses = get_allowed_statuses();

	if ( ! array_key_exists( $status, $statuses ) ) {
		$status = 'draft';
	}

	return $statuses[ $status ];
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
