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
	 *     @type string          $type    Notice type. Accepts 'success', 'error', 'info', or 'warning'.
	 *                                    Default 'success'.
	 * }
	 * @return true|\WP_Error True on successful registration, otherwise a WP_Error object.
	 */
	public function register_notice( $notice_id, $notice_args ) {
		$defaults = array(
			'message'   => '',
			'type'      => 'success',
		);

		$notice_args = array_merge( $defaults, $notice_args );

		if ( ! in_array( $notice_args['type'], array( 'success', 'error', 'info', 'warning' ), true ) ) {
			$notice_args['type'] = 'success';
		}

		return $this->add_item( $notice_id, array(
			'message' => $notice_args['message'],
			'type'    => $notice_args['type']
		) );
	}

}
