<?php
/**
 * Defines an object construct for a single Venue
 *
 * @package   Ensemble\Components\Venues
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues;

use Ensemble\Core;

/**
 * Defines the structure of a single venue.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Core\Base_Object
 */
class Venue_Object extends Core\Base_Object {

	/**
	 * Venue ID.
	 *
	 * @since 1.0.0
	 * @var   int
	 */
	public $id;

	/**
	 * Venue name.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $name = '';

	/**
	 * Venue address.
	 *
	 * @since 1.0.0
	 * @var   array
	 */
	public $address = '';

	/**
	 * Venue status.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $status;

	/**
	 * Venue type.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $type;

	/**
	 * Date the venue was added.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	public $date_added;

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
	 * Retrieves the date the venue was added, formatted with the given format.
	 *
	 * @since 1.0.0
	 *
	 * @param string $format Optional. How to format the date. Default 'm/d/Y'.
	 * @return string Formatted date.
	 */
	public function get_date_added( $format = 'm/d/Y' ) {
		return date( $format, strtotime( $this->date_added ) );
	}

}
