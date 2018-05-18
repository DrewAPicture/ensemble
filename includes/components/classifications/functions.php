<?php
/**
 * Classification Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Classifications;

/**
 * Retrieves a classification (WP_Term) object.
 *
 * @since 1.0.0
 *
 * @param int|\WP_Term $classification Classification ID or object.
 * @return \WP_Term|\WP_Error Classification object if it exists, otherwise a WP_Error.
 */
function get_classification( $classification ) {
	return get_term( $classification, 'ensemble_class' );
}
