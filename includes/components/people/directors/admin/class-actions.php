<?php
/**
 * People Actions
 *
 * @package   Ensemble\Components\People\Directors\Admin
 * @copyright Copyright (c) 2018, Drew Jaynes
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 */
namespace Ensemble\Components\People\Directors\Admin;

use Ensemble\Components\Units\Setup as Units;
use Ensemble\Core\Interfaces\Loader;
use Ensemble\Core\Traits\{View_Loader, Tab_Loader};

/**
 * Sets up logic for performing director-related actions.
 *
 * @since 1.0.0
 *
 * @see Loader
 * @see View_Loader
 * @see Tab_Loader
 */
class Actions implements Loader {

	use View_Loader, Tab_Loader;

	/**
	 * Registers hook callbacks for director actions.
	 *
	 * @since 1.0.0
	 */
	public function load() {
		add_action( 'init', array( $this, 'add_director' ) );
		add_action( 'init', array( $this, 'update_director' ) );
		add_action( 'init', array( $this, 'delete_director' ) );

		$this->register_tab_callbacks();
	}

	/**
	 * Processes adding a new director.
	 *
	 * @since 1.0.0
	 */
	public function add_director() {
		$valid_request = $_REQUEST['ensemble-add-director'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}

		$redirect = add_query_arg( 'page', 'ensemble-admin-people-directors', admin_url( 'admin.php' ) );
		$nonce    = $_REQUEST['ensemble-add-director-nonce'] ?? false;

		if ( ! wp_verify_nonce( $nonce, 'ensemble-add-director-nonce' ) ) {
			// TODO add notice handler for the different cases.
			if ( wp_redirect( $redirect ) ) {
				exit;
			}
		}

		$name  = sanitize_text_field( $_REQUEST['director-name'] ?? '' );
		$email = sanitize_text_field( $_REQUEST['director-email'] ?? '' );
		$units = array_map( 'absint', $_REQUEST['director-units'] ?? array() );

		$user_id = wp_insert_user( array(
			'user_login'   => $email,
			'user_email'   => $email,
			'user_pass'    => wp_generate_password( 24, true ),
			'display_name' => $name,
			'role'         => 'ensemble_director',
		) );

		if ( ! is_wp_error( $user_id ) && ! empty( $units ) ) {
			// Assign the units to this new director.
			wp_set_object_terms( $user_id, $units, ( new Units )->get_taxonomy_slug() );
		}

		// TODO add notice handler for the different cases.
		if ( wp_redirect( $redirect ) ) {
			exit;
		}
	}

	/**
	 * Processes updating a director.
	 *
	 * @since 1.0.0
	 */
	public function update_director() {
		$valid_request = $_REQUEST['ensemble-update-director'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}

		$user_id = absint( $_REQUEST['user-id'] ?? 0 );

		$redirect = add_query_arg( array(
			'page'       => 'ensemble-admin-people-directors',
			'ensbl-view' => 'edit',
			'user_id'    => $user_id,
		), admin_url( 'admin.php' ) );

		$nonce = $_REQUEST['ensemble-update-director-nonce'] ?? false;

		if ( ! wp_verify_nonce( $nonce, 'ensemble-update-director-nonce' ) || 0 === $user_id ) {
			if ( wp_redirect( $redirect ) ) {
				exit;
			}
		}

		$units_tax_slug = ( new Units )->get_taxonomy_slug();

		// Current user data.
		$user = get_userdata( $user_id );

		$current_units  = wp_get_object_terms( $user_id, $units_tax_slug, array( 'fields' => 'ids' ) );
		$incoming_units = array_map( 'absint', $_REQUEST['director-units'] ?? array() );


		$user_id = wp_update_user( array(
			'ID'           => $user_id,
			'display_name' => sanitize_text_field( $_REQUEST['director-name'] ?? $user->display_name ),
			'user_email'   => sanitize_text_field( $_REQUEST['director-email'] ?? $user->user_email ),
		) );

		if ( ! is_wp_error( $user_id ) ) {
			if ( ! empty( $incoming_units ) ) {

				wp_set_object_terms( $user_id, $incoming_units, $units_tax_slug );

			} elseif ( ! empty( $current_units ) && empty( $incoming_units ) ) {

				wp_remove_object_terms( $user_id, $current_units, $units_tax_slug );

			}
		}

		// TODO add notice handler for the different cases.
		if ( wp_redirect( $redirect ) ) {
			exit;
		}
	}

	/**
	 * Processes deleting a director.
	 *
	 * @since 1.0.0
	 */
	public function delete_director() {
		$valid_request = $_REQUEST['ensemble-delete-director'] ?? false;

		// Bail if the request doesn't even match.
		if ( false === $valid_request ) {
			return;
		}

		$redirect = add_query_arg( 'page', 'ensemble-admin-people-directors', admin_url( 'admin.php' ) );
		$nonce    = $_REQUEST['ensemble-delete-director-nonce'] ?? false;

		if ( ! wp_verify_nonce( $nonce, 'ensemble-delete-director-nonce' ) ) {
			// TODO add notice handler for the different cases.
			if ( wp_redirect( $redirect ) ) {
				exit;
			}
		}
	}

	/**
	 * Retrieves registered director views.
	 *
	 * @since 1.0.0
	 *
	 * @return array Registered director views.
	 */
	public function get_views() {
		return array( 'overview', 'add', 'edit', 'delete', 'tab' );
	}

	/**
	 * Retrieves the path/to/the/views.
	 *
	 * @since 1.0.0
	 *
	 * @return string Path to the view templates directory.
	 */
	public function get_views_dir() {
		return __DIR__ . '/views/';
	}

	/**
	 * Retrieves the identifier for the component whose tabs API
	 * this component is hooking into.
	 *
	 * @since 1.0.0
	 *
	 * @return string Tab component identifier.
	 */
	public function get_tab_component() {
		return 'ensemble_people';
	}

	/**
	 * Retrieves the tab slug.
	 *
	 * @since 1.0.0
	 *
	 * @return string Tab slug.
	 */
	public function get_tab_slug() {
		return 'directors';
	}

	/**
	 * Retrieves the tab label.
	 *
	 * @since 1.0.0
	 *
	 * @return string Tab label.
	 */
	public function get_tab_label() {
		return __( 'Unit Directors', 'ensemble' );
	}

	/**
	 * Outputs the contents of the tab.
	 *
	 * @since 1.0.0
	 */
	public function output_tab_contents() {
		$this->load_view( 'tab' );
	}

}