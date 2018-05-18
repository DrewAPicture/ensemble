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
 * @return string View variable vlaue.
 */
function get_view_var() {
	return isset( $_REQUEST['ensbl-view'] ) ? sanitize_key( $_REQUEST['ensbl-view' ] ) : 'overview';
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
	if ( ! $object instanceof \Ensemble\Core\Object ) {
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
 * Attempts to derive a timezone string from the WordPress settings.
 *
 * @since 1.0.0
 *
 * @return string WordPress timezone as derived from a combination of the timezone_string
 *                and gmt_offset options. If no valid timezone could be found, defaults to
 *                UTC.
 */
function get_wp_timezone() {

	// Passing a $default value doesn't work for the timezeon_string option.
	$timezone = get_option( 'timezone_string' );

	/*
	 * If the timezone isn't set, or rather was set to a UTC offset, core saves the value
	 * to the gmt_offset option and leaves timezone_string empty – because that makes
	 * total sense, obviously. ¯\_(ツ)_/¯
	 *
	 * So, try to use the gmt_offset to derive a timezone.
	 */
	if ( empty( $timezone ) ) {
		// Try to grab the offset instead.
		$gmt_offset = get_option( 'gmt_offset', 0 );

		// Yes, core returns it as a string, so as not to confuse it with falsey.
		if ( '0' !== $gmt_offset ) {
			$timezone = timezone_name_from_abbr( '', (int) $gmt_offset * HOUR_IN_SECONDS, date( 'I' ) );
		}

		// If the offset was 0 or $timezone is still empty, just use 'UTC'.
		if ( '0' === $gmt_offset || empty( $timezone ) ) {
			$timezone = 'UTC';
		}
	}

	return $timezone;
}

/**
 * Retrieves a DateTime object, optionally with the WP timezone and offset applied.
 *
 * @since 1.0.0
 *
 * @param string $date_string Optional. Date string to generate the DateTime object for.
 *                            Default 'now'.
 * @param string $timezone    Optional. Timezone. Accepts 'wp' (WordPress timezone),
 *                            or any other valid timezone string. Default UTC.
 * @param bool   $wp_to_utc   Optional. Whether to subtract the equivalent of the WP
 *                            offset from the DateTime object. `$timezone` must be UTC.
 *                            Default false.
 * @return \DateTime DateTime object.
 */
function create_date( $date_string = 'now', $timezone = 'UTC', $wp_to_utc = false ) {
	$wp_time = false;

	if ( 'wp' === $timezone ) {
		$timezone = get_wp_timezone();
		$wp_time  = true;
	}

	$datetime = new \DateTime( $date_string, new \DateTimeZone( $timezone ) );

	// If converting from WP time to UTC, subtract the WP offset.
	if ( false !== $wp_to_utc && 'UTC' === $timezone ) {
		$offset   = get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS;
		$interval = \DateInterval::createFromDateString( "-{$offset} seconds" );

		$datetime->add( $interval );

	// Otherwise, if $datetime was instantiated as WP time, apply the offset so it matches.
	} elseif ( true === $wp_time ) {
		$offset   = $datetime->getOffset();
		$interval = \DateInterval::createFromDateString( "{$offset} seconds" );

		$datetime->add( $interval );
	}

	return $datetime;
}
