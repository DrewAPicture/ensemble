<?php
/**
 * Core Ensemble Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble;

use Ensemble\Core\Admin\Notices;
use Ensemble\Core\Interfaces;
use Ensemble\Core\Traits;
use Ensemble\Components;

/**
 * Short-hand helper to initialize an aspect of the bootstrap.
 *
 * @since 1.0.0
 *
 * @param Loader $object Object to initialize.
 * @return mixed Result of the bootstrap initialization, usually an object.
 */
function load( $object ) {
	if ( $object instanceof Interfaces\Loader ) {
		return $object->load();
	}
}

/**
 * Short-hand helper to load a view defined by qualified classes.
 *
 * @since 1.0.0
 *
 * @param Traits\View_Loader $object Object to load the view for.
 * @param string             $view   View to (attempt to) load.
 * @return void (Displays).
 */
function load_view( $object, $view ) {
	$needle = 'Ensemble\\Core\\Traits\\View_Loader';

	if ( array_key_exists( $needle, class_uses( $object ) ) ) {
		$object->load_view( $view );
	} else {
		// class_uses() doesn't travel up the inheritance chain, so check the parents manually.
		foreach ( class_parents( $object ) as $parent ) {
			if ( array_key_exists( $needle, class_uses( $parent ) ) ) {
				$object->load_view( $view );
			}
		}
	}
}

/**
 * Helper to retrieve an instance of the HTML class.
 *
 * @since 1.0.0
 *
 * @return Util\HTML HTML class instance.
 */
function html() {
	return new Util\HTML;
}

/**
 * Retrieves the value of the ensbl-view $_REQUEST variable.
 *
 * @since 1.0.0
 *
 * @param string $default Optional. Default view to return if ensbl-view isn't set. Default 'overview'.
 * @return string View variable vlaue.
 */
function get_current_view( $default = 'overview' ) {
	return sanitize_key( $_REQUEST['ensbl-view'] ?? $default );
}

/**
 * Retrieves the sanitized slug for the current tab.
 *
 * @since 1.0.0
 *
 * @param string $default Optional. Tab default to use if the slug isn't set. Default empty string.
 * @return string Current tab if set, otherwise the value of `$default`.
 */
function get_current_tab( $default = '' ) {
	return sanitize_key( $_REQUEST['enble-tab'] ?? $default );
}

/**
 * Cleans the cache for a given object.
 *
 * @since 1.0.0
 *
 * @param \Ensemble\Components\Object $object Component object.
 * @return bool True if the item cache was cleaned, false otherwise.
 */
function clean_item_cache( $object ) {
	if ( ! $object instanceof \Ensemble\Core\Model ) {
		return false;
	}

	$Object_Class = get_class( $object );
	$cache_key    = $Object_Class::get_cache_key( $object->get_ID() );
	$cache_group  = $Object_Class::db()->get_cache_group();

	// Individual object.
	wp_cache_delete( $cache_key, $cache_group );

	// Prime the item cache.
	$Object_Class::get_instance( $object->get_ID() );

	$last_changed = microtime();

	// Invalidate core object queries.
	wp_cache_set( 'last_changed', $last_changed, $cache_group );
}

/**
 * Prints a given registered notice, if it exists.
 *
 * @since 1.0.0
 *
 * @param string $notice_id Notice ID.
 * @param array  $atts      Optional. Any attributes needed to display the notice. Default empty array.
 */
function print_notice( $notice_id, $atts = array() ) {
	echo ( new Notices )->build_notice( $notice_id );
}
