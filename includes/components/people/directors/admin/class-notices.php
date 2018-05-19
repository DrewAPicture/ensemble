<?php
/**
 * Sets up the Directors admin notices
 *
 * @package   Ensemble\Components\People\Directors\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Directors\Admin;

use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\Admin_Notices;

/**
 * Admin notices for directors.
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

		$registry->register_notice( 'notice-director-added', array(
			'message' => __( 'A director was successfully created.', 'ensemble' ),
		) );

		$registry->register_notice( 'notice-director-added-error', array(
			'message' => __( 'There was an error adding the director. Please try again.', 'ensemble' ),
			'type'    => 'warning',
		) );

		$registry->register_notice( 'notice-director-forbidden', array(
			'message' => __( 'Sorry, you are not allowed to do that.', 'ensemble' ),
			'type'    => 'info',
		) );

		$registry->register_notice( 'notice-director-updated', array(
			'message' => __( 'The director was successfully updated.', 'ensemble' ),
		) );

		$registry->register_notice( 'notice-director-updated-error', array(
			'message' => __( 'The director could not be updated. Please try again.', 'ensemble' ),
			'type'    => 'warning',
		) );


		$registry->register_notice( 'notice-director-deleted', array(
			'message' => __( 'The director was successfully deleted.', 'ensemble' ),
		) );

		$registry->register_notice( 'notice-director-deleted-error', array(
			'message' => __( 'The director could not be deleted. Please try again.', 'ensemble' ),
			'type'    => 'warning',
		) );
	}
}
