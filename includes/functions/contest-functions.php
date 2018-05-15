<?php
/**
 * Contest Functions
 *
 * @package   Ensemble\Functions
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble;

/**
 * Retrieves the contest status label for a given status.
 *
 * @since 1.0.0
 *
 * @param string $status Status.
 * @return string Status label.
 */
function get_contest_status_label( $status ) {
	switch( $status ) {
		case 'draft':
			return __( 'Draft', 'ensemble' );
			break;

		case 'published':
			return __( 'Published', 'ensemble' );
			break;
	}
}
