<?php
/**
 * Admin notices handler for Ensemble core
 *
 *
 *
 * @package   Ensemble\Core\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Admin;

/**
 * Implements logic for displaying a variety of notification types
 *
 * @since 1.0.0
 */
class Notices {

	/**
	 * Builds a given notice's output, if it exists..
	 *
	 * @since
	 * @param $notice_id
	 */
	public function build_notice( $notice_id ) {
		$output = '';

		$registry = Notices_Registry::instance();
		$notice   = $registry->get( $notice_id );

		if ( empty( $notice ) ) {
			return $output;
		}

		$message = $notice['message'];

		$output = sprintf( '<div id="%1$s-notice" class="notice notice-%2$s is-dismissible"><p>%3$s</p></div>',
			esc_attr( $notice_id ),
			esc_attr( $notice['type'] ),
			$message
		);

		return $output;
	}

}
