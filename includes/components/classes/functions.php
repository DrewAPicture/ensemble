<?php
/**
 * Class Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Classes;

/**
 * Retrieves a class (WP_Term) object.
 *
 * @since 1.0.0
 *
 * @param int|\WP_Term $class Class ID or object.
 * @return \WP_Term|\WP_Error Class object if it exists, otherwise a WP_Error.
 */
function get_class( $class ) {
	return get_term( $class, 'ensemble_class' );
}
