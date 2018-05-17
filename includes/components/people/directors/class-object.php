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

use function Ensemble\Components\Units\{get_unit};
use Ensemble\Core;

/**
 * Defines a Staff Member object model.
 *
 * @since 1.0.0
 *
 * @see \Ensemble\Components\User_Object
 */
class Object extends Core\User_Object {

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
	 * Associates a competing unit (if it exists) with the current director.
	 *
	 * @since 1.0.0
	 *
	 * @param int|\WP_Term $unit Unit ID or object.
	 * @return bool
	 */
	public function associate_unit( $unit ) {
		if ( ! $unit = get_unit( $unit ) ) {
			return false;
		} else {
			$tt_ids = wp_set_object_terms( $this->ID, $unit->term_id, 'ensemble_unit', true );

			return is_wp_error( $tt_ids ) ? false : true;
		}
	}

}
