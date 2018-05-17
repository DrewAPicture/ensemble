<?php
/**
 * Sets up Users functionality
 *
 * @package   Ensemble\Core
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Core;

use Ensemble\Core\Interfaces\Loader;
use function Ensemble\Components\Contests\get_contest;
use function Ensemble\Components\Venues\get_venue;

/**
 * Sets up the ability to manipulate various components as users.
 *
 * @since 1.0.0
 */
class Users implements Loader {

	/**
	 * Registers any hook callbacks needed to integration with the WordPress Users API.
	 *
	 * @since 1.0.0
	 */
	public function load() {

	}

	/**
	 * Adds roles for user-based components.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Roles $wp_roles WP_Roles instance.
	 */
	public function add_roles() {
		$wp_roles = wp_roles();

		$wp_roles->add_role( 'ensemble_director', __( 'Unit Director', 'ensemble' ), array(
			'ensemble_manage_own_units' => true,
			'ensemble_view_own_units'   => true,
			'ensemble_delete_own_units' => true,
			'ensemble_add_units'        => false,
			'ensemble_contact_circuit'  => true,
		) );

		$wp_roles->add_role( 'ensemble_staff', __( 'Circuit Staff', 'ensemble' ), array(
			'ensemble_manage_units'      => true,
			'ensemble_add_units'         => true,
			'ensemble_view_units'        => true,
			'ensemble_delete_units'      => true,
			'ensemble_manage_directors'  => true,
			'ensemble_add_directors'     => true,
			'ensemble_view_directors'    => true,
			'ensemble_contact_directors' => true,
		) );
	}

	/**
	 * Register Ensemble capabilities for admins.
	 *
	 * @since 1.0.0
	 */
	public function add_caps() {
		$wp_roles = wp_roles();

		$wp_roles->add_cap( 'administrator', 'manage_contests' );
		$wp_roles->add_cap( 'administrator', 'manage_venues' );
	}


	/**
	 * Remove Ensemble capabilities (called during uninstall).
	 *
	 * @since 1.0.0
	 */
	public function remove_caps() {
		$wp_roles = wp_roles();

		$wp_roles->remove_cap( 'administrator', 'manage_contests' );
		$wp_roles->remove_cap( 'administrator', 'manage_venues' );
	}

}
