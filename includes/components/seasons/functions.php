<?php
/**
 * Season Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Seasons;

/**
 * Retrieves a season (WP_Term) object.
 *
 * @since 1.0.0
 *
 * @param int|\WP_Term $season Season ID or object.
 * @return \WP_Term|\WP_Error Season object if it exists, otherwise a WP_Error.
 */
function get_season( $season ) {
	return get_term( $season, 'ensemble_season' );
}
