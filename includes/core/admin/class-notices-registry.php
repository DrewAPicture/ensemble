<?php
/**
 * Admin notices registry class
 *
 * This class incorporates work originating from the SellBird\Util\Notices_Registry class,
 * which is part of the SellBird platform, (c) 2018, Sandhills Development, LLC
 *
 * @package   Ensemble\Core\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core\Admin;

use Ensemble\Util\Registry;

/**
 * Implements a registry for notices.
 *
 * @since 1.0.0
 *
 * @see Registry
 */
class Notices_Registry extends Registry {

	/**
	 * The one true Notices_Registry instance.
	 *
	 * @since 1.0.0
	 * @var   Notices_Registry
	 */
	private static $instance;

	/**
	 * Retrieves the one true notices registry instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Notices_Registry Notices registry instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Notices_Registry;

			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initializes the notices registry.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		/**
		 * Fires during instantiation of the notices registry.
		 *
		 * @since 1.0.0
		 *
		 * @param Notices_Registry $this Registry instance.
		 */
		do_action( 'ensemble_notices_registry_init', $this );
	}

	/**
	 * Registers a new notice.
	 *
	 * @since 1.0.0
	 *
	 * @param string $notice_id   Unique notice ID.
	 * @param array  $notice_args {
	 *     Arguments for registering a new notice..
	 *
	 *     @type string|callable $message Notice message or a callback to retrieve it.
	 *                                    possibilities.
	 *     @type string          $type    Notice type. Accepts 'success', 'info', 'warning', or 'danger'.
	 *                                    Default 'success'.
	 *     @type string|array    $class   Class or array of classes to associate with the notice.
	 * }
	 * @return true|\WP_Error True on successful registration, otherwise a WP_Error object.
	 */
	public function register_notice( $notice_id, $notice_args ) {
		$defaults = array(
			'message'   => '',
			'type'      => 'success',
			'class'     => array( 'alert', 'mt-2', 'mt-md-0', 'mb-2', 'mb-md-5' ),
		);

		$notice_args = array_merge( $defaults, $notice_args );

		if ( ! in_array( $notice_args['type'], array( 'success', 'info', 'warning', 'danger' ), true ) ) {
			$notice_args['type'] = 'success';
		}

		if ( ! is_array( $notice_args['class'] ) ) {
			$notice_args['class'] = array( $notice_args['class'] );
		}

		$notice_args['class'] = array_map( 'sanitize_html_class', $notice_args['class'] );

		return $this->add_item( $notice_id, $notice_args );
	}

}
