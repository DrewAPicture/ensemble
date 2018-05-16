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
 * @see \Ensemble\Core\Object
 */
class Object extends Core\Object {

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

}
