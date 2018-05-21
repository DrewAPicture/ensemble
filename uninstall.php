<?php
/**
 * Ensemble Uninstaller
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */

global $wpdb;

//
// Roles & Capabilities
//

wp_roles()->remove_role( 'ensemble_director' );

//
// Custom Tables
//

$table_segments = array(
	'ensemble_contests',
	'ensemble_venues',
);

// Drop the tables (no turning back now!).
foreach ( $table_segments as $table_segment ) {
	$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . $table_segment );
}

//
// Taxonomies, Terms, & Term meta
//

$taxonomies = array(
	'ensemble_unit',
	'ensemble_class',
	'ensemble_season'
);

foreach ( $taxonomies as $taxonomy ) {
	// Get the terms.
	$terms = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s') ORDER BY t.name ASC",
			$taxonomy
		)
	);

	// If terms were found, loop through and delete the records.
	if ( $terms ) {
		foreach ( $terms as $term ) {
			$wpdb->delete( $wpdb->term_taxonomy, array(
				'term_taxonomy_id' => $term->term_taxonomy_id
			) );

			$wpdb->delete( $wpdb->terms, array(
				'term_id' => $term->term_id
			) );
		}
	}

	// Delete the taxonomy.
	$wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => $taxonomy ), array( '%s' ) );
}

// Delete leftover/orphaned term meta.
if ( ! empty( $wpdb->termmeta ) ) {
	$wpdb->query( "DELETE termmeta FROM {$wpdb->termmeta} termmeta LEFT JOIN {$wpdb->term_taxonomy} tt ON termmeta.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
}
