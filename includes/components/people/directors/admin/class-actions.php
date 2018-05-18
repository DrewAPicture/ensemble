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

		$redirect = add_query_arg( 'page', 'ensemble-admin-people-directors', admin_url( 'admin.php' ) );
		$nonce    = $_REQUEST['ensemble-update-director-nonce'] ?? false;


		if ( ! wp_verify_nonce( $nonce, 'ensemble-update-director-nonce' ) ) {
			// TODO add notice handler for the different cases.
			if ( wp_redirect( $redirect ) ) {
				exit;
			}
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