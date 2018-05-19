<?php
/**
 * Sets up the Venues admin notices
 *
 * @package   Ensemble\Components\Venues\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Venues\Admin;

use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\Admin_Notices;

/**
 * Admin notices for venues.
 *
 * @since 1.0.0
 *
 * @see Loader
 */
class Notices implements Loader {

	use Admin_Notices;

	/**
	 * Sets up callbacks for registering admin notices.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		$this->register_notices();
	}

	/**
	 * Registers admin notices.
	 *
	 * @since 1.0.0
	 */
	public function register_notices() {
		$registry = $this->get_registry();

		$registry->register_notice( 'notice-venue-added', array(
			'message' => __( 'A venue was successfully created.', 'ensemble' ),
		) );

		$registry->register_notice( 'notice-venue-added-error', array(
			'message' => __( 'There was an error adding the venue. Please try again.', 'ensemble' ),
			'type'    => 'warning',
		) );

		$registry->register_notice( 'notice-venue-forbidden', array(
			'message' => __( 'Sorry, you are not allowed to do that.', 'ensemble' ),
			'type'    => 'info',
		) );

		$registry->register_notice( 'notice-venue-updated', array(
			'message' => __( 'The venue was successfully updated.', 'ensemble' ),
		) );

		$registry->register_notice( 'notice-venue-updated-error', array(
			'message' => __( 'The venue could not be updated. Please try again.', 'ensemble' ),
			'type'    => 'warning',
		) );


		$registry->register_notice( 'notice-venue-deleted', array(
			'message' => __( 'The venue was successfully deleted.', 'ensemble' ),
		) );

		$registry->register_notice( 'notice-venue-deleted-error', array(
			'message' => __( 'The venue could not be deleted. Please try again.', 'ensemble' ),
			'type'    => 'warning',
		) );
	}
}
