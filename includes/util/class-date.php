<?php
/**
 * DateTime utility class
 *
 * @package   Ensemble\Util
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Util;

/**
 * Core class to facilitate global settings access.
 *
 * @since 1.0.0
 */
class Date extends \DateTime {

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
	public static function create( $date_string = 'now', $timezone = 'UTC', $wp_to_utc = false ) {
		$wp_time = false;

		if ( 'wp' === $timezone ) {
			$timezone = self::get_wp_timezone();
			$wp_time  = true;
		}

		$datetime = new self( $date_string, new \DateTimeZone( $timezone ) );

		// If converting from WP time to UTC, subtract the WP offset.
		if ( false !== $wp_to_utc && 'UTC' === $timezone ) {
			$offset   = absint( self::get_wp_offset() );
			$interval = \DateInterval::createFromDateString( "{$offset} seconds" );

			$datetime->add( $interval );

			// Otherwise, if $datetime was instantiated as WP time, apply the offset so it matches.
		} elseif ( true === $wp_time ) {
			$offset   = self::get_wp_offset();
			$interval = \DateInterval::createFromDateString( "{$offset} seconds" );

			$datetime->add( $interval );
		}

		return $datetime;
	}

	/**
	 * Short-hand helper to avoid the need to create a 'now' UTC date just to format it.
	 *
	 * @since 1.0.0
	 *
	 * @param string $format Any valid PHP date format string.
	 * @return string Formatted date string.
	 */
	public static function UTC( $format ) {
		return self::create()->format( $format );
	}

	/**
	 * Short-hand helper to convert a given date string from WP time to UTC time in a given format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date_string Date string.
	 * @param string $format      Optional. Date format. Default 'Y-m-d H:i:s'.
	 * @return string Formatted date string in UTC time (no offset).
	 */
	public static function WP_to_UTC( $date_string, $format = 'Y-m-d H:i:s' ) {
		return self::create( $date_string, 'UTC', true )->format( $format );
	}

	/**
	 * Short-hand helper to convert a given date string from UTC time to WP time in a given format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $date_string Date string.
	 * @param string $format      Optional. Date format. Default 'Y-m-d H:i:s'.
	 * @return string Formatted date string in WP time.
	 */
	public static function UTC_to_WP( $date_string, $format = 'Y-m-d H:i:s' ) {
		return self::create( $date_string, 'wp' )->format( $format );
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
	public static function get_wp_timezone() {

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
	 * Retrieves the GMT offset based on the WordPress timezone.
	 *
	 * @since 1.0.0
	 *
	 * @see get_wp_timezone()
	 *
	 * @return int GMT offset in seconds, as derived from get_wp_timezone().
	 */
	public static function get_wp_offset() {
		return ( new self( null, new \DateTimeZone( self::get_wp_timezone() ) ) )->getOffset();
	}

}

