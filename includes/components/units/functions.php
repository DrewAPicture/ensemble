<?php
/**
 * Unit Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Units;

/**
 * Retrieves a unit (WP_Term) object.
 *
 * @since 1.0.0
 *
 * @param int|\WP_Term $unit Unit ID or object.
 * @return \WP_Term|\WP_Error Unit object if it exists, otherwise a WP_Error.
 */
function get_unit( $unit ) {
	return get_term( $unit, 'ensemble_unit' );
}
