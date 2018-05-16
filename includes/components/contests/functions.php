<?php
/**
 * Contest Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests;

/**
 * Retrieves a contest.
 *
 * @since 1.0.0
 *
 * @param int|Object $contest Contest ID or object.
 * @return \Ensemble\Components\Contests\Object|\WP_Error Contest object if found, otherwise a WP_Error object.
 */
function get_contest( $contest ) {
	if ( is_object( $contest ) && isset( $contest->id ) ) {
		$contest_id = $contest->id;
	} elseif ( is_numeric( $contest ) ) {
		$contest_id = absint( $contest );
	} else {
		$contest_id = 0;
	}

	return ( new Database )->get_core_object( $contest_id );
}

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
	 * Filters the list of allowed statuses for contests.
	 *
	 * @since 1.0.0
	 *
	 * @param array $statuses List of allowed contest status/label pairs.
	 */
	return apply_filters( 'ensemble_contests_allowed_statuses', $statuses );
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

	if ( ! empty( $statuses[ $status ] ) ) {
		$label = $statuses[ $status ];
	} else {
		$label = '';
	}

	return $label;
}

/**
 * Retrieves the list of allowed contest types and their corresponding labels.
 *
 * @since 1.0.0
 *
 * @return array Allowed contest type/label pairs.
 */
function get_allowed_types() {
	$types = array(
		'standard' => __( 'Standard', 'ensemble' ),
	);

	/**
	 * Filters the list of allowed types for contests.
	 *
	 * @since 1.0.0
	 *
	 * @param array $statuses List of allowed contest type/label pairs.
	 */
	return apply_filters( 'ensemble_contests_allowed_types', $types );
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
	$types = get_allowed_types();

	if ( ! array_key_exists( $type, $types ) ) {
		$type = 'regular';
	}

	if ( ! empty( $types[ $type ] ) ) {
		$label = $types[ $type ];
	} else {
		$label = '';
	}

	return $label;
}
