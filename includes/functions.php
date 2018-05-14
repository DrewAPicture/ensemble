<?php
/**
 * Core Ensemble Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble {

	use Ensemble\Core\Interfaces;
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
	 * Retrieves an instance of the Contests database class.
	 *
	 * @since 1.0.0
	 *
	 * @return Components\Contests\Database Contests db.
	 */
	function contests() {
		return ( new Components\Contests\Database() );
	}

	/**
	 * Retrieves an instance of the Venues database class.
	 *
	 * @since 1.0.0
	 *
	 * @return Components\Venues\Database Venues db.
	 */
	function venues() {
		return ( new Components\Venues\Database() );
	}

	/**
	 * Retrieves an instance of the Directors database class.
	 *
	 * @since 1.0.0
	 *
	 * @return Components\People\Directors\Database Directors db.
	 */
	function directors() {
		return ( new Components\People\Directors\Database() );
	}

	/**
	 * Retrieves an instance of the Circuit Staff database class.
	 *
	 * @since 1.0.0
	 *
	 * @return Components\People\Staff\Database Staff db.
	 */
	function staff() {
		return ( new Components\People\Staff\Database() );
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
		if ( ! $object instanceof \Ensemble\Components\Object ) {
			return false;
		}

		$Object_Class = get_class( $object );
		$cache_key    = $Object_Class::get_cache_key( $object->ID );
		$cache_group  = $Object_Class::$object_type;

		// Individual object.
		wp_cache_delete( $cache_key, $cache_group );

		// Prime the item cache.
		$Object_Class::get_instance( $object->ID );

		$db_groups      = $Object_Class::get_db_groups();
		$db_cache_group = isset( $db_groups->secondary ) ? $db_groups->secondary : $db_groups->primary;

		$last_changed = microtime();

		// Invalidate core object queries.
		wp_cache_set( 'last_changed', $last_changed, $db_cache_group );
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

}

namespace {

	/**
	 * Instantiates Ensemble and initializes the object without the need for an object
	 * in the global space.
	 *
	 * h/t Pippin Williamson for the pattern inspiration.
	 *
	 * @since 1.0.0
	 *
	 * @return Ensemble Global Ensemble get_instance.
	 */
	function ensemble() {
		return Ensemble::get_instance();
	}

}
