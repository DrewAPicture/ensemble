<?php
/**
 * Venue Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Venues;

/**
 * Retrieves the list of allowed venue statuses and their corresponding labels.
 *
 * @since 1.0.0
 *
 * @return array Allowed statuses.
 */
function get_allowed_statuses() {
	$statuses = array(
		'active'   => __( 'Active', 'ensemble' ),
		'inactive' => __( 'Inactive', 'ensemble' ),
	);

	/**
	 * Filters the list of allowed statuses for venues.
	 *
	 * @since 1.0.0
	 *
	 * @param array $statuses List of allowed venue status/label pairs.
	 */
	return apply_filters( 'ensemble_venues_allowed_statuses', $statuses );
}

/**
 * Retrieves the label for a given venue status.
 *
 * @since 1.0.0
 *
 * @param string $status Status.
 * @return string Status label.
 */
function get_status_label( $status ) {
	$statuses = get_allowed_statuses();

	if ( ! array_key_exists( $status, $statuses ) ) {
		$status = 'inactive';
	}

	if ( ! empty( $statuses[ $status ] ) ) {
		$label = $statuses[ $status ];
	} else {
		$label = '';
	}

	return $label;
}
