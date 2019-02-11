<?php
/**
 * Instructor Actions
 *
 * @package   Ensemble\Components\People\Instructors\Admin
 * @copyright Copyright (c) 2019, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.1.0
 */
namespace Ensemble\Components\People\Instructors\Admin;

use Ensemble\Components\Units\Setup as Units;
use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\{View_Loader, Tab_Loader};

/**
 * Sets up logic for performing instructor-related actions.
 *
 * @since 1.1.0
 *
 * @see Loader
 * @see View_Loader
 * @see Tab_Loader
 */
class Actions implements Loader {

	use View_Loader, Tab_Loader;

	/**
	 * Registers hook callbacks for instructor actions.
	 *
	 * @since 1.1.0
	 */
	public function load() {
		add_action( 'init', array( $this, 'add_instructor' ) );
		add_action( 'init', array( $this, 'update_instructor' ) );
		add_action( 'init', array( $this, 'delete_instructor' ) );

		$this->register_tab_callbacks();
	}

	/**
	 * Processes adding a new instructor.
	 *
	 * @since 1.1.0
	 */
	public function add_instructor() {
		$valid_request = $_REQUEST['ensemble-add-instructor'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}

		$redirect = add_query_arg( 'page', 'ensemble-admin-people-instructors', admin_url( 'admin.php' ) );
		$nonce    = $_REQUEST['ensemble-add-instructor-nonce'] ?? false;

		if ( ! wp_verify_nonce( $nonce, 'ensemble-add-instructor-nonce' ) ) {
			$redirect = add_query_arg( 'notice-instructor-forbidden', 1, $redirect );

			if ( wp_redirect( $redirect ) ) {
				exit;
			}
		}

		$name  = sanitize_text_field( $_REQUEST['instructor-name'] ?? '' );
		$email = sanitize_text_field( $_REQUEST['instructor-email'] ?? '' );
		$units = array_map( 'absint', $_REQUEST['instructor-units'] ?? array() );

		if ( false !== $user = get_user_by( 'email', $email ) ) {
			$user->add_role( 'ensemble_instructor' );

			$user_id = $user->ID;
		} else {
			$user_id = wp_insert_user( array(
				'user_login'   => $email,
				'user_email'   => $email,
				'user_pass'    => wp_generate_password( 24, true ),
				'display_name' => $name,
				'role'         => 'ensemble_instructor',
			) );
		}

		if ( ! is_wp_error( $user_id ) ) {
			if ( ! empty( $units ) ) {
				// Assign the units to this new instructor.
				wp_set_object_terms( $user_id, $units, ( new Units )->get_taxonomy_slug() );
			}
			$redirect = add_query_arg( 'notice-instructor-added', 1, $redirect );
		} else {
			$redirect = add_query_arg( 'notice-instructor-added-error', 1, $redirect );
		}

		if ( wp_redirect( $redirect ) ) {
			exit;
		}
	}

	/**
	 * Processes updating a instructor.
	 *
	 * @since 1.1.0
	 */
	public function update_instructor() {
		$valid_request = $_REQUEST['ensemble-update-instructor'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}

		$user_id = absint( $_REQUEST['user-id'] ?? 0 );

		$redirect = add_query_arg( array(
			'page'       => 'ensemble-admin-people-instructors',
			'ensbl-view' => 'edit',
			'user_id'    => $user_id,
		), admin_url( 'admin.php' ) );

		$nonce = $_REQUEST['ensemble-update-instructor-nonce'] ?? false;

		if ( ! wp_verify_nonce( $nonce, 'ensemble-update-instructor-nonce' ) || 0 === $user_id ) {
			$redirect = add_query_arg( 'notice-instructor-forbidden', 1, $redirect );

			if ( wp_redirect( $redirect ) ) {
				exit;
			}
		}

		$units_tax_slug = ( new Units )->get_taxonomy_slug();

		// Current user data.
		$user = get_userdata( $user_id );

		$current_units  = wp_get_object_terms( $user_id, $units_tax_slug, array( 'fields' => 'ids' ) );
		$incoming_units = array_map( 'absint', $_REQUEST['instructor-units'] ?? array() );

		$user_id = wp_update_user( array(
			'ID'           => $user_id,
			'display_name' => sanitize_text_field( $_REQUEST['instructor-name'] ?? $user->display_name ),
			'user_email'   => sanitize_text_field( $_REQUEST['instructor-email'] ?? $user->user_email ),
		) );

		if ( ! is_wp_error( $user_id ) ) {
			if ( ! empty( $incoming_units ) ) {

				wp_set_object_terms( $user_id, $incoming_units, $units_tax_slug );

			} elseif ( ! empty( $current_units ) && empty( $incoming_units ) ) {

				wp_remove_object_terms( $user_id, $current_units, $units_tax_slug );

			}

			$redirect = add_query_arg( 'notice-instructor-updated', 1, $redirect );
		} else {
			$redirect = add_query_arg( 'notice-instructor-updated-error', 1, $redirect );
		}

		if ( wp_redirect( $redirect ) ) {
			exit;
		}
	}

