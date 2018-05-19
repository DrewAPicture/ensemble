<?php
/**
 * Ensemble Uninstaller
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */

// Remove the Director role.
wp_roles()->remove_role( 'ensemble_director' );

// Drop all Ensemble tables.
global $wpdb;

$db_segments = array(
	'ensemble_contests',
	'ensemble_venues',
);

// Drop the tables (no turning back now!).
foreach ( $db_segments as $segment ) {
	$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . $segment );
}
