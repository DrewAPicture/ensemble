<?php
/**
 * Sets up the Contests admin notices
 *
 * @package   Ensemble\Components\Contests\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\Contests\Admin;

use Ensemble\Core\Admin\Notices_Registry;
use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\Admin_Notices;

/**
 * Admin notices for contests.
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

		$registry->register_notice( 'notice-contest-added', array(
			'message' => __( 'A contest was successfully created.', 'ensemble' ),
		) );

		$registry->register_notice( 'notice-contest-added-error', array(
			'message' => __( 'There was an error adding the contest. Please try again.', 'ensemble' ),
			'type'    => 'warning',
		) );

		$registry->register_notice( 'notice-contest-forbidden', array(
			'message' => __( 'Sorry, you are not allowed to do that.', 'ensemble' ),
			'type'    => 'info',
		) );

		$registry->register_notice( 'notice-contest-updated', array(
			'message' => __( 'The contest was successfully updated.', 'ensemble' ),
		) );

		$registry->register_notice( 'notice-contest-updated-error', array(
			'message' => __( 'The contest could not be updated. Please try again.', 'ensemble' ),
			'type'    => 'warning',
		) );


		$registry->register_notice( 'notice-contest-deleted', array(
			'message' => __( 'The contest was successfully deleted.', 'ensemble' ),
		) );

		$registry->register_notice( 'notice-contest-deleted-error', array(
			'message' => __( 'The contest could not be deleted. Please try again.', 'ensemble' ),
			'type'    => 'warning',
		) );
	}

}
