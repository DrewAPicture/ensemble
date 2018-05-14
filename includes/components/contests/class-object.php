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

use Ensemble\Components;
use Ensemble\Core;

/**
 * Defines the structure of a single venue.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Components\Object
 */
class Object extends Components\Object {

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