	/**
	 * Processes deleting a instructor.
	 *
	 * @since 1.1.0
	 */
	public function delete_instructor() {
		$valid_request = $_REQUEST['ensemble-delete-instructor'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}

		$nonce   = $_REQUEST['ensemble-delete-instructor-nonce'] ?? false;
		$answer  = sanitize_key( $_REQUEST['instructor-delete'] ?? 'no' );
		$user_id = absint( $_REQUEST['user-id'] ?? 0 );

		$redirect = add_query_arg( 'page', 'ensemble-admin-people-instructors', admin_url( 'admin.php' ) );

		if ( ! wp_verify_nonce( $nonce, 'ensemble-delete-instructor-nonce' ) || 'no' === $answer || 0 === $user_id ) {
			$redirect = add_query_arg( 'notice-instructor-forbidden', 1, $redirect );

			if ( wp_redirect( $redirect ) ) {
				exit;
			}
		}

		if ( 'yes' === $answer ) {
			require_once ABSPATH . '/wp-admin/includes/user.php';

			// If this is multisite, the user is only removed from the current site.
			$deleted = \wp_delete_user( $user_id );

			if ( $deleted ) {
				$redirect = add_query_arg( 'notice-instructor-deleted', 1, $redirect );
			} else {
				$redirect = add_query_arg( 'notice-instructor-deleted-error', 1, $redirect );
			}
		} else {
			$redirect = add_query_arg( 'notice-instructor-deleted-no-change', 1, $redirect );
		}

		if ( wp_redirect( $redirect ) ) {
			exit;
		}
	}

	/**
	 * Retrieves registered instructor views.
	 *
	 * @since 1.1.0
	 *
	 * @return array Registered instructor views.
	 */
	public function get_views() {
		return array( 'overview', 'add', 'edit', 'delete', 'tab' );
	}

	/**
	 * Retrieves the path/to/the/views.
	 *
	 * @since 1.1.0
	 *
	 * @return string Path to the view templates instructory.
	 */
	public function get_views_dir() {
		return __DIR__ . '/views/';
	}

	/**
	 * Retrieves the identifier for the component whose tabs API
	 * this component is hooking into.
	 *
	 * @since 1.1.0
	 *
	 * @return string Tab component identifier.
	 */
	public function get_tab_component() {
		return 'ensemble_people';
	}

	/**
	 * Retrieves the tab slug.
	 *
	 * @since 1.1.0
	 *
	 * @return string Tab slug.
	 */
	public function get_tab_slug() {
		return 'instructors';
	}

	/**
	 * Retrieves the tab label.
	 *
	 * @since 1.1.0
	 *
	 * @return string Tab label.
	 */
	public function get_tab_label() {
		return __( 'Unit Instructors', 'ensemble' );
	}

	/**
	 * Outputs the contents of the tab.
	 *
	 * @since 1.1.0
	 */
	public function output_tab_contents() {
		$this->load_view( 'tab' );
	}

}