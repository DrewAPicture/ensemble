<?php
/**
 * Defines the object construct for a single Director
 *
 * @package   Ensemble\Components\People
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Directors;

use Ensemble\Components;

/**
 * Defines a Staff Member object model.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Components\User_Object
 */
class Object extends Components\User_Object {

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
