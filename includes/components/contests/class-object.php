<?php
/**
 * Defines an object construct for a single Venue
 *
 * @package   Ensemble\Components\Contests
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests;

use Ensemble\Core;
use Ensemble\Util\Date;

/**
 * Defines the structure of a single venue.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Core\Base_Object
 */
class Object extends Core\Base_Object {

	/**
	 * Contest ID.
	 *
	 * @since 1.0.0
	 * @var   int
	 */
	public $id;

	/**
	 * Contest name.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $name = '';

	/**
	 * Contest description.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $description = '';

	/**
	 * Array of associated venue(s).
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	public $venues = array();

	/**
	 * Contest type.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $type = '';

	/**
	 * Contest URL (if external).
	 *
	 * @since 1.0.0
	 * @var   string|null
	 */
	public $external;

	/**
	 * Contest status.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $status;

	/**
	 * Contest timezone.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $timezone;

	/**
	 * Contest start date and time.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $start_date;

	/**
	 * Contest end date and time.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $end_date;

	/**
	 * Retrieves a Database instance corresponding to this object.
	 *
	 * @since 1.0.0
	 *
	 * @return Database Contests database class instance.
	 */
	public static function db() {
		return ( new Database );
	}

	/**
	 * Retrieves the contest start date in WP time, formatted with the given format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $format Optional. How to format the date. Default 'm/d/Y'.
	 * @return string Formatted start date.
	 */
	public function get_start_date( $format = 'm/d/Y' ) {
		return Date::UTC_to_WP( $this->start_date, $format );
	}

	/**
	 * Retrieves the contest end date in WP time, formatted with the given format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $format Optional. How to format the date. Default 'm/d/Y'.
	 * @return string Formatted end date.
	 */
	public function get_end_date( $format = 'm/d/Y' ) {
		if ( ! empty( $this->end_date ) ) {
			$date = Date::UTC_to_WP( $this->end_date, $format );
		} else {
			return '';
		}
	}

}
