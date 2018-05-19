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

use function Ensemble\{get_registry};

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

		$notice  = $this->add_special_classes( $notice );
		$classes = implode( ' ', $notice['class'] );

		$message = $notice['message'];

		$output = sprintf( '<div id="%1$s-notice" class="%2$s">%3$s</div>',
			esc_attr( $notice_id ),
			esc_attr( $classes ),
			$message
		);

		return $output;
	}

	/**
	 * Helper method to add the type class to a notice for output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $notice Notice attributes.
	 * @return array Modified notice attributes.
	 */
	public function add_special_classes( $notice ) {
		$notice['class'][] = "alert-{$notice['type']}";

		return $notice;
	}

}
