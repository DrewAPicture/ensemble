<?php
/**
 * Defines an object construct for a single Venue
 *
 * @package   Ensemble\Components\Venues
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests;

use Ensemble\Core;

/**
 * Defines the structure of a single venue.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Core\Object
 */
class Object extends Core\Object {

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
	 * Retrieves the contest start date, formatted with the given format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $format Optional. How to format the date. Default 'm/d/Y'.
	 * @return string Formatted start date.
	 */
	public function start_date( $format = 'm/d/Y' ) {
		return date( $format, strtotime( $this->start_date ) );
	}

	/**
	 * Retrieves the contest end date, formatted with the given format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $format Optional. How to format the date. Default 'm/d/Y'.
	 * @return string Formatted end date.
	 */
	public function end_date( $format = 'm/d/Y' ) {
		return date( $format, strtotime( $this->end_date ) );
	}

}
